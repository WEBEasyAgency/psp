<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Serve optimized WebP image with fallback to original format
     */
    public function serveOptimized(Request $request, $path)
    {
        // Decode the path (handle Cyrillic characters)
        $path = urldecode($path);

        // Full path to original image
        $originalPath = public_path('img/' . $path);

        // Check if original exists
        if (!file_exists($originalPath)) {
            abort(404, 'Image not found');
        }

        // Get requested params
        $width = $request->get('w', null);
        $quality = (int) $request->get('q', 80);

        // Check if WebP is supported by browser
        $acceptsWebP = str_contains($request->header('Accept', ''), 'image/webp');

        // Try WebP conversion, fall back to original on any error
        if ($acceptsWebP) {
            try {
                // Build WebP cache path
                $suffix = $width ? ".{$width}w" : '';
                $webpPath = public_path('storage/webp/' . preg_replace('/\.(jpg|jpeg|png)$/i', "{$suffix}.webp", $path));

                // Create directory if needed
                $webpDir = dirname($webpPath);
                if (!is_dir($webpDir)) {
                    @mkdir($webpDir, 0775, true);
                }

                // If cached WebP exists and is fresh, serve it
                if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($originalPath)) {
                    return $this->serveWebP($webpPath);
                }

                // Convert if directory is writable
                if (is_dir($webpDir) && is_writable($webpDir)) {
                    $image = \Intervention\Image\Laravel\Facades\Image::read($originalPath);

                    if ($width && is_numeric($width)) {
                        $image->scale(width: (int) $width);
                    }

                    $image->toWebp($quality)->save($webpPath);

                    return $this->serveWebP($webpPath);
                }
            } catch (\Throwable $e) {
                \Log::warning('WebP conversion failed for ' . $path . ': ' . $e->getMessage());
            }
        }

        // Serve resized original if width requested (without WebP)
        if ($width && is_numeric($width)) {
            try {
                $image = \Intervention\Image\Laravel\Facades\Image::read($originalPath);
                $image->scale(width: (int) $width);
                $mimeType = mime_content_type($originalPath);
                $ext = pathinfo($originalPath, PATHINFO_EXTENSION);
                $encoded = match (strtolower($ext)) {
                    'png' => $image->toPng(),
                    default => $image->toJpeg($quality),
                };

                return response($encoded->toString())
                    ->header('Content-Type', $mimeType)
                    ->header('Cache-Control', 'public, max-age=31536000, immutable');
            } catch (\Throwable $e) {
                // Fall through to serve original
            }
        }

        // Ultimate fallback: serve original file as-is
        return response()->file($originalPath, [
            'Content-Type' => mime_content_type($originalPath),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Serve a WebP file with proper headers
     */
    private function serveWebP(string $path)
    {
        return response()->file($path, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Generate all WebP versions for gallery images (admin endpoint)
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
                        @mkdir($webpDir, 0775, true);
                    }

                    if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($originalPath)) {
                        continue;
                    }

                    $image = \Intervention\Image\Laravel\Facades\Image::read($originalPath);
                    $image->toWebp(80)->save($webpPath);

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
