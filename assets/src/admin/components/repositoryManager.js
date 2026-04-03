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

            form.reset()
            showBanner(__('Repository added', 'corbidevrepositories'), 'success')
            window.setTimeout(() => window.location.reload(), 250)
        })
    }

    document.addEventListener('cdr:item-state-changed', (e) => {
        const row = e.detail?.row
        const state = e.detail?.state

        if (!row || !state) {
            return
        }

        updateRowState(row, state)
    })

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('[data-action]')
        if (!btn) return

        const action = btn.dataset.action
        const row = btn.closest('tr')

        try {

            switch (action) {

                case 'activate':
                case 'deactivate':
                case 'delete':
                case 'update':
                    window.CorbidevUI?.loading?.set(btn, true)
                    break
            }

            switch (action) {

                case 'repo-delete': {

                    const confirmed = await window.CorbidevUI?.modal?.confirm({
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

                    const confirmed = await window.CorbidevUI?.modal?.confirm({
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
                    row && updateRowState(row, 'update', btn.dataset)
                    showBanner(__('Updated', 'corbidevrepositories'), 'success')
                    break
                }
            }

        } catch (err) {

            console.error(err)

            window.CorbidevUI?.banner?.show({
                message: err?.message || __('An error occurred', 'corbidevrepositories'),
                type: 'danger'
            })

        } finally {
            if (btn) window.CorbidevUI?.loading?.set(btn, false)
        }
    })
}

function showBanner(message, type = 'success') {
    window.CorbidevUI?.banner?.show({
        message,
        type
    })
}

function updateRowState(row, state, dataset = {}) {

    row.dataset.itemInstalled = state === 'installed' || state === 'active' || state === 'inactive' ? '1' : row.dataset.itemInstalled
    row.dataset.itemActive = state === 'active' ? '1' : state === 'inactive' || state === 'installed' ? '0' : row.dataset.itemActive
    row.dataset.itemHasUpdate = state === 'update-cleared' ? '0' : row.dataset.itemHasUpdate

    if (state === 'installed') {
        row.dataset.itemHasUpdate = '0'
    }

    if (state === 'active' || state === 'inactive') {
        row.dataset.itemInstalled = '1'
    }

    if (state === 'update') {
        row.dataset.itemHasUpdate = '0'
    }

    const statusCell = row.querySelector('[data-role="status"]')
    const actionsCell = row.querySelector('[data-role="actions"]')

    if (!statusCell || !actionsCell) {
        return
    }

    const itemType = row.dataset.itemType
    const itemName = row.dataset.itemName
    const itemOwner = row.dataset.itemOwner
    const itemSlug = row.dataset.itemSlug
    const isInstalled = row.dataset.itemInstalled === '1'
    const isActive = row.dataset.itemActive === '1'
    const hasUpdate = row.dataset.itemHasUpdate === '1'

    statusCell.innerHTML = renderStatusMarkup({ itemType, isInstalled, isActive, hasUpdate })
    actionsCell.innerHTML = renderActionsMarkup({
        itemType,
        itemName,
        itemOwner,
        itemSlug,
        isInstalled,
        isActive,
        hasUpdate,
    })
}

function renderStatusMarkup({ itemType, isInstalled, isActive, hasUpdate }) {

    const badges = []

    if (!isInstalled) {
        badges.push(badge('neutral', 'bg-slate-400', translate('Not installed')))
    } else if (itemType === 'plugin' && isActive) {
        badges.push(badge('success', 'bg-emerald-500', translate('Active')))
    } else if (itemType === 'plugin') {
        badges.push(badge('warning', 'bg-amber-500', translate('Installed, inactive')))
    } else {
        badges.push(badge('success', 'bg-emerald-500', translate('Installed')))
    }

    if (hasUpdate) {
        badges.push(badge('warning', 'bg-amber-500', translate('Update available')))
    }

    return `<div class="cdr-actions">${badges.join('')}</div>`
}

function renderActionsMarkup({
    itemType,
    itemName,
    itemOwner,
    itemSlug,
    isInstalled,
    isActive,
    hasUpdate,
}) {

    if (!isInstalled) {
        return `<div class="cdr-actions">
            ${button('cdr-btn cdr-btn-primary', 'install', {
                name: itemName,
                owner: itemOwner,
                type: itemType,
            }, translate('Install'))}
        </div>`
    }

    const buttons = []

    if (itemType === 'plugin') {
        buttons.push(
            isActive
                ? button('cdr-btn cdr-btn-secondary', 'deactivate', { name: itemSlug }, translate('Deactivate'))
                : button('cdr-btn cdr-btn-primary', 'activate', { name: itemSlug }, translate('Activate'))
        )
    }

    if (hasUpdate) {
        buttons.push(button('cdr-btn cdr-btn-outline', 'update', {
            name: itemName,
            owner: itemOwner,
            type: itemType,
        }, translate('Update')))
    }

    buttons.push(button('cdr-btn cdr-btn-danger', 'delete', {
        name: itemSlug,
        type: itemType,
    }, translate('Delete')))

    return `<div class="cdr-actions">${buttons.join('')}</div>`
}

function badge(variant, dotClassName, label) {
    return `<span class="cdr-badge cdr-badge-${variant}">
        <span class="cdr-status-dot ${dotClassName}"></span>
        ${label}
    </span>`
}

function button(className, action, dataset, label) {
    const attributes = Object.entries(dataset)
        .map(([key, value]) => `data-${key}="${String(value)}"`)
        .join(' ')

    return `<button class="${className}" data-action="${action}" ${attributes} type="button">${label}</button>`
}

function translate(text) {
    return window.wp?.i18n?.__(text, 'corbidevrepositories') ?? text
}
