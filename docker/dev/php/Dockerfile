FROM php:8.2-apache

RUN apt-get update && apt-get install -y git curl zip unzip libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev vim && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

COPY php-dev.ini /usr/local/etc/php/conf.d/php-dev.ini

COPY ../apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html