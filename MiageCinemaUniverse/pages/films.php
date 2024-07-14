<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/fonctions.php';
include_once __DIR__ . '/header.php';

// Récupération de la date sélectionnée (par défaut aujourd'hui)
$date_selected = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Récupération des films à l'affiche pour la date sélectionnée
$sql_films = "SELECT DISTINCT f.* 
              FROM films f 
              JOIN horaires h ON f.film_id = h.film_id
              WHERE f.date_sortie <= ? AND h.date_projection = ?
              ORDER BY f.date_sortie DESC";
$stmt = $connexion->prepare($sql_films);
$stmt->bind_param("ss", $date_selected, $date_selected);
$stmt->execute();
$resultat_films = $stmt->get_result();

// Récupération du film en vedette (supposons que c'est le premier film de la liste)
$film_vedette = $resultat_films->fetch_assoc();

// Réinitialisation du pointeur de résultat pour la boucle principale
$resultat_films->data_seek(0);

// Récupération des films à venir
$date_aujourdhui = date('Y-m-d');
$sql_a_venir = "SELECT * FROM films WHERE date_sortie > ? ORDER BY date_sortie ASC LIMIT 5";
$stmt_a_venir = $connexion->prepare($sql_a_venir);
$stmt_a_venir->bind_param("s", $date_aujourdhui);
$stmt_a_venir->execute();
$resultat_a_venir = $stmt_a_venir->get_result();

// Logger l'action
loggerAction("Consultation de la page des films");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossiers Secrets (Films) - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/films.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="<?php echo isset($_SESSION['theme']) ? $_SESSION['theme'] : 'theme-sombre'; ?>">
<main class="conteneur-principal">
    <h1 class="titre-page">Dossiers Secrets (Films)</h1>

    <?php if ($film_vedette) : ?>
        <div class="film-vedette-container">
            <section class="film-vedette" style="background-image: url('<?php echo htmlspecialchars($film_vedette['image_film']); ?>');">
                <div class="overlay">
                <div class="info-vedette">
                    <h2><?php echo htmlspecialchars($film_vedette['titre_film']); ?></h2>
                    <p><?php echo htmlspecialchars(substr($film_vedette['synopsis'], 0, 150)) . '...'; ?></p>
                    <a href="details_films.php?id=<?php echo $film_vedette['film_id']; ?>" class="btn-mission">Consulter le Dossier</a>
                </div>
            </div>
            </section>
        </div>
    <?php endif; ?>

    <section class="selecteur-dates">
        <?php
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("+$i day"));
            $jour = date('d', strtotime($date));
            $nom_jour = date('D', strtotime($date));
            $classe_active = $date == $date_selected ? " active" : "";
            echo "<a href='?date=$date' class='date-item$classe_active' aria-label='Sélectionner la date du $jour $nom_jour'>";
            echo "<span class='jour'>$jour</span>";
            echo "<span class='nom-jour'>$nom_jour</span>";
            echo "</a>";
        }
        ?>
    </section>

    <div class="grille-films-container">
        <section class="grille-films">
        <?php
        if ($resultat_films->num_rows > 0) {
            while ($film = $resultat_films->fetch_assoc()) :
                $est_nouveau = (strtotime($film['date_sortie']) > strtotime('-2 days'));

                // Récupération des horaires pour ce film à la date sélectionnée
                $sql_horaires = "SELECT heure_projection FROM horaires WHERE film_id = ? AND date_projection = ? ORDER BY heure_projection";
                $stmt_horaires = $connexion->prepare($sql_horaires);
                $stmt_horaires->bind_param("is", $film['film_id'], $date_selected);
                $stmt_horaires->execute();
                $resultat_horaires = $stmt_horaires->get_result();
                ?>
            <div class="carte-film loading">
                <article class="carte-film">
                    <a href="details_films.php?id=<?php echo $film['film_id']; ?>" class="lien-carte">
                        <div class="film-image" style="background-image: url('<?php echo htmlspecialchars($film['affiche']); ?>');">
                            <?php if ($est_nouveau) : ?>
                                <span class="etiquette-nouveau">Nouveau</span>
                            <?php endif; ?>
                        </div>
                        <div class="info-film">
                            <h2 class="titre-film"><?php echo htmlspecialchars($film['titre_film']); ?></h2>
                            <p class="genre-film">Genre : <?php echo htmlspecialchars($film['genre']); ?></p>
                            <p class="duree-film">Durée : <?php echo htmlspecialchars($film['duree_minutes']); ?> minutes</p>
                        </div>
                    </a>
                    <div class="horaires-film">
                        <h3>Horaires des missions</h3>
                        <?php while ($horaire = $resultat_horaires->fetch_assoc()) : ?>
                            <a class="btn-seance">
                                <?php echo substr($horaire['heure_projection'], 0, 5); ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </article>
            </div>
            <?php endwhile;
        } else {
            echo "<p class='message-info'>Aucune mission n'est programmée pour cette date.</p>";
        }
        ?>
        </section>
    </div>

    <div class="films-a-venir-container">
        <section class="films-a-venir">
        <h2>Missions à venir</h2>
        <div class="grille-films">
            <?php while ($film = $resultat_a_venir->fetch_assoc()) : ?>
                <article class="carte-film">
                    <a href="details_films.php?id=<?php echo $film['film_id']; ?>" class="lien-carte">
                        <div class="film-image" style="background-image: url('<?php echo htmlspecialchars($film['affiche']); ?>');"></div>
                        <div class="info-film">
                            <h3 class="titre-film"><?php echo htmlspecialchars($film['titre_film']); ?></h3>
                            <p class="date-sortie">Date de sortie : <?php echo dateToFrench($film['date_sortie'], 'd F Y'); ?></p>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
        </section>
    </div>
</main>
<script>
        document.querySelectorAll('.loading').forEach(el => el.classList.remove('loading'));
})
</script>
</body>
</html>

