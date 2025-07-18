<?php

// Détecter le pilote de base de données à utiliser. Par défaut 'mysql' pour le local.
$driver = getenv('DB_DRIVER') ?: 'mysql';

// Lire les variables d'environnement.
// Les valeurs par défaut sont pour un environnement de développement local (XAMPP/MySQL).
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'biblioteque';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'modiboKane994'; // <-- IMPORTANT: Mettez ici le mot de passe de votre base de données locale (XAMPP). Souvent, il est vide.

// Le port et le DSN (Data Source Name) dépendent du pilote
if ($driver === 'pgsql') {
    $port = getenv('DB_PORT') ?: 5432; // Port par défaut de PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
} else {
    $port = getenv('DB_PORT') ?: 3306; // Port par défaut de MySQL
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
}

try {
    // Créer une nouvelle instance de PDO pour la connexion.
    $connection = new PDO($dsn, $user, $password);

    // Configurer PDO pour lancer des exceptions en cas d'erreur.
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'échec de la connexion, logguer l'erreur et arrêter le script proprement.
    error_log("Erreur de connexion à la base de données: " . $e->getMessage());
    die("Impossible de se connecter à la base de données. Veuillez réessayer plus tard.");
}
