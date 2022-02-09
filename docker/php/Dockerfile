FROM php:8.0-fpm

RUN apt-get update && apt-get install -y  \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    --no-install-recommends \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql -j$(nproc) gd


#FROM php:8.0-fpm
#
## Arguments defined in docker-compose.yml
#ARG user
#ARG uid
#
## Set working directory
#WORKDIR /var/www
#
## Install dependencies
#RUN apt-get update && apt-get install -y \
#    git \
#    build-essential \
#    libonig-dev \
#    libpng-dev \
#    libzip-dev \
#    libjpeg62-turbo-dev \
#    libfreetype6-dev \
#    locales \
#    zip \
#    jpegoptim optipng pngquant gifsicle \
#    unzip \
#    curl
#
## Clear cache
#RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#
## Install extensions
#RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl
#RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
#
#RUN docker-php-ext-install gd
#RUN pecl install -o -f redis \
#    &&  rm -rf /tmp/pear \
#    &&  docker-php-ext-enable redis
#
#
## Get latest Composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#
#COPY . /var/www
#
## Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user
#
## Change current user to www
#USER $user
#
## Expose port 9000 and start php-fpm server
#EXPOSE 9000
#CMD ["php-fpm"]
