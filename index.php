<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ma Bibliothèque</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center font-mono justify-start gap-12 min-h-screen flex-col">

    <?php
        require_once "assets/header.php";
        require_once "assets/creerBibliotheque.php";
        require_once "data/config.php";
        // ";
        require_once "Class/Utilisateur.php";

        $bibliotheque = Bibliotheque::getAll($connection);
        foreach ($bibliotheque as $biblio) {
            echo "<h2 class='text-2xl font-bold mb-4'>{$biblio->getNom()}</h2>";
            echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
            foreach ($biblio->getUtilisateurs() as $user) {
                echo "<div class='bg-white p-4 rounded-lg shadow-md'>";
                echo "<h3 class='text-xl font-semibold'>{$user->getInfos()}</h3>";
                echo "</div>";
            }
            echo "</div>";
        }
    ?>
    
</body>
</html>
