/**
 * Converts /img/ paths to /optimized-img/ endpoint for WebP + resize.
 * The server handles content negotiation (WebP for supporting browsers, original otherwise).
 */

/**
 * Build optimized image URL
 * @param {string} src - Original image path (e.g. /img/Контент/...)
 * @param {number|null} width - Desired width in pixels
 * @returns {string} Optimized URL
 */
export function optimizedSrc(src, width = null) {
    if (!src) return ''

    // Only transform /img/ paths
    if (!src.startsWith('/img/')) return src

    const path = src.slice(5) // strip "/img/"
    let url = `/optimized-img/${path}`
    if (width) url += `?w=${width}`
    return url
}

/**
 * Generate srcset string for responsive images
 * @param {string} src - Original image path
 * @param {number[]} widths - Array of widths for srcset
 * @returns {string} srcset attribute value
 */
export function optimizedSrcset(src, widths = [480, 768, 1024]) {
    if (!src || !src.startsWith('/img/')) return ''

    const path = src.slice(5)
    return widths
        .map(w => `/optimized-img/${path}?w=${w} ${w}w`)
        .join(', ')
}
