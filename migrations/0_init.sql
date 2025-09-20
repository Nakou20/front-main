-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : lun. 01 sep. 2025 à 11:10
-- Version du serveur : 8.4.1
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ap3_2025_2026`
--

-- --------------------------------------------------------

--
-- Structure de la table `conduire`
--

CREATE TABLE `conduire` (
  `ideleve` int NOT NULL,
  `idvehicule` int NOT NULL,
  `idmoniteur` int NOT NULL,
  `heuredebut` datetime NOT NULL,
  `lieurdv` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `conduire`
--

INSERT INTO `conduire` (`ideleve`, `idvehicule`, `idmoniteur`, `heuredebut`, `lieurdv`) VALUES
(1, 1, 1, '2025-06-16 12:00:00', NULL),
(1, 1, 1, '2025-06-18 08:00:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

CREATE TABLE `eleve` (
  `ideleve` int NOT NULL,
  `nomeleve` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `prenomeleve` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `emaileleve` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `motpasseeleve` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `datenaissanceeleve` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `eleve`
--

INSERT INTO `eleve` (`ideleve`, `nomeleve`, `prenomeleve`, `emaileleve`, `motpasseeleve`, `datenaissanceeleve`) VALUES
(1, 'Guilbert', 'Valentin', 'vguilbert@proton.me', 'testtest', '1987-02-28'),
(2, 'Hermann', 'Vincent', 'vincehermann@gmail.com', 'azerty', '1982-06-24'),
(3, 'John', 'Doe', 'jdoe@hotmail.fr', 'testtest', '1982-11-25'),
(4, 'Assani', 'Julie', 'Julie.Assani-49@gmail.com', 'julie', '2007-04-01');

-- --------------------------------------------------------

--
-- Structure de la table `forfait`
--

CREATE TABLE `forfait` (
  `idforfait` int NOT NULL,
  `libelleforfait` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `descriptionforfait` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `contenuforfait` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `prixforfait` decimal(10,2) DEFAULT NULL,
  `nbheures` bigint DEFAULT NULL,
  `prixhoraire` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `forfait`
--

INSERT INTO `forfait` (`idforfait`, `libelleforfait`, `descriptionforfait`, `contenuforfait`, `prixforfait`, `nbheures`, `prixhoraire`) VALUES
(1, 'Forfait 20 Heures', 'Idéal pour commencer votre apprentissage de la conduite avec une base solide.', 'Cours de code illimités;20 heures de conduite;Accompagnement examen code;Accompagnement examen conduite', 1200.00, 20, NULL),
(2, 'Forfait 25 Heures', 'Plus d\'heures pour plus de confiance et une meilleure préparation à l\'examen.', 'Cours de code illimités;25 heures de conduite;Accompagnement examen code;Accompagnement examen conduite;1 Rendez-vous pédagogique inclus', 1450.00, 25, NULL),
(3, 'Leçon de Perfectionnement', 'Pour ceux qui ont déjà le code et souhaitent reprendre confiance ou améliorer leur conduite.', 'Conduite en ville;Manœuvres spécifiques;Conduite sur autoroute;Adapté à vos besoins', 45.00, 1, 45.00);

-- --------------------------------------------------------

--
-- Structure de la table `inscrire`
--

CREATE TABLE `inscrire` (
  `ideleve` int NOT NULL,
  `idforfait` int NOT NULL,
  `dateinscription` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `inscrire`
--

INSERT INTO `inscrire` (`ideleve`, `idforfait`, `dateinscription`) VALUES
(1, 2, '2025-06-14'),
(1, 3, '2025-08-20'),
(1, 3, '2025-09-01'),
(2, 3, '2025-09-01'),
(2, 3, '2025-09-02'),
(3, 2, '2025-07-01');

-- --------------------------------------------------------

--
-- Structure de la table `moniteur`
--

CREATE TABLE `moniteur` (
  `idmoniteur` int NOT NULL,
  `nommoniteur` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `prenommoniteur` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `emailmoniteur` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `moniteur`
--

INSERT INTO `moniteur` (`idmoniteur`, `nommoniteur`, `prenommoniteur`, `emailmoniteur`) VALUES
(1, 'John', 'Doe', 'jdoe@proton.me'),
(2, 'Sarrazin', 'Paul', 'paul.Sarr4@orange.fr'),
(3, 'Manin', 'Lydie', 'lmanin@gmail.com'),
(4, 'Duperray', 'Valentina', 'dv1999@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE `question` (
  `idquestion` int NOT NULL,
  `libellequestion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `imagequestion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `question`
--

INSERT INTO `question` (`idquestion`, `libellequestion`, `imagequestion`) VALUES
(41, 'Ce panneau annonce :', 'image41.jpg'),
(42, 'Je peux dépasser le véhicule blanc par la droite.', 'image42.jpg'),
(43, 'En cas de contrôle par les forces de l\'ordre, je dois présenter :', 'image43.jpg'),
(44, 'Sur autoroute, la bande d\'arrêt d\'urgence est réservée :', 'image44.jpg'),
(45, 'Ce panneau m\'indique :', 'image45.jpg'),
(46, 'Dans cette intersection sans signalisation, je dois céder le passage :', 'image46.jpg'),
(47, 'L\'utilisation du téléphone tenu en main en conduisant est sanctionnée par :', 'image47.jpg'),
(48, 'La distance de sécurité à 90 km/h sur sol sec est d\'environ :', 'image48.jpg'),
(49, 'Ce voyant rouge signifie :', 'image49.jpg'),
(50, 'Le stationnement est considéré comme abusif après une durée ininterrompue de :', 'image50.jpg'),
(51, 'Ce signal lumineux m\'oblige à :', 'image51.jpg'),
(52, 'Un oubli du clignotant est passible :', 'image52.jpg'),
(53, 'En présence d\'un animal sur la chaussée, mon premier réflexe est de :', 'image53.jpg'),
(54, 'Le marquage au sol de couleur bleue indique :', 'image54.jpg'),
(55, 'Sur une route pour automobiles, la vitesse maximale autorisée est de :', 'image55.jpg'),
(56, 'Ce panneau interdit l\'accès :', 'image56.jpg'),
(57, 'Le \"régulateur de vitesse adaptatif\" permet de :', 'image57.jpg'),
(58, 'Circuler sur une voie réservée aux bus est :', 'image58.jpg'),
(59, 'En cas de crevaison sur autoroute, je dois :', 'image59.jpg'),
(60, 'Le feu est vert, mais l\'intersection est encombrée. Je dois :', 'image60.jpg'),
(61, 'Ce panneau indique :', 'image61.jpg'),
(62, 'La conduite accompagnée (AAC) est possible à partir de :', 'image62.jpg'),
(63, 'Par temps de brouillard épais, je peux utiliser :', 'image63.jpg'),
(64, 'La pression des pneus doit être vérifiée :', 'image64.jpg'),
(65, 'Ce panneau met fin à :', 'image65.jpg'),
(66, 'Je m\'apprête à dépasser un cycliste. Je klaxonne pour l\'avertir.', 'image66.jpg'),
(67, 'En agglomération, de nuit, sur une chaussée non éclairée, j\'utilise :', 'image67.jpg'),
(68, 'La durée de la période probatoire pour un conducteur ayant suivi la formation traditionnelle est de :', 'image68.jpg'),
(69, 'Ce panneau signale :', 'image69.jpg'),
(70, 'Un \"aquaplaning\" (ou aquaplanage) se produit lorsque :', 'image70.jpg'),
(71, 'La signalisation m\'autorise à faire demi-tour.', 'image71.jpg'),
(72, 'Le taux légal d\'alcoolémie à ne pas atteindre est de 0,5 g/L de sang, ce qui équivaut à :', 'image72.jpg'),
(73, 'Ce panneau indique que le stationnement est :', 'image73.jpg'),
(74, 'Lors d\'un contrôle technique, une défaillance \"critique\" entraîne :', 'image74.jpg'),
(75, 'Je tracte une caravane dont le PTAC est de 800 kg. Mon permis B est suffisant.', 'image75.jpg'),
(76, 'Cette balise de virage est de couleur :', 'image76.jpg'),
(77, 'Refuser une priorité est sanctionné par :', 'image77.jpg'),
(78, 'Je sors d\'un chemin de terre. Je dois céder le passage.', 'image78.jpg'),
(79, 'Ce panneau signifie :', 'image79.jpg'),
(80, 'Pour limiter la pollution, je peux :', 'image80.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE `reponse` (
  `idquestion` int NOT NULL,
  `numreponse` int NOT NULL,
  `libellereponse` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `valide` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `reponse`
--

INSERT INTO `reponse` (`idquestion`, `numreponse`, `libellereponse`, `valide`) VALUES
(41, 1, 'Une succession de virages dont le premier est à droite.', 1),
(41, 2, 'Une chaussée particulièrement glissante.', 0),
(41, 3, 'Une interdiction de tourner à droite.', 0),
(42, 1, 'Oui, car il est sur la voie de gauche.', 0),
(42, 2, 'Non, le dépassement par la droite est interdit.', 1),
(43, 1, 'Le permis de conduire.', 1),
(43, 2, 'Le certificat d\'immatriculation (carte grise).', 1),
(43, 3, 'L\'attestation d\'assurance.', 1),
(44, 1, 'Aux véhicules lents.', 0),
(44, 2, 'À l\'arrêt ou au stationnement.', 0),
(44, 3, 'À l\'arrêt en cas de panne ou de malaise.', 1),
(45, 1, 'L\'entrée dans une zone où la vitesse est limitée à 30 km/h.', 1),
(45, 2, 'Une aire piétonne.', 0),
(45, 3, 'Une route prioritaire.', 0),
(46, 1, 'Aux véhicules venant de ma droite.', 1),
(46, 2, 'Aux véhicules venant de ma gauche.', 0),
(46, 3, 'À tous les véhicules.', 0),
(47, 1, 'Un retrait de 3 points sur le permis.', 1),
(47, 2, 'Une amende forfaitaire de 135 €.', 1),
(47, 3, 'Une simple amende de 35 €.', 0),
(48, 1, '25 mètres.', 0),
(48, 2, '50 mètres.', 1),
(48, 3, '90 mètres.', 0),
(49, 1, 'Une surchauffe du moteur.', 1),
(49, 2, 'Une pression d\'huile insuffisante.', 0),
(49, 3, 'Une batterie déchargée.', 0),
(50, 1, '24 heures.', 0),
(50, 2, '48 heures.', 0),
(50, 3, '7 jours.', 1),
(51, 1, 'M\'arrêter, sauf si mon arrêt crée un danger.', 1),
(51, 2, 'Accélérer pour passer avant le rouge.', 0),
(51, 3, 'Ralentir et passer avec prudence.', 0),
(52, 1, 'D\'un simple avertissement.', 0),
(52, 2, 'D\'une amende et d\'un retrait de 3 points.', 1),
(52, 3, 'D\'une suspension de permis.', 0),
(53, 1, 'Ralentir et me préparer à m\'arrêter.', 1),
(53, 2, 'Klaxonner fortement.', 0),
(53, 3, 'Faire un écart brusque.', 0),
(54, 1, 'Une zone de stationnement à durée limitée (zone bleue).', 1),
(54, 2, 'Une voie réservée aux vélos.', 0),
(54, 3, 'Un emplacement pour personnes handicapées.', 0),
(55, 1, '90 km/h.', 0),
(55, 2, '110 km/h.', 1),
(55, 3, '130 km/h.', 0),
(56, 1, 'Aux motocyclettes.', 1),
(56, 2, 'Aux piétons.', 0),
(56, 3, 'Aux cyclomoteurs.', 1),
(57, 1, 'Maintenir une vitesse constante.', 0),
(57, 2, 'Maintenir une distance de sécurité avec le véhicule qui précède.', 1),
(57, 3, 'Freiner automatiquement en cas d\'obstacle.', 1),
(58, 1, 'Autorisé si je dois tourner à droite.', 0),
(58, 2, 'Interdit et sanctionné par une amende.', 1),
(58, 3, 'Toléré en cas de fort trafic.', 0),
(59, 1, 'Mettre mes passagers en sécurité derrière la glissière.', 1),
(59, 2, 'Changer la roue sur la bande d\'arrêt d\'urgence.', 0),
(59, 3, 'Appeler les secours depuis la borne d\'appel d\'urgence.', 1),
(60, 1, 'Passer lentement.', 0),
(60, 2, 'M\'arrêter avant l\'intersection et attendre qu\'elle se libère.', 1),
(60, 3, 'Klaxonner pour que les autres avancent.', 0),
(61, 1, 'Une obligation d\'aller tout droit à la prochaine intersection.', 1),
(61, 2, 'Une route à sens unique.', 0),
(61, 3, 'Une direction conseillée.', 0),
(62, 1, '15 ans.', 1),
(62, 2, '16 ans.', 0),
(62, 3, '17 ans.', 0),
(63, 1, 'Les feux de croisement.', 1),
(63, 2, 'Les feux de brouillard avant.', 1),
(63, 3, 'Les feux de brouillard arrière.', 1),
(64, 1, 'Une fois par an.', 0),
(64, 2, 'Une fois par mois et avant chaque long trajet.', 1),
(64, 3, 'Uniquement lorsque le voyant s\'allume.', 0),
(65, 1, 'À toutes les interdictions précédemment signalées.', 1),
(65, 2, 'Uniquement à l\'interdiction de dépasser.', 0),
(65, 3, 'Uniquement à la limitation de vitesse.', 0),
(66, 1, 'Oui, c\'est plus prudent.', 0),
(66, 2, 'Non, l\'usage du klaxon est interdit en agglomération sauf danger immédiat.', 1),
(67, 1, 'Les feux de position seuls.', 0),
(67, 2, 'Les feux de croisement.', 1),
(67, 3, 'Les feux de route, si je ne gêne personne.', 1),
(68, 1, '2 ans.', 0),
(68, 2, '3 ans.', 1),
(69, 1, 'Un passage à niveau avec barrières.', 1),
(69, 2, 'Un passage à niveau sans barrières.', 0),
(69, 3, 'Un pont mobile.', 0),
(70, 1, 'Les pneus n\'adhèrent plus à la route à cause d\'une pellicule d\'eau.', 1),
(70, 2, 'Le moteur surchauffe à cause de la pluie.', 0),
(70, 3, 'Les freins sont moins efficaces à cause de l\'eau.', 0),
(71, 1, 'Oui, la ligne discontinue me le permet.', 0),
(71, 2, 'Non, la flèche de rabattement m\'en empêche.', 1),
(72, 1, '0,25 mg par litre d\'air expiré.', 1),
(72, 2, '0,50 mg par litre d\'air expiré.', 0),
(72, 3, '0,80 mg par litre d\'air expiré.', 0),
(73, 1, 'Payant.', 1),
(73, 2, 'Contrôlé par disque.', 0),
(73, 3, 'Gratuit.', 0),
(74, 1, 'Une obligation de réparation sous 2 mois.', 0),
(74, 2, 'Une immobilisation du véhicule le jour même à minuit.', 1),
(74, 3, 'Une simple mention sur le certificat d\'immatriculation.', 0),
(75, 1, 'Oui, si la somme des PTAC (voiture + caravane) ne dépasse pas 3500 kg.', 1),
(75, 2, 'Non, il faut le permis BE dans tous les cas.', 0),
(76, 1, 'Bleue et blanche.', 1),
(76, 2, 'Rouge et blanche.', 0),
(76, 3, 'Verte et blanche.', 0),
(77, 1, 'Une amende de 135 €.', 1),
(77, 2, 'Un retrait de 4 points.', 1),
(77, 3, 'Une peine de suspension du permis.', 1),
(78, 1, 'Oui, à tous les usagers.', 1),
(78, 2, 'Non, la priorité à droite s\'applique.', 0),
(79, 1, 'Débouché sur un quai ou une berge.', 1),
(79, 2, 'Risque de chute de pierres.', 0),
(79, 3, 'Pont mobile.', 0),
(80, 1, 'Adopter une conduite souple.', 1),
(80, 2, 'Vérifier régulièrement la pression des pneus.', 1),
(80, 3, 'Utiliser le régulateur de vitesse.', 1);

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

CREATE TABLE `resultat` (
  `idresultat` int NOT NULL,
  `ideleve` int NOT NULL,
  `dateresultat` datetime NOT NULL,
  `score` bigint NOT NULL,
  `nbquestions` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `resultat`
--

INSERT INTO `resultat` (`idresultat`, `ideleve`, `dateresultat`, `score`, `nbquestions`) VALUES
(4, 1, '2025-06-15 20:18:08', 1, 2),
(5, 1, '2025-06-27 13:16:58', 7, 10),
(6, 1, '2025-06-27 13:17:39', 7, 10),
(7, 1, '2025-06-27 13:18:39', 8, 15),
(8, 1, '2025-06-27 13:25:24', 35, 40),
(9, 1, '2025-06-27 13:26:29', 35, 40),
(10, 1, '2025-06-27 13:26:54', 8, 15),
(11, 1, '2025-06-27 13:47:44', 35, 40),
(12, 1, '2025-06-27 13:48:51', 35, 40),
(13, 1, '2025-06-27 13:49:02', 35, 40000),
(14, 1, '2025-06-27 13:56:34', 35, 40000),
(15, 1, '2025-06-27 14:16:21', 35, 40000),
(16, 1, '2025-06-27 14:27:20', 35, 40000),
(17, 1, '2025-06-27 14:27:24', 35, 40000);

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

CREATE TABLE `token` (
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ideleve` int NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `token`
--

