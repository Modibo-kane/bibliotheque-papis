<?php
session_start();
// Protection de la page : seuls les admins peuvent y accéder.
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit('Accès non autorisé.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Ajouter une Bibliothèque</title>
</head>
<body class="bg-gray-100 flex items-center justify-start gap-12 min-h-screen flex-col">
  <?php require_once "header.php"; ?>
  <form action="../traitement.php?action=creerBibliotheque" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
      <h1 class="text-2xl font-bold mb-6 text-center">Ajouter une Bibliothèque</h1>
      <input type="text" name="nom" placeholder="Nom de la bibliothèque" class="border p-2 mb-4 w-full rounded" required>
      <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg w-full hover:bg-blue-600">Ajouter</button>
</form>
</body>
</html>