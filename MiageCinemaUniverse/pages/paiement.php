<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/fonctions.php';
include_once __DIR__ . '/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    redirigerAvecMessage('connexion_inscription.php', 'Veuillez vous connecter pour effectuer un paiement.', 'warning');
}

// Récupérer l'ID de réservation depuis l'URL
$reservation_id = isset($_GET['reservation_id']) ? intval($_GET['reservation_id']) : 0;

if ($reservation_id === 0) {
    redirigerAvecMessage('films.php', 'Réservation invalide.', 'error');
}

// Récupérer les détails de la réservation
$query = "SELECT r.*, f.titre_film, f.affiche, h.date_projection, h.heure_projection
          FROM reservations r
          JOIN horaires h ON r.horaire_id = h.horaire_id
          JOIN films f ON h.film_id = f.film_id
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
$query = "SELECT rs.*, s.section, s.prix
          FROM reservations_sieges rs
          JOIN sieges s ON rs.siege_id = s.siege_id
          WHERE rs.reservation_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$sieges_reserves = $result->fetch_all(MYSQLI_ASSOC);

// Calculer le prix total
$prix_total = 0;
foreach ($sieges_reserves as $siege) {
    $prix_total += $siege['prix'];
}

// Mettre à jour le prix total dans la base de données
$query = "UPDATE reservations SET prix_total = ? WHERE reservation_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("di", $prix_total, $reservation_id);
$stmt->execute();

// Traitement du formulaire de paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier le token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        redirigerAvecMessage('films.php', 'Erreur de sécurité, veuillez réessayer.', 'error');
    }

    // Simuler le traitement du paiement
    $paiement_reussi = true; // Dans un vrai système, cette valeur dépendrait du résultat du traitement du paiement

    if ($paiement_reussi) {
        // Mettre à jour le statut de la réservation
        $query = "UPDATE reservations SET statut = 'payée' WHERE reservation_id = ?";
        $stmt = $connexion->prepare($query);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();

        // Rediriger vers la page de confirmation
        redirigerAvecMessage('confirmation.php?reservation_id=' . $reservation_id, 'Paiement réussi !', 'success');
    } else {
        $message_erreur = "Le paiement a échoué. Veuillez réessayer.";
    }
}

// Générer un nouveau token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - <?php echo htmlspecialchars($reservation['titre_film']); ?></title>
    <link rel="stylesheet" href="../assets/css/paiement.css">
