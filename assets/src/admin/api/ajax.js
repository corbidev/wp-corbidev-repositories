const { __ } = window.wp.i18n;

export async function cdrRequest(action, data = {}) {

    if (!window.cdr_ajax) {
        throw new Error(__('Missing AJAX configuration', 'corbidev'))
    }

    let response

    try {
        response = await fetch(window.cdr_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: new URLSearchParams({
                action,
                nonce: window.cdr_ajax.nonce,
                ...data
            })
        })
    } catch (err) {
        console.error('Network error:', err)
        throw new Error(__('Network error', 'corbidevrepositories'))
    }

    /**
     * 🔥 HTTP error (important)
     */
    if (!response.ok) {
        throw new Error(
            `${__('Server error', 'corbidevrepositories')} (${response.status})`
        )
    }

    let text = await response.text()
    let json

    try {
        json = JSON.parse(text)
    } catch (e) {
        console.error('INVALID JSON RESPONSE:', text)
        throw new Error(__('Invalid server response', 'corbidevrepositories'))
    }

    /**
     * 🔥 WP AJAX standard
     */
    if (!json || typeof json !== 'object') {
        throw new Error(__('Invalid server response', 'corbidevrepositories'))
    }

    if (!json.success) {
        throw new Error(
            json?.data?.message ||
            (typeof json?.data === 'string' ? json.data : null) ||
            __('Unknown error', 'corbidevrepositories')
        )
    }

    return json.data
}