# Set the base image
FROM php:8.2.11-apache

# Install the mysqli php extension and enable mod_rewrite
RUN docker-php-ext-install mysqli && a2enmod rewrite

# Install git
RUN apt update && apt -y upgrade && apt install -y git

# Clone the repository
ARG DIRECTORY=/var/www/html/kennethclemmensen.dk
RUN git clone https://github.com/kennethclemmensen/kennethclemmensen.dk.git ${DIRECTORY}

# Change DB_HOST in wp-config.php
WORKDIR /var/www/html/kennethclemmensen.dk/public
RUN sed -i 's/localhost/db:3306/g' wp-config.php

# Create a virtual host
WORKDIR /etc/apache2/sites-available
ARG FILE_NAME=000-default.conf
RUN cat > ${FILE_NAME} && printf "<VirtualHost *:80>\n \
	DocumentRoot ${DIRECTORY}/public/\n \
	ServerName kennethclemmensen.test\n \
</VirtualHost>" >> ${FILE_NAME}