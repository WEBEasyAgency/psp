@props([
    'src' => '',
    'alt' => '',
    'lazy' => true,
    'width' => null,
    'height' => null,
    'class' => '',
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw'
])

@php
    // Remove leading /img/ if present
    $path = ltrim($src, '/');
    $path = preg_replace('#^img/#', '', $path);

    // Build optimized URL
    $optimizedUrl = route('image.optimized', ['path' => $path]);
    $optimizedUrlWithWidth = $width ? $optimizedUrl . '?w=' . $width : $optimizedUrl;

    // Generate srcset for responsive images
    $srcset = [];
    if ($width) {
        foreach ([320, 640, 768, 1024, 1280] as $w) {
            if ($w <= $width) {
                $srcset[] = route('image.optimized', ['path' => $path]) . '?w=' . $w . ' ' . $w . 'w';
            }
        }
    }
    $srcsetAttr = !empty($srcset) ? implode(', ', $srcset) : null;
@endphp

<picture {{ $attributes->merge(['class' => $class]) }}>
    {{-- WebP source with srcset if available --}}
    <source
        type="image/webp"
        @if($srcsetAttr)
            srcset="{{ $srcsetAttr }}"
            sizes="{{ $sizes }}"
        @else
            srcset="{{ $optimizedUrlWithWidth }}"
        @endif
    />

    {{-- Fallback to original --}}
    <img
        src="{{ $optimizedUrlWithWidth }}"
        alt="{{ $alt }}"
        @if($lazy) loading="lazy" @endif
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        {{ $attributes->merge(['class' => $class]) }}
    />
</picture>
