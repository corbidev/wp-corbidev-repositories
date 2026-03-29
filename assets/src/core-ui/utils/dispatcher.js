import { parseUIOptions } from './parser'

/**
 * Corbidev UI - Dispatcher
 * -----------------------
 * Handle data-ui interactions globally
 */

export function initDispatcher(CorbidevUI) {

    document.addEventListener('click', async (e) => {

        const el = e.target.closest('[data-ui]')
        if (!el) return

        const type = el.dataset.ui
        const options = parseUIOptions(el)

        switch (type) {

            /**
             * =========================
             * MODAL
             * =========================
             */
            case 'modal':
                CorbidevUI.modal?.open(options)
                break

            /**
             * =========================
             * CONFIRM
             * =========================
             */
            case 'confirm': {
                const confirmed = await CorbidevUI.modal?.confirm(options)

                if (!confirmed) return

                el.dispatchEvent(new CustomEvent('corbidev:confirm', {
                    bubbles: true,
                    detail: { confirmed }
                }))
                break
            }

            /**
             * =========================
             * BANNER
             * =========================
             */
            case 'banner':
                CorbidevUI.banner?.show(options)
                break
        }
    })
}