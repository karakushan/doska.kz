FROM wordpress:latest

# Install Xdebug and other useful tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug via PECL
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copy Xdebug configuration
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy uploads configuration
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Set working directory
WORKDIR /var/www/html

# Expose port 80 for web and 9003 for debugging
EXPOSE 80 9003

CMD ["apache2-foreground"]
