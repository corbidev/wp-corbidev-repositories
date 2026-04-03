import { createRoot, type Root } from "react-dom/client"

import "@styles/tailwind.css"

import { UiBridgeRoot } from "./UiBridgeRoot"
import { getOrCreateUiBridge } from "./bridge"

declare global {
  interface Window {
    __CDR_UI_ROOT__?: Root
  }
}

function mountUiBridge() {
  const bridge = getOrCreateUiBridge()

  if (!window.CorbidevUI) {
    window.CorbidevUI = bridge.createApi()
  }

  bridge.initDataUiDispatcher()

  let container = document.getElementById("corbidev-ui-root")

  if (!container) {
    container = document.createElement("div")
    container.id = "corbidev-ui-root"
    document.body.appendChild(container)
  }

  const root = window.__CDR_UI_ROOT__ ?? createRoot(container)
  window.__CDR_UI_ROOT__ = root
  root.render(<UiBridgeRoot bridge={bridge} />)
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountUiBridge, { once: true })
} else {
  mountUiBridge()
}
