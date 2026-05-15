FROM php:8.1-cli

# Enable libxml (needed for DOMDocument XXE)
RUN apt-get update && apt-get install -y libxml2 && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Place the render secret — this is what XXE will read
RUN mkdir -p /app/config && \
    echo -n "N0v4T3ch_Rend3r_K3y_X9f2p" > /app/config/render_secret.txt

COPY src/ /app/

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]
