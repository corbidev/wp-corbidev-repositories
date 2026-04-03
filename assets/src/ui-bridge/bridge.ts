type CorbidevTranslator = (text: string, domain?: string) => string
type CorbidevListener = (snapshot: CorbidevUiSnapshot) => void
type CorbidevEventHandler = (payload?: unknown) => void

export type CorbidevBannerType =
  | "info"
  | "success"
  | "warning"
  | "danger"
  | "neutral"
  | "error"

export interface CorbidevModalButton {
  label: string
  type?: string
  value?: unknown
  action?: (helpers: { close: (value?: unknown) => void }) => void
}

export interface CorbidevModalOptions {
  title?: string
  message?: string
  buttons?: CorbidevModalButton[]
  closable?: boolean
  overlayClose?: boolean
  type?: string
}

export interface CorbidevBannerOptions {
  message: string
  title?: string
  type?: CorbidevBannerType
  position?: "top" | "bottom"
  closable?: boolean
  autoClose?: boolean
  delay?: number
}

export interface CorbidevUiSnapshot {
  activeModal: CorbidevActiveModal | null
  banners: CorbidevBannerRecord[]
}

export interface CorbidevActiveModal {
  id: string
  title: string
  message: string
  buttons: CorbidevModalButton[]
  closable: boolean
  overlayClose: boolean
  type: string
}

export interface CorbidevBannerRecord {
  id: string
  title: string
  message: string
  type: CorbidevBannerType
  position: "top" | "bottom"
  closable: boolean
  autoClose: boolean
  delay: number
  count: number
}

interface ModalQueueEntry {
  id: string
  options: CorbidevActiveModal
  resolve: (value: unknown) => void
}

declare global {
  interface Window {
    CorbidevUI?: ReturnType<CorbidevUiBridge["createApi"]>
    __CDR_UI_BRIDGE__?: CorbidevUiBridge
    ajaxurl?: string
    cdr_ajax?: {
      ajax_url?: string
      nonce?: string
    }
    wp?: {
      i18n?: {
        __?: CorbidevTranslator
      }
    }
  }
}

export class CorbidevUiBridge {
  private listeners = new Set<CorbidevListener>()
  private eventHandlers = new Map<string, Set<CorbidevEventHandler>>()
  private banners: CorbidevBannerRecord[] = []
  private modalQueue: ModalQueueEntry[] = []
  private activeModal: ModalQueueEntry | null = null
  private snapshot: CorbidevUiSnapshot = {
    activeModal: null,
    banners: [],
  }
  private counter = 0
  private translate: CorbidevTranslator = (text) => text
  private dispatcherInitialized = false

  subscribe = (listener: CorbidevListener) => {
    this.listeners.add(listener)
    return () => {
      this.listeners.delete(listener)
    }
  }

  getSnapshot = (): CorbidevUiSnapshot => this.snapshot

  setI18n = (i18n?: { __?: CorbidevTranslator }) => {
    if (typeof i18n?.__ === "function") {
      this.translate = i18n.__
    }
  }

  openModal = (options: CorbidevModalOptions = {}) =>
    new Promise((resolve) => {
      const entry: ModalQueueEntry = {
        id: this.nextId("modal"),
        options: {
          id: this.nextId("modal-view"),
          title: options.title ?? "",
          message: options.message ?? "",
          buttons: options.buttons ?? [
            {
              label: this.translate("Close", "corbidevrepositories"),
              type: "secondary",
              value: true,
            },
          ],
          closable: options.closable ?? true,
          overlayClose: options.overlayClose ?? true,
          type: options.type ?? "default",
        },
        resolve,
      }

      this.modalQueue.push(entry)
      this.flushModalQueue()
    })

  confirmModal = (options: CorbidevModalOptions = {}) =>
    this.openModal({
      ...options,
      buttons: options.buttons ?? [
        {
          label: this.translate("Cancel", "corbidevrepositories"),
          type: "secondary",
          value: false,
        },
        {
          label: this.translate("Confirm", "corbidevrepositories"),
          type: options.type === "danger" ? "danger" : "default",
          value: true,
        },
      ],
    })

