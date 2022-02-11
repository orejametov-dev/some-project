FROM php:8.0-fpm

#install mysql,redis,bcmath,xdebug
RUN pecl install redis-5.3.6 \
    && pecl install mongodb-1.12.0 \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        pdo_mysql \
    && docker-php-ext-enable \
        redis \
        mongodb

# set group and user permission on folder
# set timezone
# install composer
# install phpunit



WORKDIR /app
