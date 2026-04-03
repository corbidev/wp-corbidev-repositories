import { useEffect, useMemo, useState, useSyncExternalStore } from "react"

import { cn } from "@/lib/utils"

import type {
  CorbidevActiveModal,
  CorbidevBannerRecord,
  CorbidevModalButton,
} from "./bridge"
import { CorbidevUiBridge } from "./bridge"

interface UiBridgeRootProps {
  bridge: CorbidevUiBridge
}

export function UiBridgeRoot({ bridge }: UiBridgeRootProps) {
  const snapshot = useSyncExternalStore(
    bridge.subscribe,
    bridge.getSnapshot,
    bridge.getSnapshot
  )

  return (
    <div className="theme">
      <BannerViewport
        banners={snapshot.banners}
        position="top"
        onClose={bridge.dismissBanner}
      />
      <BannerViewport
        banners={snapshot.banners}
        position="bottom"
        onClose={bridge.dismissBanner}
      />
      <ModalLayer
        bridge={bridge}
        modal={snapshot.activeModal}
      />
    </div>
  )
}

function BannerViewport({
  banners,
  position,
  onClose,
}: {
  banners: CorbidevBannerRecord[]
  position: "top" | "bottom"
  onClose: (id: string) => void
}) {
  const items = banners.filter((banner) => banner.position === position)

  if (items.length === 0) {
    return null
  }

  return (
    <div
      className={cn(
        "pointer-events-none fixed inset-x-0 z-[9999] flex px-5 sm:px-8",
        position === "top"
          ? "cdr-ui-top top-4 flex-col"
          : "cdr-ui-bottom bottom-4 flex-col-reverse"
      )}
    >
      <div className="mx-auto flex w-full max-w-5xl flex-col gap-3">
        {items.map((banner) => (
          <BannerItem
            key={banner.id}
            banner={banner}
            onClose={onClose}
          />
        ))}
      </div>
    </div>
  )
}

function BannerItem({
  banner,
  onClose,
}: {
  banner: CorbidevBannerRecord
  onClose: (id: string) => void
}) {
  const [paused, setPaused] = useState(false)
  const delay = Math.max(1, banner.delay) * 1000

  useEffect(() => {
    if (!banner.autoClose || paused) {
      return
    }

    const timer = window.setTimeout(() => {
      onClose(banner.id)
    }, delay)

    return () => {
      window.clearTimeout(timer)
    }
  }, [banner.autoClose, banner.id, delay, onClose, paused])

  return (
    <div
      className={cn(
        "pointer-events-auto overflow-hidden rounded-[1.75rem] border shadow-2xl ring-1 ring-black/5 backdrop-blur-sm",
        bannerToneClassName[banner.type]
      )}
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
    >
      <div className="flex items-start gap-4 px-5 py-4">
        <div className="min-w-0 flex-1">
          <p className="font-semibold leading-6">{formatBannerTitle(banner)}</p>
          <p className="mt-1 text-sm leading-6 opacity-85">
            {banner.message}
          </p>
        </div>
        {banner.closable ? (
          <button
            aria-label="Close notification"
            className="inline-flex h-8 w-8 items-center justify-center rounded-full text-current transition hover:bg-black/5"
            type="button"
            onClick={() => onClose(banner.id)}
          >
            x
          </button>
        ) : null}
      </div>
      {banner.autoClose ? (
        <div className="h-1 w-full bg-black/5">
          <div
            className={cn(
              "h-full origin-left scale-x-100 animate-[cdr-ui-progress_linear_forwards]",
              bannerProgressClassName[banner.type]
            )}
            style={{
              animationDuration: `${delay}ms`,
              animationPlayState: paused ? "paused" : "running",
            }}
          />
        </div>
      ) : null}
    </div>
  )
}

