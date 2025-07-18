# Utiliser une image officielle PHP avec le serveur web Apache
FROM php:8.1-apache

# Mettre à jour les paquets, installer les extensions et activer les modules en une seule étape
# pour optimiser la taille de l'image.
RUN apt-get update && apt-get upgrade -y \
    # Installer les extensions pour MySQL et PostgreSQL
    && docker-php-ext-install pdo_mysql pdo_pgsql \
    && a2enmod rewrite \
    # Nettoyer le cache apt pour réduire la taille de l'image finale
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier tous les fichiers de votre projet dans le répertoire de travail du conteneur
# Le '.' représente le répertoire actuel où se trouve le Dockerfile
# '/var/www/html' est le répertoire où Apache cherche les fichiers
COPY . .