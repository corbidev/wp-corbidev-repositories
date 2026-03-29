import '@core-ui/styles/tailwind.css'

import CorbidevModal from '@core-ui/components/modal'
import CorbidevBanner from '@core-ui/components/banner'
import * as loading from '@core-ui/components/loading'
import * as toast from '@core-ui/components/toast'
import * as ajax from '@core-ui/components/ajax'
import * as error from '@core-ui/components/error'

import { initRegistry } from '@core-ui/utils/registry'
import { initEventBus } from '@core-ui/utils/eventBus'
import { initDispatcher } from '@core-ui/utils/dispatcher'

/**
 * =========================
 * INIT GLOBAL NAMESPACE
 * =========================
 */
window.CorbidevUI = window.CorbidevUI || {}

/**
 * =========================
 * INIT CORE SYSTEMS
 * =========================
 */
initRegistry(window.CorbidevUI)
initEventBus(window.CorbidevUI)

/**
 * =========================
 * REGISTER COMPONENTS
 * =========================
 */
window.CorbidevUI.register('modal', CorbidevModal)
window.CorbidevUI.register('banner', CorbidevBanner)

/**
 * =========================
 * ASSIGN UTILITIES
 * =========================
 */
Object.assign(window.CorbidevUI, {
    banner: CorbidevBanner,
    modal: CorbidevModal,
    loading,
    toast,
    ajax,
    error
})

/**
 * =========================
 * INIT DISPATCHER (data-ui)
 * =========================
 */
initDispatcher(window.CorbidevUI)