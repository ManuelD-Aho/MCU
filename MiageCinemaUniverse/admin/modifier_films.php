<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/fonctions.php';

// Vérification de la connexion de l'administrateur
if (!estAdmin()) {
    redirigerAvecMessage('auth/connexion.php', 'Veuillez vous connecter en tant qu\'administrateur.', 'error');
}

$message = '';
$erreur = '';
$dossierTelechargement = '../assets/images/';
$film = [];

// Augmentation des limites pour les fichiers volumineux
ini_set('upload_max_filesize', '350M');
ini_set('post_max_size', '350M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

// Récupération du film à modifier
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $film_id = intval($_GET['id']);
    $film = obtenirFilmParId($connexion, $film_id);
    if (!$film) {
        redirigerAvecMessage('gestion_films.php', 'Film non trouvé.', 'error');
    }
} else {
    redirigerAvecMessage('gestion_films.php', 'ID de film non valide.', 'error');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultat = modifier_film($connexion, $dossierTelechargement);
    if ($resultat === true) {
        $message = "Film modifié avec succès.";
        $film = obtenirFilmParId($connexion, $film_id); // Recharger les données du film
    } else {
        $erreur = $resultat;
    }
}

function modifier_film($connexion, $dossierTelechargement) {
    $film_id = intval($_POST['film_id']);
    $titre_film = validerDonnees($_POST['titre_film']);
    $duree_minutes = (int)validerDonnees($_POST['duree_minutes']);
    $description = validerDonnees($_POST['description']);
    $date_sortie = validerDonnees($_POST['date_sortie']);
    $genre = validerDonnees($_POST['genre']);
    $directeur = validerDonnees($_POST['directeur']);
    $est_nouveau = isset($_POST['est_nouveau']) ? 1 : 0;
    $public_cible = validerDonnees($_POST['public_cible']);
    $synopsis = validerDonnees($_POST['synopsis']);

    $image_film = gererUploadImage('image_film', $dossierTelechargement) ?: $_POST['ancien_image_film'];
    $bande_annonce = gererUploadVideo('bande_annonce', $dossierTelechargement) ?: $_POST['ancien_bande_annonce'];
    $affiche = gererUploadImage('affiche', $dossierTelechargement) ?: $_POST['ancien_affiche'];

    $sql = "UPDATE films SET titre_film = ?, duree_minutes = ?, image_film = ?, description = ?, date_sortie = ?, bande_annonce = ?, affiche = ?, genre = ?, directeur = ?, est_nouveau = ?, public_cible = ?, synopsis = ? WHERE film_id = ?";

    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("sisssssssissi",
        $titre_film, $duree_minutes, $image_film, $description, $date_sortie,
        $bande_annonce, $affiche, $genre, $directeur, $est_nouveau,
        $public_cible, $synopsis, $film_id
    );

    if ($stmt->execute()) {
        loggerAction("Modification du film ID $film_id : $titre_film");
        return true;
    } else {
        return "Erreur lors de la modification du film : " . $stmt->error;
    }
}

function obtenirFilmParId($connexion, $film_id) {
    $sql = "SELECT * FROM films WHERE film_id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("i", $film_id);
    $stmt->execute();
    $resultat = $stmt->get_result();
    return $resultat->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Film - Administration MCU</title>
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale">
        <h2>Administration</h2>
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
        <h1>Modifier un Film</h1>
        <?php
        if ($message) echo "<div class='alerte alerte-succes'>" . htmlspecialchars($message) . "</div>";
        if ($erreur) echo "<div class='alerte alerte-erreur'>" . htmlspecialchars($erreur) . "</div>";
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="367001600" /> <!-- 350 Mo en octets -->
            <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($film['film_id']); ?>">

            <div class="champ-formulaire">
                <label for="titre_film">Titre du film :</label>
                <input type="text" id="titre_film" name="titre_film" value="<?php echo htmlspecialchars($film['titre_film']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="duree_minutes">Durée (minutes) :</label>
                <input type="number" id="duree_minutes" name="duree_minutes" value="<?php echo htmlspecialchars($film['duree_minutes']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="image_film">Image du film :</label>
                <input type="file" id="image_film" name="image_film" accept="image/*">
                <input type="hidden" name="ancien_image_film" value="<?php echo htmlspecialchars($film['image_film']); ?>">
                <?php if ($film['image_film']): ?>
                    <img src="<?php echo htmlspecialchars($dossierTelechargement . $film['image_film']); ?>" alt="Image actuelle du film" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <div class="champ-formulaire">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($film['description']); ?></textarea>
            </div>
            <div class="champ-formulaire">
                <label for="date_sortie">Date de sortie :</label>
                <input type="date" id="date_sortie" name="date_sortie" value="<?php echo htmlspecialchars($film['date_sortie']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="bande_annonce">Bande-annonce :</label>
                <input type="file" id="bande_annonce" name="bande_annonce" accept="video/*">
                <input type="hidden" name="ancien_bande_annonce" value="<?php echo htmlspecialchars($film['bande_annonce']); ?>">
                <?php if ($film['bande_annonce']): ?>
                    <video width="320" height="240" controls>
                        <source src="<?php echo htmlspecialchars($dossierTelechargement . $film['bande_annonce']); ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                <?php endif; ?>
            </div>
            <div class="champ-formulaire">
                <label for="affiche">Affiche :</label>
                <input type="file" id="affiche" name="affiche" accept="image/*">
                <input type="hidden" name="ancien_affiche" value="<?php echo htmlspecialchars($film['affiche']); ?>">
                <?php if ($film['affiche']): ?>
                    <img src="<?php echo htmlspecialchars($dossierTelechargement . $film['affiche']); ?>" alt="Affiche actuelle du film" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <div class="champ-formulaire">
                <label for="genre">Genre :</label>
                <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($film['genre']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="directeur">Réalisateur :</label>
                <input type="text" id="directeur" name="directeur" value="<?php echo htmlspecialchars($film['directeur']); ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="est_nouveau">Nouveau film :</label>
                <input type="checkbox" id="est_nouveau" name="est_nouveau" <?php echo $film['est_nouveau'] ? 'checked' : ''; ?>>
            </div>
            <div class="champ-formulaire">
                <label for="public_cible">Public cible :</label>
                <input type="text" id="public_cible" name="public_cible" value="<?php echo htmlspecialchars($film['public_cible']); ?>">
            </div>
            <div class="champ-formulaire">
                <label for="synopsis">Synopsis :</label>
                <textarea id="synopsis" name="synopsis" required><?php echo htmlspecialchars($film['synopsis']); ?></textarea>
            </div>
            <button type="submit" class="bouton-action">Modifier le film</button>
        </form>

        <a href="gestion_films.php" class="bouton-retour">Retour à la liste des films</a>
    </main>
</div>
</body>
</html>

