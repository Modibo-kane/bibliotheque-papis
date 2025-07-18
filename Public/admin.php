<?php
session_start();
require_once "../data/config.php";
require_once "../Class/Utilisateur.php";

// 1. Sécurité : Seuls les administrateurs peuvent accéder à cette page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php?error=unauthorized');
    exit('Accès non autorisé.');
}

// 2. Récupérer tous les utilisateurs pour les afficher
$utilisateurs = Utilisateur::getAll($connection);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Panneau d'Administration</title>
</head>
<body class="bg-gray-100 font-mono">
  <?php require_once __DIR__ . "/../assets/header.php"; ?>

  <div class="container mx-auto mt-8 p-4">
    <h1 class="text-3xl font-bold mb-6">Gestion des Utilisateurs</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
      <?php foreach ($utilisateurs as $user): ?>
        <div class="flex items-center justify-between border-b py-3">
          <div>
            <p class="font-bold text-lg"><?= htmlspecialchars($user['nom']) ?> (<?= htmlspecialchars($user['prenom']) ?>)</p>
            <p class="text-sm text-gray-600">Rôle : <span class="font-semibold <?= $user['role'] === 'admin' ? 'text-red-500' : 'text-gray-700' ?>"><?= htmlspecialchars($user['role']) ?></span></p>
          </div>
          <?php if ($user['role'] !== 'admin'): ?>
            <form action="../traitement.php?action=grantAdmin" method="POST">
              <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
              <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Promouvoir Admin</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>