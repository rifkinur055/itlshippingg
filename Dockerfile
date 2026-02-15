FROM php:8.2-apache

# Install PDO MySQL and ensure Apache loads only ONE MPM (prefork)
RUN docker-php-ext-install pdo pdo_mysql \
  # Hard reset Apache MPM modules: ensure only prefork is enabled
  && rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf || true \
  && a2enmod mpm_prefork rewrite

COPY app_shipping/ /var/www/html/app_shipping/

# Optional: redirect root to app
RUN printf '%s\n' \
  '<?php header("Location: /app_shipping/login.php"); exit; ?>' \
  > /var/www/html/index.php

RUN chown -R www-data:www-data /var/www/html
