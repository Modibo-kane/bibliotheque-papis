<?php
session_start();
// Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$errorMessage = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'password_mismatch':
            $errorMessage = 'Les mots de passe ne correspondent pas.';
            break;
        case 'user_exists':
            $errorMessage = 'Ce nom d\'utilisateur est déjà pris.';
            break;
        case 'empty_fields':
            $errorMessage = 'Veuillez remplir tous les champs.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Inscription - Ma Bibliothèque</title>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form action="traitement.php?action=register" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h1 class="text-2xl font-bold mb-6 text-center">Créer un compte</h1>
        <?php if ($errorMessage): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"><?= $errorMessage ?></div>
        <?php endif; ?>
        <input class="border p-2 mb-4 w-full rounded" type="text" name="nom" placeholder="Nom d'utilisateur" required>
        <input class="border p-2 mb-4 w-full rounded" type="text" name="prenom" placeholder="Prénom" required>
        <input class="border p-2 mb-4 w-full rounded" type="password" name="password" placeholder="Mot de passe" required>
        <input class="border p-2 mb-4 w-full rounded" type="password" name="password_confirm" placeholder="Confirmez le mot de passe" required>
        <button type="submit" class="bg-green-500 text-white p-2 rounded-lg w-full hover:bg-green-600">S'inscrire</button>
        <p class="text-center mt-4 text-sm">Déjà un compte ? <a href="login.php" class="text-blue-500 hover:underline">Se connecter</a></p>
    </form>
</body>
</html>