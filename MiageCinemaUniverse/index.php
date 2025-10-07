<?php
session_start();
require_once __DIR__ . '/configuration/config.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/fonctions.php';
include_once __DIR__ . '/pages/header.php';

// Récupérer les 3 derniers films à l'affiche
$sql_films = "SELECT * FROM films WHERE date_sortie <= CURDATE() ORDER BY date_sortie DESC LIMIT 3";
$resultat_films = $connexion->query($sql_films);

// Récupérer les 2 dernières actualités
$sql_actualites = "SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 2";
$resultat_actualites = $connexion->query($sql_actualites);

// Logger l'action de visite de l'index
if (function_exists('loggerAction')) {
    loggerAction("Visite de l'index MCU");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="assets/css/accueil.css">
</head>
<body>
<div class="page-wrapper">
    <main>
        <h1>Bienvenue sur le Quartier Général du MCU</h1>
        <nav>
            <ul>
                <li><a href="pages/accueil.php">Accueil détaillé</a></li>
                <li><a href="pages/films.php">Dossiers Films</a></li>
                <li><a href="pages/actualites.php">Briefing (Actualités)</a></li>
                <li><a href="pages/clients.php">Profil Agent</a></li>
                <li><a href="pages/connexion_inscription.php">Connexion / Inscription</a></li>
            </ul>
        </nav>
        <section>
            <h2>Films à l'affiche</h2>
            <div class="films-preview">
                <?php while ($film = $resultat_films->fetch_assoc()) : ?>
                    <div class="film-card">
                        <h3><?php echo htmlspecialchars($film['titre']); ?></h3>
                        <?php if (!empty($film['affiche'])) : ?>
                            <img src="<?php echo htmlspecialchars($film['affiche']); ?>" alt="Affiche de <?php echo htmlspecialchars($film['titre']); ?>" style="max-width:120px;">
                        <?php endif; ?>
                        <p>Sortie : <?php echo htmlspecialchars($film['date_sortie']); ?></p>
                        <a href="pages/details_films.php?id=<?php echo $film['film_id']; ?>">Voir détails</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <section>
            <h2>Dernières actualités</h2>
            <div class="actus-preview">
                <?php while ($actu = $resultat_actualites->fetch_assoc()) : ?>
                    <div class="actu-card">
                        <h3><?php echo htmlspecialchars($actu['titre']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($actu['contenu'], 0, 120)) . '...'; ?></p>
                        <a href="pages/actualites.php">Lire la suite</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>

