FROM php:8.0-fpm

## Arguments defined in docker-compose.yml
ARG user="docker"
ARG uid=1000
#
COPY ./ /var/www/html
#
#WORKDIR /var/www/html
#
## Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    nano \
    vim \
    build-essential \
    libonig-dev \
    libpng-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    curl
#
## Clear cache
#RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#
# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
#
RUN docker-php-ext-install gd \
    && pecl install -o -f redis mongodb \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \
    &&  docker-php-ext-enable mongodb
#
#
RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /var/www/html/storage

# Change current user to www
USER $user
#
## Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
