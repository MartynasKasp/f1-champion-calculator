FROM php:8.1-apache
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        zlib1g-dev \
        gnupg \
        apt-transport-https \
        zip \
        libxrender1 \
        libfontconfig1 \
        libxtst6 \
        libcurl4-openssl-dev \
        pkg-config \
        libssl-dev \
        libzip-dev \
        supervisor \
        python3-pip \
        libicu-dev \
        libapache2-mod-security2 \
        git \
        libxml2-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install bcmath \
    && docker-php-ext-enable bcmath \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl https://phar.phpunit.de/phpunit.phar --output phpunit.phar -L
RUN chmod +x phpunit.phar
RUN mv phpunit.phar /usr/local/bin/phpunit

RUN composer global config allow-plugins.symfony/flex true --no-interaction
RUN set -eux; \
    composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
    composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

RUN yes | pecl install xdebug-3.1.0 \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN a2enmod rewrite headers
RUN a2enmod security2
ARG app_env='prod'
ARG app_debug='0'
ARG url_basepath='/'
ARG app_version=''

RUN sed -i 's@html@html/public@g' /etc/apache2/sites-available/000-default.conf
RUN sed -i "/<\/VirtualHost>/ i\Options FollowSymLinks" /etc/apache2/sites-available/000-default.conf
RUN echo "ServerTokens Full" >> /etc/apache2/conf-available/security.conf
RUN echo "ServerSignature Off" >> /etc/apache2/conf-available/security.conf
RUN echo 'SecServerSignature " "' >> /etc/apache2/conf-available/security.conf

COPY ./ /var/ww/html

RUN mkdir /var/www/html/var || echo 'var directory already exists'
RUN chmod -R 777 /var/www/html/var
COPY php.ini /usr/local/etc/php/conf.d
