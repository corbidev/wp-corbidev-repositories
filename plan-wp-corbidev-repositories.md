# 📦 Plan de travail — wp-corbidev-repositories

## 🎯 Objectif
Créer un plugin parent WordPress (compatible multisite et standard) permettant :
- Gestion centralisée des dépôts GitHub
- Installation de plugins et thèmes depuis ces dépôts
- Mutualisation de composants UI et métier

---

# 🧱 PHASE 1 — Stabilisation technique

## 1.1 Nettoyage architecture
- Supprimer doublons :
  - includes/Ajax/*
  - admin/Ajax/*
- Conserver une seule couche Ajax → includes/Ajax/

## 1.2 Cache GitHub
- Fusionner :
  - includes/Github/GithubCache.php
  - includes/Cache/GithubCache.php
- Garder uniquement : includes/Cache/GithubCache.php

## 1.3 Bootstrap
- Centraliser constantes :
  - CDR_PATH
  - CDR_URL
- Charger bootstrap après définition constantes

---

# 🧠 PHASE 2 — Gestion des dépôts (CRITIQUE)

## 2.1 Création stockage
Créer :
- includes/Repository/RepositoryStorage.php

Fonctions :
- getRepositories()
- addRepository()
- updateRepository()
- deleteRepository()

Stockage :
- multisite → get_site_option()
- standard → get_option()

## 2.2 RepositoryManager
- Ajouter logique métier :
  - repo par défaut "corbidev"
  - non modifiable mais supprimable
- Gestion token GitHub par repo

## 2.3 Interface admin
Page :
- Ajouter dépôt
- Modifier dépôt
- Supprimer dépôt
- Gérer token

---

# 🌐 PHASE 3 — Multisite

## 3.1 Détection
if (is_multisite())

## 3.2 Menus
- multisite → network_admin_menu
- standard → admin_menu

## 3.3 Stockage
- adapter options automatiquement

---

# 🧩 PHASE 4 — Intégration WordPress native

## 4.1 Plugins
Menu :
- Extensions → Corbidev

## 4.2 Thèmes
Menu :
- Apparence → Corbidev

## 4.3 UI
- Implémenter WP_List_Table
- Reproduire UI native WordPress :
  - colonnes
  - actions (installer)
  - statut

---

# 🔍 PHASE 5 — Scan & filtres

## 5.1 Règles plugins
- commence par "wp-"
- ne contient PAS "theme"

## 5.2 Règles thèmes
- commence par "wp-"
- contient "theme"

---

# 🏷️ PHASE 6 — Versioning

## 6.1 Service
Créer :
- includes/Services/VersionService.php

## 6.2 Fonctionnalités
- récupérer latest tag GitHub
- comparer version installée
- afficher :
  - à jour
  - mise à jour dispo

---

# ⚙️ PHASE 7 — Installation

## 7.1 PluginInstaller
- installation depuis zip GitHub

## 7.2 ThemeInstaller
- installation depuis zip GitHub

## 7.3 Boutons
- Installer si non installé
- Mettre à jour si version différente

---

# 🎨 PHASE 8 — Composants communs (objectif 2)

## 8.1 Messages
Créer :
- includes/UI/NoticeManager.php

Fonctionnalités :
- success
- error
- warning
- auto-dismiss

## 8.2 Modal
- centraliser ModalManager
- confirmation suppression
- affichage infos

## 8.3 Templates réutilisables
- messages
- modals
- notifications

---

# 🚀 PHASE 9 — Optimisation

## 9.1 Cache
- améliorer cache GitHub
- limiter appels API

## 9.2 Logs
- centraliser logs
- page admin logs

## 9.3 Sécurité
- sanitization
- nonce WP
- permissions admin

---

# 🧪 PHASE 10 — Tests

## 10.1 Tests fonctionnels
- ajout repo
- suppression repo
- installation plugin
- installation thème

## 10.2 Tests multisite
- admin réseau uniquement
- stockage partagé

## 10.3 Tests UI
- affichage correct
- cohérence WP

---

# 📌 ROADMAP PRIORITAIRE

1. Stabilisation
2. Repository system
3. Multisite
4. UI WordPress native
5. Versioning
6. Composants communs

---

# 💡 BONUS (évolution future)

- Support GitLab
- Recherche plugins
- Auto-update
- CLI WP (wp-cli)
- API interne Corbidev

---

# ✅ Résultat attendu

Un plugin :
- stable
- extensible
- compatible multisite
- intégré à WordPress
- réutilisable pour tous les projets Corbidev
