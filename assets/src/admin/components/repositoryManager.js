import { cdrRequest } from '../api/ajax';

const { __ } = window.wp.i18n;

export function initRepositoryManager() {

    const form = document.getElementById('cdr-repo-form')

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault()

            const data = new FormData(form)

            await cdrRequest('cdr_repo_add', {
                name: data.get('name'),
                token: data.get('token')
            })

            showNotice(__('Repository added', 'corbidevrepositories'))
        })
    }

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('[data-action]')
        if (!btn) return

        const action = btn.dataset.action
        const row = btn.closest('tr')

        try {

            switch (action) {

                case 'repo-delete': {

                    if (!confirm(__('Delete this repository?', 'corbidevrepositories'))) return

                    await cdrRequest('cdr_repo_delete', {
                        name: btn.dataset.name // repo
                    })

                    row?.remove()
                    showNotice(__('Repository deleted', 'corbidevrepositories'))
                    break
                }

                case 'install': {

                    setLoading(btn, true)

                    await cdrRequest('cdr_install_item', {
                        type: btn.dataset.type,
                        owner: btn.dataset.owner,
                        name: btn.dataset.name // repo
                    })

                    updateRowState(row, 'installed', btn.dataset)
                    showNotice(__('Installed', 'corbidevrepositories'))
                    break
                }

                case 'activate': {

                    setLoading(btn, true)

                    await cdrRequest('cdr_activate_item', {
                        name: btn.dataset.name // slug
                    })

                    updateRowState(row, 'active', btn.dataset)
                    showNotice(__('Activated', 'corbidevrepositories'))
                    break
                }

                case 'deactivate': {

                    setLoading(btn, true)

                    await cdrRequest('cdr_deactivate_item', {
                        name: btn.dataset.name // slug
                    })

                    updateRowState(row, 'inactive', btn.dataset)
                    showNotice(__('Deactivated', 'corbidevrepositories'))
                    break
                }

                case 'delete': {

                    if (!confirm(__('Delete this item?', 'corbidevrepositories'))) return

                    setLoading(btn, true)

                    await cdrRequest('cdr_delete_item', {
                        type: btn.dataset.type,
                        name: btn.dataset.name // slug
                    })

                    row?.remove()
                    showNotice(__('Deleted', 'corbidevrepositories'))
                    break
                }

                case 'update': {

                    setLoading(btn, true)

                    await cdrRequest('cdr_update_item', {
                        type: btn.dataset.type,
                        owner: btn.dataset.owner,
                        name: btn.dataset.name // repo
                    })

                    showNotice(__('Updated', 'corbidevrepositories'))
                    break
                }
            }

        } catch (err) {

            console.error(err)

            alert(
                err?.message ||
                __('An error occurred', 'corbidevrepositories')
            )

        } finally {
            setLoading(btn, false)
        }
    })
}

/**
 * Update UI state without reload (FIX COMPLET)
 */
function updateRowState(row, state, data = {}) {

    if (!row) return

    const cell = row.querySelector('td:last-child')
    if (!cell) return

    // 🔥 reconstruction complète (évite bugs)
    if (state === 'installed') {

        cell.innerHTML = `
            <span style="color:green; font-weight:bold;">
                ✔ ${__('Installed', 'corbidevrepositories')}
            </span>
        `
    }

    if (state === 'active') {

        cell.innerHTML = `
            <button
                class="button"
                data-action="deactivate"
                data-name="${data.name || ''}">
                ${__('Deactivate', 'corbidevrepositories')}
            </button>
        `
    }

    if (state === 'inactive') {

        cell.innerHTML = `
            <button
                class="button button-primary"
                data-action="activate"
                data-name="${data.name || ''}">
                ${__('Activate', 'corbidevrepositories')}
            </button>
        `
    }
}

/**
 * Disable button during request
 */
function setLoading(btn, state) {
    if (!btn) return

    btn.disabled = state
    btn.classList.toggle('is-loading', state)
}

/**
 * Simple WP notice
 */
function showNotice(message) {

    const notice = document.createElement('div')

    notice.className = 'notice notice-success is-dismissible'
    notice.innerHTML = `<p>${message}</p>`

    document.body.prepend(notice)

    setTimeout(() => {
        notice.remove()
    }, 3000)
}