import { cdrRequest } from '../api/ajax'

export function initRepositoryManager() {

    const { __ } = window.wp.i18n

    const form = document.getElementById('cdr-repo-form')

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault()

            const data = new FormData(form)

            await cdrRequest('cdr_repo_add', {
                name: data.get('name'),
                token: data.get('token')
            })

            showBanner(__('Repository added', 'corbidevrepositories'), 'success')
        })
    }

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('[data-action]')
        if (!btn) return

        const action = btn.dataset.action
        const row = btn.closest('tr')

        try {

            switch (action) {

                case 'install':
                case 'activate':
                case 'deactivate':
                case 'delete':
                case 'update':
                    CorbidevUI?.loading?.set(btn, true)
                    break
            }

            switch (action) {

                case 'repo-delete': {

                    const confirmed = await CorbidevUI.modal.confirm({
                        title: __('Delete', 'corbidevrepositories'),
                        message: __('Delete this repository?', 'corbidevrepositories'),
                        type: 'danger'
                    })

                    if (!confirmed) return

                    await cdrRequest('cdr_repo_delete', {
                        name: btn.dataset.name
                    })

                    row?.remove()

                    showBanner(__('Repository deleted', 'corbidevrepositories'), 'success')
                    break
                }

                case 'install': {
                    await cdrRequest('cdr_install_item', btn.dataset)
                    row && updateRowState(row, 'installed', btn.dataset)
                    showBanner(__('Installed', 'corbidevrepositories'), 'success')
                    break
                }

                case 'activate': {
                    await cdrRequest('cdr_activate_item', {
                        name: btn.dataset.name
                    })
                    row && updateRowState(row, 'active', btn.dataset)
                    showBanner(__('Activated', 'corbidevrepositories'), 'success')
                    break
                }

                case 'deactivate': {
                    await cdrRequest('cdr_deactivate_item', {
                        name: btn.dataset.name
                    })
                    row && updateRowState(row, 'inactive', btn.dataset)
                    showBanner(__('Deactivated', 'corbidevrepositories'), 'success')
                    break
                }

                case 'delete': {

                    const confirmed = await CorbidevUI.modal.confirm({
                        title: __('Delete', 'corbidevrepositories'),
                        message: __('Delete this item?', 'corbidevrepositories'),
                        type: 'danger'
                    })

                    if (!confirmed) return

                    await cdrRequest('cdr_delete_item', {
                        type: btn.dataset.type,
                        name: btn.dataset.name
                    })

                    row?.remove()

                    showBanner(__('Deleted', 'corbidevrepositories'), 'success')
                    break
                }

                case 'update': {
                    await cdrRequest('cdr_update_item', btn.dataset)
                    showBanner(__('Updated', 'corbidevrepositories'), 'success')
                    break
                }
            }

        } catch (err) {

            console.error(err)

            CorbidevUI?.banner?.show({
                message: err?.message || __('An error occurred', 'corbidevrepositories'),
                type: 'danger'
            })

        } finally {
            if (btn) CorbidevUI?.loading?.set(btn, false)
        }
    })
}

function showBanner(message, type = 'success') {
    window.CorbidevUI?.banner?.show({
        message,
        type
    })
}