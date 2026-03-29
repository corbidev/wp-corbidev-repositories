/**
 * Corbidev UI - Registry
 * ----------------------
 * Register components safely in global namespace
 */

export function initRegistry(CorbidevUI) {

    if (CorbidevUI.register) return

    CorbidevUI.register = function (key, component) {

        if (!key || typeof key !== 'string') {
            console.warn('CorbidevUI.register: invalid key')
            return
        }

        if (!component) {
            console.warn(`CorbidevUI.register: component missing for "${key}"`)
            return
        }

        if (this[key]) {
            console.warn(`CorbidevUI: "${key}" already registered`)
            return
        }

        this[key] = component
    }
}