# syntax=docker/dockerfile:1

FROM composer:lts as deps
WORKDIR /app
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:8.2-apache as final
RUN docker-php-ext-install pdo pdo_mysql mysqli
# Enable mod_rewrite for Apache
RUN a2enmod rewrite

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=deps app/vendor/ /var/www/html/vendor
COPY ./src /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Add Apache configuration to enable URL rewriting
RUN echo 'RewriteEngine On\n\
RewriteCond %{REQUEST_FILENAME} !-d\n\
RewriteCond %{REQUEST_FILENAME} !-f\n\
RewriteRule ^(.*)$ router/router.php [L]' > /var/www/html/.htaccess
RUN chmod 644 /var/www/html/.htaccess

USER www-data