    <?php
        // On s'assure que la session est démarrée pour vérifier si l'utilisateur est connecté
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once "creerBibliotheque.php";
        require_once "../data/config.php";
        require_once "../Class/Livre.php";

        $bibliotheque = Bibliotheque::getAll($connection);

        // Si l'utilisateur est connecté, on récupère les ID des bibliothèques qu'il a déjà rejointes
        $biblios_rejointes_ids = [];
        if (isset($_SESSION['user_id'])) {
            $stmt = $connection->prepare("SELECT bibliotheque_id FROM bibliotheque_user WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $biblios_rejointes_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4'>";
        foreach ($bibliotheque as $biblio) {
            echo "<div class='bg-gradient-to-r from-yellow-100 to-yellow-200 shadow-lg rounded-xl p-6 border-l-4 border-yellow-500 hover:shadow-2xl transition duration-300'>";
                echo "<div>";
                    echo "<h2 class='text-2xl font-bold text-gray-800 mb-2'>{$biblio->getNom()}</h2>";
                    echo "<p class='text-sm text-gray-600'>📚 Nombre de livres : " . count($biblio->getLivres()) . "</p>";
                    echo "<p class='text-sm text-gray-600'>👥 Utilisateurs inscrits : " . count($biblio->getUtilisateurs()) . "</p>";
                echo "</div>";
                // Logique d'affichage du bouton
                if (isset($_SESSION['user_id'])) {
                    if (in_array($biblio->getId(), $biblios_rejointes_ids)) {
                        echo "<span class='mt-4 text-center font-bold text-green-600'>Déjà membre</span>";
                    } else {
                        echo "<form action='../traitement.php?action=rejoindreBibliotheque' method='POST' class='mt-4'><input type='hidden' name='bibliotheque_id' value='{$biblio->getId()}'><button type='submit' class='w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600'>Rejoindre</button></form>";
                    }
                }
            echo "</div>";
        }
        echo "</div>";

    ?>