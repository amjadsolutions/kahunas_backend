# Use PHP 8.2 as the base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code
COPY . /var/www

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage
    
# Clear Laravel caches
# RUN php artisan config:clear \
#     && php artisan cache:clear \
#     && php artisan route:clear \
#     && php artisan view:clear \
#     && php artisan migrate:fresh

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]