INSERT INTO `token` (`token`, `ideleve`, `date_creation`) VALUES
('48c00656842c4418493e25e932fc1a49', 1, '2025-06-27 13:12:26'),
('7bd0d79a6f8fad6089ce7b7ceb25f575', 1, '2025-06-27 13:25:04'),
('a14e34a9b942cbbb854f647df284b7db', 2, '2025-08-29 13:00:29'),
('a913c8310535b02188c418ef0cb8576d', 1, '2025-06-14 20:20:39'),
('f6be5aab806d8beee07f5ce894bf886b', 1, '2025-06-27 13:11:50');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

CREATE TABLE `vehicule` (
  `idvehicule` int NOT NULL,
  `nbpassagers` int DEFAULT NULL,
  `immatriculation` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `designation` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `manuel` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`idvehicule`, `nbpassagers`, `immatriculation`, `designation`, `manuel`) VALUES
(1, 5, 'FA-828-NE', 'Tesla', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `conduire`
--
ALTER TABLE `conduire`
  ADD PRIMARY KEY (`ideleve`,`idvehicule`,`idmoniteur`,`heuredebut`) USING BTREE,
  ADD KEY `i_fk_conduire_eleve1` (`ideleve`),
  ADD KEY `i_fk_conduire_vehicule1` (`idvehicule`),
  ADD KEY `i_fk_conduire_moniteur1` (`idmoniteur`);

--
-- Index pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`ideleve`);

