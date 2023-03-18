# Set the base image
FROM php:8.2.4-apache

# Install the mysqli php extension and enable mod_rewrite
RUN docker-php-ext-install mysqli && a2enmod rewrite