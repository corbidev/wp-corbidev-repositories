export function initInfoTabs() {

    document.addEventListener('click', (e) => {

        const tab = e.target.closest('[data-tab]')
        if (!tab) return

        // 👉 uniquement sur page info
        if (!document.body.classList.contains('toplevel_page_corbidev-repositories')
            && !window.location.href.includes('corbidev-info')) {
            return
        }

        e.preventDefault()

        const key = tab.dataset.tab

        const url = new URL(window.location)
        url.searchParams.set('tab', key)

        window.history.replaceState({}, '', url)

        // reload contrôlé (ou ajax plus tard)
        window.location.search = url.searchParams.toString()
    })
}