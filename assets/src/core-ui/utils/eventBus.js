/**
 * Corbidev UI - Event Bus
 * ----------------------
 * Global event system
 */

export function initEventBus(CorbidevUI) {

    if (!CorbidevUI._events) {
        CorbidevUI._events = {}
    }

    if (!CorbidevUI.on) {
        CorbidevUI.on = function (event, callback) {
            if (!this._events[event]) {
                this._events[event] = []
            }

            this._events[event].push(callback)
        }
    }

    if (!CorbidevUI.off) {
        CorbidevUI.off = function (event, callback) {
            if (!this._events[event]) return

            this._events[event] = this._events[event].filter(cb => cb !== callback)
        }
    }

    if (!CorbidevUI.emit) {
        CorbidevUI.emit = function (event, data = null) {
            if (!this._events[event]) return

            this._events[event].forEach(cb => cb(data))
        }
    }
}