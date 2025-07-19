<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Rediriger vers la page d'accueil avec un message d'erreur
    header('Location: ../index.php?error=unauthorized');
    exit();
}

require_once "../data/config.php";
require_once "../Class/Livre.php";
require_once "../Class/Utilisateur.php";
require_once "../Class/Bibliotheque.php";

// Déterminer la vue actuelle, par défaut 'livres'
$view = $_GET['view'] ?? 'livres';

// Charger les données en fonction de la vue
$data = [];
switch ($view) {
    case 'users':
        $data = Utilisateur::getAll($connection);
        break;
    case 'bibliotheques':
        $data = Bibliotheque::getAll($connection);
        break;
    case 'livres':
    default:
        $data = Livre::getAll($connection);
        break;
}
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
    <h1 class="text-3xl font-bold mb-6">Panneau d'Administration</h1>

    <!-- Onglets de navigation -->
    <div class="flex space-x-2 mb-6 border-b overflow-x-auto pb-2">
        <a href="admin.php?view=livres" class="px-4 py-2 text-lg font-semibold <?= $view === 'livres' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">Livres</a>
        <a href="admin.php?view=users" class="px-4 py-2 text-lg font-semibold <?= $view === 'users' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">Utilisateurs</a>
        <a href="admin.php?view=bibliotheques" class="px-4 py-2 text-lg font-semibold <?= $view === 'bibliotheques' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">Bibliothèques</a>
    </div>

    <!-- Contenu dynamique -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <?php if ($view === 'livres'): ?>
            <h2 class="text-2xl font-semibold mb-4">Liste de tous les Livres</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bibliothèque</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($data as $livre): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($livre['titre']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($livre['auteur']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $livre['statut'] === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' ?>">
                                        <?= htmlspecialchars($livre['statut']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($livre['bibliotheque_nom'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($view === 'users'): ?>
            <h2 class="text-2xl font-semibold mb-4">Gestion des Utilisateurs</h2>
            <?php foreach ($data as $user): ?>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b py-3 last:border-b-0">
                    <div class="mb-2 sm:mb-0">
                        <p class="font-bold text-lg"><?= htmlspecialchars($user['nom']) ?> (<?= htmlspecialchars($user['prenom']) ?>)</p>
                        <p class="text-sm text-gray-600">Rôle : <span class="font-semibold <?= $user['role'] === 'admin' ? 'text-red-500' : 'text-gray-700' ?>"><?= htmlspecialchars($user['role']) ?></span></p>
                    </div>
                    <?php if ($user['role'] !== 'admin' && isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['id']): ?>
                        <form action="../traitement.php?action=grantAdmin" method="POST">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Promouvoir Admin</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php elseif ($view === 'bibliotheques'): ?>
            <h2 class="text-2xl font-semibold mb-4">Liste des Bibliothèques</h2>
            <?php foreach ($data as $biblio): ?>
                <div class="border-b py-3 last:border-b-0">
                    <p class="font-bold text-lg"><?= htmlspecialchars($biblio->getNom()) ?></p>
                    <p class="text-sm text-gray-600">Nombre de livres : <?= count($biblio->getLivres()) ?></p>
                    <p class="text-sm text-gray-600">Nombre d'utilisateurs : <?= count($biblio->getUtilisateurs()) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
  </div>
</body>
</html>