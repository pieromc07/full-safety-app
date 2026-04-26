FROM php:8.2-fpm

ARG UID=1000
ARG GID=1000

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        ca-certificates \
        unzip \
        zip \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libicu-dev \
        libssl-dev \
        zlib1g-dev \
        default-mysql-client \
        supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} appuser \
    && useradd -u ${UID} -g appuser -ms /bin/bash appuser \
    && mkdir -p /var/www/html \
    && chown -R appuser:appuser /var/www/html

COPY php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY www.conf /usr/local/etc/php-fpm.d/zz-www.conf

WORKDIR /var/www/html

USER appuser

EXPOSE 9000

CMD ["php-fpm"]
