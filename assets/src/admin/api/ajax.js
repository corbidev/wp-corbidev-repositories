export async function cdrRequest(action, data = {}) {
    const response = await fetch(window.cdr_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action,
            nonce: window.cdr_ajax.nonce,
            ...data
        })
    })

    const text = await response.text()

    try {
        return JSON.parse(text)
    } catch (e) {
        console.error('INVALID JSON RESPONSE:', text) // 🔥 DEBUG
        throw new Error('Invalid JSON response')
    }
}