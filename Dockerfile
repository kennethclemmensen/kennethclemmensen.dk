# Set the base image
FROM php:8.0.6-apache

# Install the mysqli php extension
RUN docker-php-ext-install mysqli

# Enable mod_rewrite
RUN a2enmod rewrite