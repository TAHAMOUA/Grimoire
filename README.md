# 📚 Grimoire — Gestion de projets de recherche

Application Laravel de gestion de projets de recherche académique avec système de rôles, notifications asynchrones et génération automatique de rapports de clôture.

---

## 📋 Table des matières

- [Technologies](#technologies)
- [Installation](#installation)
- [Configuration](#configuration)
- [Configuration Queue](#configuration-queue)
- [Commandes de lancement](#commandes-de-lancement)
- [Architecture — Events, Listeners, Jobs](#architecture)
- [Notifications](#notifications)
- [Audit des performances — Debugbar & N+1](#performances)
- [Tests](#tests)

---

## Technologies

| Outil | Version |
|---|---|
| PHP | 8.3+ |
| Laravel | 13.x |
| MySQL | 8.0+ |
| Laravel Breeze | Auth UI |
| Laravel Debugbar | Audit N+1 en dev |
| Queue driver | `database` |
| Mail driver | `log` (dev) |

---

## Installation

```bash
# 1. Cloner le projet
git clone <url> grimoire && cd grimoire

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
# DB_DATABASE=grimoire  DB_USERNAME=root  DB_PASSWORD=

# 5. Migrer la base de données
php artisan migrate

# 6. Compiler les assets
npm run build
```

---

## Configuration

### `.env` — Variables importantes

```dotenv
APP_NAME=Grimoire
APP_DEBUG=true          # Active Laravel Debugbar automatiquement

# Base de données
DB_CONNECTION=mysql
DB_DATABASE=grimoire

# Queue — driver database (table jobs)
QUEUE_CONNECTION=database

# Mail — log en local (voir storage/logs/laravel.log)
MAIL_MAILER=log
```

---

## Configuration Queue

### Fonctionnement

Le projet utilise **`QUEUE_CONNECTION=database`**. Les jobs sont stockés dans la table `jobs` et traités par un worker PHP séparé.

### Queues utilisées

| Queue | Usage |
|---|---|
| `default` | Queue par défaut |
| `notifications` | Listeners (EnvoyerNotificationMembre, EnvoyerNotificationCloture) |
| `reports` | Job de génération du rapport PDF/texte |

### Créer les tables si nécessaires

```bash
php artisan queue:table            # table jobs (si non existante)
php artisan notifications:table    # table notifications (canal database)
php artisan queue:failed-table     # table failed_jobs
php artisan migrate
```

---

## Commandes de lancement

### Développement — Tout lancer en une commande

```bash
composer run dev
```

> Lance en parallèle : `php artisan serve` + `queue:listen` + `pail` (logs) + `npm run dev`

### Lancer manuellement

```bash
# Serveur web
php artisan serve

# Queue worker (traitement des jobs)
php artisan queue:work --tries=3

# Queue worker avec queues spécifiques (ordre de priorité)
php artisan queue:work --queue=notifications,reports,default --tries=3

# Mode listen (redémarre à chaque changement de code)
php artisan queue:listen --tries=1 --timeout=0

# Voir les logs en temps réel
php artisan pail

# Voir les jobs en attente
php artisan queue:monitor

# Rejouer les jobs échoués
php artisan queue:retry all

# Vider les jobs échoués
php artisan queue:flush
```

### Supervision en production (recommandé)

```bash
# Supervisor — relancer automatiquement les workers
# /etc/supervisor/conf.d/grimoire-worker.conf
[program:grimoire-worker]
command=php /var/www/grimoire/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=2
```

---

## Architecture

### Events → Listeners → Jobs

```
ProjectController::addMember()
    └── event(MembreAjouteAuProjet) ──► EnvoyerNotificationMembre [ShouldQueue, queue: notifications]
                                            └── $user->notify(MembreAjouteNotification)

ProjectController::destroy()
    └── event(ProjetCloture) ──► EnvoyerNotificationCloture [ShouldQueue, queue: notifications]
                                     ├── foreach members → notify(ProjetClotureNotification)
                                     └── GenerateProjectReport::dispatch() [ShouldQueue, queue: reports]
                                             └── storage/app/reports/rapport_projet_{id}_{date}.txt
```

### Fichiers clés

| Fichier | Rôle |
|---|---|
| `app/Events/MembreAjouteAuProjet.php` | Event ajout membre |
| `app/Events/ProjetCloture.php` | Event clôture projet |
| `app/Listeners/EnvoyerNotificationMembre.php` | `ShouldQueue` — notifie le nouveau membre |
| `app/Listeners/EnvoyerNotificationCloture.php` | `ShouldQueue` — notifie tous les membres + rapport |
| `app/Jobs/GenerateProjectReport.php` | `ShouldQueue` — génère le rapport texte |
| `app/Notifications/MembreAjouteNotification.php` | Notification mail + database |
| `app/Notifications/ProjetClotureNotification.php` | Notification mail + database |

---

## Notifications

### Canaux utilisés

- **`mail`** : Email envoyé à l'utilisateur (driver `log` en local → voir `storage/logs/laravel.log`)
- **`database`** : Stockage dans la table `notifications` pour affichage UI futur

### Voir les notifications en log

```bash
# Pendant le développement, les mails sont logués ici :
php artisan pail --filter="mail"
# ou
tail -f storage/logs/laravel.log
```

### Rapports générés

Les rapports de clôture sont stockés dans :
```
storage/app/reports/rapport_projet_{id}_{date}.txt
```

---

## Performances

### Laravel Debugbar

Installé automatiquement en mode dev (`APP_DEBUG=true`). Visible en bas de chaque page.

**Onglets utiles :**
- **Queries** : Nombre de requêtes SQL + détection N+1
- **Timeline** : Temps d'exécution par étape
- **Models** : Modèles chargés

### Optimisations N+1 appliquées

| Méthode | Avant | Après |
|---|---|---|
| `index()` | N+1 sur `userRole()` | `->with('users')` |
| `show()` | N+1 sur membres | `$project->load('users')` |
| `archived()` | N+1 sur membres archivés | `->with('users')` |
| `destroy()` | Membres non chargés pour listener | `$project->load('users')` avant event |

---

## Tests

```bash
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage

# Lancer uniquement les tests de projet
php artisan test --filter ProjectTest
```

### Tester la queue manuellement

```bash
# Terminal 1 — Démarrer le worker
php artisan queue:work --tries=3 --verbose

# Terminal 2 — Démarrer le serveur
php artisan serve

# Puis : ajouter un membre à un projet ou archiver un projet
# Observer les logs dans le terminal du worker
```
