# Docker - Mutuelle Web

Application Yii2 containerisée avec Docker.

## Prérequis

- Docker Desktop installé
- Docker Compose installé

## Démarrage Rapide

```bash
# Build et démarrer tous les conteneurs
docker-compose up -d --build

# Voir les logs
docker-compose logs -f

# Arrêter les conteneurs
docker-compose down
```

## Accès à l'Application

- **Application Web** : http://localhost:8080
- **Base de données MySQL** : localhost:3307

## Identifiants par Défaut

### Base de Données
- **Host** : mysql (depuis les conteneurs) ou localhost:3307 (depuis l'hôte)
- **Database** : mutuelle_web
- **User** : mutuelle_user
- **Password** : mutuelle_pass
- **Root Password** : root_password

### Administrateur Application
- **Login** : admin
- **Password** : admin123

## Commandes Utiles

```bash
# Accéder au conteneur de l'application
docker-compose exec app bash

# Accéder au conteneur MySQL
docker-compose exec mysql mysql -u mutuelle_user -p mutuelle_web

# Voir les logs d'un service spécifique
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql

# Reconstruire les images
docker-compose build --no-cache

# Supprimer tous les conteneurs et volumes
docker-compose down -v
```

## Structure Docker

```
.
├── Dockerfile                      # Image de l'application PHP
├── docker-compose.yml              # Orchestration des services
├── docker/
│   ├── nginx/
│   │   └── conf.d/
│   │       └── default.conf       # Configuration Nginx
│   ├── php/
│   │   └── local.ini              # Configuration PHP
│   └── mysql/
│       └── init/
│           └── 01-schema.sql      # Initialisation DB
└── .dockerignore                   # Fichiers exclus du build
```

## Services

### app
- **Image** : PHP 8.1-FPM
- **Port** : 9000 (interne)
- **Extensions** : pdo_mysql, mbstring, gd, zip, etc.

### nginx
- **Image** : nginx:alpine
- **Port** : 8080 → 80

### mysql
- **Image** : mysql:8.0
- **Port** : 3307 → 3306
- **Volume** : mysql_data (persistant)

## Développement

Les fichiers de l'application sont montés en volume, donc les modifications sont immédiatement visibles sans rebuild.

## Production

Pour la production, créez un `docker-compose.prod.yml` avec :
- Pas de volumes montés (code dans l'image)
- Variables d'environnement sécurisées
- Logs configurés
- Healthchecks activés

## Dépannage

### Les conteneurs ne démarrent pas
```bash
docker-compose logs
```

### Problème de permissions
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
```

### Réinitialiser la base de données
```bash
docker-compose down -v
docker-compose up -d
```
