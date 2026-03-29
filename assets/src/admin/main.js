import '@admin/styles/admin.css'

import { initRepositoryManager } from './components/repositoryManager'
import { initRepositoryInstaller } from './components/repositoryInstaller'
import { initInfoTabs } from './components/infoTabs'

const { __ } = window.wp.i18n

/**
 * =========================
 * Init modules (on DOMContentLoaded)
 * =========================
 */
document.addEventListener('DOMContentLoaded', () => {
    if (window.CorbidevUI?.banner?.setI18n) {
        window.CorbidevUI.banner.setI18n({ __ })
    }

    if (window.CorbidevUI?.modal?.setI18n) {
        window.CorbidevUI.modal.setI18n({ __ })
    }

    initRepositoryManager()
    initRepositoryInstaller()
    initInfoTabs()
})