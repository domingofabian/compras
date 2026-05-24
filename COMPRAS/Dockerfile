FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev zip unzip libonig-dev \
    && docker-php-ext-install pdo_mysql mysqli \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY COMPRAS/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html
