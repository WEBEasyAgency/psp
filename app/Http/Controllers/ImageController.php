<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    /**
     * Serve optimized WebP image with resize support
     */
    public function serveOptimized(Request $request, $path)
    {
        $path = urldecode($path);
        $originalPath = public_path('img/' . $path);

        if (!file_exists($originalPath)) {
            abort(404, 'Image not found: ' . $path);
        }

        $width = $request->get('w', null);
        $quality = (int) $request->get('q', 90);
        $acceptsWebP = str_contains($request->header('Accept', ''), 'image/webp');

        // Build WebP cache path
        $suffix = $width ? ".{$width}w" : '';
        $webpPath = public_path('storage/webp/' . preg_replace('/\.(jpg|jpeg|png)$/i', "{$suffix}.q{$quality}.webp", $path));

        // Create cache directory
        $webpDir = dirname($webpPath);
        if (!is_dir($webpDir)) {
            mkdir($webpDir, 0775, true);
        }

        // Serve cached WebP if fresh
        if ($acceptsWebP && file_exists($webpPath) && filemtime($webpPath) >= filemtime($originalPath)) {
            return response()->file($webpPath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }

        // Convert to WebP
        if ($acceptsWebP) {
            $image = Image::read($originalPath);

            if ($width && is_numeric($width)) {
                $image->scale(width: (int) $width);
            }

            $image->toWebp($quality)->save($webpPath);

            return response()->file($webpPath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }

        // No WebP support — serve resized original
        if ($width && is_numeric($width)) {
            $image = Image::read($originalPath);
            $image->scale(width: (int) $width);

            $ext = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
            $encoded = match ($ext) {
                'png' => $image->toPng(),
                default => $image->toJpeg($quality),
            };

            return response($encoded->toString())
                ->header('Content-Type', mime_content_type($originalPath))
                ->header('Cache-Control', 'public, max-age=31536000, immutable');
        }

        // No resize, no WebP — serve original as-is
        return response()->file($originalPath, [
            'Content-Type' => mime_content_type($originalPath),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Diagnostic: check if WebP conversion is possible on this server
     */
    public function diagnose()
    {
        $checks = [];

        // GD
        $checks['gd_loaded'] = extension_loaded('gd');
        $checks['gd_info'] = function_exists('gd_info') ? gd_info() : 'gd_info() not available';
        $checks['gd_webp'] = function_exists('gd_info') && !empty(gd_info()['WebP Support']);

        // Intervention Image
        try {
            $class = \Intervention\Image\Laravel\Facades\Image::getFacadeRoot();
            $checks['intervention_loaded'] = $class !== null;
            $checks['intervention_class'] = get_class($class);
        } catch (\Throwable $e) {
            $checks['intervention_loaded'] = false;
            $checks['intervention_error'] = $e->getMessage();
        }

        // Storage writable
        $webpDir = public_path('storage/webp');
        $checks['webp_dir_exists'] = is_dir($webpDir);
        $checks['webp_dir_writable'] = is_dir($webpDir) && is_writable($webpDir);

        // Test conversion
        $testImage = public_path('img/dest/type1.jpg');
        $checks['test_image_exists'] = file_exists($testImage);
        if (file_exists($testImage)) {
            try {
                $img = Image::read($testImage);
                $img->scale(width: 100);
                $webp = $img->toWebp(90);
                $checks['test_conversion'] = 'OK, size: ' . strlen($webp->toString()) . ' bytes';
            } catch (\Throwable $e) {
                $checks['test_conversion'] = 'FAILED: ' . $e->getMessage();
            }
        }

        return response()->json($checks, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Batch convert all gallery images to WebP
     */
    public function generateWebPBatch()
    {
        $contentPath = public_path('img/Контент/Контент');

        if (!is_dir($contentPath)) {
            return response()->json(['error' => 'Content directory not found'], 404);
        }

        $processed = [];
        $errors = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($contentPath)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/\.(jpg|jpeg|png)$/i', $file->getFilename())) {
                try {
                    $originalPath = $file->getPathname();
                    $relativePath = str_replace($contentPath . DIRECTORY_SEPARATOR, '', $originalPath);

                    $webpPath = public_path('storage/webp/Контент/Контент/' . $relativePath);
                    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $webpPath);

                    $webpDir = dirname($webpPath);
                    if (!is_dir($webpDir)) {
                        mkdir($webpDir, 0775, true);
                    }

                    if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($originalPath)) {
                        continue;
                    }

                    $image = Image::read($originalPath);
                    $image->toWebp(90)->save($webpPath);

                    $processed[] = $relativePath;
                } catch (\Throwable $e) {
                    $errors[] = [
                        'file' => $file->getPathname(),
                        'error' => $e->getMessage()
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'processed' => count($processed),
            'errors' => count($errors),
            'files' => $processed,
            'error_details' => $errors
        ]);
    }
}
