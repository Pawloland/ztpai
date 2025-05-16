FROM php:8.4.7-fpm-alpine3.21

## Install necessary dependencies
RUN apk add --no-cache $PHPIZE_DEPS linux-headers bash curl shadow sudo\
    && pecl install xdebug-3.4.3 \
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS

ENV RUN_DEPS="\
    zlib \
    libzip \
    libpng \
    libjpeg-turbo \
    postgresql-libs \
    icu"

ENV BUILD_DEPS="\
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    postgresql-dev \
    icu-dev"

ENV PHP_EXTENSIONS="\
    opcache \
    zip \
    gd \
    bcmath \
    pgsql \
    pdo_pgsql \
    sockets \
    intl"

RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \
    && apk del .build-deps

RUN apk add --no-cache --virtual .run-deps $RUN_DEPS

## Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && \
    apk add --no-cache symfony-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy the script into the container
COPY ./docker/php/poster_saver /sbin/poster_saver

# Convert CRLF to LF using sed (in-place edit)
RUN sed -i 's/\r$//' /sbin/poster_saver \
    && chown 0:0 /sbin/poster_saver \
    && chmod 755 /sbin/poster_saver

