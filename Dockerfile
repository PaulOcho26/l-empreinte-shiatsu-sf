FROM dunglas/frankenphp:1-php8.4

# Installation des extensions PHP nécessaires pour Symfony et PostgreSQL
RUN install-php-extensions \
    pdo_pgsql \
    intl \
    gd \
    zip \
    opcache

    FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions \
    pdo_pgsql \
    intl \
    gd

# AJOUTEZ CETTE LIGNE ICI :
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer