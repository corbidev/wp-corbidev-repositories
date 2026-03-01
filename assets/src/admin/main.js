import '@styles/tailwind.css'

document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('.corbidev-repositories-admin-form');
    if (!form) return;

    const ownerInput = form.querySelector('input[name="owner"]');
    const repoInput = form.querySelector('input[name="repository"]');
    const tokenInput = form.querySelector('input[name="token"]');
    const apiInput = form.querySelector('input[name="api_base_url"]');
    const editIndexInput = form.querySelector('input[name="edit_index"]');

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('[data-edit]').forEach((button) => {

        button.addEventListener('click', () => {

            const row = button.closest('tr');

            ownerInput.value = row.dataset.owner || '';
            repoInput.value = row.dataset.repo || '';
            tokenInput.value = row.dataset.token || '';
            apiInput.value = row.dataset.api || 'https://api.github.com';
            editIndexInput.value = row.dataset.index || '';

            form.scrollIntoView({ behavior: 'smooth' });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Confirm Delete
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('[data-delete]').forEach((button) => {

        button.addEventListener('click', (e) => {

            if (!confirm('Delete this repository?')) {
                e.preventDefault();
            }
        });
    });

});