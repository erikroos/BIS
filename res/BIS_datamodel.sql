-- phpMyAdmin SQL Dump
-- version 2.6.4-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: rdbms.strato.de
-- Generatie Tijd: 26 Oct 2009 om 20:50
-- Server versie: 5.0.67
-- PHP Versie: 5.2.0
-- 
-- Database: `DB521746`
-- 

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

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `boten`
-- 

CREATE TABLE `boten` (
  `Naam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Gewicht` text collate latin1_german1_ci NOT NULL,
  `Type` varchar(10) collate latin1_german1_ci NOT NULL default '',
  `Roeigraad` varchar(45) collate latin1_german1_ci NOT NULL default 'sk1',
  `Datum_start` date NOT NULL default '1886-02-19',
  `Datum_eind` date default NULL,
  PRIMARY KEY  (`Naam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `examen_inschrijvingen`
-- 

CREATE TABLE `examen_inschrijvingen` (
  `ID` int(11) NOT NULL auto_increment,
  `Naam` varchar(45) collate latin1_german1_ci NOT NULL,
  `Ex_ID` bigint(20) NOT NULL,
  `Graad` varchar(10) collate latin1_german1_ci NOT NULL,
  `Email` varchar(45) collate latin1_german1_ci NOT NULL,
  `TelNr` varchar(20) collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `examens`
-- 

CREATE TABLE `examens` (
  `ID` bigint(20) NOT NULL auto_increment,
  `Datum` date NOT NULL,
  `Omschrijving` varchar(45) collate latin1_german1_ci NOT NULL,
  `Quotum` int(11) NOT NULL default '12',
  `ToonOpSite` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=21 ;

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
  `Boot` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB AUTO_INCREMENT=30723 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=30723 ;

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
  `Boot` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB AUTO_INCREMENT=30664 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=30664 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `mededelingen`
-- 

CREATE TABLE `mededelingen` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Bestuurslid` text collate latin1_german1_ci NOT NULL,
  `Betreft` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Mededeling` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=128 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `mededelingen_oud`
-- 

CREATE TABLE `mededelingen_oud` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Bestuurslid` text collate latin1_german1_ci NOT NULL,
  `Betreft` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Mededeling` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=127 ;

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
  PRIMARY KEY  (`Roeigraad`(10))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `schades`
-- 

CREATE TABLE `schades` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Datum_gew` date NOT NULL default '0000-00-00',
  `Naam` text collate latin1_german1_ci NOT NULL,
  `Boot` text collate latin1_german1_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=295 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=295 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `schades_oud`
-- 

CREATE TABLE `schades_oud` (
  `ID` int(11) NOT NULL auto_increment,
  `Datum` date NOT NULL default '0000-00-00',
  `Datum_gew` date NOT NULL default '0000-00-00',
  `Naam` text collate latin1_german1_ci NOT NULL,
  `Boot` text collate latin1_german1_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=288 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=288 ;

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
) ENGINE=InnoDB AUTO_INCREMENT=602 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=602 ;

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
  `Boot` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

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
  `Boot` varchar(45) collate latin1_german1_ci NOT NULL default '',
  `Pnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Ploegnaam` varchar(45) collate latin1_german1_ci NOT NULL default 'N.N.',
  `Email` text collate latin1_german1_ci NOT NULL,
  `MPB` text collate latin1_german1_ci NOT NULL,
  `Spits` int(11) NOT NULL default '0',
  `Controle` tinyint(2) NOT NULL default '0',
  `Verwijderd` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`Volgnummer`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=206 ;

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
) ENGINE=InnoDB AUTO_INCREMENT=603 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=603 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `types`
-- 

CREATE TABLE `types` (
  `Type` text collate latin1_german1_ci NOT NULL,
  `Categorie` text collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`Type`(10))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `uitdevaart`
-- 

CREATE TABLE `uitdevaart` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `Boot` varchar(50) collate latin1_german1_ci NOT NULL default '',
  `Startdatum` date NOT NULL default '0000-00-00',
  `Einddatum` date default NULL,
  `Reden` varchar(50) collate latin1_german1_ci NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=491 DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=491 ;
