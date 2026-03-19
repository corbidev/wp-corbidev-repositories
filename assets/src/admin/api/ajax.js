export async function cdrRequest(action, data = {}) {
    const response = await fetch(window.cdr_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action,
            nonce: window.cdr_ajax.nonce,
            ...data
        })
    });

    const json = await response.json();

    if (!json.success) {
        throw new Error(json.data?.message || 'Erreur inconnue');
    }

    return json.data;
}