FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql \
  && a2enmod rewrite

COPY app_shipping/ /var/www/html/app_shipping/

# Optional: redirect root to app
RUN printf '%s\n' \
  '<?php header("Location: /app_shipping/login.php"); exit; ?>' \
  > /var/www/html/index.php

RUN chown -R www-data:www-data /var/www/html
