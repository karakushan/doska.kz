FROM wordpress:latest

# Install useful tools for local development.
RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Copy uploads configuration
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Set working directory
WORKDIR /var/www/html

# Expose web port
EXPOSE 80

CMD ["apache2-foreground"]
