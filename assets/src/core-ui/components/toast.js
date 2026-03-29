let container

function ensureContainer() {
    if (container) {
        return container
    }

    container = document.getElementById('cdr-toast-container')
    if (!container) {
        if (!document.body) {
            return null
        }

        container = document.createElement('div')
        container.id = 'cdr-toast-container'
        container.style.position = 'fixed'
        container.style.top = '20px'
        container.style.right = '20px'
        container.style.zIndex = '9999'
        document.body.appendChild(container)
    }

    return container
}

export function show(message, type = 'info') {
    const toastContainer = ensureContainer()
    if (!toastContainer) {
        return
    }

    const toast = document.createElement('div')

    toast.className = 'cdr-toast'
    toast.textContent = message

    toast.style.background = '#333'
    toast.style.color = '#fff'
    toast.style.padding = '10px'
    toast.style.marginTop = '10px'
    toast.style.borderRadius = '4px'

    toastContainer.appendChild(toast)

    setTimeout(() => {
        toast.remove()
    }, 3000)
}
