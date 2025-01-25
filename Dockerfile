# Utiliser une version PHP 7.4 compatible
FROM yiisoftware/yii2-php:7.4-apache

# Install additional PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Change document root to web directory
ENV APACHE_DOCUMENT_ROOT /app/web

# Configure Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure Apache ServerName globally
RUN echo "ServerName railway.app" >> /etc/apache2/apache2.conf

# Enable Apache modules
RUN a2enmod rewrite headers deflate

# Configure MPM to be more stable
RUN echo "ServerLimit 1" >> /etc/apache2/apache2.conf \
    && echo "StartServers 1" >> /etc/apache2/apache2.conf \
    && echo "MaxRequestWorkers 1" >> /etc/apache2/apache2.conf \
    && echo "MinSpareServers 1" >> /etc/apache2/apache2.conf \
    && echo "MaxSpareServers 1" >> /etc/apache2/apache2.conf

# Configure PHP for debugging
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/debug.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/debug.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/debug.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/debug.ini \
    && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/debug.ini

# Set default environment variables for database
ENV DB_DSN="mysql://if0_38168993:ODyq34I3wKuGRRN@sql202.infinityfree.com:3306/if0_38168993_mutuelle" \
    DB_USERNAME="if0_38168993" \
    DB_PASSWORD="ODyq34I3wKuGRRN"

# Copy application files
COPY . /app

# Set permissions properly
RUN mkdir -p /app/runtime /app/web/assets \
    && chown -R www-data:www-data /app \
    && chmod -R 755 /app/runtime /app/web/assets

# Install composer dependencies
RUN composer install --no-interaction --no-dev --prefer-dist

# Create script to update Apache port and start server
RUN echo '#!/bin/bash\n\
PORT="${PORT:-80}"\n\
echo "Listen $PORT" > /etc/apache2/ports.conf\n\
echo "<VirtualHost *:$PORT>\n\
    ServerName railway.app\n\
    DocumentRoot ${APACHE_DOCUMENT_ROOT}\n\
    <Directory ${APACHE_DOCUMENT_ROOT}>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    php_flag display_errors on\n\
    php_value error_reporting E_ALL\n\
    php_flag log_errors on\n\
    php_value error_log ${APACHE_LOG_DIR}/php_errors.log\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf\n\
exec apache2-foreground' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /app

# Use our custom entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Add healthcheck with increased timeout
HEALTHCHECK --interval=30s --timeout=10s \
    CMD PORT="${PORT:-8080}" && curl -f "http://localhost:$PORT/" || exit 1