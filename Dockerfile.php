
# Use the official PHP image
FROM php:7.4-apache

# Install the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable the Apache rewrite module
RUN a2enmod rewrite

# Copy the entrypoint script into the image
COPY php_init/entrypoint.php.sh /usr/local/bin/

# Make the entrypoint script executable
RUN chmod +x /usr/local/bin/entrypoint.php.sh

# Set the entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.php.sh"]

# Set the main command (e.g., start Apache)
CMD ["apache2-foreground"]
