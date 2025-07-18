<?php
session_start(); // On démarre la session pour pouvoir la détruire

session_unset(); // On supprime les variables de session
session_destroy(); // On détruit la session

header('Location: login.php'); // On redirige vers la page de connexion
exit();