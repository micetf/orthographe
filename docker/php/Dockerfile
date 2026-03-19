FROM php:5.6-apache

RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's/security.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && apt-get update -o Acquire::Check-Valid-Until=false --allow-insecure-repositories \
    && apt-get install -y --allow-unauthenticated \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/lib/php.ini