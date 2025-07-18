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
     foreach($this->listeDesLivres as $livre){
      if($livre->getStatut() === "disponible"){
        $emprunte[] = $livre->afficherInfos();
      }
     }
     if (empty($emprunte)) {
        return "📭 Aucun livre disponible pour le moment.<br>";
    }

    return implode("<br><br>", $emprunte);
    }
    public function listeDesLivresEmpruntes(){
     $emprunte = [];
     foreach($this->listeDesLivres as $livre){
      if($livre->getStatut() === "emprunté"){
        $emprunte[] = $livre->afficherInfos();
      }
     }
     if (empty($emprunte)) {
        return "<br><br> Aucun livre n'a été emprunté pour le moment.<br>";
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
            // On charge toutes les informations du livre depuis la BDD
            $livre = new Livre($livreRow['titre'], $livreRow['auteur'], $livreRow['anneePublication']);
            $livre->setId($livreRow['id']);
            // Il faut ajouter les setters/getters pour statut et utilisateur_id dans la classe Livre si ce n'est pas fait
            $livre->setStatut($livreRow['statut']); 
            $livre->setUtilisateurId($livreRow['utilisateur_id']);
            $biblio->ajouterLivre($livre);
        }

        // Charger les utilisateurs
        $stmtUsers = $connection->prepare(
            "SELECT u.* FROM users u 
             JOIN bibliotheque_user bu ON u.id = bu.user_id 
             WHERE bu.bibliotheque_id = ?"
        );
        $stmtUsers->execute([$biblio->getId()]);
        while ($userRow = $stmtUsers->fetch(PDO::FETCH_ASSOC)) {
            $utilisateur = new Utilisateur($userRow['nom'], $userRow['prenom'], null); // On passe null pour le mot de passe car on ne le charge pas ici
            $biblio->ajouterUtilisateur($utilisateur);
        }

        $resultats[] = $biblio;
    }

    return $resultats;
}

public static function save($connection, $nom) {
    $sql = "INSERT INTO bibliotheque (nom) VALUES (?)";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$nom]);
}

}