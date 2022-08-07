# Set the base image
FROM php:8.1.9-apache

# Install the mysqli php extension and enable mod_rewrite
RUN docker-php-ext-install mysqli && a2enmod rewrite