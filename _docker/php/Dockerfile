FROM php:8.3-fpm

RUN apt-get update
RUN apt-get install -y \
      apt-utils \
      libpq-dev \
      libzip-dev \
      zip unzip \
      curl wget \
      nginx

RUN docker-php-ext-install -j$(nproc) pdo_pgsql zip

RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

COPY ./_docker/php/php.ini /usr/local/etc/php/conf.d/20-custom.ini

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install