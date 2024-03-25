# Use the official PHP image
FROM php:7.4-apache

# Install the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP application code into the container
COPY ./php-code/ /var/www/html/
