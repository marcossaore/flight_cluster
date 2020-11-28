FROM php:7.4-fpm-alpine

RUN apk update --no-cache && apk add \
icu-dev \
oniguruma-dev

RUN docker-php-ext-install intl
RUN docker-php-ext-install pcntl

RUN rm -rf /var/cache/apk/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=2.0.7

WORKDIR /var/www

CMD ["php-fpm"]
