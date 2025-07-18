<?php 
 
class Livre{

  private  $id;
  private  $titre;
  private  $auteur;
  private  $anneePublication;
  private  $statut;
  

  public function __construct($titre, $auteur, $anneePublication){
        $this->titre= $titre;
        $this->auteur= $auteur;
        $this->anneePublication= $anneePublication;
        $this->statut= "disponible";

  }

  public function afficherInfos(){
    return  "Titre: " .$this->titre . " <br>Auteur: " . $this->auteur . "<br> Année de publication: " .$this->anneePublication;
    
  }
  public function emprunter(PDO $conn) {
    // Par exemple on ajoute une colonne `emprunté` dans la table
    $stmt = $conn->prepare("UPDATE livres SET statut = 'emprunté' WHERE id = ?");
    $stmt->execute([$this->id]);
    if($stmt->rowCount() > 0) {
        $this->statut = "emprunté";
        return true;
    } else {
        return false;
    }
}

  public function rendre(){
    if($this->statut === "emprunté"){
      $this->statut = "disponible";
      return "Livre ". $this->titre ." rendue avec succès <br>";
    }else{
      return  "Livre ". $this->titre ." est déja disponible <br>";
    }
  }

  public function getTitre(){
    return $this->titre;
  }
  public function messageStatut(){
    if($this->statut === "disponible"){
      return " a été emprunté avec succes <br>";
    }else{
      return  " n'est pas disponible <br>";
    }
  }

  public function getStatut(){
    return $this->statut;
  }

  public function save($connection){
    $sql = "INSERT INTO livre (titre, auteur, anneePublication, statut) VALUES (?, ?, ?, ?)";
     require_once __DIR__. "/../data/config.php";
    $stmt = $connection -> prepare($sql);
    $stmt -> execute([$this->titre, $this->auteur, $this->anneePublication, $this->statut]);
  }

  public function setId($id){
    $this->id =$id;
  }
  public function getId($id){
    $this->id =$id;
  }

    public static function fromArray(array $data): Livre {
        $livre = new Livre($data['titre'], $data['auteur'], $data['annee']);
        if (isset($data['id'])) {
            $livre->setId($data['id']);
        }
        return $livre;
    }

}