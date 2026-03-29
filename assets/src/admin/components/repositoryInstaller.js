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

        window.CorbidevUI?.loading?.set(button, true)
        button.innerText = __('Installing...', 'corbidevrepositories')

        let installSucceeded = false

        try {

            await cdrRequest('cdr_install_item', {
                type,
                owner,
                name
            })

            if (window.CorbidevUI?.toast?.show) {
                window.CorbidevUI.toast.show(__('Installation completed successfully', 'corbidevrepositories'), 'success')
            } else {
                window.CorbidevUI?.banner?.show({
                    message: __('Installation completed successfully', 'corbidevrepositories'),
                    type: 'success'
                })
            }

            button.innerText = __('Installed', 'corbidevrepositories')
            button.classList.add('disabled')
            installSucceeded = true

        } catch (error) {

            window.CorbidevUI?.error?.handle(error)
        } finally {
            if (!installSucceeded) {
                window.CorbidevUI?.loading?.set(button, false)
            }
        }
    }

    document.addEventListener('click', (e) => {

        const btn = e.target.closest('[data-action="install"]')
        if (!btn) return

        e.preventDefault()

        handleInstall(btn)
    })
}