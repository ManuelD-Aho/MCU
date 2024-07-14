-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 13 juil. 2024 à 23:17
-- Version du serveur :  5.7.21
-- Version de PHP :  5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `miagecinemauniverse`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

DROP TABLE IF EXISTS `actualites`;
CREATE TABLE IF NOT EXISTS `actualites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_publication` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `actualites`
--

INSERT INTO `actualites` (`id`, `titre`, `contenu`, `image`, `date_publication`) VALUES
(13, 'Nuit Marvel', 'Marvel', '../assets/images/668d42f12aa9f.jpeg', '2024-07-15 10:30:00'),
(20, 'Bleach', 'Le retour de bleach....', '../assets/images/668d42e25469d.jpeg', '2024-08-02 12:00:00'),
(21, 'Nouvelle bande-annonce pour \\\"Les Gardiens de la Galaxie Vol. 3\\\"', 'Bankai', '../assets/images/668d42a3294a7.jpeg', '2023-04-15 09:00:00'),
(22, 'Confirmation du casting pour \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"Fantastic Four\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"', 'Il s\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\'agit en effet de Gamora, toujours jouÃ©e par Zoe Saldana, vue rÃ©cemment dans Avatar 2. Fille adoptive de Thanos, elle avait pourtant Ã©tÃ© sacrifiÃ©e par ce dernier dans Avengers : Infinity War, pour qu\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\'il puisse rÃ©cupÃ©rer la Pierre de l\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\'Ã‚me sur la planÃ¨te Vormir.', '../assets/images/668d4281acefa.jpeg', '2024-07-11 14:30:00'),
(27, 'Retour surprise de Robert Downey Jr. dans un projet Marvel', 'Iron Man', '../assets/images/668d4321a412b.jpeg', '2023-03-08 09:30:00'),
(28, 'Nouveau record au box-office pour le dernier film Marvel', 'Marvel', '../assets/images/668d432e66bd3.jpeg', '2023-03-01 17:00:00'),
(29, 'Annonce d\\\'un crossover inattendu entre Marvel et DC', 'Thunder Woman : si Wonder Woman (DC) possÃ©dait Mjolnir (Marvel) ! AperÃ§ue dans le duel contre le Dieu de la mythologie nordique, Wonder Woman est sur le papier digne de porter le marteau magique. Cela lui donnerait des pouvoirs encore plus puissants que son lasso de la vÃ©ritÃ© !', '../assets/images/images.jpeg', '2024-07-12 09:20:00'),
(32, 'Festival du film d\\\'animation', 'Bankai', '../assets/images/668d42ba0e56a.jpeg', '2024-07-20 14:30:00'),
(35, 'Rencontre avec Quentin Tarantino', 'Â« Je l\\\'ai rencontrÃ©e dans un club. Nous avons dansÃ© toute la nuit Â», avait racontÃ© le rÃ©alisateur lors de son passage Ã  l\\\'Ã©mission de radio Â« The Howard Stern Show Â» en novembre 2022. AprÃ¨s cette soirÃ©e, ils avaient commencÃ© Ã  se frÃ©quenter jusqu\\\'en 2012 avant de se retrouver quatre ans plus tard lors d\\\'une croisiÃ¨re.', '../assets/images/668d428d070a4.jpeg', '2024-07-10 17:20:00');

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
CREATE TABLE IF NOT EXISTS `administrateurs` (
  `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`admin_id`, `nom_utilisateur`, `mot_de_passe`) VALUES
(1, 'admin', '$2y$10$qQ.2PSLI8h4d7MmKvb7ULObhAQAJkvQRv1As01/bsyXnYMnthR2iG');

-- --------------------------------------------------------

--
-- Structure de la table `avis_utilisateurs`
--

DROP TABLE IF EXISTS `avis_utilisateurs`;
CREATE TABLE IF NOT EXISTS `avis_utilisateurs` (
  `avis_id` int(11) NOT NULL AUTO_INCREMENT,
  `film_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `commentaire` text,
  `date_avis` datetime NOT NULL,
  PRIMARY KEY (`avis_id`),
  KEY `film_id` (`film_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `avis_utilisateurs`
--

INSERT INTO `avis_utilisateurs` (`avis_id`, `film_id`, `client_id`, `note`, `commentaire`, `date_avis`) VALUES
(1, 2, 1, 4, 'Très bon film, j\'ai adoré les effets spéciaux !', '2024-07-08 10:15:00'),
(2, 3, 2, 5, 'Un chef-d\'œuvre absolu, à voir absolument.', '2024-07-08 11:30:00'),
(3, 4, 3, 3, 'Pas mal, mais j\'attendais mieux de cette suite.', '2024-07-08 14:45:00'),
(4, 5, 4, 4, 'Une belle surprise, je recommande !', '2024-07-08 16:20:00'),
(5, 6, 5, 5, 'Incroyable ! Je suis fan de cette série.', '2024-07-08 18:00:00'),
(6, 7, 6, 4, 'Une adaptation fidèle du manga, j\'ai beaucoup apprécié.', '2024-07-08 19:30:00'),
(7, 8, 7, 3, 'Un bon film, mais un peu long à mon goût.', '2024-07-08 20:45:00'),
(8, 9, 8, 5, 'Action, humour, tout y est ! Excellent film.', '2024-07-08 22:00:00'),
(9, 10, 9, 4, 'Les personnages sont très bien développés, j\'ai adoré.', '2024-07-09 09:15:00'),
(10, 11, 10, 5, 'Un vrai régal pour les yeux, animation au top !', '2024-07-09 10:30:00'),
(11, 2, 3, 4, 'Scénario original et acteurs talentueux.', '2024-07-09 11:45:00'),
(12, 3, 5, 3, 'Un peu déçu, j\'attendais plus de ce film.', '2024-07-09 13:00:00'),
(13, 4, 7, 5, 'Meilleur film de l\'année selon moi !', '2024-07-09 14:15:00'),
(14, 5, 9, 4, 'Une belle évolution par rapport au premier opus.', '2024-07-09 15:30:00'),
(15, 6, 1, 5, 'Captivant du début à la fin, j\'ai adoré !', '2024-07-09 16:45:00');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`client_id`, `nom`, `prenom`, `email`, `date_naissance`, `mot_de_passe`) VALUES
(1, 'Dupont', 'Jean', 'jean.dupont@email.com', '1985-03-15', '$2y$10$abcdefghijklmnopqrstuv'),
(2, 'Martin', 'Sophie', 'sophie.martin@email.com', '1990-07-22', '$2y$10$bcdefghijklmnopqrstuvw'),
(3, 'Leroy', 'Pierre', 'pierre.leroy@email.com', '1978-11-30', '$2y$10$cdefghijklmnopqrstuvwx'),
(6, 'Lefebvre', 'Emma', 'emma.lefebvre@email.com', '1992-04-27', '$2y$10$fghijklmnopqrstuvwxyza'),
(7, 'Garcia', 'Thomas', 'thomas.garcia@email.com', '1983-06-12', '$2y$10$ghijklmnopqrstuvwxyzab'),
(8, 'Roux', 'Chloé', 'chloe.roux@email.com', '1998-12-03', '$2y$10$hijklmnopqrstuvwxyzabc'),
(9, 'Fournier', 'Antoine', 'antoine.fournier@email.com', '1975-08-20', '$2y$10$ijklmnopqrstuvwxyzabcd'),
(10, 'Girard', 'Julie', 'julie.girard@email.com', '1993-02-14', '$2y$10$jklmnopqrstuvwxyzabcde'),
(11, 'Manuel', 'Aho', 'aho@aho.aho', '2014-03-20', '$2y$10$o0yal8ZYjc1TecDEjyPuZuAY5Ab4Qomt7GFWeNP/UvJm/9YSedXFK'),
(12, 'Aho', 'Manuel', 'ahopaul18@gmail.com', '2005-03-30', '$2y$10$.DlmR7PELK3OsSVaeCz0N.8pZoURRP93JkYC0i6i8uOLiWMJN2f9e'),
(13, 'Aho', 'Manu', 'na@na.na', '2005-03-11', '$2y$10$cY6fm0tDNzs57qSSohWNpupHNEzWTx80Tw4AFXu/eXT7fq93Qwg1.');

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

DROP TABLE IF EXISTS `films`;
CREATE TABLE IF NOT EXISTS `films` (
  `film_id` int(11) NOT NULL AUTO_INCREMENT,
  `titre_film` varchar(255) NOT NULL,
  `duree_minutes` int(11) NOT NULL,
  `image_film` varchar(1000) DEFAULT NULL,
  `evaluation` decimal(3,1) DEFAULT NULL,
  `description` text,
  `date_sortie` date NOT NULL,
  `bande_annonce` varchar(2000) DEFAULT NULL,
  `affiche` varchar(1000) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `directeur` varchar(100) DEFAULT NULL,
  `est_nouveau` tinyint(1) DEFAULT '0',
  `public_cible` varchar(100) DEFAULT NULL,
  `synopsis` text,
  PRIMARY KEY (`film_id`),
  KEY `idx_date_sortie` (`date_sortie`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`film_id`, `titre_film`, `duree_minutes`, `image_film`, `evaluation`, `description`, `date_sortie`, `bande_annonce`, `affiche`, `genre`, `directeur`, `est_nouveau`, `public_cible`, `synopsis`) VALUES
(2, 'Beetlejuice Beetlejuice', 105, '../assets/images/668c377e42ad8.jpg', '8.0', 'Apres un accident de voiture mortel, Barbara et Adam Maitland se retrouvent bloques a hanter leur residence de campagne. Lorsque la famille Deetz emmenage, les Maitland tentent de les effrayer sans succes. Leurs efforts attirent Beetlejuice, un esprit turbulent dont l&#039;aide devient vite dangereuse pour tous.', '2024-07-14', '../assets/images/668c377e43124.mp4', '../assets/images/668c377e43426.jpg', 'Comedie', 'Tim Burton', 1, 'Adultes', 'la vie de Lydia bascule lorsque sa fille adolescente, Astrid, ouvre accidentellement le portail de l&#039;au-delÃ .'),
(4, 'Moi, moche et mechant 4', 130, '../assets/images/668c56d096d40.jpg', '9.0', 'Gru accueille un nouveau membre dans sa famille, Gru Jr., qui s&#039;amuse a tourmenter son pere. Leur existence paisible est bouleversee lorsque le genie du crime Maxime Le Mal s&#039;echappe de prison et jure de se venger de Gru.', '2024-07-10', '../assets/images/668c367fbbaf1.mp4', '../assets/images/668c367fbbedc.jpg', 'Animation', 'Ken Daurio, Mike White', 1, 'Tous Publics', 'Le genie du crime Maxime Le Mal s&#039;echappe de prison et jure de se venger de Gru.'),
(5, 'Joker : Folie Ã  Deux', 85, '../assets/images/668c35ec715a6.jpg', NULL, 'Arthur Fleck, un comedien rate, rencontre l amour de sa vie, Harley Quinn, alors qu il est interne a l hopital d Etat d Arkham. A sa sortie, ils se lancent ensemble dans une aventure romantique vouee a l echec.', '2024-06-09', '../assets/images/668c35ec7199b.mp4', '../assets/images/668c35ec71cea.jpg', 'Drame', 'Todd Phillips', 1, 'Tout Publics', 'un comedien rate, rencontre l amour de sa vie, Harley Quinn'),
(6, 'Arcane 2', 50, '../assets/images/668c356c3715e.jpg', NULL, 'plusieurs personnages de la premiere saison reviendront...', '2024-07-11', '../assets/images/668c356c3754c.mp4', '../assets/images/668c356c378d4.jpg', 'Drame', 'Christian Linke, Alex Yee', 0, 'Tout public', 'plusieurs personnages de la premiere saison reviendront...'),
(7, 'One Piece', 24, '../assets/images/668c3514c4c31.jpg', NULL, 'Uta, une chanteuse adoree connue pour cacher son identite lors de ses performances, possede une voix decrite comme &quot;surnaturelle&quot;. Pour la premiere fois, elle va se reveler au monde lors d&#039;un concert en direct', '2024-06-13', '../assets/images/668c3514c501d.mp4', '../assets/images/668c3514c5357.jpg', 'Maga, Animation', 'Eiichiro Oda', 1, 'Enfant', 'Pour la premiere fois, elle va se reveler au monde lors d&#039;un concert en direct'),
(8, 'Black Panther : Wakanda pour toujours', 162, '../assets/images/668c34a6d409b.jpg', NULL, 'Apres la mort du roi T Challa, la reine Ramonda, Shuri, M&#039;Baku, Okoye et les Dora Milaje luttent pour proteger leur nation des puissances mondiales intervenantes. Les heros doivent s&#039;unir a Nakia et Everett Ross pour forger un nouveau chemin pour le royaume du Wakanda.', '2022-11-09', '../assets/images/668c34a6d449f.mp4', '../assets/images/668c34a6d4794.jpg', 'Science-fiction', 'Ryan Coogler', 1, 'Tout public', 'Shuri et les autres heros du Wakanda doivent proteger leur nation des puissances mondiales intervenantes.'),
(9, 'Bad Boys : La chevauchee ou la mort', 115, '../assets/images/668c33dc35694.jpg', NULL, 'Le film mele action, comedie et un sens de la famille, caracteristiques de la franchise. Il promet le melange emblematique d&#039;action intense et d&#039;humour que les fans attendent.', '2024-06-05', '../assets/images/668c33dc35971.mp4', '../assets/images/668c33dc35bc9.jpg', 'Drame', 'Adil El Arbi, Bilall Fallah', 0, 'Adultes', 'Ce nouveau film de la franchise Bad Boys suit les detectives de Miami Mike Lowrey et Marcus Burnett dans une nouvelle aventure. L&#039;intrigue implique des preuves compromettantes, de l&#039;argent d&#039;un cartel de drogue mexicain et un policier corrompu.'),
(10, 'Bleach', 24, '../assets/images/668c3357523e9.jpg', '0.0', 'Depuis qu il s en souvient, Ichigo Kurosaki a toujours Ã©tÃ© capable de voir des fantÃ´mes. Mais lorsqu il rencontre Rukia, une Soul Reaper qui combat des esprits malÃ©fiques connus sous le nom de Hollows, sa vie change Ã  jamais. DÃ©sormais, grÃ¢ce Ã  une nouvelle richesse d&amp;amp;#039;Ã©nergie spirituelle, Ichigo dÃ©couvre sa vÃ©ritable vocation : protÃ©ger les vivants et les morts du mal.', '2024-07-24', '../assets/images/668c335752610.mp4', '../assets/images/668c3357527aa.jpg', 'Animation', 'Hayao Miyazaki', 1, 'Enfants', 'Depuis qu il s en souvient, Ichigo Kurosaki a toujours Ã©tÃ© capable de voir des fantÃ´mes.'),
(11, 'Vice-Versa 2', 97, '../assets/images/668c32d63fbc2.jpg', '7.0', 'Le film semble tenir toutes ses promesses, avec des personnages hauts en couleur et bien cibles, ainsi qu&#039;une intrigue pertinente.', '2024-06-11', '../assets/images/668c32b84a89b.mp4', '../assets/images/668c32d63feed.jpg', 'Animation', 'Kelsey Mann', 1, 'Enfant', 'Comme le premier opus, ce nouveau film d&#039;animation Pixar se deroule dans l&#039;esprit d&#039;un personnage principal. L&#039;histoire se passe pendant l&#039;adolescence, une periode particulierement emotionnelle et complexe.');

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
  `horaire_id` int(11) NOT NULL AUTO_INCREMENT,
  `film_id` int(11) NOT NULL,
  `salle_id` int(10) UNSIGNED NOT NULL,
  `date_projection` date NOT NULL,
  `heure_projection` time NOT NULL,
  `jour_semaine` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche') NOT NULL,
  PRIMARY KEY (`horaire_id`),
  KEY `film_id` (`film_id`),
  KEY `salle_id` (`salle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `horaires`
--

INSERT INTO `horaires` (`horaire_id`, `film_id`, `salle_id`, `date_projection`, `heure_projection`, `jour_semaine`) VALUES
(47, 2, 3, '2024-07-12', '10:00:00', 'Vendredi'),
(48, 11, 3, '2024-07-12', '14:00:00', 'Vendredi'),
(49, 7, 3, '2024-07-12', '18:00:00', 'Vendredi'),
(50, 10, 3, '2024-07-12', '21:00:00', 'Vendredi'),
(51, 9, 3, '2024-07-13', '10:00:00', 'Samedi'),
(52, 11, 3, '2024-07-13', '14:00:00', 'Samedi'),
(53, 7, 3, '2024-07-13', '18:00:00', 'Samedi'),
(54, 5, 3, '2024-07-13', '21:00:00', 'Samedi'),
(55, 9, 3, '2024-07-14', '10:00:00', 'Dimanche'),
(56, 2, 3, '2024-07-14', '14:00:00', 'Dimanche'),
(57, 11, 3, '2024-07-14', '18:00:00', 'Dimanche'),
(58, 10, 3, '2024-07-14', '21:00:00', 'Dimanche'),
(59, 10, 3, '2024-07-15', '10:00:00', 'Lundi'),
(60, 10, 3, '2024-07-15', '14:00:00', 'Lundi'),
(61, 7, 3, '2024-07-15', '18:00:00', 'Lundi'),
(62, 7, 3, '2024-07-15', '21:00:00', 'Lundi'),
(63, 8, 3, '2024-07-16', '10:00:00', 'Mardi'),
(64, 5, 3, '2024-07-16', '14:00:00', 'Mardi'),
(65, 2, 3, '2024-07-16', '18:00:00', 'Mardi'),
(66, 10, 3, '2024-07-16', '21:00:00', 'Mardi'),
(67, 2, 3, '2024-07-17', '10:00:00', 'Mercredi'),
(68, 8, 3, '2024-07-17', '14:00:00', 'Mercredi'),
(69, 10, 3, '2024-07-17', '18:00:00', 'Mercredi'),
(70, 5, 3, '2024-07-17', '21:00:00', 'Mercredi'),
(71, 11, 3, '2024-07-18', '10:00:00', 'Jeudi'),
(72, 2, 3, '2024-07-18', '14:00:00', 'Jeudi'),
(73, 8, 3, '2024-07-18', '18:00:00', 'Jeudi'),
(74, 9, 3, '2024-07-18', '21:00:00', 'Jeudi'),
(75, 9, 3, '2024-07-19', '10:00:00', 'Vendredi'),
(76, 6, 3, '2024-07-19', '14:00:00', 'Vendredi'),
(77, 9, 3, '2024-07-19', '18:00:00', 'Vendredi'),
(78, 2, 3, '2024-07-19', '21:00:00', 'Vendredi'),
(79, 4, 3, '2024-07-20', '10:00:00', 'Samedi'),
(80, 5, 3, '2024-07-20', '14:00:00', 'Samedi'),
(81, 5, 3, '2024-07-20', '18:00:00', 'Samedi'),
(82, 6, 3, '2024-07-20', '21:00:00', 'Samedi'),
(83, 10, 3, '2024-07-21', '10:00:00', 'Dimanche'),
(84, 4, 3, '2024-07-21', '14:00:00', 'Dimanche'),
(85, 11, 3, '2024-07-21', '18:00:00', 'Dimanche'),
(86, 11, 3, '2024-07-21', '21:00:00', 'Dimanche'),
(87, 6, 3, '2024-07-22', '10:00:00', 'Lundi'),
(88, 7, 3, '2024-07-22', '14:00:00', 'Lundi'),
(89, 5, 3, '2024-07-22', '18:00:00', 'Lundi'),
(90, 10, 3, '2024-07-22', '21:00:00', 'Lundi'),
(91, 8, 3, '2024-07-23', '10:00:00', 'Mardi'),
(92, 6, 3, '2024-07-23', '14:00:00', 'Mardi'),
(93, 10, 3, '2024-07-23', '18:00:00', 'Mardi'),
(94, 7, 3, '2024-07-23', '21:00:00', 'Mardi'),
(95, 7, 3, '2024-07-24', '10:00:00', 'Mercredi'),
(96, 11, 3, '2024-07-24', '14:00:00', 'Mercredi'),
(97, 6, 3, '2024-07-24', '18:00:00', 'Mercredi'),
(98, 5, 3, '2024-07-24', '21:00:00', 'Mercredi'),
(99, 5, 3, '2024-07-25', '10:00:00', 'Jeudi'),
(100, 11, 3, '2024-07-25', '14:00:00', 'Jeudi'),
(101, 6, 3, '2024-07-25', '18:00:00', 'Jeudi'),
(102, 7, 3, '2024-07-25', '21:00:00', 'Jeudi'),
(103, 10, 3, '2024-07-26', '10:00:00', 'Vendredi'),
(104, 11, 3, '2024-07-26', '14:00:00', 'Vendredi'),
(105, 4, 3, '2024-07-26', '18:00:00', 'Vendredi'),
(106, 4, 3, '2024-07-26', '21:00:00', 'Vendredi'),
(107, 11, 3, '2024-07-27', '10:00:00', 'Samedi'),
(108, 8, 3, '2024-07-27', '14:00:00', 'Samedi'),
(109, 10, 3, '2024-07-27', '18:00:00', 'Samedi'),
(110, 4, 3, '2024-07-27', '21:00:00', 'Samedi'),
(111, 5, 3, '2024-07-28', '10:00:00', 'Dimanche'),
(112, 10, 3, '2024-07-28', '14:00:00', 'Dimanche'),
(113, 7, 3, '2024-07-28', '18:00:00', 'Dimanche'),
(114, 7, 3, '2024-07-28', '21:00:00', 'Dimanche'),
(115, 10, 3, '2024-07-29', '10:00:00', 'Lundi'),
(116, 5, 3, '2024-07-29', '14:00:00', 'Lundi'),
(117, 8, 3, '2024-07-30', '15:00:00', 'Mardi'),
(118, 6, 3, '2024-07-30', '17:00:00', 'Mardi'),
(119, 11, 3, '2024-07-30', '21:00:00', 'Mardi'),
(120, 4, 3, '2024-07-30', '11:00:00', 'Mardi'),
(121, 4, 3, '2024-07-31', '15:00:00', 'Mercredi'),
(122, 6, 3, '2024-07-31', '17:00:00', 'Mercredi'),
(123, 5, 3, '2024-07-31', '21:00:00', 'Mercredi'),
(124, 11, 3, '2024-07-31', '11:00:00', 'Mercredi'),
(125, 6, 3, '2024-08-01', '15:00:00', 'Jeudi'),
(126, 4, 3, '2024-08-01', '17:00:00', 'Jeudi'),
(127, 2, 3, '2024-08-01', '21:00:00', 'Jeudi'),
(128, 6, 3, '2024-08-01', '11:00:00', 'Jeudi'),
(129, 8, 3, '2024-08-02', '15:00:00', 'Vendredi'),
(130, 6, 3, '2024-08-02', '17:00:00', 'Vendredi'),
(131, 2, 3, '2024-08-02', '21:00:00', 'Vendredi'),
(132, 11, 3, '2024-08-02', '11:00:00', 'Vendredi'),
(133, 2, 3, '2024-08-03', '15:00:00', 'Samedi'),
(134, 4, 3, '2024-08-03', '17:00:00', 'Samedi'),
(135, 5, 3, '2024-08-03', '21:00:00', 'Samedi'),
(136, 11, 3, '2024-08-03', '11:00:00', 'Samedi'),
(137, 6, 3, '2024-08-04', '15:00:00', 'Dimanche'),
(138, 7, 3, '2024-08-04', '17:00:00', 'Dimanche'),
(139, 5, 3, '2024-08-04', '21:00:00', 'Dimanche'),
(140, 9, 3, '2024-08-04', '11:00:00', 'Dimanche'),
(141, 11, 3, '2024-08-05', '15:00:00', 'Lundi'),
(142, 11, 3, '2024-08-05', '17:00:00', 'Lundi'),
(143, 4, 3, '2024-08-05', '21:00:00', 'Lundi'),
(144, 7, 3, '2024-08-05', '11:00:00', 'Lundi'),
(145, 2, 3, '2024-08-06', '15:00:00', 'Mardi'),
(146, 2, 3, '2024-08-06', '17:00:00', 'Mardi'),
(147, 7, 3, '2024-08-06', '21:00:00', 'Mardi'),
(148, 5, 3, '2024-08-06', '11:00:00', 'Mardi'),
(149, 7, 3, '2024-08-07', '15:00:00', 'Mercredi'),
(150, 5, 3, '2024-08-07', '17:00:00', 'Mercredi'),
(151, 7, 3, '2024-08-07', '21:00:00', 'Mercredi'),
(152, 5, 3, '2024-08-07', '11:00:00', 'Mercredi'),
(153, 2, 3, '2024-08-08', '15:00:00', 'Jeudi'),
(154, 10, 3, '2024-08-08', '17:00:00', 'Jeudi'),
(155, 8, 3, '2024-08-08', '21:00:00', 'Jeudi'),
(156, 8, 3, '2024-08-08', '11:00:00', 'Jeudi'),
(157, 4, 3, '2024-08-09', '15:00:00', 'Vendredi'),
(158, 11, 3, '2024-08-09', '17:00:00', 'Vendredi'),
(159, 6, 3, '2024-08-09', '21:00:00', 'Vendredi'),
(160, 10, 3, '2024-08-09', '11:00:00', 'Vendredi'),
(161, 8, 3, '2024-08-10', '15:00:00', 'Samedi'),
(162, 7, 3, '2024-08-10', '17:00:00', 'Samedi'),
(163, 8, 3, '2024-08-10', '21:00:00', 'Samedi'),
(164, 11, 3, '2024-08-10', '11:00:00', 'Samedi'),
(165, 7, 3, '2024-08-11', '15:00:00', 'Dimanche'),
(166, 10, 3, '2024-08-11', '17:00:00', 'Dimanche'),
(167, 10, 3, '2024-08-11', '21:00:00', 'Dimanche'),
(168, 8, 3, '2024-08-11', '11:00:00', 'Dimanche'),
(169, 7, 3, '2024-08-12', '15:00:00', 'Lundi'),
(170, 11, 3, '2024-08-12', '17:00:00', 'Lundi'),
(171, 11, 3, '2024-08-12', '21:00:00', 'Lundi'),
(172, 10, 3, '2024-08-12', '11:00:00', 'Lundi'),
(173, 4, 3, '2024-08-13', '15:00:00', 'Mardi'),
(174, 7, 3, '2024-08-13', '17:00:00', 'Mardi'),
(175, 11, 3, '2024-08-13', '21:00:00', 'Mardi'),
(176, 2, 3, '2024-08-13', '11:00:00', 'Mardi'),
(177, 5, 3, '2024-08-14', '15:00:00', 'Mercredi'),
(178, 7, 3, '2024-08-14', '17:00:00', 'Mercredi'),
(179, 6, 3, '2024-08-14', '21:00:00', 'Mercredi'),
(180, 6, 3, '2024-08-14', '11:00:00', 'Mercredi'),
(181, 5, 3, '2024-08-15', '15:00:00', 'Jeudi'),
(182, 2, 3, '2024-08-15', '17:00:00', 'Jeudi'),
(183, 11, 3, '2024-08-15', '21:00:00', 'Jeudi'),
(184, 10, 3, '2024-08-15', '11:00:00', 'Jeudi'),
(185, 9, 3, '2024-08-16', '15:00:00', 'Vendredi'),
(186, 6, 3, '2024-08-16', '17:00:00', 'Vendredi');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `paiement_id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` datetime NOT NULL,
  `methode_paiement` varchar(50) NOT NULL,
  `statut_paiement` enum('succès','échec','en_attente') NOT NULL,
  PRIMARY KEY (`paiement_id`),
  KEY `reservation_id` (`reservation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int(10) UNSIGNED NOT NULL,
  `horaire_id` int(11) NOT NULL,
  `date_reservation` datetime NOT NULL,
  `prix_total` int(11) DEFAULT NULL,
  `statut` varchar(25) DEFAULT NULL,
  `nombre_places` int(11) DEFAULT '1',
  PRIMARY KEY (`reservation_id`),
  KEY `client_id` (`client_id`),
  KEY `horaire_id` (`horaire_id`),
  KEY `idx_horaire` (`horaire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `client_id`, `horaire_id`, `date_reservation`, `prix_total`, `statut`, `nombre_places`) VALUES
(26, 11, 22, '2024-07-09 03:19:08', 5000, 'en_attente', 1),
(27, 11, 22, '2024-07-09 03:20:23', 12000, 'en_attente', 2),
(28, 11, 29, '2024-07-09 04:44:37', 7000, 'en_attente', 1),
(29, 11, 30, '2024-07-09 04:59:01', 10000, 'payÃ©e', 2),
(30, 11, 19, '2024-07-09 08:20:52', 7000, 'en_attente', 1),
(31, 11, 21, '2024-07-09 08:31:09', 21000, 'payÃ©e', 3),
(32, 11, 23, '2024-07-09 08:57:18', 12000, 'payÃ©e', 2),
(33, 11, 19, '2024-07-09 09:32:01', 14000, 'payÃ©e', 2),
(34, 1, 22, '2024-07-09 11:34:09', 5000, 'en_attente', 1),
(35, 11, 20, '2024-07-09 14:09:21', 12000, 'en_attente', 2),
(36, 11, 19, '2024-07-09 16:04:05', 10000, 'payÃ©e', 2),
(37, 12, 24, '2024-07-09 17:29:28', 5000, 'payÃ©e', 1),
(38, 11, 32, '2024-07-10 21:15:35', 12000, 'en_attente', 2),
(39, 11, 32, '2024-07-10 21:48:26', 17000, 'en_attente', 3),
(40, 11, 32, '2024-07-10 22:20:12', 20000, 'payÃ©e', 4),
(41, 11, 32, '2024-07-10 23:48:20', 30000, 'payÃ©e', 6),
(42, 11, 32, '2024-07-11 00:35:30', 35000, 'payÃ©e', 7),
(43, 11, 32, '2024-07-11 01:52:10', 25000, 'payÃ©e', 5),
(44, 11, 33, '2024-07-11 02:28:32', 10000, 'payÃ©e', 2),
(45, 13, 57, '2024-07-12 08:21:28', NULL, 'en_attente', 2),
(46, 13, 57, '2024-07-12 08:22:44', NULL, 'en_attente', 1),
(47, 13, 53, '2024-07-12 08:31:35', NULL, 'en_attente', 2),
(48, 13, 49, '2024-07-12 08:32:55', NULL, 'payée', 1),
(49, 13, 49, '2024-07-12 08:34:13', 5000, 'payée', 1),
(50, 13, 49, '2024-07-12 08:48:42', 5000, 'payée', 1),
(51, 13, 48, '2024-07-12 08:50:58', 5000, 'en_attente', 1),
(52, 13, 48, '2024-07-12 08:51:17', 25000, 'payée', 5),
(53, 13, 76, '2024-07-12 08:53:43', 5000, 'en_attente', 1),
(54, 13, 63, '2024-07-12 08:55:37', 5000, 'payée', 1),
(55, 11, 63, '2024-07-12 09:16:28', 5000, 'payée', 1),
(56, 11, 49, '2024-07-12 10:16:56', 5000, 'en_attente', 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservations_sieges`
--

DROP TABLE IF EXISTS `reservations_sieges`;
CREATE TABLE IF NOT EXISTS `reservations_sieges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) NOT NULL,
  `horaire_id` int(11) NOT NULL,
  `siege_id` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_id` (`reservation_id`),
  KEY `idx_horaire_siege` (`horaire_id`,`siege_id`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reservations_sieges`
--

INSERT INTO `reservations_sieges` (`id`, `reservation_id`, `horaire_id`, `siege_id`) VALUES
(114, 56, 49, 'J03'),
(113, 55, 63, 'D01'),
(112, 54, 63, 'B01'),
(111, 53, 76, 'E01'),
(110, 52, 48, 'B05'),
(109, 52, 48, 'B04'),
(108, 52, 48, 'B03'),
(107, 52, 48, 'B02'),
(106, 52, 48, 'B01'),
(105, 51, 48, 'G04'),
(104, 50, 49, 'B01'),
(103, 49, 49, 'A04'),
(102, 48, 49, 'I03'),
(101, 47, 53, 'J07'),
(100, 47, 53, 'J04'),
(99, 46, 57, 'I02'),
(98, 45, 57, 'C02'),
(97, 45, 57, 'A04'),
(76, 40, 32, '0'),
(77, 41, 32, '0'),
(78, 41, 32, '0'),
(79, 41, 32, '0'),
(80, 41, 32, '0'),
(81, 41, 32, '0'),
(82, 41, 32, '0'),
(83, 42, 32, '0'),
(84, 42, 32, '0'),
(85, 42, 32, '0'),
(86, 42, 32, '0'),
(87, 42, 32, '0'),
(88, 42, 32, '0'),
(89, 42, 32, '0'),
(90, 43, 32, 'G01'),
(91, 43, 32, 'G02'),
(92, 43, 32, 'G03'),
(93, 43, 32, 'G04'),
(94, 43, 32, 'G05'),
(95, 44, 33, 'B02'),
(96, 44, 33, 'B03');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

DROP TABLE IF EXISTS `salles`;
CREATE TABLE IF NOT EXISTS `salles` (
  `salle_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_salle` varchar(50) NOT NULL,
  `capacite` int(10) UNSIGNED NOT NULL,
  `type_salle` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`salle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`salle_id`, `nom_salle`, `capacite`, `type_salle`) VALUES
(3, 'Salle Principale', 150, 'Standard');

-- --------------------------------------------------------

--
-- Structure de la table `sieges`
--

DROP TABLE IF EXISTS `sieges`;
CREATE TABLE IF NOT EXISTS `sieges` (
  `siege_id` varchar(10) NOT NULL,
  `salle_id` int(11) NOT NULL,
  `rangee` varchar(2) NOT NULL,
  `numero` int(11) NOT NULL,
  `section` varchar(10) NOT NULL,
  `prix` int(11) NOT NULL,
  `position_x` int(11) DEFAULT NULL,
  `position_y` int(11) DEFAULT NULL,
  PRIMARY KEY (`siege_id`),
  KEY `salle_id` (`salle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `sieges`
--

INSERT INTO `sieges` (`siege_id`, `salle_id`, `rangee`, `numero`, `section`, `prix`, `position_x`, `position_y`) VALUES
('A03', 3, 'A', 3, 'gauche', 5000, NULL, NULL),
('A04', 3, 'A', 4, 'gauche', 5000, NULL, NULL),
('A05', 3, 'A', 5, 'gauche', 5000, NULL, NULL),
('A06', 3, 'A', 6, 'milieu', 7000, NULL, NULL),
('A07', 3, 'A', 7, 'milieu', 7000, NULL, NULL),
('A08', 3, 'A', 8, 'milieu', 7000, NULL, NULL),
('A09', 3, 'A', 9, 'milieu', 7000, NULL, NULL),
('A10', 3, 'A', 10, 'milieu', 7000, NULL, NULL),
('A11', 3, 'A', 11, 'droite', 5000, NULL, NULL),
('A12', 3, 'A', 12, 'droite', 5000, NULL, NULL),
('A13', 3, 'A', 13, 'droite', 5000, NULL, NULL),
('A14', 3, 'A', 14, 'droite', 5000, NULL, NULL),
('A15', 3, 'A', 15, 'droite', 5000, NULL, NULL),
('B01', 3, 'B', 1, 'gauche', 5000, NULL, NULL),
('B02', 3, 'B', 2, 'gauche', 5000, NULL, NULL),
('B03', 3, 'B', 3, 'gauche', 5000, NULL, NULL),
('B04', 3, 'B', 4, 'gauche', 5000, NULL, NULL),
('B05', 3, 'B', 5, 'gauche', 5000, NULL, NULL),
('B06', 3, 'B', 6, 'milieu', 7000, NULL, NULL),
('B07', 3, 'B', 7, 'milieu', 7000, NULL, NULL),
('B08', 3, 'B', 8, 'milieu', 7000, NULL, NULL),
('B09', 3, 'B', 9, 'milieu', 7000, NULL, NULL),
('B10', 3, 'B', 10, 'milieu', 7000, NULL, NULL),
('B11', 3, 'B', 11, 'droite', 5000, NULL, NULL),
('B12', 3, 'B', 12, 'droite', 5000, NULL, NULL),
('B13', 3, 'B', 13, 'droite', 5000, NULL, NULL),
('B14', 3, 'B', 14, 'droite', 5000, NULL, NULL),
('B15', 3, 'B', 15, 'droite', 5000, NULL, NULL),
('C01', 3, 'C', 1, 'gauche', 5000, NULL, NULL),
('C02', 3, 'C', 2, 'gauche', 5000, NULL, NULL),
('C03', 3, 'C', 3, 'gauche', 5000, NULL, NULL),
('C04', 3, 'C', 4, 'gauche', 5000, NULL, NULL),
('C05', 3, 'C', 5, 'gauche', 5000, NULL, NULL),
('C06', 3, 'C', 6, 'milieu', 7000, NULL, NULL),
('C07', 3, 'C', 7, 'milieu', 7000, NULL, NULL),
('C08', 3, 'C', 8, 'milieu', 7000, NULL, NULL),
('C09', 3, 'C', 9, 'milieu', 7000, NULL, NULL),
('C10', 3, 'C', 10, 'milieu', 7000, NULL, NULL),
('C11', 3, 'C', 11, 'droite', 5000, NULL, NULL),
('C12', 3, 'C', 12, 'droite', 5000, NULL, NULL),
('C13', 3, 'C', 13, 'droite', 5000, NULL, NULL),
('C14', 3, 'C', 14, 'droite', 5000, NULL, NULL),
('C15', 3, 'C', 15, 'droite', 5000, NULL, NULL),
('D01', 3, 'D', 1, 'gauche', 5000, NULL, NULL),
('D02', 3, 'D', 2, 'gauche', 5000, NULL, NULL),
('D03', 3, 'D', 3, 'gauche', 5000, NULL, NULL),
('D04', 3, 'D', 4, 'gauche', 5000, NULL, NULL),
('D05', 3, 'D', 5, 'gauche', 5000, NULL, NULL),
('D06', 3, 'D', 6, 'milieu', 7000, NULL, NULL),
('D07', 3, 'D', 7, 'milieu', 7000, NULL, NULL),
('D08', 3, 'D', 8, 'milieu', 7000, NULL, NULL),
('D09', 3, 'D', 9, 'milieu', 7000, NULL, NULL),
('D10', 3, 'D', 10, 'milieu', 7000, NULL, NULL),
('D11', 3, 'D', 11, 'droite', 5000, NULL, NULL),
('D12', 3, 'D', 12, 'droite', 5000, NULL, NULL),
('D13', 3, 'D', 13, 'droite', 5000, NULL, NULL),
('D14', 3, 'D', 14, 'droite', 5000, NULL, NULL),
('D15', 3, 'D', 15, 'droite', 5000, NULL, NULL),
('E01', 3, 'E', 1, 'gauche', 5000, NULL, NULL),
('E02', 3, 'E', 2, 'gauche', 5000, NULL, NULL),
('E03', 3, 'E', 3, 'gauche', 5000, NULL, NULL),
('E04', 3, 'E', 4, 'gauche', 5000, NULL, NULL),
('E05', 3, 'E', 5, 'gauche', 5000, NULL, NULL),
('E06', 3, 'E', 6, 'milieu', 7000, NULL, NULL),
('E07', 3, 'E', 7, 'milieu', 7000, NULL, NULL),
('E08', 3, 'E', 8, 'milieu', 7000, NULL, NULL),
('E09', 3, 'E', 9, 'milieu', 7000, NULL, NULL),
('E10', 3, 'E', 10, 'milieu', 7000, NULL, NULL),
('E11', 3, 'E', 11, 'droite', 5000, NULL, NULL),
('E12', 3, 'E', 12, 'droite', 5000, NULL, NULL),
('E13', 3, 'E', 13, 'droite', 5000, NULL, NULL),
('E14', 3, 'E', 14, 'droite', 5000, NULL, NULL),
('E15', 3, 'E', 15, 'droite', 5000, NULL, NULL),
('F01', 3, 'F', 1, 'gauche', 5000, NULL, NULL),
('F02', 3, 'F', 2, 'gauche', 5000, NULL, NULL),
('F03', 3, 'F', 3, 'gauche', 5000, NULL, NULL),
('F04', 3, 'F', 4, 'gauche', 5000, NULL, NULL),
('F05', 3, 'F', 5, 'gauche', 5000, NULL, NULL),
('F06', 3, 'F', 6, 'milieu', 7000, NULL, NULL),
('F07', 3, 'F', 7, 'milieu', 7000, NULL, NULL),
('F08', 3, 'F', 8, 'milieu', 7000, NULL, NULL),
('F09', 3, 'F', 9, 'milieu', 7000, NULL, NULL),
('F10', 3, 'F', 10, 'milieu', 7000, NULL, NULL),
('F11', 3, 'F', 11, 'droite', 5000, NULL, NULL),
('F12', 3, 'F', 12, 'droite', 5000, NULL, NULL),
('F13', 3, 'F', 13, 'droite', 5000, NULL, NULL),
('F14', 3, 'F', 14, 'droite', 5000, NULL, NULL),
('F15', 3, 'F', 15, 'droite', 5000, NULL, NULL),
('G01', 3, 'G', 1, 'gauche', 5000, NULL, NULL),
('G02', 3, 'G', 2, 'gauche', 5000, NULL, NULL),
('G03', 3, 'G', 3, 'gauche', 5000, NULL, NULL),
('G04', 3, 'G', 4, 'gauche', 5000, NULL, NULL),
('G05', 3, 'G', 5, 'gauche', 5000, NULL, NULL),
('G06', 3, 'G', 6, 'milieu', 7000, NULL, NULL),
('G07', 3, 'G', 7, 'milieu', 7000, NULL, NULL),
('G08', 3, 'G', 8, 'milieu', 7000, NULL, NULL),
('G09', 3, 'G', 9, 'milieu', 7000, NULL, NULL),
('G10', 3, 'G', 10, 'milieu', 7000, NULL, NULL),
('G11', 3, 'G', 11, 'droite', 5000, NULL, NULL),
('G12', 3, 'G', 12, 'droite', 5000, NULL, NULL),
('G13', 3, 'G', 13, 'droite', 5000, NULL, NULL),
('G14', 3, 'G', 14, 'droite', 5000, NULL, NULL),
('G15', 3, 'G', 15, 'droite', 5000, NULL, NULL),
('H01', 3, 'H', 1, 'gauche', 5000, NULL, NULL),
('H02', 3, 'H', 2, 'gauche', 5000, NULL, NULL),
('H03', 3, 'H', 3, 'gauche', 5000, NULL, NULL),
('H04', 3, 'H', 4, 'gauche', 5000, NULL, NULL),
('H05', 3, 'H', 5, 'gauche', 5000, NULL, NULL),
('H06', 3, 'H', 6, 'milieu', 7000, NULL, NULL),
('H07', 3, 'H', 7, 'milieu', 7000, NULL, NULL),
('H08', 3, 'H', 8, 'milieu', 7000, NULL, NULL),
('H09', 3, 'H', 9, 'milieu', 7000, NULL, NULL),
('H10', 3, 'H', 10, 'milieu', 7000, NULL, NULL),
('H11', 3, 'H', 11, 'droite', 5000, NULL, NULL),
('H12', 3, 'H', 12, 'droite', 5000, NULL, NULL),
('H13', 3, 'H', 13, 'droite', 5000, NULL, NULL),
('H14', 3, 'H', 14, 'droite', 5000, NULL, NULL),
('H15', 3, 'H', 15, 'droite', 5000, NULL, NULL),
('I01', 3, 'I', 1, 'gauche', 5000, NULL, NULL),
('I02', 3, 'I', 2, 'gauche', 5000, NULL, NULL),
('I03', 3, 'I', 3, 'gauche', 5000, NULL, NULL),
('I04', 3, 'I', 4, 'gauche', 5000, NULL, NULL),
('I05', 3, 'I', 5, 'gauche', 5000, NULL, NULL),
('I06', 3, 'I', 6, 'milieu', 7000, NULL, NULL),
('I07', 3, 'I', 7, 'milieu', 7000, NULL, NULL),
('I08', 3, 'I', 8, 'milieu', 7000, NULL, NULL),
('I09', 3, 'I', 9, 'milieu', 7000, NULL, NULL),
('I10', 3, 'I', 10, 'milieu', 7000, NULL, NULL),
('I11', 3, 'I', 11, 'droite', 5000, NULL, NULL),
('I12', 3, 'I', 12, 'droite', 5000, NULL, NULL),
('I13', 3, 'I', 13, 'droite', 5000, NULL, NULL),
('I14', 3, 'I', 14, 'droite', 5000, NULL, NULL),
('I15', 3, 'I', 15, 'droite', 5000, NULL, NULL),
('J01', 3, 'J', 1, 'gauche', 5000, NULL, NULL),
('J02', 3, 'J', 2, 'gauche', 5000, NULL, NULL),
('J03', 3, 'J', 3, 'gauche', 5000, NULL, NULL),
('J04', 3, 'J', 4, 'gauche', 5000, NULL, NULL),
('J05', 3, 'J', 5, 'gauche', 5000, NULL, NULL),
('J06', 3, 'J', 6, 'milieu', 7000, NULL, NULL),
('J07', 3, 'J', 7, 'milieu', 7000, NULL, NULL),
('J08', 3, 'J', 8, 'milieu', 7000, NULL, NULL),
('J09', 3, 'J', 9, 'milieu', 7000, NULL, NULL),
('J10', 3, 'J', 10, 'milieu', 7000, NULL, NULL),
('J11', 3, 'J', 11, 'droite', 5000, NULL, NULL),
('J12', 3, 'J', 12, 'droite', 5000, NULL, NULL),
('J13', 3, 'J', 13, 'droite', 5000, NULL, NULL),
('J14', 3, 'J', 14, 'droite', 5000, NULL, NULL),
('J15', 3, 'J', 15, 'droite', 5000, NULL, NULL),
('A01', 0, 'A', 1, 'gauche', 5000, 4, 0),
('A02', 0, 'A', 2, 'gauche', 5000, 38, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
