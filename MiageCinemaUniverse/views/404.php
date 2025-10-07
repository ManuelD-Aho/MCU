<?php
$pageTitle = 'Erreur 404 - Page non trouvée';
ob_start();
?>
<div class="text-center py-20">
    <h1 class="text-8xl font-bold text-primary">404</h1>
    <p class="text-2xl mt-4">Agent, vous êtes en territoire inconnu.</p>
    <p class="mt-2">La page que vous cherchez n'existe pas ou a été déplacée.</p>
    <a href="/accueil" class="btn btn-primary mt-8">Retourner au Quartier Général</a>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>