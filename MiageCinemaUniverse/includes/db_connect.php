<?php
require_once __DIR__ . '/../configuration/config.php';

$connexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

