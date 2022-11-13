--
-- Database: `bis`
-- 
CREATE DATABASE `bis` DEFAULT CHARACTER SET latin1 COLLATE latin1_german1_ci;

USE bis;

-- If you get error messages about the default dates, use this:
-- SET sql_mode=ALLOW_INVALID_DATES,NO_ENGINE_SUBSTITUTION

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `bestuursleden`
-- 

CREATE TABLE `bestuursleden` (
  `Functie` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Naam` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Email` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `MPB` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`Functie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Gegevens worden uitgevoerd voor tabel `bestuursleden`
-- 

INSERT INTO `bestuursleden` VALUES ('Competitie-toer', 'Karel Engbers', 'composaris@hunze.nl', 1);
INSERT INTO `bestuursleden` VALUES ('Instructie', 'N.N. (vacature)', 'instructie@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Jeugd-junioren', 'N.N. (vacature)', 'junioren@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Materiaalcommissaris', 'Karel Engbers', 'materiaal@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Penningmeester', 'Rob van der Werff', 'penningmeester@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Secretaris', 'Marianne Goorhuis', 'secretaris@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Societeit', 'H. Imelman', 'societeit@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Voorzitter', 'Gerrit Corbijn van Willenswaard', 'voorzitter@hunze.nl', 0);
INSERT INTO `bestuursleden` VALUES ('Wedstrijd', 'Gijs Hoogerwerf', 'wedstrijd@hunze.nl', 1);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `boten`
-- 

CREATE TABLE `boten` (
  `ID` int(11) NOT NULL auto_increment,
  `Naam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Gewicht` text collate latin1_german1_ci NOT NULL,
  `Type` varchar(10) collate latin1_german1_ci NOT NULL default '',
  `Type_ID` int(11) NOT NULL,
  `Roeigraad` varchar(45) collate latin1_german1_ci NOT NULL default 'skiff-1',
  `Roeigraad_ID` int(11) NOT NULL default '2',
  `Datum_start` date NOT NULL default '1886-02-19',
  `Datum_eind` date default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=102 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `boten`
-- 

INSERT INTO `boten` VALUES (1, '1886', '80', '8+', 0, 'giek-1', 0, '1886-02-19', '2011-10-10');
INSERT INTO `boten` VALUES (2, 'Aa', '65', '1x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (3, 'Aalscholver', '75', 'C2x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (4, 'Ant ter Braake', '75', '4x+', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (5, 'Bak', '-', 'bak', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (6, 'Ben Wirtjes', '75', '1x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (7, 'Bestuurskamer', '-', 'soc', 0, 'geen', 0, '1886-02-19', '2009-10-19');
INSERT INTO `boten` VALUES (8, 'Bever', '80', '1x', 0, 'skiff-1', 0, '1886-02-19', '2011-10-10');
INSERT INTO `boten` VALUES (9, 'Blauwe Reiger', '80', '4x+', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (10, 'Bliksems', '75', '1x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (11, 'Boeg', '85', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (12, 'Bommen Berend', '-', 'C4+', 0, 'giek-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (13, 'Boterdiep', '90', 'C1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (14, 'Citadel', '80', '1x', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (15, 'Concept 1', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (16, 'Concept 2', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (17, 'Concept 3', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (18, 'Concept 4', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (19, 'Concept 5', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (20, 'Concept 6', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (21, 'De Hunze', '70', '4x+', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (22, 'Dintel', '55', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (23, 'Dollard', '-', 'W2x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (24, 'Doorloper', '95', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (25, 'Draaihals', '75', '2-', 0, 'giek-3', 0, '2009-10-13', NULL);
INSERT INTO `boten` VALUES (26, 'Dubbel en Dwars', '70', '2x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (27, 'Dubbelslag', '85', '2x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (28, 'Ee', '65', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (29, 'Evert Kruyswijck', '75', '1x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (30, 'Geert Pentenga', '-', 'C4x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (31, 'Gerda Dijk', '60', 'C4x+', 0, 'skiff-1', 0, '1886-02-19', '2011-01-07');
INSERT INTO `boten` VALUES (32, 'Groeninga', '-', 'C4x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (33, 'Hans Imelman', '80-95', '4+', 0, 'giek-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (34, 'Harkstede', '75', '1x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (35, 'Harry Meijer', '75', '2x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (36, 'Hells Angeles II', '75', '2-', 0, 'giek-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (37, 'Hoendiep', '80', 'C2x+/C3x-', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (38, 'Hoornsediep', '90', 'C2+', 0, 'giek-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (39, 'IJsvogel', '75', '1x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (40, 'Illustraal', '75', '2x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (41, 'Jan Herman Schokkenbroek', '-', 'C4x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (42, 'Johan D', '65', '1x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (43, 'Kemphaan', '75', '1x', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (44, 'Kolkentrekker', '95', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (45, 'Lauwers', '-', 'W2x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (46, 'Lien Veenstra', '80', '2x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (47, 'Lijntrekker', '-', 'W1x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (48, 'Lokvogel', '75', '1x', 0, 'skiff-3', 0, '1886-02-19', '2011-10-10');
INSERT INTO `boten` VALUES (49, 'Martini', '85', '4x+', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (50, 'Mijneentje', '55', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (51, 'Noorderlicht', '85', '8+', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (52, 'Otter', '80', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (53, 'Paul Rakke', '85', '1x', 0, 'MPB', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (54, 'Pier', '55', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (55, 'Pol', '55', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (56, 'Puck', '65', '4x+', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (57, 'Reitdiep', '90', 'C1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (58, 'RowPerfect 1', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (59, 'RowPerfect 2', '-', 'ergo', 0, 'geen', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (60, 'Schier', '95', 'B4+', 0, 'giek-1', 0, '1886-02-19', '2011-02-21');
INSERT INTO `boten` VALUES (61, 'Slag', '85', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (62, 'Societeitszaal', '-', 'soc', 0, 'geen', 0, '1886-02-19', '2009-10-19');
INSERT INTO `boten` VALUES (63, 'Tiemen', '85', '2-', 0, 'giek-2', 0, '2009-09-03', NULL);
INSERT INTO `boten` VALUES (64, 'Topaas', '90', '1x', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (65, 'Twad', '-', 'W2x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (66, 'Tweetact', '85', '2x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (67, 'Twijspan', '70', '2x', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (68, 'Unicum', '65', '2x', 0, 'skiff-1', 0, '1886-02-19', '2011-01-07');
INSERT INTO `boten` VALUES (69, 'Van der Berg', '80', '4x+', 0, 'skiff-2', 0, '1886-02-19', '2011-10-10');
INSERT INTO `boten` VALUES (70, 'Vecht', '80', '1x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (71, 'Vuurwater', '75', '2x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (72, 'Waal', '100', '1x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (73, 'Waterhaas', '75', '1x', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (74, 'Waterheld', '90', '1x', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (75, 'Waterjuffer', '65', '1x', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (76, 'Waterlander', '75', '1x', 0, 'skiff-2', 0, '1886-02-19', '2011-10-10');
INSERT INTO `boten` VALUES (77, 'Westerhaven', '-', 'C4x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (78, 'Willem Schierbeek', '90', 'C2x+', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (79, 'Witte Zwaan', '75', '4x+', 0, 'skiff-2', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (80, 'Zegevier', '80', '4x-', 0, 'skiff-3', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (81, 'Zilverreiger', '80', 'C2x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (82, 'Zwaaikom', '55', '1x', 0, 'skiff-1', 0, '1886-02-19', NULL);
INSERT INTO `boten` VALUES (83, 'Zweethout', '80', '8+', 0, 'giek-2', 0, '1886-02-19', '2011-10-10');
INSERT INTO `boten` VALUES (85, 'Blauwe Reiger', '80', '4+', 0, 'MPB', 2, '2010-01-31', NULL);
INSERT INTO `boten` VALUES (86, 'Bommen Berend', '-', 'C4x+', 0, 'skiff-1', 2, '2010-01-31', NULL);
INSERT INTO `boten` VALUES (87, 'Hoornsediep', '90', 'C2x+', 0, 'skiff-1', 2, '2010-01-31', NULL);
INSERT INTO `boten` VALUES (88, 'Hells Angeles II', '75', '2x', 0, 'skiff-3', 2, '2010-01-31', NULL);
INSERT INTO `boten` VALUES (89, 'Martini', '85', '4+', 0, 'giek-3', 2, '2010-02-25', '2010-03-22');
INSERT INTO `boten` VALUES (90, 'Hunze', '70', '4x+', 0, 'skiff-2', 2, '2010-03-25', '2010-03-25');
INSERT INTO `boten` VALUES (91, 'Th. Niemeijer', '65', '2x', 0, 'skiff-2', 2, '2010-11-07', NULL);
INSERT INTO `boten` VALUES (92, 'Noorderkroon', '75', '8+', 0, 'MPB', 2, '2011-02-19', NULL);
INSERT INTO `boten` VALUES (93, 'Wellgunde VI', '85', '4x+', 0, 'skiff-2', 2, '2011-02-19', NULL);
INSERT INTO `boten` VALUES (94, 'Probeer Ergo Dynamic', '', 'ergo', 0, 'geen', 2, '2011-02-21', '2011-04-18');
INSERT INTO `boten` VALUES (95, 'Gerda Dijk II', '65', '1x', 0, 'MPB', 2, '2011-05-09', NULL);
INSERT INTO `boten` VALUES (96, 'Hoetjer II (leenboot G.S.R. Aegir)', '75-80', '2-', 0, 'giek-3', 2, '2011-06-29', '2011-10-17');
INSERT INTO `boten` VALUES (97, 'Tineke Floor', '80-85', '8+', 0, 'MPB', 2, '2011-07-09', NULL);
INSERT INTO `boten` VALUES (98, 'Marathon', '75', '1x', 0, 'prive', 2, '2011-09-23', NULL);
INSERT INTO `boten` VALUES (99, 'Iduna', '55', '1x', 0, 'prive', 2, '2011-09-23', NULL);
INSERT INTO `boten` VALUES (100, 'Grote Griet', '85', '2-', 0, 'giek-3', 2, '2011-11-13', NULL);
INSERT INTO `boten` VALUES (101, 'Grote Griet', '85', '2x', 0, 'skiff-3', 2, '2011-11-13', NULL);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `cursus_inschrijvingen`
-- 

CREATE TABLE `cursus_inschrijvingen` (
  `ID` int(11) NOT NULL auto_increment,
  `Naam` varchar(45) collate latin1_german1_ci NOT NULL,
  `Demand` varchar(100) collate latin1_german1_ci default NULL,
  `Ex_ID` bigint(20) NOT NULL,
  `Email` varchar(45) collate latin1_german1_ci NOT NULL,
  `TelNr` varchar(20) collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=309 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `cursus_inschrijvingen`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `cursussen`
-- 

CREATE TABLE `cursussen` (
  `ID` bigint(20) NOT NULL auto_increment,
  `Startdatum` date NOT NULL,
  `Einddatum` date default NULL,
  `Type` varchar(100) collate latin1_german1_ci NOT NULL,
  `Omschrijving` varchar(45) collate latin1_german1_ci NOT NULL,
  `Mailadres` varchar(45) collate latin1_german1_ci NOT NULL,
  `Quotum` int(11) NOT NULL default '12',
  `ToonOpSite` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=46 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `cursussen`
-- 

INSERT INTO `cursussen` VALUES (42, '2012-05-14', '2012-07-07', 'Cursus skiff-2 - inschrijving gesloten', 'Data onder voorbehoud', 'idvanjoost@hotmail.com', 6, 1);
INSERT INTO `cursussen` VALUES (43, '2012-09-03', '2012-11-03', 'Cursus skiff-2', 'Data onder voorbehoud', 'idvanjoost@hotmail.com', 8, 1);
INSERT INTO `cursussen` VALUES (44, '2012-05-14', '2012-07-07', 'Cursus skiff-1 - inschrijving gesloten', '', 'skiff1@hunze.nl', 6, 1);
INSERT INTO `cursussen` VALUES (45, '2012-09-03', '2012-11-03', 'Cursus skiff-1', '', 'skiff1@hunze.nl', 6, 1);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `examen_inschrijvingen`
-- 

CREATE TABLE `examen_inschrijvingen` (
  `ID` int(11) NOT NULL auto_increment,
  `Naam` varchar(45) collate latin1_german1_ci NOT NULL,
  `Ex_ID` bigint(20) NOT NULL,
  `Graad` varchar(10) collate latin1_german1_ci NOT NULL,
  `Leeftijd` varchar(100) collate latin1_german1_ci NOT NULL,
  `Email` varchar(45) collate latin1_german1_ci NOT NULL,
  `TelNr` varchar(20) collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=641 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=641 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `examen_inschrijvingen`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `examens`
-- 

CREATE TABLE `examens` (
  `ID` bigint(20) NOT NULL auto_increment,
  `Datum` date NOT NULL,
  `Omschrijving` varchar(45) collate latin1_german1_ci NOT NULL,
  `Graden` varchar(255) collate latin1_german1_ci NOT NULL,
  `Quotum` int(11) NOT NULL default '12',
  `ToonOpSite` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=77 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `examens`
-- 

INSERT INTO `examens` VALUES (65, '2012-03-04', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 0);
INSERT INTO `examens` VALUES (66, '2012-04-01', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 0);
INSERT INTO `examens` VALUES (67, '2012-04-21', 'Theorie examen T1/T2 (start 13:30)', 'theorie-1,theorie-2', 25, 0);
INSERT INTO `examens` VALUES (68, '2012-05-06', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 0);
INSERT INTO `examens` VALUES (69, '2012-06-03', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 1);
INSERT INTO `examens` VALUES (70, '2012-06-23', 'Theorie examen T1/T2 (start 13:30hr)', 'theorie-1,theorie-2', 25, 1);
INSERT INTO `examens` VALUES (71, '2012-07-01', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 1);
INSERT INTO `examens` VALUES (72, '2012-09-02', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 1);
INSERT INTO `examens` VALUES (73, '2012-10-07', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s', 15, 1);
INSERT INTO `examens` VALUES (74, '2012-10-20', 'Theorie examen T1/T2 (start 13:30)', 'theorie-1,theorie-2', 25, 1);
INSERT INTO `examens` VALUES (75, '2012-11-04', 'Regulier praktijk examen (start 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 1);
INSERT INTO `examens` VALUES (76, '2012-12-02', 'Regulier praktijk examen (strat 10:00)', 'skiff-1,skiff-2,skiff-3,giek-1,giek-2,giek-3,wherry-1,wherry-2,C1,kleine-s,grote-S', 15, 1);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `inschrijvingen`
-- 

CREATE TABLE `inschrijvingen` (
  `Volgnummer` int(10) unsigned NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Inschrijfdatum` date NOT NULL default '0000-00-00',
  `Begintijd` time NOT NULL default '00:00:00',
  `Eindtijd` time NOT NULL default '00:00:00',
  `Boot_ID` int(11) NOT NULL,
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Wedstrijdblok` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`),
  KEY `Datum` (`Datum`),
  KEY `Eindtijd` (`Eindtijd`),
  KEY `Boot_ID` (`Boot_ID`),
  KEY `Verwijderd` (`Verwijderd`)
) ENGINE=InnoDB AUTO_INCREMENT=84283 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=84283 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `inschrijvingen`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `inschrijvingen_oud`
-- 

