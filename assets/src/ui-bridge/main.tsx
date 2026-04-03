import { createRoot } from "react-dom/client"

import "@styles/tailwind.css"

import { UiBridgeRoot } from "./UiBridgeRoot"
import { getOrCreateUiBridge } from "./bridge"

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

  const root = createRoot(container)
  root.render(<UiBridgeRoot bridge={bridge} />)
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountUiBridge, { once: true })
} else {
  mountUiBridge()
}
