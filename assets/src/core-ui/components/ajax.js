export async function request(action, data = {}) {
    try {
        const res = await fetch(window.ajaxurl, {
            method: 'POST',
            body: new URLSearchParams({
                action,
                ...data
            })
        })

        const json = await res.json()

        if (!json.success) {
            throw new Error(json.data?.message || 'Error')
        }

        return { success: true, data: json.data }

    } catch (error) {
        CorbidevUI?.error?.handle(error)

        return { success: false, message: error.message }
    }
}
