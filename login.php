<?php
session_start();
// Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] == '1') {
    $errorMessage = 'Nom d\'utilisateur ou mot de passe incorrect.';
}

$successMessage = '';
if (isset($_GET['status']) && $_GET['status'] == 'register_success') {
    $successMessage = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Connexion - Ma Bibliothèque</title>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form action="traitement.php?action=login" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h1 class="text-2xl font-bold mb-6 text-center">Connexion</h1>
        <?php if ($errorMessage): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"><?= $errorMessage ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert"><?= $successMessage ?></div>
        <?php endif; ?>
        <input class="border p-2 mb-4 w-full rounded" type="text" name="nom" placeholder="Votre nom d'utilisateur" required>
        <input class="border p-2 mb-4 w-full rounded" type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg w-full hover:bg-blue-600">Se connecter</button>
        <p class="text-center mt-4 text-sm">Pas de compte ? <a href="inscription.php" class="text-blue-500 hover:underline">S'inscrire</a></p>
    </form>
</body>
</html>