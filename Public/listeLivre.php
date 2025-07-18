<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();// On démarre la session sur chaque page où le header est inclus
// pour avoir accès aux variables de session.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
  <title>Document</title>
</head>
<body class="bg-gray-100 flex items-center font-mono justify-start gap-12 min-h-screen flex-col">
  <?php 
      require_once __DIR__ . "/../assets/header.php";
      require_once "../assets/creerBibliotheque.php";
      require_once "../data/config.php";
      require_once "../Class/Livre.php"; // Assurez-vous que c'est le bon chemin et le bon nom de fichier

  ?>
    <div class="w-full px-8">
        <?php
        // Affichage des messages de statut (erreurs ou succès)
        if (isset($_GET['error']) && $_GET['error'] === 'max_emprunts') {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>
                    <strong class='font-bold'>Limite atteinte !</strong>
                    <span class='block sm:inline'>Vous ne pouvez pas emprunter plus de 3 livres à la fois. Veuillez en rendre un d'abord.</span>
                  </div>";
        }
        if (isset($_GET['emprunt']) && $_GET['emprunt'] === 'success') {
            echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>
                    <strong class='font-bold'>Succès !</strong>
                    <span class='block sm:inline'>Le livre a été emprunté avec succès.</span>
                  </div>";
        }
        ?>
        <?php require_once __DIR__ . "/../assets/livre.php"; ?>
    </div>
</body>
</html>