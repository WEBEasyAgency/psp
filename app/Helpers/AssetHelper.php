<?php

namespace App\Helpers;

class AssetHelper
{
    public static function render($entry)
    {
        $manifestPath = public_path('build/manifest.json');

        if (!file_exists($manifestPath)) {
            return '<!-- manifest.json not found -->';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!isset($manifest[$entry])) {
            return "<!-- Asset {$entry} not found in manifest -->";
        }

        $file = $manifest[$entry];
        $src = '/build/' . $file['file'];

        if (str_ends_with($entry, '.css')) {
            return "<link rel=\"stylesheet\" href=\"{$src}\" />";
        }

        return "<script type=\"module\" src=\"{$src}\"></script>";
    }
}
