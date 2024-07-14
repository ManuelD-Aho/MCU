<?php
session_start();
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/fonctions.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: connexion_inscription.php');
    exit();
}

$client_id = $_SESSION['client_id'];

// Récupérer les informations du client
$query = "SELECT * FROM clients WHERE client_id = ?";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// Récupérer les réservations du client
$query = "SELECT r.*, f.titre_film, h.date_projection, h.heure_projection 
          FROM reservations r
          JOIN horaires h ON r.horaire_id = h.horaire_id
          JOIN films f ON h.film_id = f.film_id
          WHERE r.client_id = ?
          ORDER BY r.date_reservation DESC";
$stmt = $connexion->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Traitement de la modification du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_nom = nettoyerEntree($_POST['nouveau_nom']);
    $nouveau_prenom = nettoyerEntree($_POST['nouveau_prenom']);
    $nouvelle_email = nettoyerEntree($_POST['nouvelle_email']);

    $query = "UPDATE clients SET nom = ?, prenom = ?, email = ? WHERE client_id = ?";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("sssi", $nouveau_nom, $nouveau_prenom, $nouvelle_email, $client_id);

    if ($stmt->execute()) {
        $message_succes = "Profil mis à jour avec succès !";
        // Mettre à jour les données du client dans la session
        $_SESSION['nom'] = $nouveau_nom;
        $_SESSION['prenom'] = $nouveau_prenom;
        $_SESSION['email'] = $nouvelle_email;
    } else {
        $message_erreur = "Erreur lors de la mise à jour du profil.";
    }
}
include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Agent - MiageCinemaUniverse</title>
    <link rel="stylesheet" href="../assets/css/clients.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<main class="conteneur-principal">
    <h1 class="titre-page">Profil de l'Agent <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></h1>

    <?php if (isset($message_succes)): ?>
        <div class="message-succes"><?php echo $message_succes; ?></div>
    <?php endif; ?>

    <?php if (isset($message_erreur)): ?>
        <div class="message-erreur"><?php echo $message_erreur; ?></div>
    <?php endif; ?>

    <section class="section-profil">
        <h2>Informations de l'Agent</h2>
        <form action="" method="post" class="formulaire-modification">
            <div class="champ-formulaire">
                <label for="nouveau_nom">Nom :</label>
                <input type="text" id="nouveau_nom" name="nouveau_nom" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="nouveau_prenom">Prénom :</label>
                <input type="text" id="nouveau_prenom" name="nouveau_prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="nouvelle_email">Email :</label>
                <input type="email" id="nouvelle_email" name="nouvelle_email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label>Date de naissance :</label>
                <span><?php echo htmlspecialchars($client['date_naissance']); ?></span>
            </div>
            <button type="submit" class="btn-modification">Modifier le profil</button>
        </form>
    </section>

    <section class="section-reservations">
        <h2>Missions récentes (Réservations)</h2>
        <?php if (empty($reservations)): ?>
            <p>Aucune mission en cours, Agent. Restez en alerte pour de nouvelles opportunités.</p>
        <?php else: ?>
            <ul class="liste-reservations">
                <?php foreach ($reservations as $reservation): ?>
                    <li class="reservation-item">
                        <h3><?php echo htmlspecialchars($reservation['titre_film']); ?></h3>
                        <p>Date de la mission : <?php echo dateToFrench($reservation['date_projection'], 'l j F Y'); ?></p>
                        <p>Heure de briefing : <?php echo htmlspecialchars($reservation['heure_projection']); ?></p>
                        <p>Nombre d'agents : <?php echo htmlspecialchars($reservation['nombre_places']); ?></p>
                        <p>Statut : <span class="statut-<?php echo strtolower($reservation['statut']); ?>"><?php echo htmlspecialchars($reservation['statut']); ?></span></p>
                        <p>Prix de la mission : <?php echo htmlspecialchars($reservation['prix_total']); ?> FCFA</p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>
</body>
</html>

