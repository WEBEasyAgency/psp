/**
 * Converts /img/ paths to /optimized-img/ endpoint for WebP + resize.
 * The server handles content negotiation (WebP for supporting browsers, original otherwise).
 */

const DEFAULT_QUALITY = 90

/**
 * Build optimized image URL
 * @param {string} src - Original image path (e.g. /img/Контент/...)
 * @param {number|null} width - Desired width in pixels
 * @returns {string} Optimized URL
 */
export function optimizedSrc(src, width = null) {
    if (!src) return ''
    if (!src.startsWith('/img/')) return src

    const path = src.slice(5) // strip "/img/"
    const params = []
    if (width) params.push(`w=${width}`)
    params.push(`q=${DEFAULT_QUALITY}`)
    return `/optimized-img/${path}?${params.join('&')}`
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
        .map(w => `/optimized-img/${path}?w=${w}&q=${DEFAULT_QUALITY} ${w}w`)
        .join(', ')
}