  resolveActiveModal = (value: unknown = false) => {
    if (!this.activeModal) {
      return
    }

    const current = this.activeModal
    this.activeModal = null
    current.resolve(value)
    this.emit("modal.close", current.options)
    this.notify()
    this.flushModalQueue()
  }

  showBanner = (input: CorbidevBannerOptions | string) => {
    const options =
      typeof input === "string"
        ? { message: input }
        : input

    const type = normalizeBannerType(options.type)
    const position = options.position ?? "top"
    const existing = this.banners.find(
      (banner) =>
        banner.message === options.message &&
        banner.type === type &&
        banner.position === position
    )

    if (existing) {
      existing.count += 1
      this.notify()
      return existing
    }

    const banner: CorbidevBannerRecord = {
      id: this.nextId("banner"),
      title: options.title ?? defaultBannerTitle(type, this.translate),
      message: options.message,
      type,
      position,
      closable: options.closable ?? true,
      autoClose: options.autoClose ?? true,
      delay: options.delay ?? 4,
      count: 1,
    }

    this.banners = [banner, ...this.banners].slice(0, 5)
    this.emit("banner.open", banner)
    this.notify()

    return banner
  }

  dismissBanner = (id: string) => {
    const banner = this.banners.find((item) => item.id === id)
    if (!banner) {
      return
    }

    this.banners = this.banners.filter((item) => item.id !== id)
    this.emit("banner.close", banner)
    this.notify()
  }

  setLoading = (button: HTMLElement | null, state: boolean) => {
    if (!button) {
      return
    }

    const element = button as HTMLButtonElement

    element.disabled = state

    if (state) {
      element.dataset.originalText = element.innerHTML
      element.innerHTML = "..."
      return
    }

    element.innerHTML = element.dataset.originalText || element.innerHTML
  }

  request = async (action: string, data: Record<string, unknown> = {}) => {
    const endpoint = window.cdr_ajax?.ajax_url || window.ajaxurl

    if (!endpoint) {
      throw new Error("WordPress AJAX URL is not available.")
    }

    const body = new URLSearchParams()
    body.set("action", action)

    for (const [key, value] of Object.entries(data)) {
      if (value === undefined || value === null) {
        continue
      }

      body.set(key, String(value))
    }

    if (window.cdr_ajax?.nonce && !body.has("nonce")) {
      body.set("nonce", window.cdr_ajax.nonce)
    }

    const response = await fetch(endpoint, {
      method: "POST",
      body,
      credentials: "same-origin",
    })

    let payload: {
      success?: boolean
      data?: {
        message?: string
      } & Record<string, unknown>
    }

    try {
      payload = await response.json()
    } catch {
      throw new Error("Invalid server response.")
    }

    if (!response.ok) {
      throw new Error(
        payload?.data?.message || `Request failed with status ${response.status}.`
      )
    }

    if (!payload?.success) {
      throw new Error(payload?.data?.message || "An unexpected error occurred.")
    }

    return {
      success: true,
      data: payload.data,
    }
  }

  handleError = (error: unknown) => {
    console.error(error)
    this.showBanner({
      message: toErrorMessage(error),
      type: "danger",
    })
  }

  on = (event: string, handler: CorbidevEventHandler) => {
    if (!this.eventHandlers.has(event)) {
      this.eventHandlers.set(event, new Set())
    }

    this.eventHandlers.get(event)?.add(handler)
  }

  off = (event: string, handler: CorbidevEventHandler) => {
    this.eventHandlers.get(event)?.delete(handler)
  }

  emit = (event: string, payload?: unknown) => {
    this.eventHandlers.get(event)?.forEach((handler) => {
      handler(payload)
    })
  }

  createApi = () => {
    const bridge = this
    const registry: Record<string, unknown> = {}

    return {
      register(key: string, component: unknown) {
        if (!key || typeof key !== "string" || !component || registry[key]) {
          return
        }

        registry[key] = component
      },
      modal: {
        open: bridge.openModal,
        confirm: bridge.confirmModal,
        setI18n: bridge.setI18n,
      },
      banner: {
        show: bridge.showBanner,
        setI18n: bridge.setI18n,
      },
      toast: {
        show: (message: string, type: CorbidevBannerType = "info") =>
          bridge.showBanner({
            message,
            type,
            delay: 3,
            autoClose: true,
          }),
      },
      loading: {
        set: bridge.setLoading,
      },
      ajax: {
        request: bridge.request,
      },
      error: {
        handle: bridge.handleError,
      },
      on: bridge.on,
      off: bridge.off,
      emit: bridge.emit,
    }
  }

