#!/bin/sh
set -e

# Optionnel: Mettre en cache la configuration et les routes pour de meilleures performances
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Exécuter les migrations de base de données à chaque démarrage du conteneur
echo "Exécution des migrations..."
php artisan migrate --force

# Lancer Apache en premier plan (foreground)
echo "Démarrage d'Apache..."
exec apache2-foreground
