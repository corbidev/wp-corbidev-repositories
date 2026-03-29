export function getI18n() {

    if (!window.wp || !window.wp.i18n) {
        throw new Error('wp.i18n is not available')
    }

    return window.wp.i18n
}

export function __(text, domain = 'corbidev') {
    return getI18n().__ (text, domain)
}