FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    git \
    unzip \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --optimize-autoloader --no-scripts --ignore-platform-req=ext-mongodb

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080