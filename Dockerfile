# Cont1: Servidor Web — PHP + Apache, con soporte para PostgreSQL (pdo_pgsql)
# para poder conectarse al contenedor de la base de datos (Cont2: hosp-db).
FROM php:8.2-apache

# libpq-dev trae los archivos que necesita PHP para compilar el driver de
# PostgreSQL; docker-php-ext-install lo instala y lo activa dentro de PHP.
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

EXPOSE 80
