RÉCAP FINAL (corrigé)
➕ À créer
✅  RepositoryDelete.php
✅  RepositoryUpdater.php

✏️ À modifier

🔌 AJAX
✅  RepositoryAjax.php (SEUL point d’entrée)

⚙️ Métier
✅  RepositoryService.php
✅  RepositoryManager.php

🔍 Scan
PluginScanner.php
ThemeScanner.php

🌐 GitHub
GithubClient.php

🎨 Front
✅  repositoryManager.js
ajax.js

🧩 UI
✅  repository-item.php



🔥 Ordre de migration recommandé
✅ Créer RepositoryScanner
✅ Modifier RepositoryService
✅ Modifier repository-item.php
✅ Tester install / activate
✅ Supprimer PluginScanner / ThemeScanner
✅ Nettoyer Installer


1. 🔥 corriger RepositoryUpdater (priorité absolue)
2. 🔥 sécuriser slug partout
3. 🧠 améliorer scanner (optionnel mais conseillé)
4. 🎨 améliorer UX JS




Amélioration possible (prochaine étape)
👉 éviter location.reload() partout
Remplacer par :
suppression DOM
update état bouton
toast notification
🚀 Si tu veux aller plus loin
Je peux te faire :
une version sans reload (UX WordPress-like)
ou ajouter loader spinner + feedback visuel propre
ou brancher update automatique si version détectée




Si tu veux aller plus loin
Je peux te faire :
👉 version UI WordPress-like (badges, couleurs, inline actions)
👉 ou version sans reload (UX moderne)
👉 ou enrichir $item avec :
is_installed
is_active
has_update



💬 Si tu veux aller plus loin
Je peux te faire :
👉 détection has_update complète (backend + UI)
👉 ou système update WordPress natif (niveau pro)



Si tu veux aller plus loin
Prochaine amélioration logique :
👉 empêcher bouton "Update" si non installé (UI)
👉 ou upgrader sans delete (niveau WordPress natif)