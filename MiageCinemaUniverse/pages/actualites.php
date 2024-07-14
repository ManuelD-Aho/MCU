<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/fonctions.php';

// Récupérer les actualités avec tous les champs
$sql_actualites = "SELECT id, titre, contenu, image, date_publication FROM actualites ORDER BY date_publication DESC";
$resultat_actualites = mysqli_query($connexion, $sql_actualites);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletins d'information - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/actualites.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur">
    <header class="header-actualites">
        <h1 class="titre-animation">Bulletins d'information</h1>
    </header>

    <main class="contenu-principal">
        <section class="actualites">
            <?php while ($actu = mysqli_fetch_assoc($resultat_actualites)) : ?>
                <article class="carte-actualite" data-aos="fade-up">
                    <div class="carte-contenu">
                        <h2 class="titre-actualite"><?php echo htmlspecialchars($actu['titre']); ?></h2>
                        <?php if ($actu['image']) : ?>
                            <div class="conteneur-image">
                                <img src="<?php echo htmlspecialchars($actu['image']); ?>" alt="Image pour <?php echo htmlspecialchars($actu['titre']); ?>" class="image-actualite">
                            </div>
                        <?php endif; ?>
                        <p class="extrait"><?php echo htmlspecialchars(substr($actu['contenu'], 0, 200) . '...'); ?></p>
                        <p class="date-publication">Date de publication : <?php echo date('d/m/Y H:i', strtotime($actu['date_publication'])); ?></p>
                        <a href="actualites.php?id=<?php echo $actu['id']; ?>" class="lire-plus">Lire le rapport complet</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </section>
    </main>

    <section class="mission">
        <h2 class="titre-mission">Notre Mission Secrète</h2>
        <p class="texte-mission">Le MiageCinemaUniverse s'engage à offrir une expérience cinématographique unique, mêlant divertissement de pointe et technologie de surveillance avancée.</p>
    </section>
</div>
</body>
</html>

