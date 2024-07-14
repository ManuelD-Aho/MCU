<?php
session_start();
require_once '../../includes/fonctions.php';
require_once '../../configuration/config.php';

// Fonction pour enregistrer la déconnexion
function enregistrerDeconnexion($type, $nom) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    loggerAction("Déconnexion du $type $nom - IP: $ip_address - Navigateur: $user_agent");
}

// Vérifier si l'utilisateur ou l'admin est connecté
if (isset($_SESSION['admin_id']) || isset($_SESSION['client_id'])) {
    if (isset($_SESSION['admin_id'])) {
        $nom = isset($_SESSION['nom_admin']) ? $_SESSION['nom_admin'] : 'Inconnu';
        enregistrerDeconnexion('administrateur', $nom);
    } elseif (isset($_SESSION['client_id'])) {
        $nom = isset($_SESSION['nom_utilisateur']) ? $_SESSION['nom_utilisateur'] : 'Inconnu';
        enregistrerDeconnexion('utilisateur', $nom);
    }

    // Détruire toutes les variables de session
    $_SESSION = array();

    // Détruire le cookie de session si utilisé
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Détruire la session
    session_destroy();
}

// Générer un nouveau jeton CSRF pour la page de connexion
session_start(); // Redémarrer une nouvelle session pour le jeton CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Rediriger vers la page appropriée avec un message
if (isset($_SESSION['admin_id'])) {
    redirigerAvecMessage("../../admin/auth/connexion.php", "Vous avez été déconnecté avec succès.", "success");
} else {
    redirigerAvecMessage("../../pages/connexion_inscription.php", "Vous avez été déconnecté avec succès.", "success");
}
exit();
?>

