#!/bin/bash
# Start PHP server with custom router for static files
# Use absolute path to router.php
php -S 0.0.0.0:${PORT} -t /app/web /app/web/router.php
