# Use the official PHP image
FROM php:7.4-apache

# Install the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP application code into the container
COPY ./website_root/ /var/www/html/

# Copy the Apache configuration file to the container
COPY ./apache_config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the Apache rewrite module
RUN a2enmod rewrite
