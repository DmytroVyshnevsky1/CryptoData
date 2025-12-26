FROM php:8.5.1-fpm

RUN apt-get update

RUN apt-get install -y git
RUN apt-get install -y unzip
RUN apt-get install -y curl

RUN pecl install redis \
    && docker-php-ext-enable redis


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer && \
    composer --version

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug