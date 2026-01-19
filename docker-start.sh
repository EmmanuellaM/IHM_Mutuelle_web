#!/bin/sh
cd /var/www/html
php -S 0.0.0.0:${PORT:-8080} -t web web/router.php
