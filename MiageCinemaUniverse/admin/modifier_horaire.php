<?php
session_start();
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/fonctions.php';

// Vérifier la session admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: auth/connexion.php');
    exit;
}

$message = '';
$horaire = null;

// Récupération de l'horaire à modifier
if (isset($_GET['id'])) {
    $horaire_id = intval($_GET['id']);
    $sql = "SELECT h.*, f.titre_film, s.nom_salle FROM horaires h 
            JOIN films f ON h.film_id = f.film_id 
            JOIN salles s ON h.salle_id = s.salle_id 
            WHERE h.horaire_id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("i", $horaire_id);
    $stmt->execute();
    $resultat = $stmt->get_result();
    $horaire = $resultat->fetch_assoc();
}

// Modification d'horaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $horaire_id = intval($_POST['horaire_id']);
    $film_id = nettoyerEntree($_POST['film_id']);
    $salle_id = nettoyerEntree($_POST['salle_id']);
    $date_projection = nettoyerEntree($_POST['date_projection']);
    $heure_projection = nettoyerEntree($_POST['heure_projection']);

    $date_heure = new DateTime($date_projection . ' ' . $heure_projection);
    $jour_semaine = $date_heure->format('l');

    // Vérifier si le nouvel horaire n'entre pas en conflit avec un existant
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM horaires WHERE film_id = ? AND salle_id = ? AND date_projection = ? AND heure_projection = ? AND horaire_id != ?");
    $stmt->bind_param('iissi', $film_id, $salle_id, $date_projection, $heure_projection, $horaire_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Cette mission est déjà planifiée à cette date et heure.";
    } else {
        $stmt = $connexion->prepare("UPDATE horaires SET film_id = ?, salle_id = ?, date_projection = ?, heure_projection = ?, jour_semaine = ? WHERE horaire_id = ?");
        $stmt->bind_param('iisssi', $film_id, $salle_id, $date_projection, $heure_projection, $jour_semaine, $horaire_id);

        if ($stmt->execute()) {
            $message = "Mission mise à jour avec succès.";
            // Mise à jour des données affichées
            $horaire['film_id'] = $film_id;
            $horaire['salle_id'] = $salle_id;
            $horaire['date_projection'] = $date_projection;
            $horaire['heure_projection'] = $heure_projection;
            $horaire['jour_semaine'] = $jour_semaine;
        } else {
            $message = "Erreur lors de la mise à jour de la mission : " . $stmt->error;
        }
    }
}

// Récupération des films
$sql_films = "SELECT film_id, titre_film FROM films ORDER BY titre_film";
$resultat_films = $connexion->query($sql_films);

// Récupération des salles
$sql_salles = "SELECT salle_id, nom_salle FROM salles ORDER BY nom_salle";
$resultat_salles = $connexion->query($sql_salles);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Mission - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale">
        <h2>Quartier Général</h2>
        <nav>
            <ul>
                <li><a href="admin.php">Tableau de bord</a></li>
                <li><a href="gestion_films.php">Dossiers Secrets</a></li>
                <li><a href="gestion_horaires.php" class="actif">Planification des Missions</a></li>
                <li><a href="gestion_clients.php">Registre des Agents</a></li>
                <li><a href="gestion_actualites.php">Actualités du MCU</a></li>
                <li><a href="auth/deconnexion.php">Évacuation d'Urgence</a></li>
            </ul>
        </nav>
    </aside>

    <main class="contenu-principal">
        <h1>Modifier la Mission</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($horaire): ?>
            <form action="" method="post">
                <input type="hidden" name="horaire_id" value="<?php echo $horaire['horaire_id']; ?>">

                <div class="champ-formulaire">
                    <label for="film_id">Mission (Film) :</label>
                    <select name="film_id" id="film_id" required>
                        <?php while ($film = $resultat_films->fetch_assoc()): ?>
                            <option value="<?php echo $film['film_id']; ?>" <?php echo ($film['film_id'] == $horaire['film_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($film['titre_film']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="champ-formulaire">
                    <label for="salle_id">Lieu (Salle) :</label>
                    <select name="salle_id" id="salle_id" required>
                        <?php while ($salle = $resultat_salles->fetch_assoc()): ?>
                            <option value="<?php echo $salle['salle_id']; ?>" <?php echo ($salle['salle_id'] == $horaire['salle_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($salle['nom_salle']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="champ-formulaire">
                    <label for="date_projection">Date de lancement :</label>
                    <input type="date" id="date_projection" name="date_projection" value="<?php echo $horaire['date_projection']; ?>" required>
                </div>

                <div class="champ-formulaire">
                    <label for="heure_projection">Heure de déploiement :</label>
                    <input type="time" id="heure_projection" name="heure_projection" value="<?php echo $horaire['heure_projection']; ?>" required>
                </div>

                <button type="submit" class="bouton-action">Mettre à jour la Mission</button>
            </form>
        <?php else: ?>
            <p>Mission non trouvée.</p>
        <?php endif; ?>

        <a href="gestion_horaires.php" class="bouton-retour">Retour à la Planification</a>
    </main>
</div>
</body>
</html>

