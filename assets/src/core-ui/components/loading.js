export function set(btn, state) {
    if (!btn) return

    btn.disabled = state

    if (state) {
        btn.dataset.originalText = btn.innerHTML
        btn.innerHTML = '...'
    } else {
        btn.innerHTML = btn.dataset.originalText || btn.innerHTML
    }
}
