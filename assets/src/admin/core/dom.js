export function $(selector, scope = document) {
    return scope.querySelector(selector)
}

export function $$(selector, scope = document) {
    return scope.querySelectorAll(selector)
}

export function on(el, event, callback) {
    if (!el) return
    el.addEventListener(event, callback)
}