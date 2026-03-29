/**
 * Corbidev UI - Data Attribute Parser
 * -----------------------------------
 * Transform data-ui-* attributes into usable JS options
 */

export function parseUIOptions(el) {
    if (!el || !el.dataset) {
        return {}
    }

    const options = {}

    // =========================
    // BASIC FIELDS
    // =========================
    if (el.dataset.uiTitle) {
        options.title = el.dataset.uiTitle
    }

    if (el.dataset.uiMessage) {
        options.message = el.dataset.uiMessage
    }

    if (el.dataset.uiType) {
        options.type = el.dataset.uiType
    }

    // =========================
    // BOOLEAN OPTIONS
    // =========================
    if (el.dataset.uiAutoClose !== undefined) {
        options.autoClose = parseBoolean(el.dataset.uiAutoClose)
    }

    // =========================
    // NUMERIC OPTIONS
    // =========================
    if (el.dataset.uiDelay) {
        options.delay = parseNumber(el.dataset.uiDelay)
    }

    if (el.dataset.uiPriority) {
        options.priority = parseNumber(el.dataset.uiPriority)
    }

    // =========================
    // POSITION
    // =========================
    if (el.dataset.uiPosition) {
        options.position = el.dataset.uiPosition
    }

    // =========================
    // BUTTONS (JSON)
    // =========================
    if (el.dataset.uiButtons) {
        try {
            options.buttons = JSON.parse(el.dataset.uiButtons)
        } catch (e) {
            console.warn('[CorbidevUI] Invalid JSON in data-ui-buttons', e)
        }
    }

    return options
}

/**
 * Parse boolean values
 */
function parseBoolean(value) {
    if (typeof value === 'boolean') return value

    return value === 'true' || value === '1'
}

/**
 * Parse numbers safely
 */
function parseNumber(value) {
    const n = Number(value)
    return isNaN(n) ? undefined : n
}