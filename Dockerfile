FROM php:8-fpm-alpine3.15 AS base

# Instalar dependencias y herramientas necesarias
RUN apk --update add --no-cache \
    alpine-sdk \
    linux-headers \
    openssl-dev \
    php8-pear \
    php8-dev \
    autoconf \
    automake \
    make \
    gcc \
    g++ \
    git \
    bash \
    icu-dev \
    libzip-dev \
    rabbitmq-c \
    rabbitmq-c-dev \
    postgresql-dev

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        opcache \
        intl \
        zip \
        pcntl

# Instalar extensiones de PECL
RUN pecl install apcu-5.1.23 amqp-2.1.1 xdebug-3.3.0 redis-5.3.4

# Habilitar extensiones de PHP
RUN docker-php-ext-enable amqp apcu opcache redis bcmath pcntl

# Limpiar caché de APK
RUN rm -rf /var/cache/apk/*

EXPOSE 9000

# Etapa de desarrollo
FROM base AS development

ENV TZ=${TZ}

# Actualizar canal PECL y establecer la zona horaria
RUN pecl channel-update pecl.php.net \
    && apk add --no-cache tzdata \
    && ln -sf /usr/share/zoneinfo/$TZ /etc/localtime

# Instalar y habilitar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuración de Xdebug
RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
