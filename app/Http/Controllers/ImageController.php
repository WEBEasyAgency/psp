<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    /**
     * Serve optimized WebP image with fallback to original format
     *
     * @param Request $request
     * @param string $path Full image path after /img/
     * @return \Illuminate\Http\Response
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

        // Get requested width (for responsive images)
        $width = $request->get('w', null);
        $quality = $request->get('q', 85); // WebP quality (default 85)

        // Generate cache key
        $cacheKey = 'img_' . md5($path . $width . $quality);

        // Check if WebP is supported by browser
        $acceptsWebP = str_contains($request->header('Accept', ''), 'image/webp');

        // Build WebP path
        $webpPath = public_path('storage/webp/' . $path);
        if ($width) {
            $webpPath = str_replace(
                pathinfo($webpPath, PATHINFO_EXTENSION),
                $width . 'w.webp',
                $webpPath
            );
        } else {
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $webpPath);
        }

        // Create directory if doesn't exist
        $webpDir = dirname($webpPath);
        if (!is_dir($webpDir)) {
            mkdir($webpDir, 0755, true);
        }

        // If WebP already exists and is newer than original, serve it
        if ($acceptsWebP && file_exists($webpPath) && filemtime($webpPath) >= filemtime($originalPath)) {
            return response()->file($webpPath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }

        // Generate WebP if browser supports it
        if ($acceptsWebP) {
            try {
                $image = Image::read($originalPath);

                // Resize if width specified
                if ($width && is_numeric($width)) {
                    $image->scale(width: (int)$width);
                }

                // Encode to WebP
                $image->toWebp($quality)->save($webpPath);

                return response()->file($webpPath, [
                    'Content-Type' => 'image/webp',
                    'Cache-Control' => 'public, max-age=31536000, immutable',
                ]);
            } catch (\Exception $e) {
                // Fallback to original on error
                \Log::warning('WebP conversion failed: ' . $e->getMessage());
            }
        }

        // Serve original if WebP not supported or conversion failed
        $mimeType = mime_content_type($originalPath);
        return response()->file($originalPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Generate all WebP versions for gallery images (can be run via artisan command)
     */
    public function generateWebPBatch()
    {
        $contentPath = public_path('img/Контент/Контент');

        if (!is_dir($contentPath)) {
            return response()->json(['error' => 'Content directory not found'], 404);
        }

        $processed = [];
        $errors = [];

        // Get all image files recursively
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

                    // Create directory
                    $webpDir = dirname($webpPath);
                    if (!is_dir($webpDir)) {
                        mkdir($webpDir, 0755, true);
                    }

                    // Skip if WebP exists and is newer
                    if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($originalPath)) {
                        continue;
                    }

                    // Convert
                    $image = Image::read($originalPath);
                    $image->toWebp(85)->save($webpPath);

                    $processed[] = $relativePath;
                } catch (\Exception $e) {
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
