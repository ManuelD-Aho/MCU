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

// Augmentation des limites pour les fichiers volumineux
ini_set('upload_max_filesize', '350M');
ini_set('post_max_size', '350M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultat = ajouter_film($connexion, $dossierTelechargement);
    if ($resultat === true) {
        $message = "Nouveau film ajouté avec succès.";
    } else {
        $erreur = $resultat;
    }
}

function ajouter_film($connexion, $dossierTelechargement) {
    $titre_film = validerDonnees($_POST['titre_film']);
    $duree_minutes = (int)validerDonnees($_POST['duree_minutes']);
    $description = validerDonnees($_POST['description']);
    $date_sortie = validerDonnees($_POST['date_sortie']);
    $genre = validerDonnees($_POST['genre']);
    $directeur = validerDonnees($_POST['directeur']);
    $est_nouveau = isset($_POST['est_nouveau']) ? 1 : 0;
    $public_cible = validerDonnees($_POST['public_cible']);
    $synopsis = validerDonnees($_POST['synopsis']);

    $image_film = gererUploadImage('image_film', $dossierTelechargement);
    $bande_annonce = gererUploadVideo('bande_annonce', $dossierTelechargement);
    $affiche = gererUploadImage('affiche', $dossierTelechargement);

    if ($image_film === null || $bande_annonce === null || $affiche === null) {
        return "Erreur lors de l'upload des fichiers. Vérifiez les formats et les tailles.";
    }

    $sql = "INSERT INTO films (titre_film, duree_minutes, image_film, description, date_sortie, bande_annonce, affiche, genre, directeur, est_nouveau, public_cible, synopsis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("sisssssssiss",
        $titre_film, $duree_minutes, $image_film, $description, $date_sortie,
        $bande_annonce, $affiche, $genre, $directeur, $est_nouveau,
        $public_cible, $synopsis
    );

    if ($stmt->execute()) {
        loggerAction("Ajout d'un nouveau film : $titre_film");
        return true;
    } else {
        return "Erreur lors de l'ajout du film : " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Nouveau Film - Administration MCU</title>
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale">
        <h2>Administration</h2>
        <nav>
            <ul>
                <li><a href="admin.php">Tableau de bord</a></li>
                <li><a href="gestion_films.php"class="actif">Dossiers Secrets</a></li>
                <li><a href="gestion_horaires.php">Planification des Missions</a></li>
                <li><a href="gestion_clients.php">Registre des Agents</a></li>
                <li><a href="gestion_actualites.php" >Actualités du MCU</a></li>
                <li><a href="auth/deconnexion.php">Évacuation d'Urgence</a></li>
            </ul>
        </nav>
    </aside>
    <main class="contenu-principal">
        <h1>Ajouter un Nouveau Film</h1>
        <?php
        if ($message) echo "<div class='alerte alerte-succes'>$message</div>";
        if ($erreur) echo "<div class='alerte alerte-erreur'>$erreur</div>";
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="367001600" />
            <div class="champ-formulaire">
                <label for="titre_film">Titre du film :</label>
                <input type="text" id="titre_film" name="titre_film" required>
            </div>
            <div class="champ-formulaire">
                <label for="duree_minutes">Durée (minutes) :</label>
                <input type="number" id="duree_minutes" name="duree_minutes" required>
            </div>
            <div class="champ-formulaire">
                <label for="image_film">Image du film :</label>
                <input type="file" id="image_film" name="image_film" accept="image/*" required>
            </div>
            <div class="champ-formulaire">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="champ-formulaire">
                <label for="date_sortie">Date de sortie :</label>
                <input type="date" id="date_sortie" name="date_sortie" required>
            </div>
            <div class="champ-formulaire">
                <label for="bande_annonce">Bande-annonce :</label>
                <input type="file" id="bande_annonce" name="bande_annonce" accept="video/*" required>
            </div>
            <div class="champ-formulaire">
                <label for="affiche">Affiche :</label>
                <input type="file" id="affiche" name="affiche" accept="image/*" required>
            </div>
            <div class="champ-formulaire">
                <label for="genre">Genre :</label>
                <input type="text" id="genre" name="genre" required>
            </div>
            <div class="champ-formulaire">
                <label for="directeur">Réalisateur :</label>
                <input type="text" id="directeur" name="directeur" required>
            </div>
            <div class="champ-formulaire">
                <label for="est_nouveau">Nouveau film :</label>
                <input type="checkbox" id="est_nouveau" name="est_nouveau">
            </div>
            <div class="champ-formulaire">
                <label for="public_cible">Public cible :</label>
                <input type="text" id="public_cible" name="public_cible">
            </div>
            <div class="champ-formulaire">
                <label for="synopsis">Synopsis :</label>
                <textarea id="synopsis" name="synopsis" required></textarea>
            </div>
            <button type="submit" class="bouton-action">Ajouter le film</button>
        </form>

        <a href="gestion_films.php" class="bouton-retour">Retour à la liste des films</a>
    </main>
</div>
</body>
</html>

