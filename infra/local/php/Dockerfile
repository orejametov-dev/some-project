FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    pkg-config \
    libssl-dev \
    git \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

#install xdebug
RUN pecl install redis-5.3.6 \
    && pecl install mongodb-1.12.0 \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        pdo_mysql \
        pcntl \
    && docker-php-ext-enable \
        redis \
        mongodb

COPY ./composer-installer /usr/local/bin/composer-installer
RUN chmod +x /usr/local/bin/ \
    && /usr/local/bin/composer-installer \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /app

RUN chown -R www-data:www-data /app

USER www-data
