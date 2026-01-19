FROM php:8.1-cli

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Créer les répertoires nécessaires et définir les permissions
RUN mkdir -p runtime web/assets \
    && chmod -R 777 runtime web/assets

# Copier et rendre exécutable le script de démarrage
COPY docker-start.sh /start.sh
RUN chmod +x /start.sh

# Exposer le port
EXPOSE 8080

# Démarrer avec le script
CMD ["/start.sh"]