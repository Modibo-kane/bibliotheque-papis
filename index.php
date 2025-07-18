<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();// On démarre la session sur chaque page où le header est inclus
// pour avoir accès aux variables de session.
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ma Bibliothèque</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-mono">

    <?php
        require_once "assets/header.php";
        require_once "data/config.php";
        require_once "Class/Livre.php";
        require_once "Class/Utilisateur.php";
    ?>

    <div class="container mx-auto mt-8 p-4">
        <?php if (isset($_SESSION['user_id'])): 
            // L'utilisateur est connecté, on charge ses données pour le tableau de bord
            $livresEmpruntes = Livre::findBorrowedByUserId($connection, $_SESSION['user_id']);
            $sharedUsers = Utilisateur::findSharedLibraryUsers($connection, $_SESSION['user_id']);
        ?>
            <h1 class="text-3xl font-bold mb-6">Tableau de bord de <?= htmlspecialchars($_SESSION['user_nom']) ?></h1>

            <!-- Section des livres empruntés -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Mes livres empruntés</h2>
                <div class="bg-white shadow-md rounded-lg p-6">
                    <?php if (empty($livresEmpruntes)): ?>
                        <p class="text-gray-600">Vous n'avez emprunté aucun livre pour le moment.</p>
                    <?php else: ?>
                        <ul class="list-disc list-inside space-y-2 text-gray-800">
                        <?php foreach ($livresEmpruntes as $livre): ?>
                            <li><strong><?= htmlspecialchars($livre->getTitre()) ?></strong> par <?= htmlspecialchars($livre->getAuteur()) ?></li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section des utilisateurs partageant une bibliothèque -->
            <div>
                <h2 class="text-2xl font-semibold mb-4">Utilisateurs dans vos bibliothèques</h2>
                <div class="bg-white shadow-md rounded-lg p-6">
                    <?php if (empty($sharedUsers)): ?>
                        <p class="text-gray-600">Personne d'autre n'a rejoint vos bibliothèques pour le moment.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                        <?php foreach ($sharedUsers as $user): ?>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                <p class="font-semibold text-lg"><?= htmlspecialchars($user['nom']) ?></p>
                                <p class="text-sm text-gray-600">Bibliothèques en commun : <?= htmlspecialchars($user['shared_libraries']) ?></p>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- L'utilisateur n'est pas connecté -->
            <div class="text-center py-16">
                <h1 class="text-4xl font-bold mb-4">Bienvenue sur Ma Bibliothèque</h1>
                <p class="text-lg text-gray-700">Veuillez vous <a href="login.php" class="text-blue-500 hover:underline">connecter</a> ou vous <a href="inscription.php" class="text-blue-500 hover:underline">inscrire</a> pour accéder à nos services.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
