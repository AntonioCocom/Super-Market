# Usar la imagen oficial de PHP
FROM php:8.1.25-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalar la extensión de MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copiar el contenido del proyecto al directorio raíz del servidor web
COPY src/ /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80
