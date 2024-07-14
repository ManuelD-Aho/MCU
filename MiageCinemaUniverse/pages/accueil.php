<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/fonctions.php';
include_once __DIR__ . '/header.php';


// Récupérer le film en vedette (film_id 21)
$sql_vedette = "SELECT * FROM films WHERE film_id = ?";
$stmt_vedette = $connexion->prepare($sql_vedette);
$film_id_vedette = 5;
$stmt_vedette->bind_param("i", $film_id_vedette);
$stmt_vedette->execute();
$resultat_vedette = $stmt_vedette->get_result();
$film_vedette = $resultat_vedette->fetch_assoc();

// Récupérer les films à l'affiche
$sql_films = "SELECT * FROM films WHERE date_sortie <= CURDATE() ORDER BY date_sortie DESC LIMIT 5";
$resultat_films = $connexion->query($sql_films);

// Récupérer les actualités
$sql_actualites = "SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 3";
$resultat_actualites = $connexion->query($sql_actualites);

// Logger l'action de visite de la page d'accueil
loggerAction("Visite de la page d'accueil");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quartier Général - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="../assets/css/accueil.css">
</head>
<body>
<div class="page-wrapper">
    <main>
        <?php if ($film_vedette): ?>
            <section class="film-vedette" style="background-image: url('<?php echo htmlspecialchars($film_vedette['image_film']); ?>');">
                <div class="vedette-overlay">
                    <div class="vedette-info">
                        <h2>Mission Principale</h2>
                        <h3><?php echo htmlspecialchars($film_vedette['titre_film']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($film_vedette['synopsis'], 0, 200)) . '...'; ?></p>
                        <a href="details_films.php?id=<?php echo $film_vedette['film_id']; ?>" class="btn btn-primary">Accepter la mission</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="films-affiche">
            <h2>Opérations en cours</h2>
            <div class="carousel">
                <?php while ($film = $resultat_films->fetch_assoc()) : ?>
                    <article class="carousel-item">
                        <img src="<?php echo htmlspecialchars($film['affiche']); ?>" alt="<?php echo htmlspecialchars($film['titre_film']); ?>">
                        <div class="film-info">
                            <h3><?php echo htmlspecialchars($film['titre_film']); ?></h3>
                            <p class="genre"><?php echo htmlspecialchars($film['genre']); ?></p>
                            <a href="details_films.php?id=<?php echo $film['film_id']; ?>" class="btn btn-secondary">Consulter le dossier</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="actualites">
            <h2>Bulletins d'information</h2>
            <div class="actualites-grid">
                <?php while ($actu = $resultat_actualites->fetch_assoc()) : ?>
                    <article class="actualite-card">
                        <h3><?php echo htmlspecialchars($actu['titre']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($actu['contenu'], 0, 100)) . '...'; ?></p>
                        <a href="actualites.php?id=<?php echo $actu['id']; ?>" class="btn btn-tertiary">Lire le rapport</a>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="mission-cinema">
            <h2>Notre Mission Secrète</h2>
            <p>Le MiageCinemaUniverse s'engage à offrir une expérience cinématographique unique, mêlant divertissement de pointe et technologie de surveillance avancée.</p>
        </section>
    </main>
</div>
</body>
</html>