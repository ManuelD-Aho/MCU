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
// Définition des requêtes pour les statistiques
$requetes = [
    'films' => "SELECT COUNT(*) as total FROM films WHERE MONTH(date_sortie) = MONTH(CURRENT_DATE())",
    'clients' => "SELECT COUNT(*) as total FROM clients",
    'reservations' => "SELECT COUNT(*) as total FROM reservations WHERE MONTH(date_reservation) = MONTH(CURRENT_DATE())",
    'avis_utilisateurs' => "SELECT COUNT(*) as total FROM avis_utilisateurs WHERE MONTH(date_avis) = MONTH(CURRENT_DATE())",
    'salles' => "SELECT COUNT(*) as total FROM salles",
    'moyenne_notes' => "SELECT AVG(note) as moyenne FROM avis_utilisateurs WHERE MONTH(date_avis) = MONTH(CURRENT_DATE())",
    'seances' => "SELECT COUNT(*) as total FROM horaires WHERE MONTH(date_projection) = MONTH(CURRENT_DATE())",
    'actualites' => "SELECT COUNT(*) as total FROM actualites",
    'top_films' => "SELECT f.film_id, f.titre_film, f.genre, AVG(a.note) as evaluation FROM films f LEFT JOIN avis_utilisateurs a ON f.film_id = a.film_id GROUP BY f.film_id ORDER BY evaluation DESC LIMIT 5",
    'dernieres_reservations' => "SELECT r.reservation_id, c.nom, c.prenom, f.titre_film, r.date_reservation FROM reservations r JOIN clients c ON r.client_id = c.client_id JOIN horaires h ON r.horaire_id = h.horaire_id JOIN films f ON h.film_id = f.film_id ORDER BY r.date_reservation DESC LIMIT 10",
    'dernieres_actualites' => "SELECT id, titre, image, date_publication FROM actualites ORDER BY date_publication DESC LIMIT 5"
];

// Exécution des requêtes et stockage des résultats
$statistiques = [];
foreach ($requetes as $key => $sql) {
    $result = mysqli_query($connexion, $sql);
    if ($result) {
        if (in_array($key, ['top_films', 'dernieres_reservations', 'dernieres_actualites'])) {
            $statistiques[$key] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $statistiques[$key] = mysqli_fetch_assoc($result);
        }
    } else {
        $statistiques[$key] = ($key == 'moyenne_notes') ? ['moyenne' => 0] : ['total' => 0];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administration MCU</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/gestions.css">
</head>
<body class="<?php echo $_SESSION['theme'] ?? 'theme-sombre'; ?>">
<div class="conteneur-admin">
    <aside class="barre-laterale" id="barreLaterale">
        <h2>Quartier Général</h2>
        <nav>
            <ul>
                <li><a href="admin.php" class="actif">Tableau de bord</a></li>
                <li><a href="gestion_films.php">Dossiers Secrets</a></li>
                <li><a href="gestion_horaires.php">Planification des Missions</a></li>
                <li><a href="gestion_clients.php">Registre des Agents</a></li>
                <li><a href="gestion_actualites.php">Actualités du MCU</a></li>
                <li><a href="auth/deconnexion.php">Évacuation d'Urgence</a></li>
            </ul>
        </nav>
    </aside>
    <main class="contenu-principal">
        <section class="statistiques">
            <div class="carte">
                <h3>Missions du Mois (Films)</h3>
                <p><?php echo $statistiques['films']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Agents Actifs (Clients)</h3>
                <p><?php echo $statistiques['clients']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Opérations en Cours (Réservations)</h3>
                <p><?php echo $statistiques['reservations']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Rapports de Mission (Avis Utilisateurs)</h3>
                <p><?php echo $statistiques['avis_utilisateurs']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Positions Sécurisées (Sièges Réservés)</h3>
                <p><?php echo $statistiques['sieges_reserves']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Bases d'Opération (Salles)</h3>
                <p><?php echo $statistiques['salles']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Efficacité des Missions (Moyenne des Notes)</h3>
                <p><?php echo number_format($statistiques['moyenne_notes']['moyenne'] ?? 0, 2); ?>/5</p>
            </div>
            <div class="carte">
                <h3>Briefings Programmés (Séances)</h3>
                <p><?php echo $statistiques['seances']['total'] ?? 0; ?></p>
            </div>
            <div class="carte">
                <h3>Actualités Publiées</h3>
                <p><?php echo $statistiques['actualites']['total'] ?? 0; ?></p>
            </div>
        </section>
        <section class="missions-populaires">
            <h2>Missions les Plus Efficaces (Top 5 Films)</h2>
            <?php if (!empty($statistiques['top_films'])): ?>
                <ul>
                    <?php foreach ($statistiques['top_films'] as $film): ?>
                        <li><?php echo htmlspecialchars($film['titre_film']) . ' - Genre: ' . htmlspecialchars($film['genre']) . ' - Évaluation: ' . number_format($film['evaluation'], 2); ?>/5</li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune donnée disponible pour le moment.</p>
            <?php endif; ?>
        </section>

        <section class="dernieres-operations">
            <h2>Dernières Opérations (Réservations)</h2>
            <?php if (!empty($statistiques['dernieres_reservations'])): ?>
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Agent</th>
                        <th>Mission (Film)</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($statistiques['dernieres_reservations'] as $reservation): ?>
                        <tr>
                            <td><?php echo $reservation['reservation_id']; ?></td>
                            <td><?php echo htmlspecialchars($reservation['nom'] . ' ' . $reservation['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['titre_film']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($reservation['date_reservation'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune réservation récente.</p>
            <?php endif; ?>
        </section>

        <section class="dernieres-actualites">
            <h2>Dernières Actualités Publiées</h2>
            <?php if (!empty($statistiques['dernieres_actualites'])): ?>
                <ul>
                    <?php foreach ($statistiques['dernieres_actualites'] as $actualite): ?>
                        <li>
                            <?php if (!empty($actualite['image'])): ?>
                                <img src="<?php echo htmlspecialchars($actualite['image']); ?>" alt="Image de l'actualité" class="miniature-actualite">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($actualite['titre']); ?></h3>
                            <p>Date de publication : <?php echo date('d/m/Y', strtotime($actualite['date_publication'])); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune actualité récente.</p>
            <?php endif; ?>
        </section>
    </main>
</div>
</body>
</html>

