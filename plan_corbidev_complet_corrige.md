# 📘 Plan COMPLET – Corbidev Architecture (version détaillée + actions fichiers + contrats)

---

# 🎯 Objectif

Créer une architecture :

- modulaire
- réutilisable multi-plugins
- sans dépendance implicite
- sans scripts spécifiques par plugin

👉 Objectif final :

➡️ Objectif :
Minimiser le JS spécifique plugin/thème (en se reposant au maximum sur core-ui)

---

# 🧱 Architecture cible

```
assets/
│
├── admin/
│   ├── components/
│   │   ├── repositoryManager.js
│   │   ├── repositoryInstaller.js
│   │   └── infoTabs.js
│   │
│   ├── api/
│   │   └── ajax.js
│   │
│   └── main.js
│
├── src/
│   └── main.js
│
└── core-ui/
    ├── main.js
    └── components/
        ├── banner.js
        ├── modal.js
        ├── loading.js
        ├── toast.js          ← (à créer)
        ├── ajax.js           ← (à créer)
        └── error.js          ← (à créer)
```

---

# 🔥 RÈGLE D’ARCHITECTURE

👉 core-ui gère les outils génériques (UI, AJAX, erreurs)

👉 admin/api définit les actions métier (WordPress)

👉 admin/components gère les interactions utilisateur

👉 Le plugin assemble ces couches sans dupliquer la logique

[ ] Tout ce qui est métier admin corbidevrepositories → assets/admin  
[ ] Tout ce qui est front corbidevrepositories → assets/src  
[ ] Tout ce qui est UI générique → assets/core-ui  

[ ] Aucun helper global implicite  
[ ] Aucun doublon entre core-ui et admin  

---

# 🧠 CONTRATS TECHNIQUES (AJOUT IMPORTANT)

## core-ui/ajax

```js
request(action: string, data?: object): Promise<{
  success: boolean,
  data?: any,
  message?: string
}>
```

## core-ui/loading

```js
set(element: HTMLElement, state: boolean): void
```

## core-ui/banner

```js
show(message: string, type: 'success' | 'error'): void
```

## core-ui/modal

```js
confirm(options: object): Promise<boolean>
```

## core-ui/toast

```js
show(message: string, type?: string): void
```

## core-ui/error

```js
handle(error: Error): void
```

---

# 📂 EXEMPLES D’UTILISATION (AJOUT IMPORTANT)

```js
await CorbidevUI.ajax.request('cdr_repo_add', { name, token })

CorbidevUI.loading.set(button, true)

CorbidevUI.banner.show('Saved', 'success')

const confirmed = await CorbidevUI.modal.confirm({ message: 'Confirm ?' })
```

---

# 📂 DÉTAIL PAR FICHIER

---

# 1️⃣ admin/main.js

## 📌 Rôle
Bootstrap admin WordPress

## 🛠 Modifications à prévoir
[ ] Ajouter DOMContentLoaded  
[ ] Importer core-ui  
[ ] Exposer CorbidevUI  

## ✅ Code attendu

```js
document.addEventListener('DOMContentLoaded', () => {
    initRepositoryManager()
    initRepositoryInstaller()
    initInfoTabs()
})
```

## 🤔 Pourquoi
- éviter erreurs DOM
- centraliser init

