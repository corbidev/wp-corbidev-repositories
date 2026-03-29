export async function cdrRequest(action, data = {}) {
    return window.CorbidevUI?.ajax?.request(action, data)
}