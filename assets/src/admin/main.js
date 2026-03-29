import '@admin/styles/admin.css'

import { initRepositoryManager } from './components/repositoryManager'
import { initRepositoryInstaller } from './components/repositoryInstaller'
import { initInfoTabs } from './components/infoTabs'

import CorbidevBanner from '@core-ui/components/banner'
import CorbidevModal from '@core-ui/components/modal'

/**
 * =========================
 * i18n (WordPress → core-ui)
 * =========================
 */
const { __ } = window.wp.i18n

CorbidevBanner.setI18n({ __ })
CorbidevModal.setI18n({ __ })

/**
 * =========================
 * Init modules
 * =========================
 */
initRepositoryManager()
initRepositoryInstaller()
initInfoTabs()

/**
 * =========================
 * Core UI global access
 * =========================
 */
window.CorbidevUI = window.CorbidevUI || {}
window.CorbidevUI.banner = CorbidevBanner
window.CorbidevUI.modal = CorbidevModal