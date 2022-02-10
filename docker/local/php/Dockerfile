FROM php:8.0-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    nano \
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

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/

RUN docker-php-ext-install gd
RUN pecl install -o -f redis mongodb \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \
    &&  docker-php-ext-enable mongodb


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /var/www/storage

# Change current user to www
USER $user

WORKDIR /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
