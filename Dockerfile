FROM php:8.1-fpm


RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install intl \
    && docker-php-ext-install mysqli


WORKDIR /var/www/html

COPY ./app /var/www/html


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install