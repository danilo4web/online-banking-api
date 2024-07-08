FROM php:8.1-fpm
WORKDIR /var/www/html

USER root

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -ms /bin/bash app-user
USER app-user

COPY . /var/www/html