CREATE TABLE `inschrijvingen_oud` (
  `Volgnummer` int(10) unsigned NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Inschrijfdatum` date NOT NULL default '0000-00-00',
  `Begintijd` time NOT NULL default '00:00:00',
  `Eindtijd` time NOT NULL default '00:00:00',
  `Boot_ID` int(11) NOT NULL,
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB AUTO_INCREMENT=84234 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=84234 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `inschrijvingen_oud`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `mededelingen`
-- 

CREATE TABLE `mededelingen` (
  `ID` int(11) NOT NULL,
  `Datum` date NOT NULL default '0000-00-00',
  `Bestuurslid` text collate latin1_german1_ci NOT NULL,
  `Betreft` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Mededeling` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Gegevens worden uitgevoerd voor tabel `mededelingen`
-- 

INSERT INTO `mededelingen` VALUES (164, '2010-04-02', 'wedstrijdcommissaris', 'mpb wedstrijdboten', 'Bij gebruik maken van wedstrijdboten met mpb is een coach langs de kant noodzakelijk.');

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `mededelingen_oud`
-- 

CREATE TABLE `mededelingen_oud` (
  `ID` int(11) NOT NULL,
  `Datum` date NOT NULL default '0000-00-00',
  `Bestuurslid` text collate latin1_german1_ci NOT NULL,
  `Betreft` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Mededeling` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Gegevens worden uitgevoerd voor tabel `mededelingen_oud`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `roeigraden`
-- 

CREATE TABLE `roeigraden` (
  `ID` int(11) NOT NULL default '0',
  `Roeigraad` text collate latin1_german1_ci NOT NULL,
  `ToonInBIS` tinyint(4) NOT NULL,
  `KleurInBIS` varchar(10) collate latin1_german1_ci default NULL,
  `Examinabel` tinyint(4) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Gegevens worden uitgevoerd voor tabel `roeigraden`
-- 

INSERT INTO `roeigraden` VALUES (1, 'geen', 1, '#FFFF99', 0);
INSERT INTO `roeigraden` VALUES (2, 'skiff-1', 1, '#FFFF99', 1);
INSERT INTO `roeigraden` VALUES (3, 'skiff-2', 1, '#AAFFAA', 1);
INSERT INTO `roeigraden` VALUES (4, 'skiff-3', 1, '#737CA1', 1);
INSERT INTO `roeigraden` VALUES (5, 'giek-1', 1, '#FFFF99', 1);
INSERT INTO `roeigraden` VALUES (6, 'giek-2', 1, '#AAFFAA', 1);
INSERT INTO `roeigraden` VALUES (7, 'giek-3', 1, '#737CA1', 1);
INSERT INTO `roeigraden` VALUES (8, 'MPB', 1, '#FFC1C1', 0);
INSERT INTO `roeigraden` VALUES (9, 'wherry-1', 0, '', 1);
INSERT INTO `roeigraden` VALUES (10, 'wherry-2', 0, '', 1);
INSERT INTO `roeigraden` VALUES (11, 'C1', 0, '', 1);
INSERT INTO `roeigraden` VALUES (12, 'kleine-s', 0, '', 1);
INSERT INTO `roeigraden` VALUES (13, 'grote-S', 0, '', 1);
INSERT INTO `roeigraden` VALUES (14, 'theorie-1', 0, '#FFFF99', 1);
INSERT INTO `roeigraden` VALUES (15, 'theorie-2', 0, '#FFFF99', 1);
INSERT INTO `roeigraden` VALUES (16, 'prive', 1, '#0B6121', 0);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `schades`
-- 

CREATE TABLE `schades` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Datum_gew` date NOT NULL default '0000-00-00',
  `Naam` text collate latin1_german1_ci NOT NULL,
  `Boot_ID` int(11) NOT NULL,
  `Oms_lang` text collate latin1_german1_ci NOT NULL,
  `Feedback` text collate latin1_german1_ci NOT NULL,
  `Actie` text collate latin1_german1_ci NOT NULL,
  `Actiehouder` text collate latin1_german1_ci NOT NULL,
  `Prio` tinyint(4) NOT NULL default '2',
  `Realisatie` smallint(6) NOT NULL default '0',
  `Datum_gereed` date NOT NULL default '0000-00-00',
  `Noodrep` text collate latin1_german1_ci NOT NULL,
  `Opmerkingen` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=920 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=920 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `schades`
-- 

INSERT INTO `schades` VALUES (768, '2011-10-01', '2012-05-07', 'michiel van dongen', 97, 'kras in huid SB bij 7-plaats', 'Wordt tzt bijgewerkt', '', 'Matcie', 2, 0, '0000-00-00', '', '');
INSERT INTO `schades` VALUES (815, '2011-11-12', '2012-05-07', 'Simone Steenbeek', 97, 'Beschadiging lak op de boeg.', 'Wordt tzt bijgewerkt', 'Het zou mooi zijn om het puntje nog even wit te spuiten.', 'Stefan', 2, 90, '0000-00-00', 'Aangestipt met epoxy', '');
INSERT INTO `schades` VALUES (873, '2012-03-31', '2012-05-07', 'willem larmoyeur', 64, 'Water is niet goed uit de achterpunt te krijgen. Er is geen ontwateringsgat op het laagste punt (bijvoorbeeld bovenop het houten dekje bij het voetenboord). Doordat er altijd water blijft staan begint het hier al te rotten.', 'Wordt op matavond juni bekeken.', '', 'Matcie', 2, 0, '0000-00-00', '', '');
INSERT INTO `schades` VALUES (874, '2012-03-31', '2012-04-15', 'Simone Steenbeek', 43, 'Bij schoonmaken van de Kemphaan een plek opgemerkt waar zich twee scheurtjes in de lak bevinden die aan het inwateren zijn. Deze plek zit midden onder de boot in de laklaag. ', 'Boot was niet lek. Dit betrof een oude garantiereparatie van Wiersma. Door inspectie nu is deze reparatie weer opengemaakt en is de boot nu opnieuw geimpregneerd met epoxy. 15/4: BOOT IS TIJDELIJK VOORZIEN VAN NIEUWE VERFLAAG. MOET NOG EEN KEER OPNIEUW GESCHUURD EN GESPOTEN WORDEN MET JUISTE KLEUR. TOT DIE TIJD MAG ER IN GEVAREN WORDEN.', '', 'Theo', 2, 99, '0000-00-00', '', '');
-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `schades_gebouw`
-- 

CREATE TABLE `schades_gebouw` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Datum_gew` date NOT NULL default '0000-00-00',
  `Naam` text collate latin1_german1_ci NOT NULL,
  `Oms_lang` text collate latin1_german1_ci NOT NULL,
  `Feedback` text collate latin1_german1_ci NOT NULL,
  `Actie` text collate latin1_german1_ci NOT NULL,
  `Actiehouder` text collate latin1_german1_ci NOT NULL,
  `Prio` tinyint(4) NOT NULL default '2',
  `Realisatie` smallint(6) NOT NULL default '0',
  `Datum_gereed` date NOT NULL default '0000-00-00',
  `Noodrep` text collate latin1_german1_ci NOT NULL,
  `Opmerkingen` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=57 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `schades_gebouw`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `schades_gebouw_oud`
-- 

CREATE TABLE `schades_gebouw_oud` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Datum_gew` date NOT NULL default '0000-00-00',
  `Naam` text collate latin1_german1_ci NOT NULL,
  `Oms_lang` text collate latin1_german1_ci NOT NULL,
  `Feedback` text collate latin1_german1_ci NOT NULL,
  `Actie` text collate latin1_german1_ci NOT NULL,
  `Actiehouder` text collate latin1_german1_ci NOT NULL,
  `Prio` tinyint(4) NOT NULL default '2',
  `Realisatie` smallint(6) NOT NULL default '0',
  `Datum_gereed` date NOT NULL default '0000-00-00',
  `Noodrep` text collate latin1_german1_ci NOT NULL,
  `Opmerkingen` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=56 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `schades_gebouw_oud`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `schades_oud`
-- 

CREATE TABLE `schades_oud` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Datum_gew` date NOT NULL default '0000-00-00',
  `Naam` text collate latin1_german1_ci NOT NULL,
  `Boot_ID` int(11) NOT NULL,
  `Oms_lang` text collate latin1_german1_ci NOT NULL,
  `Feedback` text collate latin1_german1_ci NOT NULL,
  `Actie` text collate latin1_german1_ci NOT NULL,
  `Actiehouder` text collate latin1_german1_ci NOT NULL,
  `Prio` tinyint(4) NOT NULL default '2',
  `Realisatie` smallint(6) NOT NULL default '0',
  `Datum_gereed` date NOT NULL default '0000-00-00',
  `Noodrep` text collate latin1_german1_ci NOT NULL,
  `Opmerkingen` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=917 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=917 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `schades_oud`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `stats`
-- 

CREATE TABLE `stats` (
  `ID` int(11) NOT NULL auto_increment,
  `Peildatum` date NOT NULL default '0000-00-00',
  `TotIns` bigint(20) NOT NULL default '0',
  `TotInsOud` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1519 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1519 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `stats`
-- 

INSERT INTO `stats` VALUES (1388, '2012-01-01', 3033, 39438);
INSERT INTO `stats` VALUES (1389, '2012-01-02', 3029, 39445);
INSERT INTO `stats` VALUES (1390, '2012-01-03', 3078, 39457);
INSERT INTO `stats` VALUES (1391, '2012-01-04', 3078, 39470);
INSERT INTO `stats` VALUES (1392, '2012-01-05', 3077, 39490);
INSERT INTO `stats` VALUES (1393, '2012-01-06', 3051, 39525);
INSERT INTO `stats` VALUES (1394, '2012-01-07', 3027, 39540);
INSERT INTO `stats` VALUES (1395, '2012-01-08', 2935, 39615);
INSERT INTO `stats` VALUES (1396, '2012-01-09', 2932, 39696);
INSERT INTO `stats` VALUES (1397, '2012-01-10', 2924, 39707);
INSERT INTO `stats` VALUES (1398, '2012-01-11', 2908, 39730);
INSERT INTO `stats` VALUES (1399, '2012-01-12', 2886, 39760);
INSERT INTO `stats` VALUES (1400, '2012-01-13', 2874, 39774);
INSERT INTO `stats` VALUES (1401, '2012-01-14', 2810, 39785);
INSERT INTO `stats` VALUES (1402, '2012-01-15', 2725, 39833);
INSERT INTO `stats` VALUES (1403, '2012-01-16', 2687, 39871);
INSERT INTO `stats` VALUES (1404, '2012-01-17', 2662, 39887);
INSERT INTO `stats` VALUES (1405, '2012-01-18', 2654, 39902);
INSERT INTO `stats` VALUES (1406, '2012-01-19', 2643, 39922);
INSERT INTO `stats` VALUES (1407, '2012-01-20', 2624, 39944);
INSERT INTO `stats` VALUES (1408, '2012-01-21', 2576, 39955);
INSERT INTO `stats` VALUES (1409, '2012-01-22', 2471, 40020);
INSERT INTO `stats` VALUES (1410, '2012-01-23', 2422, 40063);
INSERT INTO `stats` VALUES (1411, '2012-01-24', 2412, 40063);
INSERT INTO `stats` VALUES (1412, '2012-01-25', 2385, 40085);
INSERT INTO `stats` VALUES (1413, '2012-01-26', 2390, 40102);
INSERT INTO `stats` VALUES (1414, '2012-01-27', 2378, 40119);
INSERT INTO `stats` VALUES (1415, '2012-01-28', 2308, 40131);
INSERT INTO `stats` VALUES (1416, '2012-01-29', 2265, 40178);
INSERT INTO `stats` VALUES (1417, '2012-01-30', 2187, 40252);
INSERT INTO `stats` VALUES (1418, '2012-01-31', 2220, 40266);
INSERT INTO `stats` VALUES (1419, '2012-02-01', 2202, 40282);
INSERT INTO `stats` VALUES (1420, '2012-02-02', 2185, 40299);
INSERT INTO `stats` VALUES (1421, '2012-02-03', 2166, 40306);
INSERT INTO `stats` VALUES (1422, '2012-02-04', 2120, 40312);
INSERT INTO `stats` VALUES (1423, '2012-02-05', 2043, 40366);
INSERT INTO `stats` VALUES (1424, '2012-02-06', 1977, 40421);
INSERT INTO `stats` VALUES (1425, '2012-02-07', 1956, 40430);
INSERT INTO `stats` VALUES (1426, '2012-02-08', 1936, 40441);
INSERT INTO `stats` VALUES (1427, '2012-02-09', 1907, 40455);
INSERT INTO `stats` VALUES (1428, '2012-02-10', 1890, 40466);
INSERT INTO `stats` VALUES (1429, '2012-02-11', 1847, 40472);
INSERT INTO `stats` VALUES (1430, '2012-02-12', 1776, 40514);
INSERT INTO `stats` VALUES (1431, '2012-02-13', 1725, 40560);
INSERT INTO `stats` VALUES (1432, '2012-02-14', 1703, 40569);
INSERT INTO `stats` VALUES (1433, '2012-02-15', 1687, 40579);
INSERT INTO `stats` VALUES (1434, '2012-02-16', 1663, 40599);
INSERT INTO `stats` VALUES (1435, '2012-02-17', 1658, 40616);
INSERT INTO `stats` VALUES (1436, '2012-02-18', 1608, 40622);
INSERT INTO `stats` VALUES (1437, '2012-02-19', 1529, 40672);
INSERT INTO `stats` VALUES (1438, '2012-02-20', 1471, 40732);
INSERT INTO `stats` VALUES (1439, '2012-02-21', 1451, 40742);
INSERT INTO `stats` VALUES (1440, '2012-02-22', 1434, 40755);
INSERT INTO `stats` VALUES (1441, '2012-02-23', 1408, 40774);
INSERT INTO `stats` VALUES (1442, '2012-02-24', 1415, 40782);
INSERT INTO `stats` VALUES (1443, '2012-02-25', 1361, 40795);
INSERT INTO `stats` VALUES (1444, '2012-02-26', 1246, 40855);
INSERT INTO `stats` VALUES (1445, '2012-02-27', 1221, 40884);
INSERT INTO `stats` VALUES (1446, '2012-02-28', 1204, 40901);
INSERT INTO `stats` VALUES (1447, '2012-02-29', 1187, 40929);
INSERT INTO `stats` VALUES (1448, '2012-03-01', 1167, 40954);
INSERT INTO `stats` VALUES (1449, '2012-03-02', 1152, 40975);
INSERT INTO `stats` VALUES (1450, '2012-03-03', 1085, 40985);
INSERT INTO `stats` VALUES (1451, '2012-03-04', 995, 41025);
INSERT INTO `stats` VALUES (1452, '2012-03-05', 952, 41060);
INSERT INTO `stats` VALUES (1453, '2012-03-06', 930, 41069);
INSERT INTO `stats` VALUES (1454, '2012-03-07', 915, 41081);
INSERT INTO `stats` VALUES (1455, '2012-03-08', 907, 41099);
INSERT INTO `stats` VALUES (1456, '2012-03-09', 896, 41120);
INSERT INTO `stats` VALUES (1457, '2012-03-10', 822, 41126);
INSERT INTO `stats` VALUES (1458, '2012-03-11', 730, 41164);
INSERT INTO `stats` VALUES (1459, '2012-03-12', 713, 41186);
INSERT INTO `stats` VALUES (1460, '2012-03-13', 683, 41206);
INSERT INTO `stats` VALUES (1461, '2012-03-14', 674, 41217);
INSERT INTO `stats` VALUES (1462, '2012-03-15', 656, 41244);
INSERT INTO `stats` VALUES (1463, '2012-03-16', 629, 41276);
INSERT INTO `stats` VALUES (1464, '2012-03-17', 546, 41286);
INSERT INTO `stats` VALUES (1465, '2012-03-18', 456, 41313);
INSERT INTO `stats` VALUES (1466, '2012-03-19', 445, 41324);
INSERT INTO `stats` VALUES (1467, '2012-03-20', 435, 41338);
INSERT INTO `stats` VALUES (1468, '2012-03-21', 428, 41355);
INSERT INTO `stats` VALUES (1469, '2012-03-22', 417, 41383);
INSERT INTO `stats` VALUES (1470, '2012-03-23', 431, 41404);
INSERT INTO `stats` VALUES (1471, '2012-03-24', 356, 41431);
INSERT INTO `stats` VALUES (1472, '2012-03-25', 265, 41488);
INSERT INTO `stats` VALUES (1473, '2012-03-26', 230, 41537);
INSERT INTO `stats` VALUES (1474, '2012-03-27', 227, 41555);
INSERT INTO `stats` VALUES (1475, '2012-03-28', 120, 41581);
INSERT INTO `stats` VALUES (1476, '2012-03-29', 114, 41615);
INSERT INTO `stats` VALUES (1477, '2012-03-30', 250, 41654);
INSERT INTO `stats` VALUES (1478, '2012-03-31', 235, 41676);
INSERT INTO `stats` VALUES (1479, '2012-04-01', 199, 41716);
INSERT INTO `stats` VALUES (1480, '2012-04-03', 243, 41736);
INSERT INTO `stats` VALUES (1481, '2012-04-04', 243, 41765);
INSERT INTO `stats` VALUES (1482, '2012-04-05', 233, 41793);
INSERT INTO `stats` VALUES (1483, '2012-04-06', 233, 41819);
INSERT INTO `stats` VALUES (1484, '2012-04-07', 225, 41840);
INSERT INTO `stats` VALUES (1485, '2012-04-08', 204, 41880);
INSERT INTO `stats` VALUES (1486, '2012-04-09', 209, 41901);
INSERT INTO `stats` VALUES (1487, '2012-04-10', 229, 41919);
INSERT INTO `stats` VALUES (1488, '2012-04-11', 251, 41944);
INSERT INTO `stats` VALUES (1489, '2012-04-12', 254, 41967);
INSERT INTO `stats` VALUES (1490, '2012-04-13', 255, 42000);
INSERT INTO `stats` VALUES (1491, '2012-04-14', 268, 42026);
INSERT INTO `stats` VALUES (1492, '2012-04-15', 231, 42090);
INSERT INTO `stats` VALUES (1493, '2012-04-16', 209, 42135);
INSERT INTO `stats` VALUES (1494, '2012-04-17', 234, 42148);
INSERT INTO `stats` VALUES (1495, '2012-04-18', 264, 42169);
INSERT INTO `stats` VALUES (1496, '2012-04-19', 258, 42194);
INSERT INTO `stats` VALUES (1497, '2012-04-20', 252, 42232);
INSERT INTO `stats` VALUES (1498, '2012-04-21', 239, 42258);
INSERT INTO `stats` VALUES (1499, '2012-04-22', 230, 42292);
INSERT INTO `stats` VALUES (1500, '2012-04-23', 227, 42328);
INSERT INTO `stats` VALUES (1501, '2012-04-24', 250, 42343);
INSERT INTO `stats` VALUES (1502, '2012-04-25', 247, 42381);
INSERT INTO `stats` VALUES (1503, '2012-04-26', 248, 42408);
INSERT INTO `stats` VALUES (1504, '2012-04-27', 242, 42446);
INSERT INTO `stats` VALUES (1505, '2012-04-28', 264, 42475);
INSERT INTO `stats` VALUES (1506, '2012-04-29', 230, 42530);
INSERT INTO `stats` VALUES (1507, '2012-04-30', 219, 42574);
INSERT INTO `stats` VALUES (1508, '2012-05-01', 232, 42595);
INSERT INTO `stats` VALUES (1509, '2012-05-02', 246, 42626);
INSERT INTO `stats` VALUES (1510, '2012-05-03', 252, 42667);
INSERT INTO `stats` VALUES (1511, '2012-05-04', 236, 42716);
INSERT INTO `stats` VALUES (1512, '2012-05-05', 236, 42731);
INSERT INTO `stats` VALUES (1513, '2012-05-06', 231, 42767);
INSERT INTO `stats` VALUES (1514, '2012-05-07', 215, 42807);
INSERT INTO `stats` VALUES (1515, '2012-05-08', 239, 42826);
INSERT INTO `stats` VALUES (1516, '2012-05-09', 243, 42860);
INSERT INTO `stats` VALUES (1517, '2012-05-10', 254, 42883);
INSERT INTO `stats` VALUES (1518, '2012-05-11', 249, 42911);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `test_inschrijvingen`
-- 

CREATE TABLE `test_inschrijvingen` (
  `Volgnummer` int(10) unsigned NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Inschrijfdatum` date NOT NULL default '0000-00-00',
  `Begintijd` time NOT NULL default '00:00:00',
  `Eindtijd` time NOT NULL default '00:00:00',
  `Boot_ID` int(11) NOT NULL,
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `test_inschrijvingen`
-- 


-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `test_inschrijvingen_oud`
-- 

CREATE TABLE `test_inschrijvingen_oud` (
  `Volgnummer` int(10) unsigned NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Inschrijfdatum` date NOT NULL default '0000-00-00',
  `Begintijd` time NOT NULL default '00:00:00',
  `Eindtijd` time NOT NULL default '00:00:00',
  `Boot_ID` int(11) NOT NULL,
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=206 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `test_inschrijvingen_oud`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `test_stats`
-- 

CREATE TABLE `test_stats` (
  `ID` int(11) NOT NULL auto_increment,
  `Peildatum` date NOT NULL default '0000-00-00',
  `TotIns` bigint(20) NOT NULL default '0',
  `TotInsOud` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1530 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1530 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `test_stats`
-- 
INSERT INTO `test_stats` VALUES (1399, '2012-01-01', 0, 178);
INSERT INTO `test_stats` VALUES (1400, '2012-01-02', 0, 178);
INSERT INTO `test_stats` VALUES (1401, '2012-01-03', 0, 178);
INSERT INTO `test_stats` VALUES (1402, '2012-01-04', 0, 178);
INSERT INTO `test_stats` VALUES (1403, '2012-01-05', 0, 178);
INSERT INTO `test_stats` VALUES (1404, '2012-01-06', 0, 178);
INSERT INTO `test_stats` VALUES (1405, '2012-01-07', 0, 178);
INSERT INTO `test_stats` VALUES (1406, '2012-01-08', 0, 178);
INSERT INTO `test_stats` VALUES (1407, '2012-01-09', 0, 178);
INSERT INTO `test_stats` VALUES (1408, '2012-01-10', 0, 178);
INSERT INTO `test_stats` VALUES (1409, '2012-01-11', 0, 178);
INSERT INTO `test_stats` VALUES (1410, '2012-01-12', 0, 178);
INSERT INTO `test_stats` VALUES (1411, '2012-01-13', 0, 178);
INSERT INTO `test_stats` VALUES (1412, '2012-01-14', 0, 178);
INSERT INTO `test_stats` VALUES (1413, '2012-01-15', 0, 178);
INSERT INTO `test_stats` VALUES (1414, '2012-01-16', 0, 178);
INSERT INTO `test_stats` VALUES (1415, '2012-01-17', 0, 178);
INSERT INTO `test_stats` VALUES (1416, '2012-01-18', 0, 178);
INSERT INTO `test_stats` VALUES (1417, '2012-01-19', 0, 178);
INSERT INTO `test_stats` VALUES (1418, '2012-01-20', 0, 178);
INSERT INTO `test_stats` VALUES (1419, '2012-01-21', 0, 178);
INSERT INTO `test_stats` VALUES (1420, '2012-01-22', 0, 178);
INSERT INTO `test_stats` VALUES (1421, '2012-01-23', 0, 178);
INSERT INTO `test_stats` VALUES (1422, '2012-01-24', 0, 178);
INSERT INTO `test_stats` VALUES (1423, '2012-01-25', 0, 178);
INSERT INTO `test_stats` VALUES (1424, '2012-01-26', 0, 178);
INSERT INTO `test_stats` VALUES (1425, '2012-01-27', 0, 178);
INSERT INTO `test_stats` VALUES (1426, '2012-01-28', 0, 178);
INSERT INTO `test_stats` VALUES (1427, '2012-01-29', 0, 178);
INSERT INTO `test_stats` VALUES (1428, '2012-01-30', 0, 178);
INSERT INTO `test_stats` VALUES (1429, '2012-01-31', 0, 178);
INSERT INTO `test_stats` VALUES (1430, '2012-02-01', 0, 178);
INSERT INTO `test_stats` VALUES (1431, '2012-02-02', 0, 178);
INSERT INTO `test_stats` VALUES (1432, '2012-02-03', 0, 178);
INSERT INTO `test_stats` VALUES (1433, '2012-02-04', 0, 178);
INSERT INTO `test_stats` VALUES (1434, '2012-02-05', 0, 178);
INSERT INTO `test_stats` VALUES (1435, '2012-02-06', 0, 178);
INSERT INTO `test_stats` VALUES (1436, '2012-02-07', 0, 178);
INSERT INTO `test_stats` VALUES (1437, '2012-02-08', 0, 178);
INSERT INTO `test_stats` VALUES (1438, '2012-02-09', 0, 178);
INSERT INTO `test_stats` VALUES (1439, '2012-02-10', 0, 178);
INSERT INTO `test_stats` VALUES (1440, '2012-02-11', 0, 178);
INSERT INTO `test_stats` VALUES (1441, '2012-02-12', 0, 178);
INSERT INTO `test_stats` VALUES (1442, '2012-02-13', 0, 178);
INSERT INTO `test_stats` VALUES (1443, '2012-02-14', 0, 178);
INSERT INTO `test_stats` VALUES (1444, '2012-02-15', 0, 178);
INSERT INTO `test_stats` VALUES (1445, '2012-02-16', 0, 178);
INSERT INTO `test_stats` VALUES (1446, '2012-02-17', 0, 178);
INSERT INTO `test_stats` VALUES (1447, '2012-02-18', 0, 178);
INSERT INTO `test_stats` VALUES (1448, '2012-02-19', 0, 178);
INSERT INTO `test_stats` VALUES (1449, '2012-02-20', 0, 178);
INSERT INTO `test_stats` VALUES (1450, '2012-02-21', 0, 178);
INSERT INTO `test_stats` VALUES (1451, '2012-02-22', 0, 178);
INSERT INTO `test_stats` VALUES (1452, '2012-02-23', 0, 178);
INSERT INTO `test_stats` VALUES (1453, '2012-02-24', 0, 178);
INSERT INTO `test_stats` VALUES (1454, '2012-02-25', 0, 178);
INSERT INTO `test_stats` VALUES (1455, '2012-02-26', 0, 178);
INSERT INTO `test_stats` VALUES (1456, '2012-02-27', 0, 178);
INSERT INTO `test_stats` VALUES (1457, '2012-02-28', 0, 178);
INSERT INTO `test_stats` VALUES (1458, '2012-02-29', 0, 178);
INSERT INTO `test_stats` VALUES (1459, '2012-03-01', 0, 178);
INSERT INTO `test_stats` VALUES (1460, '2012-03-02', 0, 178);
INSERT INTO `test_stats` VALUES (1461, '2012-03-03', 0, 178);
INSERT INTO `test_stats` VALUES (1462, '2012-03-04', 0, 178);
INSERT INTO `test_stats` VALUES (1463, '2012-03-05', 0, 178);
INSERT INTO `test_stats` VALUES (1464, '2012-03-06', 0, 178);
INSERT INTO `test_stats` VALUES (1465, '2012-03-07', 0, 178);
INSERT INTO `test_stats` VALUES (1466, '2012-03-08', 0, 178);
INSERT INTO `test_stats` VALUES (1467, '2012-03-09', 0, 178);
INSERT INTO `test_stats` VALUES (1468, '2012-03-10', 0, 178);
INSERT INTO `test_stats` VALUES (1469, '2012-03-11', 0, 178);
INSERT INTO `test_stats` VALUES (1470, '2012-03-12', 0, 178);
INSERT INTO `test_stats` VALUES (1471, '2012-03-13', 0, 178);
INSERT INTO `test_stats` VALUES (1472, '2012-03-14', 0, 178);
INSERT INTO `test_stats` VALUES (1473, '2012-03-15', 0, 178);
INSERT INTO `test_stats` VALUES (1474, '2012-03-16', 0, 178);
INSERT INTO `test_stats` VALUES (1475, '2012-03-17', 0, 178);
INSERT INTO `test_stats` VALUES (1476, '2012-03-18', 0, 178);
INSERT INTO `test_stats` VALUES (1477, '2012-03-19', 0, 178);
INSERT INTO `test_stats` VALUES (1478, '2012-03-20', 0, 178);
INSERT INTO `test_stats` VALUES (1479, '2012-03-21', 0, 178);
INSERT INTO `test_stats` VALUES (1480, '2012-03-22', 0, 178);
INSERT INTO `test_stats` VALUES (1481, '2012-03-23', 0, 178);
INSERT INTO `test_stats` VALUES (1482, '2012-03-24', 0, 178);
INSERT INTO `test_stats` VALUES (1483, '2012-03-25', 0, 178);
INSERT INTO `test_stats` VALUES (1484, '2012-03-26', 0, 178);
INSERT INTO `test_stats` VALUES (1485, '2012-03-27', 0, 178);
INSERT INTO `test_stats` VALUES (1486, '2012-03-28', 0, 178);
INSERT INTO `test_stats` VALUES (1487, '2012-03-29', 0, 178);
INSERT INTO `test_stats` VALUES (1488, '2012-03-30', 0, 178);
INSERT INTO `test_stats` VALUES (1489, '2012-03-31', 0, 178);
INSERT INTO `test_stats` VALUES (1490, '2012-04-01', 0, 178);
INSERT INTO `test_stats` VALUES (1491, '2012-04-03', 0, 178);
INSERT INTO `test_stats` VALUES (1492, '2012-04-04', 0, 178);
INSERT INTO `test_stats` VALUES (1493, '2012-04-05', 0, 178);
INSERT INTO `test_stats` VALUES (1494, '2012-04-06', 0, 178);
INSERT INTO `test_stats` VALUES (1495, '2012-04-07', 0, 178);
INSERT INTO `test_stats` VALUES (1496, '2012-04-08', 0, 178);
INSERT INTO `test_stats` VALUES (1497, '2012-04-09', 0, 178);
INSERT INTO `test_stats` VALUES (1498, '2012-04-10', 0, 178);
INSERT INTO `test_stats` VALUES (1499, '2012-04-11', 0, 178);
INSERT INTO `test_stats` VALUES (1500, '2012-04-12', 0, 178);
INSERT INTO `test_stats` VALUES (1501, '2012-04-13', 0, 178);
INSERT INTO `test_stats` VALUES (1502, '2012-04-14', 0, 178);
INSERT INTO `test_stats` VALUES (1503, '2012-04-15', 0, 178);
INSERT INTO `test_stats` VALUES (1504, '2012-04-16', 0, 178);
INSERT INTO `test_stats` VALUES (1505, '2012-04-17', 0, 178);
INSERT INTO `test_stats` VALUES (1506, '2012-04-18', 0, 178);
INSERT INTO `test_stats` VALUES (1507, '2012-04-19', 0, 178);
INSERT INTO `test_stats` VALUES (1508, '2012-04-20', 0, 178);
INSERT INTO `test_stats` VALUES (1509, '2012-04-21', 0, 178);
INSERT INTO `test_stats` VALUES (1510, '2012-04-22', 0, 178);
INSERT INTO `test_stats` VALUES (1511, '2012-04-23', 0, 178);
INSERT INTO `test_stats` VALUES (1512, '2012-04-24', 0, 178);
INSERT INTO `test_stats` VALUES (1513, '2012-04-25', 0, 178);
INSERT INTO `test_stats` VALUES (1514, '2012-04-26', 0, 178);
INSERT INTO `test_stats` VALUES (1515, '2012-04-27', 0, 178);
INSERT INTO `test_stats` VALUES (1516, '2012-04-28', 0, 178);
INSERT INTO `test_stats` VALUES (1517, '2012-04-29', 0, 178);
INSERT INTO `test_stats` VALUES (1518, '2012-04-30', 0, 178);
INSERT INTO `test_stats` VALUES (1519, '2012-05-01', 0, 178);
INSERT INTO `test_stats` VALUES (1520, '2012-05-02', 0, 178);
INSERT INTO `test_stats` VALUES (1521, '2012-05-03', 0, 178);
INSERT INTO `test_stats` VALUES (1522, '2012-05-04', 0, 178);
INSERT INTO `test_stats` VALUES (1523, '2012-05-05', 0, 178);
INSERT INTO `test_stats` VALUES (1524, '2012-05-06', 0, 178);
INSERT INTO `test_stats` VALUES (1525, '2012-05-07', 0, 178);
INSERT INTO `test_stats` VALUES (1526, '2012-05-08', 0, 178);
INSERT INTO `test_stats` VALUES (1527, '2012-05-09', 0, 178);
INSERT INTO `test_stats` VALUES (1528, '2012-05-10', 0, 178);
INSERT INTO `test_stats` VALUES (1529, '2012-05-11', 0, 178);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `types`
-- 

CREATE TABLE `types` (
  `ID` int(11) NOT NULL auto_increment,
  `Type` text collate latin1_german1_ci NOT NULL,
  `Categorie` text collate latin1_german1_ci NOT NULL,
  `Roeisoort` varchar(10) collate latin1_german1_ci NOT NULL default 'scull',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=20 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `types`
-- 

INSERT INTO `types` VALUES (1, '1x', 'Skiffs en C1en', 'scull');
INSERT INTO `types` VALUES (2, '2-', 'Boordboten', 'boord');
INSERT INTO `types` VALUES (3, '2x', 'Dubbeltweeen', 'scull');
INSERT INTO `types` VALUES (4, '4+', 'Boordboten', 'boord');
INSERT INTO `types` VALUES (5, '4x+', 'Dubbelvieren', 'scull');
INSERT INTO `types` VALUES (6, '4x-', 'Dubbelvieren', 'scull');
INSERT INTO `types` VALUES (7, '8+', 'Boordboten', 'boord');
INSERT INTO `types` VALUES (8, 'B4+', 'Boordboten', 'boord');
INSERT INTO `types` VALUES (9, 'bak', 'Ergometers en bak', 'scull');
INSERT INTO `types` VALUES (10, 'C1x', 'Skiffs en C1en', 'scull');
INSERT INTO `types` VALUES (11, 'C2+', 'Boordboten', 'boord');
INSERT INTO `types` VALUES (12, 'C2x', 'Wherries en C2en', 'scull');
INSERT INTO `types` VALUES (13, 'C2x+', 'Wherries en C2en', 'scull');
INSERT INTO `types` VALUES (14, 'C2x+/C3x-', 'Wherries en C2en', 'scull');
INSERT INTO `types` VALUES (15, 'C4+', 'Boordboten', 'boord');
INSERT INTO `types` VALUES (16, 'C4x+', 'Dubbelvieren', 'scull');
INSERT INTO `types` VALUES (17, 'ergo', 'Ergometers en bak', 'scull');
INSERT INTO `types` VALUES (18, 'W1x+', 'Wherries en C2en', 'scull');
INSERT INTO `types` VALUES (19, 'W2x+', 'Wherries en C2en', 'scull');

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `uitdevaart`
-- 

CREATE TABLE `uitdevaart` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `Boot_ID` int(11) NOT NULL,
  `Startdatum` date NOT NULL default '0000-00-00',
  `Einddatum` date default NULL,
  `Reden` varchar(50) collate latin1_german1_ci NOT NULL default '',
  `Verwijderd` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Boot_ID` (`Boot_ID`),
  KEY `Startdatum` (`Startdatum`),
  KEY `Einddatum` (`Einddatum`),
  KEY `Verwijderd` (`Verwijderd`)
) ENGINE=InnoDB AUTO_INCREMENT=1140 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1140 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `uitdevaart`
-- 

INSERT INTO `uitdevaart` VALUES (1055, 30, '2012-08-12', '2011-12-09', 'Uit de vaart (verhuur Daventria)', 1);
INSERT INTO `uitdevaart` VALUES (1056, 32, '2012-08-12', '2011-12-09', 'Uit de vaart (verhuur Daventria)', 1);
INSERT INTO `uitdevaart` VALUES (1057, 32, '2012-08-18', '2011-12-09', 'Uit de vaart (verhuur Daventria)', 1);
INSERT INTO `uitdevaart` VALUES (1058, 30, '2012-08-18', '2011-12-09', 'Uit de vaart (Verhuur Daventria)', 1);
INSERT INTO `uitdevaart` VALUES (1059, 77, '2012-08-18', '2011-12-09', 'Uit de vaart (Verhuur Daventria)', 1);
INSERT INTO `uitdevaart` VALUES (1060, 30, '2012-09-18', '2012-09-18', 'Uit de vaart (Verhuur Daventria)', 0);
INSERT INTO `uitdevaart` VALUES (1061, 32, '2012-09-18', '2012-09-18', 'Uit de vaart (Verhuur Daventria)', 0);
INSERT INTO `uitdevaart` VALUES (1062, 77, '2012-09-18', '2012-09-18', 'Uit de vaart (Verhuur Daventria)', 0);
INSERT INTO `uitdevaart` VALUES (1063, 37, '2012-01-01', '2012-01-06', 'Uit de vaart (Snippe)', 1);
INSERT INTO `uitdevaart` VALUES (1064, 34, '2012-01-04', '2012-01-07', 'Uit de vaart: groot onderhoud', 1);
INSERT INTO `uitdevaart` VALUES (1065, 6, '2012-01-04', '2012-01-07', 'Uit de vaart: reparatie lakschades', 1);
INSERT INTO `uitdevaart` VALUES (1066, 39, '2012-01-04', '2012-01-09', 'Uit de vaart: groot onderhoud', 1);
INSERT INTO `uitdevaart` VALUES (1067, 64, '2012-01-04', '2012-01-09', 'Uit de vaart: reparatie waterkering', 1);
INSERT INTO `uitdevaart` VALUES (1068, 37, '2012-01-07', '2012-01-08', 'Uit de vaart', 1);
INSERT INTO `uitdevaart` VALUES (1069, 4, '2012-01-09', '2012-01-12', 'Uit de vaart: reparatie puntje', 1);
INSERT INTO `uitdevaart` VALUES (1070, 30, '2012-09-08', '2012-09-08', 'Uit de vaart (verhuur RV De Dragt)', 0);
INSERT INTO `uitdevaart` VALUES (1071, 12, '2011-09-08', '2012-01-27', 'Uit de vaart (verhuur RV De Dragt)', 1);
INSERT INTO `uitdevaart` VALUES (1072, 12, '2012-09-08', '2012-09-08', 'Uit de vaart (verhuur RV De Dragt)', 0);
INSERT INTO `uitdevaart` VALUES (1073, 35, '2012-02-19', '2012-03-09', 'Uit de vaart (uitleen KNRB)', 1);
INSERT INTO `uitdevaart` VALUES (1074, 4, '2012-03-16', '2012-03-19', 'Uit de vaart (Head of the River Amstel)', 1);
INSERT INTO `uitdevaart` VALUES (1075, 9, '2012-03-09', '2012-03-19', 'Uit de vaart (Heineken en Head)', 1);
INSERT INTO `uitdevaart` VALUES (1076, 49, '2012-03-16', '2012-03-19', 'Uit de vaart (Head of the River Amstel)', 1);
INSERT INTO `uitdevaart` VALUES (1077, 92, '2012-03-09', '2012-03-19', 'Uit de vaart (Heineken en Head)', 1);
INSERT INTO `uitdevaart` VALUES (1078, 93, '2012-03-16', '2012-03-19', 'Uit de vaart (Head of the River Amstel)', 1);
INSERT INTO `uitdevaart` VALUES (1079, 97, '2012-03-16', '2012-03-19', 'Uit de vaart (Head of the River Amstel)', 1);
INSERT INTO `uitdevaart` VALUES (1080, 32, '2012-09-08', '2012-09-08', 'Uit de vaart (verhuur RV De Dragt)', 0);
INSERT INTO `uitdevaart` VALUES (1081, 30, '2012-05-14', '2012-05-15', 'Uit de vaart (Verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1082, 32, '2012-05-14', '2012-05-15', 'Uit de vaart (verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1083, 30, '2012-05-17', '2012-03-14', 'Uit de vaart (Verhuur Dorster Ruderverrein)', 1);
INSERT INTO `uitdevaart` VALUES (1084, 32, '2012-05-17', '2012-05-19', 'Uit de vaart (verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1085, 78, '2012-05-14', '2012-05-11', 'Uit de vaart (verhuur Dorster Ruderverrein)', 1);
INSERT INTO `uitdevaart` VALUES (1086, 23, '2012-05-14', '2012-05-11', 'Uit de vaart (verhuur Dorster Ruderverrein)', 1);
INSERT INTO `uitdevaart` VALUES (1087, 30, '2012-05-18', '2012-05-19', 'Uit de vaart (verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1088, 12, '2012-05-17', '2012-05-19', 'Uit de vaart (verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1089, 78, '2012-05-17', '2012-05-11', 'Uit de vaart (verhuur Dorster Ruderverrein)', 1);
INSERT INTO `uitdevaart` VALUES (1090, 23, '2012-05-17', '2012-04-16', 'Uit de vaart (verhuur Dorster Ruderverrein)', 1);
INSERT INTO `uitdevaart` VALUES (1091, 30, '2012-07-19', '2012-07-19', 'Uit de vaart (verhuur Vada)', 0);
INSERT INTO `uitdevaart` VALUES (1092, 32, '2012-07-19', '2012-07-19', 'Uit de vaart (verhuur Vada)', 0);
INSERT INTO `uitdevaart` VALUES (1093, 23, '2012-07-19', '2012-07-19', 'Uit de vaart (verhuur Vada)', 0);
INSERT INTO `uitdevaart` VALUES (1094, 78, '2012-07-19', '2012-07-19', 'Uit de vaart (verhuur Vada)', 0);
INSERT INTO `uitdevaart` VALUES (1095, 6, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1096, 10, '2012-03-30', '2012-04-02', 'Uit de vaart (skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1097, 21, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1098, 29, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1099, 95, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1100, 100, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1101, 39, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1102, 40, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1103, 25, '2012-03-30', '2012-03-26', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1104, 79, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1105, 53, '2012-03-30', '2012-04-02', 'Uit de vaart (Skiffhead en Tweehead)', 1);
INSERT INTO `uitdevaart` VALUES (1106, 30, '2012-05-17', '2012-05-17', 'Uit de vaart (Verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1107, 12, '2012-05-22', '2012-05-30', 'Uit de vaart (Vogalonga)', 0);
INSERT INTO `uitdevaart` VALUES (1108, 30, '2012-05-22', '2012-05-30', 'Uit de vaart (Vogalonga)', 0);
INSERT INTO `uitdevaart` VALUES (1109, 43, '2012-04-03', '2012-04-15', 'Uit de vaart (reparatie)', 1);
INSERT INTO `uitdevaart` VALUES (1110, 12, '2012-05-14', '2012-05-15', 'Uit de vaart (verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1111, 100, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1112, 39, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1113, 10, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1114, 95, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1115, 53, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1116, 35, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1117, 46, '2012-04-20', '2012-04-23', 'Uit de vaart (Hollandia)', 1);
INSERT INTO `uitdevaart` VALUES (1118, 79, '2012-04-20', '2012-04-23', 'Uit de vaart (Nereus Carpit Noctem)', 1);
INSERT INTO `uitdevaart` VALUES (1119, 65, '2012-05-05', '2012-05-05', 'Uit de vaart (verhuur Wetterwille)', 1);
INSERT INTO `uitdevaart` VALUES (1120, 45, '2012-05-05', '2012-05-05', 'Uit de vaart (verhuur Wetterwille)', 1);
INSERT INTO `uitdevaart` VALUES (1121, 37, '2012-04-28', '2012-04-28', 'Uit de vaart (Hart van Holland Marathon)', 1);
INSERT INTO `uitdevaart` VALUES (1122, 38, '2012-04-28', '2012-04-28', 'Uit de vaart (Hart van Holland marathon)', 1);
INSERT INTO `uitdevaart` VALUES (1123, 67, '2012-04-25', '0000-00-00', 'Uit de vaart (gebroken huid)', 0);
INSERT INTO `uitdevaart` VALUES (1124, 38, '2012-05-18', '2012-05-21', 'Uit de vaart (Elfstedentocht)', 0);
INSERT INTO `uitdevaart` VALUES (1125, 37, '2012-05-18', '2012-05-21', 'Uit de vaart (Elfstedentocht)', 0);
INSERT INTO `uitdevaart` VALUES (1126, 21, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1127, 71, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1128, 91, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1129, 2, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1130, 34, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1131, 43, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1132, 73, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1133, 75, '2012-05-04', '2012-05-06', 'Uit de vaart (Bremen Regatta)', 1);
INSERT INTO `uitdevaart` VALUES (1134, 87, '2012-05-18', '2012-05-21', 'Uit de vaart (Elfstedentocht)', 0);
INSERT INTO `uitdevaart` VALUES (1135, 87, '2012-05-21', '0000-00-00', 'Uit de vaart (boord geriggerd)', 0);
INSERT INTO `uitdevaart` VALUES (1136, 38, '2012-05-04', '2012-05-20', 'Uit de vaart (scull geriggerd)', 0);
INSERT INTO `uitdevaart` VALUES (1137, 23, '2012-05-17', '2012-05-19', 'Uit de vaart (Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1138, 65, '2012-05-17', '2012-05-19', 'Uit de vaart (Verhuur Dorster Ruderverrein)', 0);
INSERT INTO `uitdevaart` VALUES (1139, 78, '2012-05-18', '2012-05-19', 'Uit de vaart (Elfstedentocht)', 0);

CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `mail` varchar(64) default '',
  `mode` tinyint(4) NOT NULL default '0',
  `sort` tinyint(4) default '0',
  `threshold` tinyint(4) default '0',
  `theme` varchar(255) NOT NULL default '',
  `signature` varchar(255) NOT NULL default '',
  `signature_format` smallint(6) NOT NULL default '0',
  `created` int(11) NOT NULL default '0',
  `access` int(11) NOT NULL default '0',
  `login` int(11) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `timezone` varchar(8) default NULL,
  `language` varchar(12) NOT NULL default '',
  `picture` varchar(255) NOT NULL default '',
  `init` varchar(64) default '',
  `data` longtext,
  `timezone_id` int(11) NOT NULL default '0',
  `timezone_name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `name` (`name`),
  KEY `access` (`access`),
  KEY `created` (`created`),
  KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `users`
-- 

INSERT INTO `users` VALUES (1, 'hunzelid', '5d554ac45fdcfcdaca3c49d74ef313f5', 'bis@hunze.nl', 0, 0, 0, '', '', 0, 1328697408, 1336753852, 1336647298, 1, NULL, 'nl', '', 'bis@hunze.nl', 'a:8:{s:13:"form_build_id";s:37:"form-618dec10c53ed8fdcb69f86213059078";s:7:"captcha";s:0:"";s:11:"captcha_sid";i:335;s:13:"captcha_token";s:32:"ff8147b2f288574d00349d7129906f3f";s:16:"captcha_response";s:5:"TH9M2";s:14:"picture_delete";s:0:"";s:14:"picture_upload";s:0:"";s:5:"block";a:1:{s:5:"block";a:1:{i:1;i:1;}}}', 0, '');

