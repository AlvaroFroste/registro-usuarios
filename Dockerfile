# Imagen de PHP 8.3 con Apache
FROM php:8.3-apache

# Instalamos las dependencias necesarias para mbstring
RUN apt-get update && apt-get install -y \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring \
    && rm -rf /var/lib/apt/lists/*

# Copiamos nuestra aplicación
COPY app/ /var/www/html/

# Puerto de Apache
EXPOSE 80
