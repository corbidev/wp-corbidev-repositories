# Correction des appels modal - Corbidev Core UI

## Liste des fichiers à corriger

### JS (CRITIQUE)
 - [X] assets/src/admin/components/repositoryInstaller.js  
 - [X] assets/src/admin/main.js  

### PHP (templates / admin)
 - [ ] admin/pages/info.php  
 - [ ] admin/pages/repositories.php  
 - [ ] admin/pages/repositories-manager.php  

### Templates UI
 - [ ] admin/pages/components/modal.php  
 - [ ] core-ui/templates/modal.php  

---

## Corrections à faire (fichier par fichier)

### 1. repositoryInstaller.js

#### Problème
Ancien modal :
```js
new CorbidevModal()
corbidevModal.open()
```

#### Correction
```js
const confirmed = await window.CorbidevUI.modal.confirm({
    title: 'Confirm',
    message: 'Install plugin?',
})

if (!confirmed) return
```

---

### 2. admin/main.js

#### Problème
```js
window.corbidevModal
```

#### Correction
```js
window.CorbidevUI.modal
```

---

### 3. info.php

#### Problème
```html
onclick="corbidevModal.open(...)"
```

#### Correction
```html
onclick="CorbidevUI.modal.open({...})"
```

---

### 4. repositories.php

#### Correction
Remplacer :
```js
corbidevModal.*
```
Par :
```js
CorbidevUI.modal.*
```

---

### 5. repositories-manager.php

Même correction :
```js
CorbidevUI.modal.confirm(...)
```

---

### 6. admin/pages/components/modal.php

#### Problème
Ancien HTML modal

#### Correction
Supprimer le fichier ou le vider

---

### 7. core-ui/templates/modal.php

#### Doit contenir uniquement
```html
<div id="corbidev-modal-root"></div>
```

---

## Règles globales

### Interdit
- new CorbidevModal()
- corbidevModal.*

### Obligatoire
```js
window.CorbidevUI.modal.open(...)
window.CorbidevUI.modal.confirm(...)
window.CorbidevUI.modal.alert(...)
```

---

## Résultat final

- API unifiée
- Async modal
- Core UI centralisé
- Zéro conflit
