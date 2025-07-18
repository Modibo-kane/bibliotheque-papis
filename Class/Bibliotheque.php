<?php
require_once "Livre.php";
require_once "Utilisateur.php";
class Bibliotheque{
  private $nom; 
  private $id; 
  private $listeDesLivres;
  private $listeDesUtilisateurs;

  public function __construct($nom){
    $this->nom= $nom;
    $this->listeDesLivres= [];
    $this->listeDesUtilisateurs= [];

  }
  public function ajouterLivre($livre) {
    $this->listeDesLivres[] = $livre;
}


    public function ajouterUtilisateur($utilisateur) {
        $this->listeDesUtilisateurs[] = $utilisateur;
    }

    public function listeDesLivresDisponibles(){
     $emprunte = [];
     foreach($this->listeDesLivres as $user){
      if($user->getStatut() === "emprunte"){
        $emprunte[] = $user->afficherInfos();
      }
     }
     if (empty($emprunte)) {
        return "📭 Aucun user emprunte pour le moment.<br>";
    }

    return implode("<br><br>", $emprunte);
    }
    public function listeDesLivresEmpruntes(){
     $emprunte = [];
     foreach($this->listeDesLivres as $user){
      if($user->getStatut() === "emprunté"){
        $emprunte[] = $user->afficherInfos();
      }
     }
     if (empty($emprunte)) {
        return "<br><br> Aucun user n'a emprunté pour le moment.<br>";
    }

    return implode("<br><br>", $emprunte);
    }
    public function getUtilisateurs() {
    return $this->listeDesUtilisateurs;
}
    public function getLivres() {
    return $this->listeDesLivres;
}


public function getNom() {
    return $this->nom;
}     

public function setId($id) {
        $this->id = $id;
    }

    // GETTER pour l'id
    public function getId() {
        return $this->id;
    }

public static function getAll($connection) {
    $sql = "SELECT * FROM bibliotheque";
    $stmt = $connection->query($sql);
    $resultats = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $biblio = new Bibliotheque($row['nom']);
        $biblio->setId($row['id']); // si tu as un champ id

        // Charger les livres
        $stmtLivres = $connection->prepare("SELECT * FROM livre WHERE bibliotheque_id = ?");
        $stmtLivres->execute([$row['id']]);
        while ($livreRow = $stmtLivres->fetch(PDO::FETCH_ASSOC)) {
            $livre = new Livre($livreRow['titre'], $livreRow['auteur'], $livreRow['annee']);
            $biblio->ajouterLivre($livre);
        }

        // Charger les utilisateurs
        $stmtUsers = $connection->prepare("SELECT * FROM users WHERE bibliotheque_id = ?");
        $stmtUsers->execute([$row['id']]);
        while ($userRow = $stmtUsers->fetch(PDO::FETCH_ASSOC)) {
            $utilisateur = new Utilisateur($userRow['nom'], $userRow['email']);
            $biblio->ajouterUtilisateur($utilisateur);
        }

        $resultats[] = $biblio;
    }

    return $resultats;
}

}