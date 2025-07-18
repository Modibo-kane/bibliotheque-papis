    <?php
        require_once "creerBibliotheque.php";
        require_once "../data/config.php";
        require_once "../Class/Livre.php";

        $bibliotheque = Bibliotheque::getAll($connection);
        echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4'>";
        foreach ($bibliotheque as $biblio) {
            echo "<div class='bg-gradient-to-r from-yellow-100 to-yellow-200 shadow-lg rounded-xl p-6 border-l-4 border-yellow-500 hover:shadow-2xl transition duration-300'>";
                echo "<h2 class='text-2xl font-bold text-gray-800 mb-2'>{$biblio->getNom()}</h2>";
                echo "<p class='text-sm text-gray-600'>📚 Nombre de livres : " . count($biblio->getLivres()) . "</p>";
                echo "<p class='text-sm text-gray-600'>👥 Utilisateurs inscrits : " . count($biblio->getUtilisateurs()) . "</p>";
            echo "</div>";
        }
        echo "</div>";

    ?>