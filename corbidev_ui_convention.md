# Corbidev UI Convention (data-* + API unifiée)

## Objectif

Créer un système universel :

HTML (data-*) → Core UI → modal / banner

- Aucun JS par page
- Réutilisable (plugins + thèmes)
- Extensible et scalable

---

## 📦 ✅ LISTE DES FICHIERS À MODIFIER

🔴 CORE UI (CRITIQUE)
 - [X] assets/src/core-ui/main.js
 - [X] assets/src/core-ui/components/modal.js
 - [X] assets/src/core-ui/components/banner.js

🟠 ADMIN JS (LOGIQUE MÉTIER)
 - [X] assets/src/admin/components/ repositoryInstaller.js
 - [X] assets/src/admin/components/repositoryManager.js

🟡 TEMPLATES PHP (DATA-* UI)
 - [X] admin/pages/info.php
 - [ ] admin/pages/repositories.php
 - [ ] admin/pages/repositories-manager.php

🟢 CLEANUP (SUPPRESSION LEGACY)
 - [ ] admin/pages/components/modal.php
 - [ ] assets/src/admin/components/modal.js (ancien modal admin)

🔵 OPTIONNEL (RECOMMANDÉ)
 - [X] assets/src/core-ui/utils/parser.js (si tu veux un parser data- propre)*

---

## 1. Convention globale

### Attribut principal

```
data-ui="modal | confirm | banner"
```

---

### Modal

```
<button
    data-ui="modal"
    data-ui-title="Delete"
    data-ui-message="Are you sure?"
    data-ui-type="danger">
    Delete
</button>
```

---

### Confirm

```
<button
    data-ui="confirm"
    data-ui-title="Confirm"
    data-ui-message="Install plugin?">
    Install
</button>
```

---

### Banner

```
<button
    data-ui="banner"
    data-ui-message="Saved"
    data-ui-type="success">
    Show banner
</button>
```

---

## 2. API unifiée

Côté JavaScript :

```
CorbidevUI.modal.open()
CorbidevUI.modal.confirm()
CorbidevUI.banner.show()
```

---

## 3. Dispatcher global (Core UI)

```js
document.addEventListener('click', async (e) => {

    const el = e.target.closest('[data-ui]')
    if (!el) return

    const type = el.dataset.ui

    const options = {
        title: el.dataset.uiTitle,
        message: el.dataset.uiMessage,
        type: el.dataset.uiType || 'info'
    }

    switch (type) {

        case 'modal':
            window.CorbidevUI.modal.open(options)
            break

        case 'confirm':
            const confirmed = await window.CorbidevUI.modal.confirm(options)

            if (!confirmed) return

            el.dispatchEvent(new CustomEvent('corbidev:confirm', {
                bubbles: true
            }))
            break

        case 'banner':
            window.CorbidevUI.banner.show(options)
            break
    }

})
```

---

## 4. Système d’événements

### Exemple HTML

```
<button
    data-ui="confirm"
    data-ui-title="Install"
    data-ui-message="Install plugin?"
    data-action="install"
    data-type="plugin"
    data-owner="corbidev"
    data-name="test">
    Install
</button>
```

---

### JS métier

```js
document.addEventListener('corbidev:confirm', (e) => {

    const btn = e.target.closest('[data-action="install"]')
    if (!btn) return

    // logique métier ici
})
```

---

## 5. Avantages

- Zéro JS par page
- Compatible thème et plugin
- API stable
- Séparation UI / métier
- Réutilisable partout

---

## 6. Options avancées

### Boutons custom

```
data-ui-buttons='[
    {"label":"Cancel","value":false},
    {"label":"OK","value":true}
]'
```

JS :

```js
if (el.dataset.uiButtons) {
    options.buttons = JSON.parse(el.dataset.uiButtons)
}
```

---

### Banner delay

```
data-ui-delay="5"
```

---

## 7. Règle officielle

- data-ui = trigger
- data-* = configuration
- Core UI = exécution
- JS métier = réaction

---

## 8. Conclusion

- PHP décrit l’action
- Core UI exécute
- JS métier réagit

➡️ Mini framework UI WordPress prêt production
