FROM php:7.4-apache

# Copy challenge files
COPY index.php /var/www/html/index.php
COPY challenge.php /var/www/html/challenge.php
COPY internal.php /var/www/html/internal.php
COPY flag.php /var/www/html/flag.php

# Make Apache user the owner
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
