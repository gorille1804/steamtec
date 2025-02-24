# Dockerfile
FROM php:8.2.16-apache

# Installation des dépendances systèmes
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    intl \
    zip \
    opcache

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configuration d'Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers de l'application
COPY . .

# Installation des dépendances et génération de l'autoload
RUN composer install --optimize-autoloader
RUN composer dump-autoload --optimize

#Lancer la migration
RUN php bin/console doctrine:migrations:migrate --no-interaction

# Donner les permissions avant le cache:clear
RUN chown -R www-data:www-data var/
RUN chmod -R 777 var/cache var/log

# Configuration du mode prod et des permissions
ENV APP_ENV=dev
ENV APP_DEBUG=1


# Démarrage d'Apache avec les logs visibles
CMD ["apache2-foreground"]