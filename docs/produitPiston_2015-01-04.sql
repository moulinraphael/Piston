# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Hôte: 127.0.0.1 (MySQL 5.5.38)
# Base de données: produitPiston
# Temps de génération: 2015-01-04 03:11:50 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table adherents
# ------------------------------------------------------------

DROP TABLE IF EXISTS `adherents`;

CREATE TABLE `adherents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_saison` int(11) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `caution` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table emprunts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `emprunts`;

CREATE TABLE `emprunts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_saison` int(11) DEFAULT NULL,
  `date_emprunt` int(11) DEFAULT NULL,
  `id_adherent` int(11) DEFAULT NULL,
  `id_materiel` int(11) DEFAULT NULL,
  `id_vendeur` int(11) DEFAULT NULL,
  `duree` varchar(255) DEFAULT NULL,
  `batons` tinyint(1) DEFAULT NULL,
  `etat_debut` varchar(255) DEFAULT NULL,
  `date_retour` int(11) DEFAULT NULL,
  `etat_retour` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table materiels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `materiels`;

CREATE TABLE `materiels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_saison` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `description` text,
  `etat_initial` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table reparations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reparations`;

CREATE TABLE `reparations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_emprunt` int(11) DEFAULT NULL,
  `date_reparation` int(11) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `rembourse` tinyint(1) DEFAULT NULL,
  `repare` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table saisons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `saisons`;

CREATE TABLE `saisons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table tarifs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tarifs`;

CREATE TABLE `tarifs` (
  `id_saison` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `duree` varchar(255) DEFAULT NULL,
  `tarif` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table vendeurs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vendeurs`;

CREATE TABLE `vendeurs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_saison` int(11) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
