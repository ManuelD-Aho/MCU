<?php
session_start();
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/fonctions.php';
include_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCU - Quartier Général des Agents Secrets</title>
    <link rel="stylesheet" href="../assets/css/cinema.css">
</head>
<body class="theme-sombre">
<header class="header-principal">
</header>

<main class="contenu-principal">
    <section class="hero">
        <h1 class="titre-principal">Bienvenue au MiageCinemaUniverse</h1>
        <p class="sous-titre">Votre Quartier Général pour des Missions Cinématographiques</p>
    </section>

    <section class="histoire">
        <h2>Notre Histoire Secrète</h2>
        <p>Fondé en 2010, le MiageCinemaUniverse (MCU) est né de la vision d'un groupe d'agents secrets passionnés de cinéma. Notre mission : créer un lieu où le septième art rencontre l'espionnage, offrant une expérience unique à nos "agents" spectateurs.</p>
    </section>

    <section class="installations">
        <h2>Nos Installations de Pointe</h2>
        <ul>
            <li>5 salles équipées de la dernière technologie de projection 4K</li>
            <li>Système audio immersif Dolby Atmos</li>
            <li>Sièges ergonomiques avec compartiments secrets</li>
            <li>Écrans géants de 20 mètres de large</li>
            <li>Bar à cocktails thématiques "Shaken, not stirred"</li>
        </ul>
    </section>

    <section class="experiences">
        <h2>Expériences Uniques</h2>
        <div class="experience-grid">
            <div class="experience-item">
                <h3>Séances Secrètes</h3>
                <p>Participez à nos projections mystères où le film n'est révélé qu'au dernier moment.</p>
            </div>
            <div class="experience-item">
                <h3>Escape Game Cinéma</h3>
                <p>Résolvez des énigmes inspirées de grands classiques du cinéma d'espionnage.</p>
            </div>
            <div class="experience-item">
                <h3>Réalité Virtuelle</h3>
                <p>Plongez dans des missions virtuelles après certaines séances.</p>
            </div>
        </div>
    </section>

    <section class="evenements">
        <h2>Événements Spéciaux</h2>
        <ul>
            <li>Nuits marathons James Bond</li>
            <li>Festivals du film d'espionnage</li>
            <li>Rencontres avec des réalisateurs de films d'action</li>
            <li>Avant-premières exclusives</li>
        </ul>
    </section>

    <section class="technologie">
        <h2>Notre Technologie Secrète</h2>
        <p>Le MCU est fier d'utiliser des technologies de pointe pour offrir une expérience cinématographique incomparable :</p>
        <ul>
            <li>Projecteurs laser 4K pour une image cristalline</li>
            <li>Systèmes de son surround 3D</li>
            <li>Écrans à haute luminosité pour une immersion totale</li>
            <li>Sièges interactifs avec retour haptique</li>
        </ul>
    </section>

    <section class="mission">
        <h2>Notre Mission</h2>
        <p>Au MCU, notre mission est de :</p>
        <ul>
            <li>Offrir une expérience cinématographique immersive et innovante</li>
            <li>Promouvoir le cinéma d'action et d'espionnage</li>
            <li>Créer une communauté d'amateurs de cinéma passionnés</li>
            <li>Soutenir les talents émergents du cinéma d'action</li>
        </ul>
    </section>

    <section class="services">
        <h2>Nos Services Secrets</h2>
        <div class="service-grid">
            <div class="service-item">
                <h3>Carte d'Agent Fidélité</h3>
                <p>Accumulez des points et débloquez des avantages exclusifs.</p>
            </div>
            <div class="service-item">
                <h3>Location de Salles Privées</h3>
                <p>Pour vos événements spéciaux ou missions secrètes.</p>
            </div>
            <div class="service-item">
                <h3>Cours de Cinéma</h3>
                <p>Apprenez les secrets du cinéma d'action et d'espionnage.</p>
            </div>
        </div>
    </section>

    <section class="equipe">
        <h2>Notre Équipe d'Élite</h2>
        <p>Le MCU est dirigé par une équipe passionnée de cinéphiles et d'anciens agents :</p>
        <ul>
            <li>Directeur : Agent X (ancien de la CIA)</li>
            <li>Responsable Technique : Dr. Gadget (expert en technologies avancées)</li>
            <li>Chef des Opérations : Mme Bond (spécialiste en logistique d'événements)</li>
            <li>Responsable Marketing : L'Illusionniste (expert en communication secrète)</li>
        </ul>
    </section>

    <section class="temoignages">
        <h2>Ce que disent nos Agents</h2>
        <div class="temoignage-grid">
            <blockquote>
                "Une expérience cinématographique comme nulle autre. J'ai l'impression d'être dans un vrai film d'espionnage à chaque visite !" - Agent 007
            </blockquote>
            <blockquote>
                "Les séances secrètes sont addictives. On ne sait jamais à quoi s'attendre, c'est excitant !" - Espionne Novice
            </blockquote>
            <blockquote>
                "La qualité de projection et de son est incroyable. C'est mieux que dans mon QG secret !" - Le Caméléon
            </blockquote>
        </div>
    </section>

    <section class="contact">
        <h2>Contact Secret</h2>
        <p>Pour toute mission ou renseignement :</p>
        <ul>
            <li>Téléphone Rouge : +33 1 23 45 67 89</li>
            <li>Email Crypté : contact@mcu-secret.com</li>
            <li>Adresse : 42 Rue des Agents, 75001 Paris, France</li>
        </ul>
    </section>
</main>

<footer class="footer-principal">
    <p>© 2024 MiageCinemaUniverse - Tous droits réservés</p>
    <nav class="navigation-footer">
        <ul>
            <li><a href="#">Mentions Légales</a></li>
            <li><a href="#">Politique de Confidentialité</a></li>
            <li><a href="#">Nous Contacter</a></li>
        </ul>
    </nav>
</footer>
</body>
</html>

