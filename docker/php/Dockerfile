FROM php:8.4.4-fpm-alpine3.21

## Install Xdebug and other necessary tools
RUN apk add --no-cache $PHPIZE_DEPS linux-headers\
    && pecl install xdebug-3.4.1\
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS

ENV RUN_DEPS="\
    zlib \
    libzip \
    libpng \
    libjpeg-turbo \
    postgresql-libs"

ENV BUILD_DEPS="\
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    postgresql-dev"

ENV PHP_EXTENSIONS="\
    opcache \
    zip \
    gd \
    bcmath \
    pgsql \
    pdo_pgsql"

RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \
    && apk del .build-deps

RUN apk add --no-cache --virtual .run-deps $RUN_DEPS

# Copy the application code
# COPY . /app

# VOLUME ["/app"]
