FROM php:8.2-apache

# Install system dependencies required for PostgreSQL extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (safe even if unused)
RUN a2enmod rewrite
