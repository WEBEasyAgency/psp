/**
 * Converts /img/ paths to /optimized-img/ endpoint for WebP + resize.
 * The server handles content negotiation (WebP for supporting browsers, original otherwise).
 */

const DEFAULT_QUALITY = 75

/**
 * Build optimized image URL
 * @param {string} src - Original image path (e.g. /img/Контент/...)
 * @param {number|null} width - Desired width in pixels
 * @param {number} quality - WebP quality (1-100)
 * @returns {string} Optimized URL
 */
export function optimizedSrc(src, width = null, quality = DEFAULT_QUALITY) {
    if (!src) return ''

    // Only transform /img/ paths
    if (!src.startsWith('/img/')) return src

    const path = src.slice(5) // strip "/img/"
    const params = []
    if (width) params.push(`w=${width}`)
    if (quality !== 85) params.push(`q=${quality}`)
    const qs = params.length ? '?' + params.join('&') : ''
    return `/optimized-img/${path}${qs}`
}

/**
 * Generate srcset string for responsive images
 * @param {string} src - Original image path
 * @param {number[]} widths - Array of widths for srcset
 * @param {number} quality - WebP quality (1-100)
 * @returns {string} srcset attribute value
 */
export function optimizedSrcset(src, widths = [480, 768, 1024], quality = DEFAULT_QUALITY) {
    if (!src || !src.startsWith('/img/')) return ''

    const path = src.slice(5)
    const qParam = quality !== 85 ? `&q=${quality}` : ''
    return widths
        .map(w => `/optimized-img/${path}?w=${w}${qParam} ${w}w`)
        .join(', ')
}