--
-- Index pour la table `forfait`
--
ALTER TABLE `forfait`
  ADD PRIMARY KEY (`idforfait`);

--
-- Index pour la table `inscrire`
--
ALTER TABLE `inscrire`
  ADD PRIMARY KEY (`ideleve`,`idforfait`,`dateinscription`),
  ADD KEY `i_fk_inscrire_eleve1` (`ideleve`),
  ADD KEY `i_fk_inscrire_forfait1` (`idforfait`);

--
-- Index pour la table `moniteur`
--
ALTER TABLE `moniteur`
  ADD PRIMARY KEY (`idmoniteur`);

--
-- Index pour la table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`idquestion`);

--
-- Index pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD PRIMARY KEY (`idquestion`,`numreponse`),
  ADD KEY `i_fk_reponse_question1` (`idquestion`);

--
-- Index pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD PRIMARY KEY (`idresultat`),
  ADD KEY `i_fk_resultat_eleve1` (`ideleve`);

--
-- Index pour la table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `token_ibfk_1` (`ideleve`);

--
-- Index pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`idvehicule`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `eleve`
--
ALTER TABLE `eleve`
  MODIFY `ideleve` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `forfait`
--
ALTER TABLE `forfait`
  MODIFY `idforfait` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `moniteur`
--
ALTER TABLE `moniteur`
  MODIFY `idmoniteur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `question`
--
ALTER TABLE `question`
  MODIFY `idquestion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT pour la table `resultat`
--
ALTER TABLE `resultat`
  MODIFY `idresultat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `idvehicule` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `conduire`
--
ALTER TABLE `conduire`
  ADD CONSTRAINT `conduire_ibfk_1` FOREIGN KEY (`ideleve`) REFERENCES `eleve` (`ideleve`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `conduire_ibfk_2` FOREIGN KEY (`idvehicule`) REFERENCES `vehicule` (`idvehicule`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `conduire_ibfk_3` FOREIGN KEY (`idmoniteur`) REFERENCES `moniteur` (`idmoniteur`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `inscrire`
--
ALTER TABLE `inscrire`
  ADD CONSTRAINT `inscrire_ibfk_1` FOREIGN KEY (`ideleve`) REFERENCES `eleve` (`ideleve`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inscrire_ibfk_2` FOREIGN KEY (`idforfait`) REFERENCES `forfait` (`idforfait`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`idquestion`) REFERENCES `question` (`idquestion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD CONSTRAINT `resultat_ibfk_1` FOREIGN KEY (`ideleve`) REFERENCES `eleve` (`ideleve`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`ideleve`) REFERENCES `eleve` (`ideleve`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
