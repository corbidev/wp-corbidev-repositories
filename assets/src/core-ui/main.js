import '@core-ui/styles/tailwind.css'

import CorbidevModal from '@core-ui/components/modal'
import CorbidevBanner from '@core-ui/components/banner'

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
 * INIT DISPATCHER (data-ui)
 * =========================
 */
initDispatcher(window.CorbidevUI)