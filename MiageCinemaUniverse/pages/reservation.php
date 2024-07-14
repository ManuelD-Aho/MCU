<?php
session_start();
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/fonctions.php';
include_once __DIR__ . '/header.php';

if (!isset($_SESSION['client_id'])) {
    redirigerAvecMessage('connexion_inscription.php', 'Veuillez vous connecter pour effectuer une réservation.', 'warning');
}

$horaire_id = isset($_GET['horaire_id']) ? intval($_GET['horaire_id']) : 0;

if ($horaire_id === 0) {
    redirigerAvecMessage('films.php', 'Horaire invalide.', 'error');
}

// Récupérer les informations sur l'horaire et le film
$query = "SELECT h.*, f.titre_film, f.duree_minutes, f.affiche, s.nom_salle, s.capacite
          FROM horaires h
          JOIN films f ON h.film_id = f.film_id
          JOIN salles s ON h.salle_id = s.salle_id
          WHERE h.horaire_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $horaire_id);
$stmt->execute();
$result = $stmt->get_result();
$horaire = $result->fetch_assoc();

if (!$horaire) {
    redirigerAvecMessage('films.php', 'Horaire non trouvé.', 'error');
}

// Récupérer les sièges déjà réservés pour cet horaire
$query = "SELECT siege_id FROM reservations_sieges WHERE horaire_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $horaire_id);
$stmt->execute();
$result = $stmt->get_result();
$sieges_reserves = $result->fetch_all(MYSQLI_ASSOC);

$sieges_reserves_ids = array_column($sieges_reserves, 'siege_id');

// Récupérer tous les sièges de la salle
$query = "SELECT * FROM sieges WHERE salle_id = ? ORDER BY rangee, numero";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $horaire['salle_id']);
$stmt->execute();
$result = $stmt->get_result();
$tous_sieges = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sieges_selectionnes = isset($_POST['sieges']) ? $_POST['sieges'] : [];
    $nombre_places = count($sieges_selectionnes);

    if ($nombre_places === 0) {
        $message_erreur = "Veuillez sélectionner au moins un siège.";
    } else {
        // Créer la réservation
        $query = "INSERT INTO reservations (client_id, horaire_id, date_reservation, nombre_places, statut) VALUES (?, ?, NOW(), ?, 'en_attente')";
        $stmt = $connexion->prepare($query);
        $stmt->bind_param("iii", $_SESSION['client_id'], $horaire_id, $nombre_places);
        $stmt->execute();
        $reservation_id = $connexion->insert_id;

        // Insérer les sièges réservés
        $query = "INSERT INTO reservations_sieges (reservation_id, horaire_id, siege_id) VALUES (?, ?, ?)";
        $stmt = $connexion->prepare($query);
        foreach ($sieges_selectionnes as $siege_id) {
            $stmt->bind_param("iis", $reservation_id, $horaire_id, $siege_id);
            $stmt->execute();
        }

        // Rediriger vers la page de paiement
        header("Location: paiement.php?reservation_id=" . $reservation_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/reservation.css">
</head>
<body>
<div class="conteneur-principal">
    <header>
        <h1 class="titre-animation">Réservation pour <?php echo htmlspecialchars($horaire['titre_film']); ?></h1>
    </header>

    <div class="reservation-layout">
        <div class="info-film">
            <img src="<?php echo htmlspecialchars($horaire['affiche']); ?>" alt="<?php echo htmlspecialchars($horaire['titre_film']); ?>" class="affiche-film">
            <div class="details-film">
                <p>Date : <?php echo date('d/m/Y', strtotime($horaire['date_projection'])); ?></p>
                <p>Heure : <?php echo date('H:i', strtotime($horaire['heure_projection'])); ?></p>
                <p>Salle : <?php echo htmlspecialchars($horaire['nom_salle']); ?></p>
                <p>Durée : <?php echo $horaire['duree_minutes']; ?> minutes</p>
            </div>
        </div>

        <div class="formulaire-reservation">
            <form method="post">
                <div class="salle-cinema">
                    <div class="ecran">Écran</div>
                    <div class="sieges-container">
                        <?php
                        $rangee_courante = '';
                        foreach ($tous_sieges as $index => $siege) {
                            if ($siege['rangee'] !== $rangee_courante) {
                                if ($rangee_courante !== '') {
                                    echo '</div>';
                                }
                                $rangee_courante = $siege['rangee'];
                                echo '<div class="rangee">';
                            }
                            $est_reserve = in_array($siege['siege_id'], $sieges_reserves_ids);
                            $classe_siege = $est_reserve ? 'reserve' : $siege['section'];
                            $delay = $index + 1;
                            ?>
                            <div class="siege <?php echo $classe_siege; ?>" style="--delay: <?php echo $delay; ?>">
                                <input type="checkbox" name="sieges[]" value="<?php echo $siege['siege_id']; ?>" id="siege-<?php echo $siege['siege_id']; ?>" <?php echo $est_reserve ? 'disabled' : ''; ?>>
                                <label for="siege-<?php echo $siege['siege_id']; ?>" class="siege-icon"></label>
                                <span class="siege-info"><?php echo $siege['rangee'] . $siege['numero']; ?> - <?php echo number_format($siege['prix'] / 100, 2, '', ' '); ?> FCFA</span>
                            </div>
                            <?php
                            if ($siege['numero'] == 5 || $siege['numero'] == 10) {
                                echo '<div class="couloir"></div>';
                            }
                        }
                        if ($rangee_courante !== '') {
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <button type="submit" class="btn-reserver">Réserver</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

