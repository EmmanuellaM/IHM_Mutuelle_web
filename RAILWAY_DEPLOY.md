# Déploiement sur Railway avec Docker

## Étapes de Déploiement

### 1. Créer un Projet Railway

1. Allez sur [railway.app](https://railway.app)
2. Connectez-vous avec GitHub
3. Cliquez sur "New Project"
4. Sélectionnez "Deploy from GitHub repo"
5. Choisissez votre repository `IHM_Mutuelle_web`

### 2. Ajouter une Base de Données MySQL

1. Dans votre projet Railway, cliquez sur "+ New"
2. Sélectionnez "Database" → "MySQL"
3. Railway va créer automatiquement les variables d'environnement :
   - `DB_HOST`
   - `DB_PORT`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASSWORD`

### 3. Configurer les Variables d'Environnement

Dans les settings de votre service app, ajoutez :

```
DOCKER_ENV=true
```

Railway va automatiquement injecter les variables de la base de données MySQL.

### 4. Déployer

Railway va automatiquement :
1. Détecter le `Dockerfile`
2. Build l'image Docker
3. Déployer l'application
4. Exposer l'application sur une URL publique

### 5. Initialiser la Base de Données

Une fois déployé, vous devrez initialiser la base de données :

**Option A : Via Railway CLI**
```bash
# Installer Railway CLI
npm install -g @railway/cli

# Se connecter
railway login

# Se connecter au projet
railway link

# Exécuter les migrations
railway run php yii migrate
```

**Option B : Importer votre base locale**
1. Exportez votre base locale : `mysqldump -u root mutuelle_web > dump.sql`
2. Connectez-vous à Railway MySQL via le client MySQL
3. Importez : `mysql -h [RAILWAY_HOST] -u [USER] -p [DATABASE] < dump.sql`

**Option C : Script d'initialisation web**
Créez un fichier `web/init-db.php` accessible via navigateur pour créer les tables et l'admin.

### 6. Accéder à l'Application

Railway vous donnera une URL comme :
`https://votre-app.up.railway.app`

## Configuration Railway vs Docker Local

| Environnement | Host DB | Port | Database |
|---------------|---------|------|----------|
| **Local** | mysql | 3306 | mutuelle_web |
| **Railway** | [AUTO] | [AUTO] | [AUTO] |

Railway injecte automatiquement les bonnes variables d'environnement.

## Commandes Utiles

```bash
# Voir les logs
railway logs

# Ouvrir l'application
railway open

# Variables d'environnement
railway variables

# Redéployer
git push origin main
```

## Dépannage

### L'application ne démarre pas
```bash
railway logs
```

### Problème de connexion DB
Vérifiez que les variables d'environnement Railway sont bien injectées :
```bash
railway variables
```

### Rebuild forcé
Dans Railway Dashboard :
1. Settings → Deployments
2. Cliquez sur "Redeploy"

## Architecture Railway

```
┌─────────────────────────────────────┐
│         Railway Project             │
├─────────────────────────────────────┤
│                                     │
│  ┌──────────────┐  ┌─────────────┐ │
│  │   App        │  │   MySQL     │ │
│  │  (Docker)    │──│  Database   │ │
│  │              │  │             │ │
│  └──────────────┘  └─────────────┘ │
│         │                           │
│         │                           │
│    Public URL                       │
│  https://xxx.up.railway.app         │
└─────────────────────────────────────┘
```

## Avantages de Railway + Docker

✅ Déploiement automatique à chaque push
✅ Environnement identique local/prod
✅ Scalabilité facile
✅ Logs centralisés
✅ SSL automatique
✅ Base de données managée
