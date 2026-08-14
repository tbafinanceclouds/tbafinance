FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (✅ ADDED pdo_pgsql and pgsql)
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Install and build frontend assets
RUN npm install && npm run build

# Create storage directories
RUN mkdir -p /var/www/html/storage /var/www/html/storage/app /var/www/html/storage/framework /var/www/html/storage/logs /var/www/html/bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache to serve from public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Change port to 10000
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's/:80/:10000/g' /etc/apache2/sites-available/000-default.conf

# Make startup script executable
RUN chmod +x /var/www/html/startup.sh

EXPOSE 10000

ENTRYPOINT ["/var/www/html/startup.sh"]