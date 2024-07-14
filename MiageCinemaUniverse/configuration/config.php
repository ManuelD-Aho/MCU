<?php
// Configuration du site MCU - Quartier Général des Agents Secrets

// Informations de connexion à la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'miagecinemauniverse');

// Paramètres du site
define('SITE_NAME', 'MCU - Quartier Général');

// Connexion globale à la base de données
$connexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$connexion) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8mb4");

