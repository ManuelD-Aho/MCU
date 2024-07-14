<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/fonctions.php';

// Vérification de la connexion de l'administrateur
if (!estAdmin()) {
    header('Location: auth/connexion.php');
    exit();
}

$message = '';
$erreur = '';

// Traitement de la suppression d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
    $client_id = intval($_POST['client_id']);
    $sql_supprimer = "DELETE FROM clients WHERE client_id = ?";
    $stmt = mysqli_prepare($connexion, $sql_supprimer);
    mysqli_stmt_bind_param($stmt, "i", $client_id);
    if (mysqli_stmt_execute($stmt)) {
        $message = "L'agent a été retiré avec succès de la base de données.";
    } else {
        $erreur = "Erreur lors de la suppression de l'agent : " . mysqli_error($connexion);
    }
}

// Récupération de la liste des clients
$sql = "SELECT * FROM clients ORDER BY nom, prenom";
$resultat = mysqli_query($connexion, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Agents - Secrétariat MCU</title>
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale">
        <h2>Quartier Général</h2>
        <nav>
            <ul>
                <li><a href="admin.php">Tableau de bord</a></li>
                <li><a href="gestion_films.php">Dossiers Secrets</a></li>
                <li><a href="gestion_horaires.php">Planification des Missions</a></li>
                <li><a href="gestion_clients.php" class="actif">Registre des Agents</a></li>
                <li><a href="gestion_actualites.php" >Actualités du MCU</a></li>
                <li><a href="auth/deconnexion.php">Évacuation d'Urgence</a></li>
            </ul>
        </nav>
    </aside>

    <main class="contenu-principal">
        <h1>Gestion des Agents</h1>
        <header>
            <h1>Tableau de Bord des Opérations Secrètes</h1>
        </header>
        <?php if ($message) : ?>
            <div class="alerte alerte-succes"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($erreur) : ?>
            <div class="alerte alerte-erreur"><?php echo $erreur; ?></div>
        <?php endif; ?>

        <table class="table-gestion">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Date de naissance</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($client = mysqli_fetch_assoc($resultat)) : ?>
                <tr>
                    <td><?php echo $client['client_id']; ?></td>
                    <td><?php echo htmlspecialchars($client['nom']); ?></td>
                    <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                    <td><?php echo $client['date_naissance']; ?></td>
                    <td>
                        <form action="" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?');">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">
                            <button type="submit" class="bouton-supprimer">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>

