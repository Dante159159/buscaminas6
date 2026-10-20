# Imagen base con PHP 8.3 y FPM
FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones necesarias para Laravel y MySQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo en el contenedor
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . /var/www

# Instalar dependencias de Laravel optimizadas para producción
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos de almacenamiento para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto 80 para Render
EXPOSE 80

# Iniciar el servidor web de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
