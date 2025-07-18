<?php 

class Utilisateur{
  private $nom;
  private $prenom;
  private $listeDesLivreEmpruntes;


  public function __construct($nom, $prenom){
    $this->nom= $nom;
    $this->prenom= $prenom;
    $this->listeDesLivreEmpruntes= [];
  }

    public function getInfos(){
      return "Nom: ".$this->nom . "<br> " . "Prenom: ".$this->prenom;
    }

  public function emprunterLivre($livre){
    if(count($this->listeDesLivreEmpruntes)>=3){
      return "Vous avez dépassé le nombre de livre autorisé à emprunter. Veuillez rendre un livre pour pouvoir emprunter un nouveau";
    }else if($livre->emprunter()){
     $this->listeDesLivreEmpruntes[]= $livre;
     return  "Le livre: ". "<strong>". $livre->getTitre(). "</strong>" . $livre->messageStatut();
    }else{
     return  "Le livre: ". "<strong>". $livre->getTitre(). "</strong>" . "est déja emprunté";
    };
  }
  public function rendreLivre($titre) {
    foreach($this->listeDesLivreEmpruntes as $index => $liste){
      if($liste->getTitre() === $titre){
          $message = $liste->rendre();

          unset($this->listeDesLivreEmpruntes[$index]);
          return $message;
      };
    }
  }

  public function afficherLivresEmpruntes(){
      if(count($this->listeDesLivreEmpruntes) === 0){
        echo "liste vide";
      }else{
        foreach($this->listeDesLivreEmpruntes as $liste){
        echo $liste->getTitre(). "<br>";
      }
      }
  }

  public function __toString() {
    return $this->prenom . " " . $this->nom . "<br>";
}

  public function save($connection){
    $sql = "INSERT INTO utilisateur (nom, prenom,listeDesLivreEmpruntes) VALUES (?, ?, ?)";
    //  require_once __DIR__. "/../data/config.php";
    $stmt = $connection -> prepare($sql);
    $stmt -> execute([$this->nom, $this->prenom, json_encode($this->listeDesLivreEmpruntes)]);
  }

//   public static function getAll($connection) {
//     $sql = "SELECT * FROM utilisateur";
//     $stmt = $connection->query($sql);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }
}