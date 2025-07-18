<!-- liste des livres de chaque bibliotheque -->

    <?php
        require_once __DIR__ . "/creerBibliotheque.php";
        $bibliotheque = Bibliotheque::getAll($connection);
        foreach ($bibliotheque as $biblio) {
              echo "<h2 class='text-2xl font-bold mb-4 bg-gray-100  rounded w-full'>{$biblio->getNom()}</h2>";
              echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
              foreach ($biblio->getLivres() as $livre) {
                  echo "<div class='relative bg-white p-6 rounded-xl shadow-xl w-auto min-h-48 transform  before:absolute before:left-0 before:top-0 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-xl'>";
                      echo "<div class='z10 relative h-full flex flex-col justifify-between gap-4'>";
                          echo "<h3 class='text-lg font-bold text-gray-800'>{$livre->afficherInfos()}</h3>";
                           echo "<div class='flex justify-between items-center'>";
                              if($livre->getStatut() === "disponible") {
                                 foreach ($biblio->getLivres() as $livre): ?>
                                    <form method="POST" action="emprunter.php">
                                        <input type="hidden" name="livre_id" value="<?= $livre->getId() ?>">
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded duration-500 hover:bg-blue-600">
                                            Emprunter
                                        </button>
                                    </form>
                                <?php endforeach; 
                              } else {
                                  
                                  echo "<p  class='px-4 py-2  cursor-pointer font-bold'>Emprunté </p>";

                              }
                              echo "   <p> statut:{$livre->getStatut()} </p>";
                           echo "</div>";
                      echo "</div>";
                     
                  echo "</div>";
              }
              echo "</div>";
              echo "<br>";
              echo "<br>";
              echo "<br>";
          }
   
            // require_once "data/config.php";
            // require_once "Class/Livre.php";
        
            // $livres = Livre::getAll($connection);
        
            // foreach ($livres as $livre) {
            //     echo "<div>{$livre['titre']} - {$livre['auteur']} ({$livre['anneePublication']})</div>";
            // }

