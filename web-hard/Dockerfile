FROM php:8.1-apache

RUN apt-get update && apt-get install -y openssl && \
    rm -rf /var/lib/apt/lists/*

# Generate RSA-2048 keypair
RUN mkdir -p /app/keys && \
    openssl genrsa -out /app/keys/private.pem 2048 && \
    openssl rsa -in /app/keys/private.pem -pubout -out /app/keys/public.pem

# Render secret — discovered via XXE
RUN mkdir -p /app/config && \
    echo -n "N0v4T3ch_Rend3r_K3y_X9f2p" > /app/config/render_secret.txt

# THE FLAG
RUN echo -n "TeXSS{JWT_SSRF_XXE_PHP_Ev4l_Ch41n_3xpl01t3d!}" > /flag.txt && \
    chmod 444 /flag.txt

# Fix permissions so www-data (Apache) can read keys and config
RUN chown -R www-data:www-data /app && \
    chmod 640 /app/keys/private.pem && \
    chmod 644 /app/keys/public.pem && \
    chmod 640 /app/config/render_secret.txt

COPY src/ /var/www/html/

# Enable mod_rewrite
RUN a2enmod rewrite && \
    sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80
