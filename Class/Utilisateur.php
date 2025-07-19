<?php 

class Utilisateur{
  private $id;
  private $nom;
  private $prenom;
  private $password; // Ajout du mot de passe
  private $listeDesLivreEmpruntes;


  public function __construct($nom, $prenom, $password = null){
    $this->nom= $nom;
    $this->prenom= $prenom;
    $this->password = $password;
    $this->listeDesLivreEmpruntes= [];
  }

    public function getInfos(){
      return "Nom: ".$this->nom . "<br> " . "Prenom: ".$this->prenom;
    }

  public function __toString() {
    return $this->prenom . " " . $this->nom . "<br>";
}

  public function save($connection){
    // On hache le mot de passe avant de le sauvegarder
    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (nom, prenom, password) VALUES (?, ?, ?)";
    //  require_once __DIR__. "/../data/config.php";
    $stmt = $connection -> prepare($sql);
    $stmt -> execute([$this->nom, $this->prenom, $hashedPassword]);
  }

  public static function getAll($connection) {
    $sql = "SELECT id, nom, prenom, role FROM users ORDER BY nom ASC";
    $stmt = $connection->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function findSharedLibraryUsers(PDO $conn, int $currentUserId): array {
    // On détecte le pilote de BDD pour utiliser la bonne fonction d'agrégation de chaînes.
    // GROUP_CONCAT pour MySQL, STRING_AGG pour PostgreSQL.
    $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);
    $aggFunction = ($driver === 'mysql') 
        ? "GROUP_CONCAT(b.nom SEPARATOR ', ')" 
        : "STRING_AGG(b.nom, ', ')";

    $sql = "SELECT
                u.id, u.nom, u.prenom,
                {$aggFunction} as shared_libraries
            FROM users u
            JOIN bibliotheque_user bu ON u.id = bu.user_id
            JOIN bibliotheque b ON bu.bibliotheque_id = b.id
            WHERE bu.bibliotheque_id IN (
                SELECT bibliotheque_id FROM bibliotheque_user WHERE user_id = ?
            ) AND u.id != ?
            GROUP BY u.id, u.nom, u.prenom
            ORDER BY u.nom";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$currentUserId, $currentUserId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}