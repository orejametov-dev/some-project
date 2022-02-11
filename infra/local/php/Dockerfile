FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    pkg-config \
    libssl-dev \
    git \
    zip \
    unzip

#install mysql,redis,bcmath,xdebug
RUN pecl install redis-5.3.6 \
    && pecl install mongodb-1.12.0 \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        pdo_mysql \
        pcntl \
    && docker-php-ext-enable \
        redis \
        mongodb

# set group and user permission on folder
# set timezone
# install composer

COPY ./composer-installer /usr/local/bin/composer-installer
RUN chmod +x /usr/local/bin/composer-installer \
    && /usr/local/bin/composer-installer \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version

# install phpunit

WORKDIR /app
