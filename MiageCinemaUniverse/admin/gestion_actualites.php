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

// Traitement de la suppression d'une actualité
if (isset($_POST['action']) && $_POST['action'] == 'supprimer') {
    $id = intval($_POST['id']);
    $sql_supprimer = "DELETE FROM actualites WHERE id = ?";
    $stmt = mysqli_prepare($connexion, $sql_supprimer);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "L'actualité a été supprimée avec succès.";
    } else {
        $_SESSION['erreur'] = "Erreur lors de la suppression de l'actualité : " . mysqli_error($connexion);
    }
    header('Location: gestion_actualites.php');
    exit();
}

// Récupération de la liste des actualités
$sql = "SELECT * FROM actualites ORDER BY date_publication DESC";
$resultat = mysqli_query($connexion, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Actualités - Secrétariat MCU</title>
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
                <li><a href="gestion_horaires.php">Planification des Missions</a></li>
                <li><a href="gestion_clients.php">Registre des Agents</a></li>
                <li><a href="gestion_actualites.php" class="actif">Actualités du MCU</a></li>
                <li><a href="auth/deconnexion.php">Évacuation d'Urgence</a></li>
            </ul>
        </nav>
    </aside>
    <main class="contenu-principal">
        <header>
            <h1>Gestion des Actualités du MCU</h1>
        </header>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alerte alerte-succes'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['erreur'])) {
            echo "<div class='alerte alerte-erreur'>" . $_SESSION['erreur'] . "</div>";
            unset($_SESSION['erreur']);
        }
        ?>

        <table class="table-gestion">
            <thead>
            <tr>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Image</th>
                <th>Date de publication</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($actualite = mysqli_fetch_assoc($resultat)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($actualite['titre']); ?></td>
                    <td><?php echo substr(htmlspecialchars($actualite['contenu']), 0, 100) . '...'; ?></td>
                    <td>
                        <?php if ($actualite['image']) : ?>
                            <img src="<?php echo htmlspecialchars($actualite['image']); ?>" alt="Image de l'actualité" class="miniature-image">
                        <?php else : ?>
                            Aucune image
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($actualite['date_publication'])); ?></td>
                    <td>
                        <a href="modifier_actualites.php?id=<?php echo $actualite['id']; ?>" class="bouton-modifier">Modifier</a>
                        <form action="" method="post" class="formulaire-supprimer">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="id" value="<?php echo $actualite['id']; ?>">
                            <button type="submit" class="bouton-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>
<script src="../assets/js/script.js"></script>
</body>
</html>

