FROM php:7.4-fpm

ARG uid=1000

ARG user=user

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    mkdir -p /home/$user/.ssh && \
    chown -R $user:$user /home/$user

# Install Composer 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ssh-keyscan -t rsa github.com > /home/$user/.ssh/known_hosts

# Set working directory
WORKDIR /var/www

USER $user

# Install PHP CodeSniffer
RUN composer global require "squizlabs/php_codesniffer=*"

RUN /home/$user/.composer/vendor/bin/phpcs --config-set show_progress 1 && \
    /home/$user/.composer/vendor/bin/phpcs --config-set colors 1 && \
    /home/$user/.composer/vendor/bin/phpcs --config-set report_width 140 && \
    /home/$user/.composer/vendor/bin/phpcs --config-set encoding utf-8

ENV PATH="/home/$user/.composer/vendor/bin:${PATH}"
