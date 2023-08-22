FROM bitnami/php-fpm:latest

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    autoconf \
    php-dev \
    libtool \
    libpq-dev \
    postgresql-client \
    php-pgsql \
    && pecl install redis

# Create a new ini file with the extension enabled
RUN echo "extension=redis.so" >> /opt/bitnami/php/etc/conf.d/redis.ini
RUN echo "extension=pdo_pgsql.so" >> /opt/bitnami/php/etc/conf.d/pdo_pgsql.ini

WORKDIR /api

COPY  . .

COPY www.conf /opt/bitnami/php/etc/php-fpm.d/www.conf