## 🔗 Liens
- appelle → components/*
- utilise → core-ui

---

# 2️⃣ admin/components/repositoryManager.js

## 📌 Rôle
Logique métier (repositories)

## 🛠 Modifications
[ ] supprimer setLoading local  
[ ] utiliser core-ui.loading  
[ ] vérifier updateRowState  

## 🔁 Remplacement

```diff
- setLoading(btn, true)
+ CorbidevUI?.loading?.set(btn, true)
```

## 🤔 Pourquoi
- éviter duplication
- standardiser UX

## 🔗 Dépendances
- utilise → core-ui (banner, modal, loading)
- utilise → api/ajax.js

---

# 3️⃣ admin/components/repositoryInstaller.js

## 📌 Rôle
Installer plugin/thème

## 🛠 Modifications
[ ] remplacer gestion loading par core-ui  
[ ] remplacer messages par banner/toast

## 🔗 Dépendances
- core-ui.modal
- core-ui.banner

---

# 4️⃣ admin/components/infoTabs.js

## 📌 Rôle
Gestion navigation tabs

## 🛠 Modifications
[ ] Aucune (déjà clean)

## 🔗 Dépendances
- aucune

---

# 5️⃣ admin/api/ajax.js

## 📌 Rôle
Wrapper AJAX spécifique admin

## 🛠 Modifications

[ ] Faire utiliser core-ui/ajax par admin/api  
[ ] NE PAS déplacer la logique métier dans core-ui  

## 🤔 Pourquoi
- éviter duplication entre plugins

## ✅ Exemple attendu

```js
export function request(action, data) {
    return CorbidevUI.ajax.request(action, data)
}
```

---

# 6️⃣ core-ui/components/loading.js

## 📌 Rôle
Gestion loading UI

## 🛠 Création
[ ] créer fichier

## 🔗 Utilisation
- appelé par components

---

# 7️⃣ core-ui/components/banner.js

## 📌 Rôle
Afficher messages

## 🛠 Vérifier
[ ] support erreurs WP  
[ ] support succès  

---

# 8️⃣ core-ui/components/modal.js

## 📌 Rôle
Confirmation utilisateur

## 🛠 Vérifier
[ ] Promise-based confirm  

---

# 9️⃣ core-ui/components/toast.js

## 📌 Rôle
Notifications stack

## 🛠 Création
[ ] créer système queue  
[ ] auto dismiss  

---

# 🔟 core-ui/components/ajax.js

## 📌 Rôle
Wrapper AJAX global

👉 core-ui/ajax doit normaliser les réponses :

- success → { success: true, data }
- error → { success: false, message }

👉 et ne jamais retourner la réponse brute WordPress

## 🛠 Création

```js
request(action, data)
```

## 🤔 Pourquoi

- centraliser la logique technique des requêtes AJAX
- éviter la duplication entre plugins
- standardiser les échanges avec WordPress

👉 core-ui/ajax :
- envoie la requête
- gère le nonce
- normalise le format des réponses
- gère les erreurs génériques

❌ core-ui/ajax ne doit jamais contenir :
- des noms d’actions WordPress spécifiques
- de logique métier
- de dépendance à un plugin

✅ core-ui/ajax doit rester 100% générique

---

# 11️⃣ core-ui/components/error.js

## 📌 Rôle
Gestion erreurs WP

👉 error.js doit :

- afficher via toast OU banner
- fallback automatique si toast indisponible
- logger console.error en dev

👉 ajax.js doit appeler error.js en cas d’échec

## 🛠 Création
[ ] parser wp_send_json_error  
[ ] fallback UI  

---

# 12️⃣ core-ui/main.js

## 📌 Rôle
Expose API globale

👉 core-ui/main.js doit importer tous les composants UI :

- banner
- modal
- loading
- toast
- ajax
- error

👉 puis les exposer via window.CorbidevUI

## 🛠 Création

```js
window.CorbidevUI = window.CorbidevUI || {}

Object.assign(window.CorbidevUI, {
    banner,
    modal,
    loading,
    toast,
    ajax,
    error
})
```

---

# 🔄 SUPPRESSIONS

[ ] supprimer setLoading partout  
[ ] supprimer duplications AJAX  
[ ] supprimer helpers locaux  

---

# 🔁 FLOW FINAL

```
Component (UI)
   ↓
admin/api (métier)
   ↓
core-ui (infra)
   ↓
WordPress
   ↓
UI / AJAX / Errors
```

---

# 🚀 RÉSULTAT FINAL

✔ aucun code dupliqué  
✔ plugins ultra légers  
✔ UX homogène  
✔ maintenance simple  

---

# 🎯 VISION

👉 écrire un plugin = config + HTML  
👉 JS = déjà prêt via core-ui  

---
