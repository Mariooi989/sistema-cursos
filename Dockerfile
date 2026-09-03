FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

RUN sed -i 's/80/${PORT:-10000}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf
ENV PORT=10000
EXPOSE 10000

CMD ["apache2-foreground"]