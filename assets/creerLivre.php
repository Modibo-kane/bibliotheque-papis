<?php
session_start();
// Protection de la page : seuls les admins peuvent y accéder.
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit('Accès non autorisé.');
}

require_once "../data/config.php";
require_once "../Class/Bibliotheque.php";

// On récupère toutes les bibliothèques pour les afficher dans le formulaire
$bibliotheques = Bibliotheque::getAll($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Ajouter un Livre</title>
</head>
<body class="bg-gray-100 flex items-center justify-start gap-12 min-h-screen flex-col">
  <?php 
  require_once "../assets/header.php";
  ?>

  <form action="../traitement.php?action=creerLivre" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
      <h1 class="text-2xl font-bold mb-6 text-center">Ajouter un nouveau livre</h1>
      <input type="text" name="titre" placeholder="Titre du livre" class="border p-2 mb-4 w-full rounded" required> 
      <input type="text" name="auteur" placeholder="Auteur du livre" class="border p-2 mb-4 w-full rounded" required> 
      <input type="number" name="annee" placeholder="Année de publication" class="border p-2 mb-4 w-full rounded" required> 
      
      <label for="bibliotheque" class="block mb-2 text-sm font-medium text-gray-900">Choisir une bibliothèque :</label>
      <select name="bibliotheque_id" id="bibliotheque" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-4" required>
        <option value="" disabled selected>-- Sélectionnez --</option>
        <?php foreach ($bibliotheques as $biblio): ?>
            <option value="<?= $biblio->getId() ?>"><?= htmlspecialchars($biblio->getNom()) ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg w-full hover:bg-blue-600">Ajouter le livre</button>
</form>
</body>
</html>