FROM php:8.1-apache

# Instalar extensiones
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html/

EXPOSE 80
