export async function cdrRequest(action, data = {}) {

    if (!window.cdr_ajax) {
        throw new Error('cdr_ajax non défini')
    }

    const response = await fetch(window.cdr_ajax.ajax_url, {
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

    const text = await response.text()

    let json

    try {
        json = JSON.parse(text)
    } catch (e) {
        console.error('INVALID JSON RESPONSE:', text)
        throw new Error('Réponse serveur invalide')
    }

    /**
     * 🔥 FIX CRITIQUE
     */
    if (!json.success) {
        throw new Error(
            json?.data?.message ||
            json?.data ||
            'Erreur inconnue'
        )
    }

    return json.data
}