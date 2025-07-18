<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
  <title>Document</title>
</head>
<body>
  <!-- ATTENTION: L'action 'creerUser' n'existe plus dans traitement.php. L'action pour l'inscription est 'register' et requiert un mot de passe. -->
  <form action="../traitement.php?action=register" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
  <h1 class="text-2xl font-bold mb-6 text-center">Ajouter un utilisateur</h1>
  <input class="border p-2 mb-4 w-full rounded" type="text" name="nom" placeholder="Nom d'utilisateur" required>
  <input class="border p-2 mb-4 w-full rounded" type="text" name="prenom" placeholder="Prénom" required>
  <input class="border p-2 mb-4 w-full rounded" type="password" name="password" placeholder="Mot de passe" required>
  <input class="border p-2 mb-4 w-full rounded" type="password" name="password_confirm" placeholder="Confirmez le mot de passe" required>
  <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg w-full" >Ajouter Utilisateur</button>
</form>
</body>
</html>