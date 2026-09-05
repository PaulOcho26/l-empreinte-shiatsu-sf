FROM dunglas/frankenphp:1-php8.4

WORKDIR /app

# Extensions PHP nécessaires à Symfony et PostgreSQL
RUN install-php-extensions \
    pdo_pgsql \
    intl \
    gd \
    zip \
    opcache

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
