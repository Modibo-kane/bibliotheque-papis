<?php

// On utilise getenv() pour lire les variables d'environnement fournies par Render.
// Les valeurs entre '||' sont des valeurs par défaut pour le développement local si les variables ne sont pas définies.
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'modiboKane994';
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'biblioteque';

$connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

$connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
