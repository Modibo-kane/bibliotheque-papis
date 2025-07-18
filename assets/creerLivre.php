<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
  <title>Document</title>
</head>
<body class="bg-gray-100 flex items-center justify-start gap-12 min-h-screen flex-col">
  <?php 
  require_once "../assets/header.php";
  require_once "../assets/livre.php";
  ?>

  <form action="traitement.php?action=creerLivre" method="POST" class="bg-white p-4 rounded-lg shadow-md w-1/2">
      <h1 class="text-xl font-bold mb-4">Creer un livre</h1>
      <input type="text" name="titre" placeholder="Titre du livre" class="border p-2 mb-4 w-full"> 
      <input type="text" name="auteur" placeholder="Auteur du livre" class="border p-2 mb-4 w-full"> 
      <input type="text" name="annee" placeholder="Année de publication" class="border p-2 mb-4 w-full"> 
      <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg">Ajouter</button>
</form>
</body>
</html>