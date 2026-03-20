import { cdrRequest } from '../api/ajax'

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

            location.reload()
        })
    }

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('.cdr-delete')
        if (!btn) return

        if (!confirm('Supprimer ce dépôt ?')) return

        await cdrRequest('cdr_repo_delete', {
            name: btn.dataset.name
        })

        location.reload()
    })
}