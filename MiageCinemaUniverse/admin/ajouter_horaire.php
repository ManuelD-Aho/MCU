<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/fonctions.php';

// Vérifier la session admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: auth/connexion.php');
    exit;
}

$message = '';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $film_id = validerDonnees($_POST['film_id']);
    $salle_id = validerDonnees($_POST['salle_id']);
    $date_projection = validerDonnees($_POST['date_projection']);
    $heure_projection = validerDonnees($_POST['heure_projection']);
    $jour_semaine = date('l', strtotime($date_projection));

    // Vérifier si l'horaire n'existe pas déjà
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM horaires WHERE film_id = ? AND salle_id = ? AND date_projection = ? AND heure_projection = ?");
    $stmt->bind_param('iiss', $film_id, $salle_id, $date_projection, $heure_projection);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Cette mission est déjà planifiée.";
    } else {
        $stmt = $connexion->prepare("INSERT INTO horaires (film_id, salle_id, date_projection, heure_projection, jour_semaine) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iisss', $film_id, $salle_id, $date_projection, $heure_projection, $jour_semaine);

        if ($stmt->execute()) {
            $message = "Nouvelle mission ajoutée avec succès.";
        } else {
            $message = "Erreur lors de l'ajout de la mission : " . $stmt->error;
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
    <title>Ajouter une Mission - <?php echo SITE_NAME; ?></title>
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
        <h1>Ajouter une Nouvelle Mission</h1>
        <header>
            <h1>Tableau de Bord des Opérations Secrètes</h1>
        </header>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="champ-formulaire">
                <label for="film_id">Mission (Film) :</label>
                <select name="film_id" id="film_id" required>
                    <?php while ($film = $resultat_films->fetch_assoc()): ?>
                        <option value="<?php echo $film['film_id']; ?>">
                            <?php echo htmlspecialchars($film['titre_film']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="salle_id">Lieu (Salle) :</label>
                <select name="salle_id" id="salle_id" required>
                    <?php while ($salle = $resultat_salles->fetch_assoc()): ?>
                        <option value="<?php echo $salle['salle_id']; ?>">
                            <?php echo htmlspecialchars($salle['nom_salle']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="date_projection">Date de lancement :</label>
                <input type="date" id="date_projection" name="date_projection" required>
            </div>

            <div class="champ-formulaire">
                <label for="heure_projection">Heure de déploiement :</label>
                <input type="time" id="heure_projection" name="heure_projection" required>
            </div>

            <button type="submit" class="bouton-action">Ajouter la Mission</button>
        </form>

        <a href="gestion_horaires.php" class="bouton-retour">Retour à la Planification</a>
    </main>
</div>
</body>
</html>

