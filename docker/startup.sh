#!/bin/sh

# Default to port 8080 if PORT is not set
export PORT="${PORT:-8080}"

# Substitute PORT in nginx config
envsubst '$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/sites-available/default

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
