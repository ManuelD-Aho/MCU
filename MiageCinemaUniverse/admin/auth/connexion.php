<?php
global $connexion;
session_start();
require_once '../../configuration/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/fonctions.php';

$erreur = "";
$succes = "";
$afficher_creation_admin = false;

// Vérifier s'il existe déjà un administrateur
$sql_check_admin = "SELECT COUNT(*) as count FROM administrateurs";
$result_check_admin = $connexion->query($sql_check_admin);
$row = $result_check_admin->fetch_assoc();
$admin_exists = $row['count'] > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'creer_admin') {
        // Traitement de la création d'un nouvel administrateur
        $nom_utilisateur = nettoyerEntree($_POST['nouveau_nom_utilisateur']);
        $mot_de_passe = $_POST['nouveau_mot_de_passe'];
        $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

        if ($mot_de_passe !== $confirmer_mot_de_passe) {
            $erreur = "Les codes d'accès ne correspondent pas, Agent.";
        } else {
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO administrateurs (nom_utilisateur, mot_de_passe) VALUES (?, ?)";
            $stmt = $connexion->prepare($sql_insert);
            $stmt->bind_param("ss", $nom_utilisateur, $mot_de_passe_hash);

            if ($stmt->execute()) {
                $succes = "Nouvel agent administrateur créé avec succès. Vous pouvez maintenant accéder au système.";
                $admin_exists = true;
            } else {
                $erreur = "Erreur lors de la création du nouvel agent administrateur.";
            }
        }
    } else {
        // Traitement de la connexion
        $nom_utilisateur = nettoyerEntree($_POST['nom_utilisateur']);
        $mot_de_passe = $_POST['mot_de_passe'];

        if (empty($nom_utilisateur) || empty($mot_de_passe)) {
            $erreur = "Veuillez fournir tous les codes d'accès, Agent.";
        } else {
            $sql = "SELECT admin_id, nom_utilisateur, mot_de_passe FROM administrateurs WHERE nom_utilisateur = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bind_param("s", $nom_utilisateur);
            $stmt->execute();
            $resultat = $stmt->get_result();

            if ($resultat->num_rows === 1) {
                $admin = $resultat->fetch_assoc();
                if (password_verify($mot_de_passe, $admin['mot_de_passe'])) {
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['nom_utilisateur'] = $admin['nom_utilisateur'];
                    loggerAction("Accès au système par l'agent administrateur " . $admin['nom_utilisateur']);
                    header("Location: ../admin.php");
                    exit();
                } else {
                    $erreur = "Code d'accès incorrect, Agent. Veuillez réessayer.";
                }
            } else {
                $erreur = "Agent non reconnu. Vérifiez vos accréditations.";
            }
        }
    }
}

// Si aucun admin n'existe, afficher le formulaire de création
if (!$admin_exists) {
    $afficher_creation_admin = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Sécurisé - Agents Administrateurs</title>
    <link rel="stylesheet" href="../../assets/css/com_admin.css">
    <script src="../../assets/js/script.js" defer></script>
</head>
<body>
<div class="container">
    <header>
        <a href="../../pages/accueil.php" class="bouton-retour">Retour à la base</a>
    </header>
    <div class="loader"></div>
    <h1><?php echo $afficher_creation_admin ? "Création du Premier Agent Administrateur" : "Accès Sécurisé - Agents Administrateurs"; ?></h1>
    <?php if (!empty($erreur)) { echo "<div class='erreur'>$erreur</div>"; } ?>
    <?php if (!empty($succes)) { echo "<div class='succes'>$succes</div>"; } ?>

    <?php if ($afficher_creation_admin): ?>
        <form action="connexion.php" method="post">
            <input type="hidden" name="action" value="creer_admin">
            <label for="nouveau_nom_utilisateur">Nom de code :</label>
            <input type="text" id="nouveau_nom_utilisateur" name="nouveau_nom_utilisateur" required>

            <label for="nouveau_mot_de_passe">Code d'accès :</label>
            <div class="password-toggle">
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <span class="toggle-icon">👁️</span>
            </div>
            <label for="confirmer_mot_de_passe">Confirmer le code d'accès :</label>
            <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>

            <button type="submit">Créer l'Agent Administrateur</button>
        </form>
    <?php else: ?>
        <form action="connexion.php" method="post">
            <label for="nom_utilisateur">Nom de code :</label>
            <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>

            <label for="mot_de_passe">Code d'accès :</label>
            <div class="password-toggle">
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                <span class="toggle-icon">👁️</span>
            </div>
            <button type="submit">Accéder au Système</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>