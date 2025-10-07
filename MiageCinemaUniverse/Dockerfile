FROM php:8.2-apache

# Installer les extensions nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier le code source dans le conteneur
COPY . /var/www/html/

# Copier la configuration Apache personnalisée
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Donner les droits à Apache
RUN chown -R www-data:www-data /var/www/html

# Activer mod_rewrite
RUN a2enmod rewrite

EXPOSE 80
