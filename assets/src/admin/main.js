import '@styles/tailwind.css'

import CorbidevModal from './components/modal'
import { cdrRequest } from './api/ajax'

// Init modal
const modal = new CorbidevModal()

/**
 * Installation générique (plugin / theme)
 */
async function handleInstall(button) {
    const type = button.dataset.type
    const owner = button.dataset.owner
    const name = button.dataset.name

    button.disabled = true
    button.innerText = 'Installation...'

    try {
        await cdrRequest('cdr_install_item', {
            type,
            owner,
            name
        })

        modal.show('Installation réussie', 'success')

        button.innerText = 'Installé'
        button.classList.add('disabled')

    } catch (error) {

        modal.show(error.message || 'Erreur serveur', 'error')

        button.disabled = false
        button.innerText = 'Installer'
    }
}

/**
 * Event delegation global
 */
document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action="install"]')
    if (!btn) return

    e.preventDefault()
    handleInstall(btn)
})