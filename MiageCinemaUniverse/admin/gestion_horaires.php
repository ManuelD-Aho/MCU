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

// Suppression d'un horaire
if (isset($_POST['supprimer_horaire'])) {
    $horaire_id = intval($_POST['horaire_id']);
    $stmt = $connexion->prepare("DELETE FROM horaires WHERE horaire_id = ?");
    $stmt->bind_param("i", $horaire_id);
    if ($stmt->execute()) {
        $message = "Mission annulée avec succès.";
    } else {
        $message = "Erreur lors de l'annulation de la mission.";
    }
}

// Récupération des horaires
$sql = "SELECT h.*, f.titre_film, s.nom_salle 
        FROM horaires h 
        JOIN films f ON h.film_id = f.film_id 
        JOIN salles s ON h.salle_id = s.salle_id 
        ORDER BY h.date_projection, h.heure_projection";
$resultat = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Horaires - <?php echo SITE_NAME; ?></title>
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
        <h1>Planification des Missions</h1>
        <header>
            <h1>Tableau de Bord des Opérations Secrètes</h1>
        </header>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <a href="ajouter_horaire.php" class="bouton-ajouter">Ajouter une nouvelle Mission</a>

        <table>
            <thead>
            <tr>
                <th>Mission (Film)</th>
                <th>Lieu (Salle)</th>
                <th>Date de lancement</th>
                <th>Heure de déploiement</th>
                <th>Jour de la semaine</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($horaire = $resultat->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($horaire['titre_film']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['nom_salle']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['date_projection']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['heure_projection']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['jour_semaine']); ?></td>
                    <td>
                        <a href="modifier_horaire.php?id=<?php echo $horaire['horaire_id']; ?>" class="bouton-modifier">Modifier</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="horaire_id" value="<?php echo $horaire['horaire_id']; ?>">
                            <button type="submit" name="supprimer_horaire" class="bouton-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette mission ?');">Annuler</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>

