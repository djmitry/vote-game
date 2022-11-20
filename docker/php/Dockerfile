FROM php:8.1-fpm-alpine

RUN apk update && apk add \
    $PHPIZE_DEPS \
    zip \
    bash \
    git \
#    npm \
    yarn

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo pdo_mysql

ARG XDEBUG_INI=/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo "xdebug.discover_client_host = on" >> ${XDEBUG_INI} \
    && echo "xdebug.start_with_request = on" >> ${XDEBUG_INI} \
    && echo "xdebug.mode = debug" >> ${XDEBUG_INI}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
