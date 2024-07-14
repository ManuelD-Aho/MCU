<?php
session_start();
require_once '../configuration/config.php';
require_once '../includes/fonctions.php';

$erreur = "";
$succes = "";

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['client_id'])) {
    header("Location: accueil.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'connexion') {
            $email = nettoyerEntree($_POST['email']);
            $mot_de_passe = $_POST['mot_de_passe'];

            // Vérification pour la connexion admin
            if ($email === "ad@ad.ad" && $mot_de_passe === "ad") {
                header("Location: ../admin/auth/connexion.php");
                exit();
            }

            // Connexion client
            $sql = "SELECT client_id, email, mot_de_passe FROM clients WHERE email = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultat = $stmt->get_result();

            if ($resultat->num_rows === 1) {
                $client = $resultat->fetch_assoc();
                if (password_verify($mot_de_passe, $client['mot_de_passe'])) {
                    $_SESSION['client_id'] = $client['client_id'];
                    header("Location: accueil.php");
                    exit();
                } else {
                    $erreur = "Code d'accès incorrect, Agent. Veuillez réessayer.";
                }
            } else {
                $erreur = "Agent non reconnu. Vérifiez vos accréditations.";
            }
        } elseif ($_POST['action'] == 'inscription') {
            // Traitement de l'inscription
            $nom = nettoyerEntree($_POST['nom']);
            $prenom = nettoyerEntree($_POST['prenom']);
            $email = nettoyerEntree($_POST['email']);
            $mot_de_passe = $_POST['mot_de_passe'];
            $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

            if ($mot_de_passe !== $confirmer_mot_de_passe) {
                $erreur = "Les codes d'accès ne correspondent pas, Agent.";
            } else {
                $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                $date_naissance = $_POST['date_naissance'];
                $sql_insert = "INSERT INTO clients (nom, prenom, email, date_naissance, mot_de_passe) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connexion->prepare($sql_insert);
                $stmt->bind_param("sssss", $nom, $prenom, $email, $date_naissance, $mot_de_passe_hash);

                if ($stmt->execute()) {
                    $succes = "Votre dossier d'agent a été créé avec succès. Vous pouvez maintenant vous connecter.";
                } else {
                    $erreur = "Erreur lors de la création de votre dossier d'agent.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de Recrutement et d'Accès - MiageCinemaUniverse</title>
    <link rel="stylesheet" href="../assets/css/connexion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <a href="accueil.php" class="bouton-retour">
        <i class="fas fa-arrow-left"></i> Retour à la base
    </a>

    <div class="form-container">
        <input type="radio" id="connexion" name="tab" checked>
        <input type="radio" id="inscription" name="tab">
        <div class="tabs">
            <label for="connexion" class="tab">Connexion Agent</label>
            <label for="inscription" class="tab">Nouveau Recrutement</label>
        </div>

        <div class="content">
            <div class="connexion-content">
                <form action="connexion_inscription.php" method="post">
                    <h2><i class="fas fa-user-secret"></i> Accès Agent</h2>
                    <?php if (!empty($erreur)) { echo "<div class='erreur'><i class='fas fa-exclamation-triangle'></i> $erreur</div>"; } ?>
                    <?php if (!empty($succes)) { echo "<div class='succes'><i class='fas fa-check-circle'></i> $succes</div>"; } ?>
                    <input type="hidden" name="action" value="connexion">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Code d'Agent (Email)" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="mot_de_passe" placeholder="Mot de Passe Sécurisé" required>
                    </div>
                    <button type="submit" class="btn-submit">
                        <span class="btn-text">Activer l'accès</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </form>
            </div>

            <div class="inscription-content">
                <form action="connexion_inscription.php" method="post">
                    <h2><i class="fas fa-user-plus"></i> Création de Dossier Agent</h2>
                    <input type="hidden" name="action" value="inscription">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nom" placeholder="Nom de code" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="prenom" placeholder="Prénom de code" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email sécurisé" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-calendar"></i>
                        <input type="date" name="date_naissance" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="mot_de_passe" placeholder="Code d'accès" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirmer_mot_de_passe" placeholder="Confirmer le code d'accès" required>
                    </div>
                    <button type="submit" class="btn-submit">
                        <span class="btn-text">Créer le dossier</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('theme-switch').addEventListener('change', function() {
        document.body.classList.toggle('dark-theme');
    });
</script>
</body>
</html>

