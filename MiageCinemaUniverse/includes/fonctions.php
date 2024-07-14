<?php
require_once __DIR__ . '/../configuration/config.php';

/**
 * Nettoie et sécurise les entrées utilisateur.
 * @param string $donnee La donnée à nettoyer.
 * @return string La donnée nettoyée et sécurisée.
 */
function nettoyerEntree($donnee) {
    global $connexion;
    $donnee = trim($donnee);
    $donnee = stripslashes($donnee);
    $donnee = htmlspecialchars($donnee, ENT_QUOTES, 'UTF-8');
    return $connexion->real_escape_string($donnee);
}

/**
 * Gère l'upload d'une image.
 * @param string $nomChamp Nom du champ de formulaire.
 * @param string $dossierDestination Chemin du dossier de destination.
 * @return string|null Chemin du fichier uploadé ou null en cas d'échec.
 */
function gererUploadImage($nomChamp, $dossierDestination) {
    if (!isset($_FILES[$nomChamp]) || $_FILES[$nomChamp]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif'];
    $extensionFichier = strtolower(pathinfo($_FILES[$nomChamp]['name'], PATHINFO_EXTENSION));

    if (!in_array($extensionFichier, $extensionsAutorisees)) {
        return null;
    }

    $nomUnique = uniqid() . '.' . $extensionFichier;
    $cheminDestination = $dossierDestination . $nomUnique;

    if (move_uploaded_file($_FILES[$nomChamp]['tmp_name'], $cheminDestination)) {
        return $cheminDestination;
    }

    return null;
}

/**
 * Obtient le prix d'un siège en fonction de sa position.
 * @param array $tarifs Liste des tarifs.
 * @param string $position Position du siège.
 * @return float Prix du siège.
 */

/**
 * Valide et sécurise les données.
 * @param string $donnees Les données à valider.
 * @return string Les données validées et sécurisées.
 */
function validerDonnees($donnees) {
    global $connexion;
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees, ENT_QUOTES, 'UTF-8');
    return $connexion->real_escape_string($donnees);
}

/**
 * Gère l'upload d'une vidéo.
 * @param string $nomChamp Nom du champ de formulaire.
 * @param string $dossierDestination Chemin du dossier de destination.
 * @return string|null Chemin du fichier uploadé ou null en cas d'échec.
 */
function gererUploadVideo($nomChamp, $dossierDestination) {
    if (!isset($_FILES[$nomChamp]) || $_FILES[$nomChamp]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensionsAutorisees = ['mp4', 'avi', 'mov'];
    $extensionFichier = strtolower(pathinfo($_FILES[$nomChamp]['name'], PATHINFO_EXTENSION));

    if (!in_array($extensionFichier, $extensionsAutorisees)) {
        return null;
    }

    $nomUnique = uniqid() . '.' . $extensionFichier;
    $cheminDestination = $dossierDestination . $nomUnique;

    if (move_uploaded_file($_FILES[$nomChamp]['tmp_name'], $cheminDestination)) {
        return $cheminDestination;
    }

    return null;
}

/**
 * Vérifie si l'utilisateur est un administrateur.
 * @return bool True si l'utilisateur est un admin, false sinon.
 */
function estAdmin() {
    return isset($_SESSION['admin_id']);
}

/**
 * Enregistre une action importante dans un fichier de log.
 * @param string $action Description de l'action.
 */
function loggerAction($action) {
    $fichierLog = __DIR__ . '/../autres/actions.log';
    if (!file_exists(dirname($fichierLog))) {
        mkdir(dirname($fichierLog), 0777, true);
    }
    if (!file_exists($fichierLog)) {
        touch($fichierLog);
    }
    $date = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $utilisateur = $_SESSION['nom_utilisateur'] ?? 'Invité';
    $entreeLog = "[$date] IP: $ip | Utilisateur: $utilisateur | Action: $action\n";
    file_put_contents($fichierLog, $entreeLog, FILE_APPEND);
}
function redirigerAvecMessage($url, $message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}
function genererSieges($salle_id, $connexion) {
    // Vérifier si les sièges existent déjà pour cette salle
    $query = "SELECT COUNT(*) as count FROM sieges WHERE salle_id = ?";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("i", $salle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        // Les sièges existent déjà, pas besoin de les régénérer
        return;
    }

    // Configuration de la salle
    $rangees = range('A', 'J'); // 10 rangées de A à J
    $sieges_par_rangee = 15;

    // Générer les sièges
    $sieges = [];
    foreach ($rangees as $index => $rangee) {
        $position_y = $index * 30; // Espacement vertical entre les rangées
        for ($numero = 1; $numero <= $sieges_par_rangee; $numero++) {
            $siege_id = $rangee . sprintf("%02d", $numero);

            // Déterminer la section et le prix
            if ($numero <= 5) {
                $section = 'gauche';
                $prix = 5000;
            } elseif ($numero > 10) {
                $section = 'droite';
                $prix = 5000;
            } else {
                $section = 'milieu';
                $prix = 7000;
            }

            // Calculer la position_x pour créer un arc
            $position_x = ($numero - 1) * 30; // Espacement horizontal de base
            $courbure = 20 * sin(M_PI * ($numero / ($sieges_par_rangee + 1))); // Formule pour créer un arc
            $position_x += $courbure;

            $sieges[] = [
                'siege_id' => $siege_id,
                'salle_id' => $salle_id,
                'rangee' => $rangee,
                'numero' => $numero,
                'section' => $section,
                'prix' => $prix,
                'position_x' => round($position_x),
                'position_y' => $position_y
            ];
        }
    }

    // Insérer les sièges dans la base de données
    $query = "INSERT INTO sieges (siege_id, salle_id, rangee, numero, section, prix, position_x, position_y) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connexion->prepare($query);

    foreach ($sieges as $siege) {
        $stmt->bind_param("sisssiii", $siege['siege_id'], $siege['salle_id'], $siege['rangee'], $siege['numero'], $siege['section'], $siege['prix'], $siege['position_x'], $siege['position_y']);
        $stmt->execute();
    }

    $stmt->close();
}

function getPrixSiege($siege, $categorie_client = 'normal') {
    // Vérifier si $siege est un tableau et contient la clé 'prix'
    if (!is_array($siege) || !isset($siege['prix'])) {
        throw new Exception("Données de siège invalides");
    }

    $prix_base = $siege['prix'];

    return round($prix_base, 2);
}
function dateToFrench($date, $format)
{
    $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    $french_days = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
    $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
    return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date))));
}

function genererTokenCSRF() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}



