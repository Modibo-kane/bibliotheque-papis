<?php 
session_start();
require_once "data/config.php";
require_once "Class/Livre.php";
require_once "Class/Utilisateur.php";
require_once "Class/Bibliotheque.php";

// On s'assure qu'une action est bien demandée
if (!isset($_GET['action'])) {
    header('Location: index.php');
    exit('Action non spécifiée.');
}

$action = $_GET['action'];

switch ($action) {
    case 'creerLivre':
        // 1. Protection de l'action : seuls les admins peuvent passer
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Redirection ou affichage d'une erreur pour les non-admins
            header('Location: index.php?error=unauthorized');
            exit('Accès non autorisé.');
        }

        // On pourrait protéger cette action pour que seuls les admins puissent ajouter des livres
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $titre = trim($_POST["titre"]);
            $auteur = trim($_POST["auteur"]);
            $annee = trim($_POST["annee"]);
            $bibliotheque_id = (int)$_POST["bibliotheque_id"];

            $livre = new Livre($titre, $auteur, $annee);
            $livre->save($connection, $bibliotheque_id); // La méthode save gère l'insertion
            header("Location: Public/listeLivre.php?creation=success");
            exit();
        }
        break;

    case 'register': // Inscription d'un nouvel utilisateur
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $prenom = trim($_POST["prenom"]);
            $password = $_POST["password"];
            $password_confirm = $_POST["password_confirm"];

            // Validation simple
            if (empty($nom) || empty($prenom) || empty($password)) {
                header('Location: inscription.php?error=empty_fields');
                exit();
            }

            if ($password !== $password_confirm) {
                header('Location: inscription.php?error=password_mismatch');
                exit();
            }

            // Vérifier si l'utilisateur existe déjà
            $stmt = $connection->prepare("SELECT id FROM users WHERE nom = ?");
            $stmt->execute([$nom]);
            if ($stmt->fetch()) {
                header('Location: inscription.php?error=user_exists');
                exit();
            }

            $utilisateur = new Utilisateur($nom, $prenom, $password);
            $utilisateur->save($connection);
            
            // On redirige vers la page de connexion avec un message de succès
            header("Location: login.php?status=register_success");
            exit();
        }
        break;

    case 'login':
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = $_POST['nom'];
            $password = $_POST['password'];

            $stmt = $connection->prepare("SELECT * FROM users WHERE nom = ?");
            $stmt->execute([$nom]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Le mot de passe est correct, on crée la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role']; // 2. On stocke le rôle en session
                header('Location: index.php');
                exit();
            } else {
                // Identifiants incorrects
                header('Location: login.php?error=1');
                exit();
            }
        }
        break;

    case 'emprunter':
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit('Vous devez être connecté pour emprunter un livre.');
        }

        if (isset($_POST['livre_id'])) {
            $livreId = (int)$_POST['livre_id'];
            $utilisateurId = (int)$_SESSION['user_id'];

            // Vérifier le nombre de livres déjà empruntés par l'utilisateur
            $stmt = $connection->prepare("SELECT COUNT(*) FROM livre WHERE utilisateur_id = ?");
            $stmt->execute([$utilisateurId]);
            $nombreEmprunts = $stmt->fetchColumn();

            if ($nombreEmprunts >= 3) {
                // L'utilisateur a atteint la limite
                header('Location: Public/listeLivre.php?error=max_emprunts');
                exit();
            }

            // On utilise la méthode statique de la classe Livre pour gérer l'emprunt
            $succes = Livre::emprunter($connection, $livreId, $utilisateurId);

            if ($succes) {
                // Redirection avec un message de succès si vous le souhaitez
                header('Location: Public/listeLivre.php?emprunt=success');
            } else {
                // Le livre a peut-être déjà été emprunté par quelqu'un d'autre ou n'existe pas
                header('Location: Public/listeLivre.php?emprunt=fail');
            }
            exit();
        }
        break;

    case 'rendre':
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit('Vous devez être connecté pour rendre un livre.');
        }

        if (isset($_POST['livre_id'])) {
            $livreId = (int)$_POST['livre_id'];
            $utilisateurId = (int)$_SESSION['user_id'];

            $succes = Livre::rendre($connection, $livreId, $utilisateurId);

            if ($succes) {
                header('Location: Public/listeLivre.php?rendu=success');
            } else {
                // Échec : peut-être que le livre n'était pas emprunté par cet utilisateur
                header('Location: Public/listeLivre.php?rendu=fail');
            }
            exit();
        }
        break;

    case 'rejoindreBibliotheque':
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit('Vous devez être connecté pour rejoindre une bibliothèque.');
        }

        if (isset($_POST['bibliotheque_id'])) {
            $bibliothequeId = (int)$_POST['bibliotheque_id'];
            $utilisateurId = (int)$_SESSION['user_id'];

            // On insère la nouvelle relation dans la table pivot
            // On utilise IGNORE pour éviter une erreur si l'utilisateur essaie de rejoindre une bibliothèque où il est déjà
            $stmt = $connection->prepare("INSERT IGNORE INTO bibliotheque_user (bibliotheque_id, user_id) VALUES (?, ?)");
            $stmt->execute([$bibliothequeId, $utilisateurId]);

            header('Location: Public/listeBibliotheque.php?join=success');
            exit();
        }
        header('Location: Public/listeBibliotheque.php');
        break;

    case 'creerBibliotheque':
        // 3. Nouvelle action protégée pour créer une bibliothèque
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?error=unauthorized');
            exit('Accès non autorisé.');
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty(trim($_POST['nom']))) {
            $nom = trim($_POST['nom']);
            
            // On utilise une méthode statique pour sauvegarder la nouvelle bibliothèque
            Bibliotheque::save($connection, $nom);

            header("Location: Public/listeBibliotheque.php?creation=success");
            exit();
        }
        // Redirection en cas d'échec
        header("Location: Public/listeBibliotheque.php?creation=fail");
        break;

    case 'grantAdmin':
        // Sécurité : Seuls les admins peuvent promouvoir d'autres utilisateurs
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?error=unauthorized');
            exit('Accès non autorisé.');
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
            $userIdToPromote = (int)$_POST['user_id'];

            $stmt = $connection->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
            $stmt->execute([$userIdToPromote]);

            header("Location: Public/admin.php?status=promoted");
            exit();
        }
        header("Location: Public/admin.php?status=fail");
        break;

    default:
        header('Location: index.php');
        exit('Action inconnue.');
}
