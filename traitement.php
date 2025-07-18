<?php
 require_once "Class/Livre.php";
 require_once "data/config.php";
 require_once "assets/creerBibliotheque.php";

 $bibliotheque = new Bibliotheque("Premiere Bibliothèque");
 

 if($_SERVER["REQUEST_METHOD"] === "POST") {
   if(isset($_GET["action"])){
      $demande= $_GET['action'];
      if ($demande === "creerLivre"){
        $titre = $_POST["titre"];
        $auteur = $_POST["auteur"];
        $annee = $_POST["annee"];

        $livre = new Livre($titre, $auteur, $annee);
        $bibliotheque -> ajouterLivre($livre);
        $livre->save($connection);
        header("Location: index.php");
        exit();
      }

      if($demande === "creerUtilisateur"){
        $nom= $_POST["nom"];
        $prenom= $_POST["prenom"];

        $utilisateur = new Utilisateur($nom, $prenom);
        
        $bibliotheque -> ajouterUtilisateur($utilisateur);
        $utilisateur->save($connection);
        header("Location: index.php");
        exit();
      }

      if(isset($_POST['livre_id'])){
          $livreId = (int) $_POST['livre_id'];

          // Rechercher le livre dans la base
          $stmt = $connection->prepare("SELECT * FROM livres WHERE id = ?");
          $stmt->execute([$livreId]);
          $data = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($data) {
                // On suppose que tu as une méthode Livre::fromArray($data)
                $livre = new Livre($data['titre'], $data['auteur'], $data['annee']);
                $livre->setId($data['id']);

                // 👉 Appelle ici la méthode emprunter (tu dois la créer dans Livre)
                $livre->emprunter($connection);

                echo "Le livre a été emprunté avec succès !";
            } else {
                echo "Livre non trouvé.";
            }
        } else {
            echo "Requête invalide.";
        }
      }
   }
