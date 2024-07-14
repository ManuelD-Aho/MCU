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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = mysqli_real_escape_string($connexion, $_POST['titre']);
    $contenu = mysqli_real_escape_string($connexion, $_POST['contenu']);
    $date_publication = mysqli_real_escape_string($connexion, $_POST['date_publication']);

    // Traitement de l'image
    $image = $_POST['image_actuelle']; // Par défaut, on garde l'image actuelle
    if ($_FILES['nouvelle_image']['size'] > 0) {
        $resultat_upload = gererUploadImage('nouvelle_image', '../assets/images/');
        if ($resultat_upload) {
            $image = $resultat_upload;
        } else {
            $_SESSION['erreur'] = "Erreur lors du téléchargement de l'image.";
        }
    }

    $sql = "UPDATE actualites SET titre = ?, contenu = ?, image = ?, date_publication = ? WHERE id = ?";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $titre, $contenu, $image, $date_publication, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "L'actualité a été mise à jour avec succès.";
        header('Location: gestion_actualites.php');
        exit();
    } else {
        $_SESSION['erreur'] = "Erreur lors de la mise à jour de l'actualité : " . mysqli_error($connexion);
    }
} else {
    $sql = "SELECT * FROM actualites WHERE id = ?";
    $stmt = mysqli_prepare($connexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
    $actualite = mysqli_fetch_assoc($resultat);

    if (!$actualite) {
        header('Location: gestion_actualites.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Actualité - Secrétariat MCU</title>
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale">
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
            <h1>Modifier une Actualité</h1>
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

        <form action="" method="post" enctype="multipart/form-data" class="formulaire-admin">
            <div class="champ-formulaire">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($actualite['titre']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="contenu">Contenu :</label>
                <textarea id="contenu" name="contenu" required><?php echo htmlspecialchars($actualite['contenu']); ?></textarea>
            </div>
            <div class="champ-formulaire">
                <label for="image_actuelle">Image actuelle :</label>
                <?php if ($actualite['image']) : ?>
                    <img src="<?php echo htmlspecialchars($actualite['image']); ?>" alt="Image de l'actualité" class="preview-image">
                    <input type="hidden" name="image_actuelle" value="<?php echo htmlspecialchars($actualite['image']); ?>">
                <?php else : ?>
                    <p>Aucune image</p>
                <?php endif; ?>
            </div>
            <div class="champ-formulaire">
                <label for="nouvelle_image">Nouvelle image (optionnel) :</label>
                <input type="file" id="nouvelle_image" name="nouvelle_image" accept="image/*">
            </div>
            <div class="champ-formulaire">
                <label for="date_publication">Date de publication :</label>
                <input type="datetime-local" id="date_publication" name="date_publication" value="<?php echo date('Y-m-d\TH:i', strtotime($actualite['date_publication'])); ?>" required>
            </div>
            <div class="actions-formulaire">
                <button type="submit" class="bouton-soumettre">Mettre à jour l'actualité</button>
                <a href="gestion_actualites.php" class="bouton-annuler">Annuler</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>

