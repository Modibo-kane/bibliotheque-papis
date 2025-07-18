# Utiliser une image officielle PHP avec le serveur web Apache
FROM php:8.1-apache-alpine

# Mettre à jour les paquets pour corriger les vulnérabilités de sécurité connues
# Le -y confirme automatiquement toutes les invites
RUN apt-get update && apt-get upgrade -y

# Installer les extensions PHP nécessaires pour se connecter à MySQL
# pdo_mysql est l'extension utilisée par votre code via PDO
RUN docker-php-ext-install pdo_mysql

# Activer le module de réécriture d'Apache (bonne pratique)
RUN a2enmod rewrite

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier tous les fichiers de votre projet dans le répertoire de travail du conteneur
# Le '.' représente le répertoire actuel où se trouve le Dockerfile
# '/var/www/html' est le répertoire où Apache cherche les fichiers
COPY . .