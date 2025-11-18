-- Migration : Création de la table demandes pour gérer les demandes d'heures supplémentaires

CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ideleve` int NOT NULL,
  `type_demande` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT 'heures_supplementaires',
  `commentaire` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin,
  `statut` enum('en_attente','validee','refusee') CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT 'en_attente',
  `date_creation` datetime NOT NULL,
  `date_traitement` datetime DEFAULT NULL,
  `traite_par` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ideleve` (`ideleve`),
  KEY `idx_statut` (`statut`),
  KEY `idx_traite_par` (`traite_par`),
  CONSTRAINT `fk_demandes_eleve` FOREIGN KEY (`ideleve`) REFERENCES `eleve` (`ideleve`) ON DELETE CASCADE,
  CONSTRAINT `fk_demandes_moniteur` FOREIGN KEY (`traite_par`) REFERENCES `moniteur` (`idmoniteur`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

