import { cdrRequest } from '@admin/api/ajax';

export function initRepositoryInstaller() {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action="install"]');
        if (!btn) return;

        const type = btn.dataset.type;
        const owner = btn.dataset.owner;
        const name = btn.dataset.name;

        btn.disabled = true;
        btn.innerText = 'Installation...';

        try {
            await cdrRequest('cdr_install_item', {
                type,
                owner,
                name
            });

            btn.innerText = 'Installé ✅';

        } catch (e) {
            btn.innerText = 'Erreur ❌';
        } finally {
            btn.disabled = false;
        }
    });
}