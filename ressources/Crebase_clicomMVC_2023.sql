-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: clicommvc
-- ------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP DATABASE if exists clicomMVC;
create database if not exists clicomMVC;
--
-- Table structure for table `client`
--
Use clicommvc;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4*/;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titreCli` char(12) NOT NULL DEFAULT 'Monsieur',
  `nomCli` varchar(30) NOT NULL,
  `prenomCli` varchar(30) NOT NULL,
  `adresseRue1Cli` varchar(40) NOT NULL,
  `adresseRue2Cli` varchar(40) DEFAULT NULL,
  `cpCli` char(6) NOT NULL,
  `villeCli` varchar(30) NOT NULL,
  `telCli` char(14) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` int(11) NOT NULL,
  `dateCde` date NOT NULL,
  `noFacture` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_COMMANDE_PASSER_CLIENT` (`idClient`),
  CONSTRAINT `FK_COMMANDE_CLIENT` FOREIGN KEY (`idClient`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98769 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` char(20) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `dateCreation` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

 