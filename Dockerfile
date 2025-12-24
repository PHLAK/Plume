FROM php:8.5-apache
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

ENV HOME="/home/app"
ENV COMPOSER_HOME="${HOME}/.config/composer"
ENV XDG_CONFIG_HOME="${HOME}/.config"

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
COPY --from=node:24.6 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:24.6 /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln --symbolic ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN ln --symbolic ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

RUN apt-get update && apt-get install --assume-yes --no-install-recommends \
    libmemcached-dev libssl-dev tar zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install apcu \
    && pecl install memcached \
    && pecl install redis \
    && pecl install xdebug

# Fix for memcached
# RUN echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini

RUN docker-php-ext-enable apcu memcached redis xdebug

COPY .docker/apache2/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.ini /usr/local/etc/php/php.ini

RUN a2enmod rewrite
