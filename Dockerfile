# PHP app (UI + api.php) for Render — nginx + PHP-FPM, listen on port 10000
FROM php:8.2-fpm-bookworm AS app

# GD for imagecreatefrompng, imagepng, etc. (api.php)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# nginx
RUN apt-get update && apt-get install -y --no-install-recommends nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# App lives here
ENV WEBROOT=/var/www/html
WORKDIR $WEBROOT

# nginx listens on Render's default port
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Application code (respects .dockerignore)
COPY . $WEBROOT/

# Writable dir for api.php generated PNGs
RUN mkdir -p $WEBROOT/temp && chown -R www-data:www-data $WEBROOT/temp

# Start both: php-fpm in background, nginx in foreground (Render runs one process)
COPY docker/entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]
