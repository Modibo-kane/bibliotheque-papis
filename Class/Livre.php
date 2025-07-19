<?php 
 
class Livre{

  private  $id;
  private  $titre;
  private  $auteur;
  private  $anneePublication;
  private  $statut;
  private  $utilisateur_id;
  

  public function __construct($titre, $auteur, $anneePublication){
        $this->titre= $titre;
        $this->auteur= $auteur;
        $this->anneePublication= $anneePublication;
        $this->statut= "disponible";
        $this->utilisateur_id = null;

  }

  public function afficherInfos(){
    return  "Titre: " .$this->titre . " <br>Auteur: " . $this->auteur . "<br> Année de publication: " .$this->anneePublication;
    
  }
  public static function emprunter(PDO $conn, int $livreId, int $utilisateurId): bool {
    // On met à jour le statut et on lie le livre à l'utilisateur
    // On s'assure aussi que le livre est bien disponible pour éviter les "race conditions"
    $stmt = $conn->prepare("UPDATE livre SET statut = 'emprunté', utilisateur_id = ? WHERE id = ? AND statut = 'disponible'");
    $stmt->execute([$utilisateurId, $livreId]);
    if($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

  public static function rendre(PDO $conn, int $livreId, int $utilisateurId): bool {
    // On rend le livre en remettant son statut à "disponible" et en retirant l'ID de l'utilisateur
    // On vérifie que c'est bien le bon utilisateur qui rend le livre
    $stmt = $conn->prepare("UPDATE livre SET statut = 'disponible', utilisateur_id = NULL WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$livreId, $utilisateurId]);
    return $stmt->rowCount() > 0;
}

  public function getTitre(){
    return $this->titre;
  }
  public function getAuteur(){
    return $this->auteur;
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

  public function setStatut($statut){
    $this->statut = $statut;
  }

  public function getUtilisateurId(){
      return $this->utilisateur_id;
  }

  public function setUtilisateurId($id){
      $this->utilisateur_id = $id;
  }

  public function save($connection, $bibliotheque_id){
    // Utilisation de minuscules pour les noms de colonnes pour la compatibilité (PostgreSQL)
    $sql = "INSERT INTO livre (titre, auteur, anneepublication, statut, bibliotheque_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection -> prepare($sql);
    $stmt -> execute([$this->titre, $this->auteur, $this->anneePublication, $this->statut, $bibliotheque_id]);
  }

  public function setId($id){
    $this->id =$id;
  }
  public function getId(){
    return $this->id;
  }

    public static function getAll(PDO $conn): array {
        $stmt = $conn->prepare(
            "SELECT l.*, b.nom as bibliotheque_nom 
             FROM livre l 
             LEFT JOIN bibliotheque b ON l.bibliotheque_id = b.id 
             ORDER BY l.titre ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fromArray(array $data): Livre {
        // Gérer la casse différente des clés pour la compatibilité MySQL/PostgreSQL
        $annee = $data['anneePublication'] ?? $data['anneepublication'] ?? null;
        $livre = new Livre($data['titre'], $data['auteur'], $annee);
        if (isset($data['id'])) {
            $livre->setId($data['id']);
        }
        return $livre;
    }

    public static function findBorrowedByUserId(PDO $conn, int $utilisateurId): array {
        $stmt = $conn->prepare("SELECT * FROM livre WHERE utilisateur_id = ?");
        $stmt->execute([$utilisateurId]);
        
        $livres = [];
        $livresData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($livresData as $data) {
            // Gérer la casse différente des clés pour la compatibilité MySQL/PostgreSQL
            $annee = $data['anneePublication'] ?? $data['anneepublication'] ?? null;
            $livre = new Livre($data['titre'], $data['auteur'], $annee);
            $livre->setId($data['id']);
            $livre->setStatut($data['statut']);
            $livre->setUtilisateurId($data['utilisateur_id']);
            $livres[] = $livre;
        }
        return $livres;
    }

}