  initDataUiDispatcher = () => {
    if (this.dispatcherInitialized) {
      return
    }

    this.dispatcherInitialized = true

    document.addEventListener("click", async (event) => {
      const target = event.target

      if (!(target instanceof Element)) {
        return
      }

      const element = target.closest("[data-ui]")

      if (!(element instanceof HTMLElement)) {
        return
      }

      const type = element.dataset.ui
      const options = parseUiOptions(element)

      switch (type) {
        case "modal":
          void this.openModal(options)
          break
        case "confirm": {
          const confirmed = await this.confirmModal(options)

          if (!confirmed) {
            return
          }

          element.dispatchEvent(
            new CustomEvent("corbidev:confirm", {
              bubbles: true,
              detail: { confirmed },
            })
          )
          break
        }
        case "banner":
          this.showBanner(options as CorbidevBannerOptions)
          break
      }
    })
  }

  private flushModalQueue() {
    if (this.activeModal || this.modalQueue.length === 0) {
      return
    }

    this.activeModal = this.modalQueue.shift() || null

    if (!this.activeModal) {
      return
    }

    this.emit("modal.open", this.activeModal.options)
    this.notify()
  }

  private notify() {
    this.snapshot = {
      activeModal: this.activeModal?.options ?? null,
      banners: [...this.banners],
    }

    const snapshot = this.snapshot
    this.listeners.forEach((listener) => {
      listener(snapshot)
    })
  }

  private nextId(prefix: string) {
    this.counter += 1
    return `${prefix}-${this.counter}`
  }
}

export function getOrCreateUiBridge() {
  if (window.__CDR_UI_BRIDGE__) {
    return window.__CDR_UI_BRIDGE__
  }

  const bridge = new CorbidevUiBridge()

  if (typeof window.wp?.i18n?.__ === "function") {
    bridge.setI18n(window.wp.i18n)
  }

  window.__CDR_UI_BRIDGE__ = bridge

  return bridge
}

function normalizeBannerType(type?: CorbidevBannerType) {
  if (type === "error") {
    return "danger"
  }

  return type ?? "info"
}

function defaultBannerTitle(
  type: CorbidevBannerType,
  translate: CorbidevTranslator
) {
  switch (type) {
    case "success":
      return translate("Success", "corbidevrepositories")
    case "warning":
      return translate("Warning", "corbidevrepositories")
    case "danger":
      return translate("Error", "corbidevrepositories")
    case "neutral":
      return translate("Notice", "corbidevrepositories")
    default:
      return translate("Information", "corbidevrepositories")
  }
}

function toErrorMessage(error: unknown) {
  if (error instanceof Error && error.message) {
    return error.message
  }

  return "An unexpected error occurred."
}

function parseUiOptions(element: HTMLElement) {
  const options: CorbidevModalOptions & CorbidevBannerOptions = {
    message: element.dataset.uiMessage || "",
  }

  if (element.dataset.uiTitle) {
    options.title = element.dataset.uiTitle
  }

  if (element.dataset.uiType) {
    options.type = element.dataset.uiType as CorbidevBannerType
  }

  if (element.dataset.uiAutoClose !== undefined) {
    options.autoClose = parseBoolean(element.dataset.uiAutoClose)
  }

  if (element.dataset.uiDelay) {
    options.delay = parseNumber(element.dataset.uiDelay)
  }

  if (element.dataset.uiPosition === "top" || element.dataset.uiPosition === "bottom") {
    options.position = element.dataset.uiPosition
  }

  if (element.dataset.uiButtons) {
    try {
      options.buttons = JSON.parse(element.dataset.uiButtons) as CorbidevModalButton[]
    } catch (error) {
      console.warn("[CorbidevUI] Invalid JSON in data-ui-buttons", error)
    }
  }

  return options
}

function parseBoolean(value: string) {
  return value === "true" || value === "1"
}

function parseNumber(value: string) {
  const parsed = Number(value)
  return Number.isNaN(parsed) ? undefined : parsed
}
