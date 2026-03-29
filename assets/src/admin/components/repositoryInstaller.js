import { cdrRequest } from '../api/ajax'

export function initRepositoryInstaller() {

    const { __ } = window.wp.i18n

    async function handleInstall(button) {

        const type  = button.dataset.type
        const owner = button.dataset.owner
        const name  = button.dataset.name

        if (!type || !owner || !name) {
            window.CorbidevUI?.banner?.show({
                message: __('Missing data', 'corbidevrepositories'),
                type: 'danger'
            })
            return
        }

        const confirmed = await window.CorbidevUI?.modal?.confirm({
            title: __('Confirm', 'corbidevrepositories'),
            message: __('Do you want to install this item?', 'corbidevrepositories'),
            type: 'info'
        })

        if (!confirmed) return

        button.disabled = true
        button.innerText = __('Installing...', 'corbidevrepositories')

        try {

            await cdrRequest('cdr_install_item', {
                type,
                owner,
                name
            })

            window.CorbidevUI?.banner?.show({
                message: __('Installation completed successfully', 'corbidevrepositories'),
                type: 'success'
            })

            button.innerText = __('Installed', 'corbidevrepositories')
            button.classList.add('disabled')

        } catch (error) {

            window.CorbidevUI?.banner?.show({
                message: error.message || __('Server error', 'corbidevrepositories'),
                type: 'danger'
            })

            button.disabled = false
            button.innerText = __('Install', 'corbidevrepositories')
        }
    }

    document.addEventListener('click', (e) => {

        const btn = e.target.closest('[data-action="install"]')
        if (!btn) return

        e.preventDefault()

        handleInstall(btn)
    })
}