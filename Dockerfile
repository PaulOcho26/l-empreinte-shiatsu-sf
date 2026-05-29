FROM dunglas/frankenphp:1-php8.4

# Installation des extensions PHP nécessaires pour Symfony et PostgreSQL
RUN install-php-extensions \
    pdo_pgsql \
    intl \
    gd \
    zip \
    opcache