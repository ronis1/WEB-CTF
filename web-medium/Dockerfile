FROM php:7.4-apache
RUN docker-php-ext-install mysqli

# Copy the files first
COPY . /var/www/html/

# Fix permissions: change owner to www-data (the Apache user)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
