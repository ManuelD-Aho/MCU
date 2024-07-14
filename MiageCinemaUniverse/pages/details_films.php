<?php
session_start();
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/fonctions.php';
include_once __DIR__ . '/header.php';


$film_id = intval($_GET['id']);

// Récupérer les détails du film
$sql_film = "SELECT * FROM films WHERE film_id = ?";
$stmt = $connexion->prepare($sql_film);
if ($stmt === false) {
    die("Erreur de préparation de la requête film : " . $connexion->error);
}
if (!$stmt->bind_param("i", $film_id)) {
    die("Erreur de liaison des paramètres : " . $stmt->error);
}
if (!$stmt->execute()) {
    die("Erreur d'exécution de la requête : " . $stmt->error);
}
$resultat_film = $stmt->get_result();
$film = $resultat_film->fetch_assoc();

if (!$film) {
    redirigerAvecMessage('films.php', 'Film non trouvé.', 'error');
}

// Récupérer les horaires pour ce film pour la semaine à venir
$sql_horaires = "SELECT * FROM horaires 
                 WHERE film_id = ? 
                 AND date_projection BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                 ORDER BY date_projection, heure_projection";
$stmt_horaires = $connexion->prepare($sql_horaires);
if ($stmt_horaires === false) {
    die("Erreur de préparation de la requête horaires : " . $connexion->error);
}
if (!$stmt_horaires->bind_param("i", $film_id)) {
    die("Erreur de liaison des paramètres pour les horaires : " . $stmt_horaires->error);
}
if (!$stmt_horaires->execute()) {
    die("Erreur d'exécution de la requête horaires : " . $stmt_horaires->error);
}
$resultat_horaires = $stmt_horaires->get_result();

// Organiser les horaires par jour
$horaires_par_jour = [];
while ($horaire = $resultat_horaires->fetch_assoc()) {
    $jour = date('Y-m-d', strtotime($horaire['date_projection']));
    if (!isset($horaires_par_jour[$jour])) {
        $horaires_par_jour[$jour] = [];
    }
    $horaires_par_jour[$jour][] = $horaire;
}

// Logger l'action
loggerAction("Consultation des détails du film ID: " . $film_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['titre_film']); ?> - Détails du Film</title>
    <meta name="theme-color" content="#0a0a0a">
    <link rel="stylesheet" href="../assets/css/details_films.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Détails et horaires pour le film <?php echo htmlspecialchars($film['titre_film']); ?>">
</head>
<body>
<div class="video-background">
    <video loop>
        <source src="<?php echo htmlspecialchars($film['bande_annonce']); ?>" type="video/mp4">
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>
</div>
<button class="play-button" aria-label="Lire/Pause la bande-annonce">
    <span class="icon"></span>
</button>
<main>
    <section class="film-details">
        <section class="film-details animated-section">
        <div class="film-poster">
            <img src="<?php echo htmlspecialchars($film['affiche']); ?>" alt="<?php echo htmlspecialchars($film['titre_film']); ?>">
        </div>
        <div class="film-info">
            <h1><?php echo htmlspecialchars($film['titre_film']); ?></h1>
            <p><strong>Durée:</strong> <?php echo htmlspecialchars($film['duree_minutes']); ?> minutes</p>
            <p><strong>Date de sortie:</strong> <?php echo date('d/m/Y', strtotime($film['date_sortie'])); ?></p>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($film['genre']); ?></p>
            <p><strong>Réalisateur:</strong> <?php echo htmlspecialchars($film['directeur']); ?></p>
            <p><strong>Public cible:</strong> <?php echo htmlspecialchars($film['public_cible']); ?></p>
            <?php if ($film['est_nouveau']) : ?>
                <p><strong>Nouveau film!</strong></p>
            <?php endif; ?>
            <p class="synopsis animated-text"><strong>Synopsis:</strong> <?php echo nl2br(htmlspecialchars($film['synopsis'])); ?></p>
            <p class="description animated-text"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($film['description'])); ?></p>
        </div>
    </section>
    </section>

    <section class="horaires">
        <section class="horaires animated-section">
        <h2>Horaires des projections</h2>
        <?php if (empty($horaires_par_jour)): ?>
            <p>Aucune projection prévue pour les 7 prochains jours.</p>
        <?php else: ?>
            <?php foreach ($horaires_par_jour as $jour => $horaires): ?>
                <div class="jour-horaires">
                    <h3><?php echo date('l d/m', strtotime($jour)); ?></h3>
                    <ul>
                        <?php foreach ($horaires as $horaire): ?>
                            <li>
                                <?php echo date('H:i', strtotime($horaire['heure_projection'])); ?>
                                <a href="reservation.php?horaire_id=<?php echo $horaire['horaire_id']; ?>" class="btn-reserver">Réserver</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
    </section>
</main>
</body>
<script>
    document.addEventListener('DOMContentLoaded',()=>{
        const a=document.querySelectorAll('.animated-section'),
            b=document.querySelectorAll('.animated-text'),
            c=document.querySelector('.video-background video'),
            d=document.querySelector('.play-button'),
            e=document.querySelector('.video-background'),
            f=document.querySelector('main');
        function g(h,i){h.style.opacity=0;h.style.transition=`opacity ${i}ms ease`;setTimeout(()=>h.style.opacity=1,10)}
        g(document.body,1e3);
        const j=new IntersectionObserver(k=>{k.forEach(l=>{l.isIntersecting&&(l.target.classList.add('visible'),g(l.target,500))})},{threshold:.1});
        a.forEach(h=>j.observe(h));
        b.forEach(h=>j.observe(h));
        function m(){const{offsetWidth:h,offsetHeight:i}=e,n=16/9;c.style.width=h/i>n?`${h}px`:'auto';c.style.height=h/i>n?'auto':`${i}px`}
        window.addEventListener('resize',m);
        m();
        d.addEventListener('click',()=>{
            if(c.paused){c.play();d.classList.add('playing');e.classList.add('video-playing');f.style.opacity='0';f.style.pointerEvents='none'}
            else{c.pause();d.classList.remove('playing');e.classList.remove('video-playing');f.style.opacity='1';f.style.pointerEvents='auto'}
        });
        c.addEventListener('ended',()=>{d.classList.remove('playing');e.classList.remove('video-playing');f.style.opacity='1';f.style.pointerEvents='auto'})
    });
</script>
</html>
