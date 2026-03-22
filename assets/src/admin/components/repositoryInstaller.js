import { cdrRequest } from '../api/ajax'
import CorbidevModal from './modal'

export function initRepositoryInstaller() {

    const modal = new CorbidevModal()

    async function handleInstall(button) {

        const type  = button.dataset.type
        const owner = button.dataset.owner
        const name  = button.dataset.name

        if (!type || !owner || !name) {
            modal.show('Données manquantes', 'error')
            return
        }

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

    document.addEventListener('click', (e) => {

        const btn = e.target.closest('[data-action="install"]')
        if (!btn) return

        e.preventDefault()

        handleInstall(btn)
    })
}