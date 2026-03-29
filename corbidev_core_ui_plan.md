# Plan de correction Core UI - Corbidev

## Liste des fichiers à corriger

1. loader/bootstrap.php  
2. core-ui/includes/Core/Assets.php  
3. core-ui/includes/Core/Renderer.php  
4. includes/Core/Plugin.php  
5. assets/src/admin/main.js  
6. assets/src/main.js  
7. vite.config.js  

---

## Corrections à faire (fichier par fichier)

### 1. loader/bootstrap.php

Problèmes :
- lecture du manifest faite ici ET ailleurs
- mélange orchestration + assets
- duplication potentielle

À faire :

Ajouter :

```php
global $cdr_manifest;

$manifest_path = CDR_PLUGIN_DIR . 'assets/dist/.vite/manifest.json';

$cdr_manifest = file_exists($manifest_path)
    ? json_decode(file_get_contents($manifest_path), true)
    : [];
```

Ensuite :

```php
function corbidev_get_manifest(): array {
    global $cdr_manifest;
    return $cdr_manifest ?? [];
}
```

Résultat :
- manifest lu une seule fois
- accessible partout

---

### 2. core-ui/includes/Core/Assets.php

Problèmes :
- relit le manifest
- fallback inutile
- logique trop lourde

À faire :

Supprimer toute lecture de manifest.

Utiliser :

```php
$manifest = corbidev_get_manifest();
$entry = $manifest['assets/src/core-ui/main.js'] ?? null;
```

Résultat :
- zéro duplication
- plus rapide
- logique claire

---

### 3. core-ui/includes/Core/Renderer.php

Problème :
- dépendance implicite plugin

À faire :

Remplacer CDR_PLUGIN_DIR par un chemin calculé ou injecté.

Résultat :
- Core UI indépendant

---

### 4. includes/Core/Plugin.php

Problèmes :
- mélange UI / logique métier
- duplication possible

À faire :

Ne pas charger Core UI ici.

Garder uniquement :
- menus
- ajax
- logique admin

Résultat :
- découplage propre

---

### 5. assets/src/admin/main.js

Problème :
- import Core UI

À faire :

Supprimer :
```js
import CorbidevModal
import CorbidevBanner
```

Résultat :
- évite double chargement

---

### 6. assets/src/main.js

Même correction que admin.js

Résultat :
- Core UI chargé uniquement via PHP

---

### 7. vite.config.js

Problème :
- mauvaise clé

À faire :

```js
input: {
  app: ...,
  admin: ...,
  'core-ui': path.resolve(__dirname, 'assets/src/core-ui/main.js')
}
```

Résultat :
- clé stable dans manifest

---

## Architecture finale

bootstrap.php
→ manifest chargé une fois
→ Assets utilise manifest
→ Renderer gère HTML
→ Plugin gère logique

---

## Règle d’or

- 1 manifest → 1 lecture
- 1 UI → 1 loader
- 1 responsabilité par fichier

---

## Résultat final

- plus de doublons JS
- plus d’erreur “already declared”
- Core UI stable
- meilleures performances
- architecture scalable
