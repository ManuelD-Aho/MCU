<?php
session_start();
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/fonctions.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    redirigerAvecMessage('connexion_inscription.php', 'Veuillez vous connecter pour accéder à votre confirmation.', 'warning');
}

// Récupérer l'ID de réservation depuis l'URL
$reservation_id = isset($_GET['reservation_id']) ? intval($_GET['reservation_id']) : 0;

if ($reservation_id === 0) {
    redirigerAvecMessage('films.php', 'Réservation invalide.', 'error');
}

// Récupérer les détails de la réservation
$query = "SELECT r.*, f.titre_film, f.affiche, h.date_projection, h.heure_projection, s.nom_salle
          FROM reservations r
          JOIN horaires h ON r.horaire_id = h.horaire_id
          JOIN films f ON h.film_id = f.film_id
          JOIN salles s ON h.salle_id = s.salle_id
          WHERE r.reservation_id = ? AND r.client_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("ii", $reservation_id, $_SESSION['client_id']);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();

if (!$reservation) {
    redirigerAvecMessage('films.php', 'Réservation non trouvée.', 'error');
}

// Récupérer les sièges réservés
$query = "SELECT rs.*, s.rangee, s.numero, s.section
          FROM reservations_sieges rs
          JOIN sieges s ON rs.siege_id = s.siege_id
          WHERE rs.reservation_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$sieges_reserves = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Mission - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/confirmation.css">
</head>
<body>
<div class="container">
    <header>
        <h1 class="title-animation">Confirmation de Mission</h1>
        <a href="accueil.php" class="back-link">Retour au Quartier Général</a>
    </header>

    <main>
        <div class="confirmation-card">
            <div class="mission-info">
                <img src="<?php echo htmlspecialchars($reservation['affiche']); ?>" alt="Affiche du film" class="mission-poster">
                <h2>Détails de votre mission secrète</h2>
                <ul class="mission-details">
                    <li><strong>Nom de code:</strong> <?php echo htmlspecialchars($reservation['titre_film']); ?></li>
                    <li><strong>Date d'exécution:</strong> <?php echo date('d/m/Y', strtotime($reservation['date_projection'])); ?></li>
                    <li><strong>Heure de déploiement:</strong> <?php echo date('H:i', strtotime($reservation['heure_projection'])); ?></li>
                    <li><strong>Lieu de rendez-vous:</strong> <?php echo htmlspecialchars($reservation['nom_salle']); ?></li>
                    <li><strong>Nombre d'agents:</strong> <?php echo $reservation['nombre_places']; ?></li>
                </ul>
                <div class="positions">
                    <h3>Positions assignées:</h3>
                    <ul class="seat-list">
                        <?php foreach ($sieges_reserves as $siege): ?>
                            <li class="seat"><?php echo htmlspecialchars($siege['rangee'] . $siege['numero'] . ' (' . $siege['section'] . ')'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <p class="budget"><strong>Budget alloué:</strong> <span><?php echo number_format($reservation['prix_total'], 2); ?> FCFA</span></p>
                <p class="status"><strong>Statut de la mission:</strong> <span><?php echo htmlspecialchars($reservation['statut']); ?></span></p>
            </div>

            <div class="qr-code">
                <h3>Code d'accès sécurisé</h3>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode('Mission ID: ' . $reservation_id); ?>" alt="QR Code de la mission">
                <p>Scannez ce code pour accéder à votre mission</p>
            </div>
        </div>

        <div class="actions">
            <a href="films.php" class="btn btn-primary">Autres missions disponibles</a>
            <button onclick="window.print();" class="btn btn-secondary">Imprimer le dossier de mission</button>
        </div>
    </main>

    <footer>
        <p>© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> - Tous droits réservés</p>
    </footer>
</div>
</body>
</html>

