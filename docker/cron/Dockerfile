FROM php:8.1-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
    autoconf \
    g++ \
    make \
    libzip-dev \
    hiredis-dev \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN docker-php-ext-install pdo_mysql

COPY docker/cron/crontab /etc/crontabs/root

CMD ["crond", "-f"]