function ModalLayer({
  bridge,
  modal,
}: {
  bridge: CorbidevUiBridge
  modal: CorbidevActiveModal | null
}) {
  useEffect(() => {
    if (!modal) {
      return
    }

    const previousOverflow = document.body.style.overflow
    document.body.style.overflow = "hidden"

    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape" && modal.closable) {
        bridge.resolveActiveModal(false)
      }
    }

    document.addEventListener("keydown", onKeyDown)

    return () => {
      document.body.style.overflow = previousOverflow
      document.removeEventListener("keydown", onKeyDown)
    }
  }, [bridge, modal])

  const content = useMemo(() => {
    if (!modal) {
      return null
    }

    return {
      __html: modal.message || "",
    }
  }, [modal])

  if (!modal) {
    return null
  }

  return (
    <div className="fixed inset-0 z-[10000] flex items-center justify-center p-4">
      <button
        aria-hidden="true"
        className="absolute inset-0 bg-black/45 backdrop-blur-[2px]"
        type="button"
        onClick={() => {
          if (modal.overlayClose) {
            bridge.resolveActiveModal(false)
          }
        }}
      />
      <div
        className={cn(
          "relative z-10 flex w-full max-w-xl flex-col overflow-hidden rounded-[1.75rem] border bg-white shadow-2xl ring-1 ring-black/10",
          modal.type === "danger" && "border-red-200"
        )}
        role="dialog"
        aria-modal="true"
        aria-labelledby="corbidev-ui-modal-title"
      >
        <div className="flex items-start justify-between gap-4 border-b px-6 py-5">
          <div className="space-y-1">
            <h2
              className="text-lg font-semibold text-slate-950"
              id="corbidev-ui-modal-title"
            >
              {modal.title}
            </h2>
          </div>
          {modal.closable ? (
            <button
              aria-label="Close dialog"
              className="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-100 hover:text-slate-950"
              type="button"
              onClick={() => bridge.resolveActiveModal(false)}
            >
              x
            </button>
          ) : null}
        </div>
        <div className="px-6 py-5 text-sm leading-6 text-slate-600">
          <div dangerouslySetInnerHTML={content || undefined} />
        </div>
        <div className="flex flex-wrap justify-end gap-3 border-t bg-slate-50 px-6 py-4">
          {modal.buttons.map((button, index) => (
            <ModalAction
              key={`${modal.id}-${index}`}
              bridge={bridge}
              button={button}
            />
          ))}
        </div>
      </div>
    </div>
  )
}

function ModalAction({
  bridge,
  button,
}: {
  bridge: CorbidevUiBridge
  button: CorbidevModalButton
}) {
  return (
    <button
      className={cn(
        "inline-flex h-10 items-center justify-center rounded-full px-4 text-sm font-medium transition",
        actionButtonClassName[mapButtonVariant(button.type)]
      )}
      type="button"
      onClick={() => {
        button.action?.({
          close: (value?: unknown) => {
            bridge.resolveActiveModal(value)
          },
        })

        bridge.resolveActiveModal(button.value ?? true)
      }}
    >
      {button.label}
    </button>
  )
}

function mapButtonVariant(type?: string) {
  switch (type) {
    case "danger":
      return "destructive"
    case "secondary":
      return "secondary"
    case "outline":
      return "outline"
    case "ghost":
      return "ghost"
    default:
      return "default"
  }
}

function formatBannerTitle(banner: CorbidevBannerRecord) {
  if (banner.count <= 1) {
    return banner.title
  }

  return `${banner.title} (${banner.count})`
}

const bannerToneClassName: Record<CorbidevBannerRecord["type"], string> = {
  info: "border-sky-200/80 bg-sky-50/95 text-sky-950",
  success: "border-emerald-200/80 bg-emerald-50/95 text-emerald-950",
  warning: "border-amber-200/80 bg-amber-50/95 text-amber-950",
  danger: "border-red-200/80 bg-red-50/95 text-red-950",
  neutral: "border-slate-200/80 bg-white/95 text-slate-950",
  error: "border-red-200/80 bg-red-50/95 text-red-950",
}

const bannerProgressClassName: Record<CorbidevBannerRecord["type"], string> = {
  info: "bg-sky-500",
  success: "bg-emerald-500",
  warning: "bg-amber-500",
  danger: "bg-red-500",
  neutral: "bg-slate-500",
  error: "bg-red-500",
}

const actionButtonClassName = {
  default: "bg-slate-950 text-white hover:bg-slate-800",
  destructive: "bg-red-600 text-white hover:bg-red-700",
  secondary: "bg-slate-100 text-slate-900 hover:bg-slate-200",
  outline: "border border-slate-200 bg-white text-slate-900 hover:bg-slate-50",
  ghost: "bg-transparent text-slate-700 hover:bg-slate-100",
}
