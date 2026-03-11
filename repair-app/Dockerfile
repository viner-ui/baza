FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libsqlite3-dev \
    && docker-php-ext-install pdo sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN test -f .env || cp .env.example .env \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan key:generate --no-interaction \
    && touch database/database.sqlite

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
