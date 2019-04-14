-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bankdb
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `bankdb`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `bankdb` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `bankdb`;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `UserID` varchar(7) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `FirstName` varchar(22) NOT NULL,
  `LastName` varchar(22) NOT NULL,
  `Balance` float NOT NULL DEFAULT '0',
  `Currency` varchar(3) NOT NULL DEFAULT 'EUR',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UserID_UNIQUE` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'0000001','123456','Eimantas','Klimavicius',1000,'EUR'),(2,'0000002','123456','Vardenis','Pavardenis',50,'EUR'),(3,'0000003','123456','Ponas','Jonas',2500,'EUR');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Receiver` varchar(50) NOT NULL,
  `Currency` varchar(3) NOT NULL,
  `Amount` float NOT NULL,
  `Purpose` varchar(100) NOT NULL,
  `LastChanged` date NOT NULL,
  `PaymentStatus` varchar(10) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`accounts_id`),
  KEY `fk_requests_accounts_idx` (`accounts_id`),
  CONSTRAINT `fk_requests_accounts` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Current Database: `printbase`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `printbase` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `printbase`;

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `Brand` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Brand_UNIQUE` (`Brand`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
INSERT INTO `brand` VALUES (1,'BIQU'),(6,'Createbot'),(3,'joy2buy'),(2,'KINGROON'),(0,'Malyan'),(4,'PEI'),(5,'Tronxy');
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `imagePath` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `imagePath_UNIQUE` (`imagePath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (0,'Heating Blocks','images/heating_block.jpg'),(1,'Coolers','images/cooler.jpg'),(2,'Power Supply','images/power_supply.jpg'),(3,'Filament','images/filament.jpg'),(4,'Motors','images/motor.jpg'),(5,'Sheets','images/sheet.jpg'),(6,'Extruders','images/extruder.jpg'),(7,'Main Boards','images/main_board.jpg'),(8,'Rites','images/rite.jpg');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(22) NOT NULL,
  `lastName` varchar(22) NOT NULL,
  `phoneNumber` decimal(22,0) NOT NULL,
  `city` varchar(22) DEFAULT NULL,
  `address` varchar(45) NOT NULL,
  `postalCode` decimal(7,0) NOT NULL,
  `country` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `orderDate` date NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `color` (
  `id` int(11) NOT NULL,
  `Color` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `Color_UNIQUE` (`Color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color`
--

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (3,'Black'),(2,'Blue'),(0,'Metalic'),(1,'RGB');
/*!40000 ALTER TABLE `color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `component`
--

DROP TABLE IF EXISTS `component`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `component` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Name` varchar(45) NOT NULL,
  `imagePath` varchar(45) NOT NULL,
  `Price` float DEFAULT NULL,
  `In_Stock` int(11) DEFAULT NULL,
  `brand_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `weight_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`category_id`,`brand_id`,`color_id`,`size_id`,`weight_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_Component_Category_idx` (`category_id`),
  KEY `fk_component_brands1_idx` (`brand_id`),
  KEY `fk_component_colors1_idx` (`color_id`),
  KEY `fk_component_sizes1_idx` (`size_id`),
  KEY `fk_component_weights1_idx` (`weight_id`),
  CONSTRAINT `fk_Component_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_component_brands1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_component_colors1` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_component_sizes1` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_component_weights1` FOREIGN KEY (`weight_id`) REFERENCES `weight` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `component`
--

LOCK TABLES `component` WRITE;
/*!40000 ALTER TABLE `component` DISABLE KEYS */;
INSERT INTO `component` VALUES (0,0,'Good for Heating','Malyan M180 Dual Head 3D Printer Replacement ','images/heating_block1.jpg',9.99,10,0,0,0,0),(1,0,'Good for Stuff','Malyan M150 i3 3D Printer Replacement Heating','images/heating_block1.jpg',9.99,5,0,0,0,0),(2,0,'Good for Heating And Stuff...','Mini Fabrikator V2/M200 3D Printer Replacemen','images/heating_block2.jpg',9.99,6,0,0,1,1),(3,1,'Good for Cooling And Stuff...','3D V6 hotend','images/cooler1.jpg',10.99,8,1,0,1,0),(4,1,'Good for Cooling And Stuff..','V6 J-head Hotend Extruder Kit 3D Printers Par','images/cooler2.jpg',10.99,5,2,0,1,0),(5,2,'Power of the gods, top output 12V ','3D printer parts power supply prusa i3','images/power1.jpg',10.99,8,5,0,8,5),(6,2,'Zeus all mighty in this box ','Power Supply&cable Pursa Mendel','images/power2.jpg',11.99,10,5,0,8,5),(7,3,'It also works as a rope','printer filament ABS','images/filament1.jpg',11.99,51,6,0,8,6),(8,3,'To collor up someones dull life','PETG filaments','images/filament2.jpg',11.99,15,6,3,8,6),(9,4,'Simple, but strangly compeling piece of metal with few wires','ANYCUBIC NEMA 17 Stepper Motor','images/motor1.jpg',19.99,20,4,0,8,6),(10,5,'A sheet for printers','Blue 3D Printer Sheets','images/sheet1.jpg',19.99,52,4,2,6,0),(11,5,'A sheet for printers','Sheets for 3D Printer Beds','images/sheet2.jpg',19.99,35,4,3,7,0),(12,5,'A sheet for printers','Turnigy Blue 3D Printer Bed Tape Sheets','images/sheet3.jpg',19.99,21,4,2,6,1),(13,6,'Extruder with an end','3D Printer Assembled Extruder Kits Hot End','images/extruder1.jpg',19.99,25,3,0,5,4),(14,6,'Extruder','3D Printer MK8 Extruder Aluminum Frame Block','images/extruder2.jpg',29.99,14,3,0,5,4),(15,7,'Main Board','3D Printer Main Board Smart Adapter Plate Ext','images/Board1.jpg',29.99,46,2,2,4,3),(16,7,'Mother of all boards','3D Printer-Ultimaker V1.5.7 PCB Main Control ','images/Board2.jpg',29.99,30,1,0,1,2),(17,8,'Rite with switches that switch','Print-Rite DIY 3D Printer - X, Y and Z Axis L','images/rite3.jpg',29.99,45,1,0,2,2),(18,8,'Rite for 3d printers','Print-Rite DIY 3D','images/rite1.jpg',25.99,18,2,2,2,2),(19,8,'Conveyor belt','Print-Rite DIY 3D X and Y Axis Belts','images/rite2.jpg',25.99,23,1,0,2,2);
/*!40000 ALTER TABLE `component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`component_id`,`client_id`),
  KEY `fk_cart_component1_idx` (`component_id`),
  KEY `fk_orders_buyer1_idx` (`client_id`),
  CONSTRAINT `fk_cart_component1` FOREIGN KEY (`component_id`) REFERENCES `component` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_buyer1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `size`
--

DROP TABLE IF EXISTS `size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `size` (
  `id` int(11) NOT NULL,
  `size` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `size_UNIQUE` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `size`
--

LOCK TABLES `size` WRITE;
/*!40000 ALTER TABLE `size` DISABLE KEYS */;
INSERT INTO `size` VALUES (5,'1.75'),(6,'130 x 140'),(7,'200 x 200'),(1,'260 x 100'),(3,'320 x 160'),(4,'62 x 18 x 15'),(8,'640 x 640'),(0,'70 x 55'),(2,'Bowden extruder');
/*!40000 ALTER TABLE `size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weight`
--

DROP TABLE IF EXISTS `weight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weight` (
  `id` int(11) NOT NULL,
  `Weight` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `Weight_UNIQUE` (`Weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weight`
--

LOCK TABLES `weight` WRITE;
/*!40000 ALTER TABLE `weight` DISABLE KEYS */;
INSERT INTO `weight` VALUES (6,'1 kg'),(0,'10 g'),(2,'100 g'),(4,'12 g'),(5,'450 g'),(1,'5.87 kg'),(3,'8 g');
/*!40000 ALTER TABLE `weight` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-24 18:11:37
