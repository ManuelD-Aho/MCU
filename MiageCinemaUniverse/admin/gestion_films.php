<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/fonctions.php';

// Vérification de la connexion de l'administrateur
if (!estAdmin()) {
    header('Location: auth/connexion.php');
    exit();
}

// Traitement de la suppression d'un film
if (isset($_POST['action']) && $_POST['action'] == 'supprimer') {
    $film_id = intval($_POST['film_id']);
    $sql_supprimer = "DELETE FROM films WHERE film_id = ?";
    $stmt = mysqli_prepare($connexion, $sql_supprimer);
    mysqli_stmt_bind_param($stmt, "i", $film_id);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Le dossier de mission a été classé avec succès.";
    } else {
        $erreur = "Erreur lors du classement du dossier : " . mysqli_error($connexion);
    }
}

// Récupération de la liste des films
$sql = "SELECT * FROM films ORDER BY date_sortie DESC";
$resultat = mysqli_query($connexion, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Dossiers de Mission - Secrétariat MCU</title>
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale">
        <h2>Quartier Général</h2>
        <nav>
            <ul>
                <li><a href="admin.php">Tableau de bord</a></li>
                <li><a href="gestion_films.php" class="actif">Dossiers Secrets</a></li>
                <li><a href="gestion_horaires.php">Planification des Missions</a></li>
                <li><a href="gestion_clients.php">Registre des Agents</a></li>
                <li><a href="gestion_actualites.php" >Actualités du MCU</a></li>
                <li><a href="auth/deconnexion.php">Évacuation d'Urgence</a></li>
            </ul>
        </nav>
    </aside>
    <main class="contenu-principal">
        <h1>Gestion des Dossiers de Mission (Films)</h1>
        <header>
            <h1>Tableau de Bord des Opérations Secrètes</h1>
        </header>
        <?php if (isset($message)) : ?>
            <div class="alerte alerte-succes"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (isset($erreur)) : ?>
            <div class="alerte alerte-erreur"><?php echo $erreur; ?></div>
        <?php endif; ?>

        <a href="ajouter_film.php" class="bouton-action">Ajouter une nouvelle mission</a>

        <table class="table-gestion">
            <thead>
            <tr>
                <th>Nom de code (Titre)</th>
                <th>Photo de couverture</th>
                <th>Date de lancement</th>
                <th>Durée (minutes)</th>
                <th>Type de mission (Genre)</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($film = mysqli_fetch_assoc($resultat)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($film['titre_film']); ?></td>
                    <td>
                        <?php if ($film['affiche']) : ?>
                            <img src="<?php echo htmlspecialchars($film['affiche']); ?>" alt="Couverture de <?php echo htmlspecialchars($film['titre_film']); ?>" style="width: 50px;">
                        <?php else : ?>
                            Aucune couverture
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($film['date_sortie'])); ?></td>
                    <td><?php echo $film['duree_minutes']; ?></td>
                    <td><?php echo htmlspecialchars($film['genre']); ?></td>
                    <td>
                        <a href="modifier_films.php?id=<?php echo $film['film_id']; ?>" class="bouton-modifier">Modifier</a>
                        <form action="" method="post" style="display: inline;">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="film_id" value="<?php echo $film['film_id']; ?>">
                            <button type="submit" class="bouton-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir classer cette mission ?');">Supprimer</button>
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