</head>
<body>
<div class="conteneur">
    <header>
        <h1 class="titre-animation">Paiement pour <?php echo htmlspecialchars($reservation['titre_film']); ?></h1>
    </header>

    <main class="carte-paiement">
        <section class="details-reservation">
            <div class="affiche-film">
                <img src="<?php echo htmlspecialchars($reservation['affiche']); ?>" alt="Affiche du film">
            </div>
            <div class="info-reservation">
                <h2>Détails de la réservation</h2>
                <ul class="liste-details">
                    <li><strong>Date :</strong> <?php echo htmlspecialchars($reservation['date_projection']); ?></li>
                    <li><strong>Heure :</strong> <?php echo htmlspecialchars($reservation['heure_projection']); ?></li>
                    <li><strong>Nombre de places :</strong> <?php echo $reservation['nombre_places']; ?></li>
                    <?php if (!empty($sieges_reserves)): ?>
                        <li>
                            <strong>Sièges réservés :</strong>
                            <ul class="liste-sieges">
                                <?php foreach ($sieges_reserves as $siege): ?>
                                    <li class="siege">
                                        <?php
                                        $siege_id = isset($siege['siege_id']) ? htmlspecialchars($siege['siege_id']) : 'N/A';
                                        $section = isset($siege['section']) ? ucfirst(htmlspecialchars($siege['section'])) : 'N/A';
                                        $prix = isset($siege['prix']) ? htmlspecialchars($siege['prix']) : 'N/A';
                                        echo "{$siege_id} (  {$section} - {$prix} FCFA)";
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li>Aucun siège réservé trouvé.</li>
                    <?php endif; ?>
                </ul>
                <p class="prix-total"><strong>Prix total :</strong> <?php echo number_format($prix_total, 0, '', ' '); ?> FCFA</p>
            </div>
        </section>

        <section class="formulaire-paiement">
            <h2>Informations de paiement</h2>
            <form method="post" class="formulaire" id="formulaire-paiement">
                <input type="hidden" name="csrf_token" value="<?php echo genererTokenCSRF(); ?>">

                <div class="champ">
                    <label for="moyen_paiement">Choisissez votre moyen de paiement :</label>
                    <select id="moyen_paiement" name="moyen_paiement" required>
                        <option value="">Sélectionnez un moyen de paiement</option>
                        <option value="Orange Money">Orange Money</option>
                        <option value="Wave">Wave</option>
                        <option value="Mtn Money">MTN Money</option>
                        <option value="Moov Money">Moov Money</option>
                        <option value="Djamo">Djamo</option>
                        <option value="Carte Visa">Carte Visa</option>
                    </select>
                </div>

                <div id="champs-paiement">
                    <!-- Orange Money -->
                    <div class="champ orange-money" style="display: none;">
                        <label for="numero_orange">Numéro Orange Money</label>
                        <input type="tel" id="numero_orange" name="numero_orange" pattern="[0-9]{10}" placeholder="0x xx xx xx xx">
                    </div>

                    <!-- Wave -->
                    <div class="champ wave" style="display: none;">
                        <label for="numero_wave">Numéro Wave</label>
                        <input type="tel" id="numero_wave" name="numero_wave" pattern="[0-9]{10}" placeholder="0x xx xx xx xx">
                    </div>

                    <!-- MTN Money -->
                    <div class="champ mtn-money" style="display: none;">
                        <label for="numero_mtn">Numéro MTN Money</label>
                        <input type="tel" id="numero_mtn" name="numero_mtn" pattern="[0-9]{10}" placeholder="0x xx xx xx xx">
                    </div>

                    <!-- Moov Money -->
                    <div class="champ moov-money" style="display: none;">
                        <label for="numero_moov">Numéro Moov Money</label>
                        <input type="tel" id="numero_moov" name="numero_moov" pattern="[0-9]{10}" placeholder="0x xx xx xx xx">
                    </div>

                    <!-- Djamo -->
                    <div class="champ djamo" style="display: none;">
                        <label for="numero_compte_djamo">Numéro de compte Djamo</label>
                        <input type="text" id="numero_compte_djamo" name="numero_compte_djamo" pattern="[A-Za-z0-9]{10,}" placeholder="Entrez votre numéro de compte Djamo">
                    </div>

                    <!-- Carte Visa -->
                    <div class="champ carte-visa" style="display: none;">
                        <label for="numero_carte">Numéro de carte</label>
                        <input type="text" id="numero_carte" name="numero_carte" pattern="[0-9]{16}" inputmode="numeric" placeholder="xxxx xxxx xxxx xxxx">
                        <label for="date_expiration">Date d'expiration</label>
                        <input type="text" id="date_expiration" name="date_expiration" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" placeholder="MM/AA">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" pattern="[0-9]{3,4}" inputmode="numeric" placeholder="xxx">
                    </div>
                </div>

                <button type="submit" class="btn-payer">Payer <?php echo number_format($prix_total, 0, '', ' '); ?> FCFA</button>
            </form>
        </section>
    </main>
</div>

<script>
    document.getElementById('moyen_paiement').addEventListener('change', function() {
        var moyenPaiement = this.value;
        var champsContainer = document.getElementById('champs-paiement');
        var tousLesChamps = champsContainer.querySelectorAll('.champ');

        tousLesChamps.forEach(function(champ) {
            champ.style.display = 'none';
        });

        if (moyenPaiement) {
            var champSpecifique = champsContainer.querySelector('.' + moyenPaiement.toLowerCase().replace(' ', '-'));
            if (champSpecifique) {
                champSpecifique.style.display = 'block';
            }
        }
    });
</script>
</body>
</html>
