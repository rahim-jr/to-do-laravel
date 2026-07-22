# Use official PHP 8.4 CLI Alpine image
FROM php:8.4-cli-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies, required PHP extensions, and Node.js/NPM
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring bcmath gd

# Copy Composer binary from official Composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 1. Create a non-root user (laravel) first for security best practices
RUN addgroup -g 1000 laravel && \
    adduser -u 1000 -G laravel -h /home/laravel -D laravel

# Copy existing application source code with ownership assigned to laravel user
COPY --chown=laravel:laravel . /var/www/html

# Switch to the non-root user
USER laravel

# 2. Install all necessary Laravel composer packages & frontend assets
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Switch back to root temporarily to set entrypoint script permissions
USER root
COPY --chown=laravel:laravel docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Switch back to non-root user
USER laravel

# Expose application port
EXPOSE 8000

# 3. Entrypoint script executes `php artisan migrate --force` && `php artisan db:seed --force`
ENTRYPOINT ["docker-entrypoint.sh"]

# 4. Default command to run the project
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
