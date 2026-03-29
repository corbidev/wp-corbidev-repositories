export function setLoading(btn, state, text = '...') {
    if (!btn) return

    btn.disabled = state

    if (state) {
        btn.dataset.originalText = btn.innerHTML
        btn.innerHTML = text
        btn.classList.add('is-loading')
    } else {
        btn.innerHTML = btn.dataset.originalText || btn.innerHTML
        btn.classList.remove('is-loading')
    }
}

export function showNotice(message, type = 'success') {
    const notice = document.createElement('div')
    notice.className = `notice notice-${type}`
    notice.innerHTML = `<p>${message}</p>`

    const container = document.querySelector('.wrap')
    if (container) {
        container.prepend(notice)
    }
}