FROM php:8.2-apache

LABEL environment="recette"

RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY php-recette.ini /usr/local/etc/php/conf.d/php-recette.ini
COPY apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html