FROM php:8.3-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP requises par Laravel
RUN docker-php-ext-install pdo_mysql bcmath zip gd

# Activer le module de réécriture d'URL d'Apache (mod_rewrite)
RUN a2enmod rewrite

# Configurer le dossier public de Laravel comme racine du serveur Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurer Apache pour écouter sur le port injecté par Railway ($PORT)
ENV PORT=80
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code de l'application
COPY . /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances Composer sans les dépendances de développement (dev)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Configurer les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Rendre le script d'entrée exécutable
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Définir le point d'entrée
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
