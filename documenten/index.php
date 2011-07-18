<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include.php");
include_once("../mail.php");
setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Documenten voor leden</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<h1>Documenten voor leden</h1>
<p><a href='../index.php'>Naar BIS&gt;&gt;</a><br />
<a href='./bis_logout.php'>Uitloggen&gt;&gt;</a></p>

<p><em>Alle documenten zijn in het PDF-formaat en openen in een nieuw venster.</em></p>

<strong>Ledenlijsten 2011</strong>
<ul>
	<li><a href='Alleleden2011.pdf' target='_blank'>Alle leden&gt;&gt;</a></li>
	<li><a href='Alleledenachternaam.pdf' target='_blank'>Alle leden op achternaam&gt;&gt;</a></li>
	<li><a href='Alleledenvoornaam.pdf' target='_blank'>Alle leden op voornaam&gt;&gt;</a></li>
	<li><a href='Alleledenadressen.pdf' target='_blank'>Alle adressen&gt;&gt;</a></li>
	<li><a href='Jeugdleden.pdf' target='_blank'>Jeugdleden&gt;&gt;</a></li>
	<li><a href='Donateurs.pdf' target='_blank'>Donateurs&gt;&gt;</a></li>
	<li><a href='Diplomalijst.pdf' target='_blank'>Diplomalijst&gt;&gt;</a></li>
</ul>

<strong>Jaarvergadering 2011</strong>
<ul>
	<li><a href='uitnodiging2011.pdf' target='_blank'>Uitnodiging 2011&gt;&gt;</a></li>
	<li><a href='VerslagAV2010.pdf' target='_blank'>Verslag jaarvergadering 2010&gt;&gt;</a></li>
	<li><a href='Begroting2011.pdf' target='_blank'>Begroting 2011&gt;&gt;</a></li>
	<li><a href='Jaarverslagen2010.pdf' target='_blank'>Jaarverslagen 2010&gt;&gt;</a></li>
	<li><a href='JaarverslagPenningmeester2010.pdf' target='_blank'>Jaarverslag 2010 door de penningmeester&gt;&gt;</a></li>
</ul>

<strong>Botenplanvergadering 2011</strong>
<ul>
	<li><a href="NotulenBotenplanAV2011.pdf" target="_blank">Notulen Botenplanvergadering 9 maart 2011&gt;&gt;</a></li>
	<li><a href="AgendaBotenplanvergadering09-03-2011.pdf" target="_blank">Agenda Botenplanvergadering 9 maart 2011&gt;&gt;</a></li>
    <li><a href="PresentatieBotenplan2011.ppt" target="_blank">Download het botenplan presentatie 2011&gt;&gt;</a></li>
	<li><a href="Botenplan2011.pdf" target="_blank">Botenplan 2011&gt;&gt;</a></li>
</ul>

<strong>Botenplanvergadering 2010</strong>
<ul>
	<li><a href="Botenplan2010.pdf" target="_blank">Botenplan 2010&gt;&gt;</a></li>
	<li><a href="NotulenBotenplanAV09-03-2010.pdf" target="_blank">Notulen botenplanvergadering 2010&gt;&gt;</a></li>
	<li><a href="VerslagPrebesprekingBotenplan2010.pdf" target="_blank">Verslag pre-bespreking botenplan 2010&gt;&gt;</a></li>
	<li><a href="NotulenBotenplanAV2009.pdf" target="_blank">Notulen botenplanvergadering 2009&gt;&gt;</a></li>
</ul>

<strong>Jaarvergadering 2010</strong>
<ul>
	<li><a href="NotulenJaarverg23mrt2009.pdf" target="_blank">Notulen jaarvergadering 2009&gt;&gt;</a></li>
	<li><a href="agendajaarvergadering.pdf" target="_blank">Agenda jaarvergadering 2010&gt;&gt;</a></li>
	<li><a href="Begroting2010.pdf" target="_blank">Begroting 2010&gt;&gt;</a></li>
</ul>

<strong>Jaarverslagen 2009</strong>
<ul>
	<li><a href="jaarverslagcomp2009.pdf" target="_blank">Jaarverslag commissariaat competitieroeien 2009&gt;&gt;</a></li>
	<li><a href="Jaarverslaginstr2009.pdf" target="_blank">Jaarverslag instructiecommissie 2009&gt;&gt;</a></li>
	<li><a href="jaarverslagjun2009.pdf" target="_blank">Jaarverslag juniorencommissaris 2009&gt;&gt;</a></li>
	<li><a href="Jaarverslagmat2009.pdf" target="_blank">Jaarverslag materiaalcommissaris 2009&gt;&gt;</a></li>
	<li><a href="JaarverslagPenning2009.pdf" target="_blank">Jaarverslag penningmeester 2009&gt;&gt;</a></li>
	<li><a href="Jaarverslagsecretaris2009.pdf" target="_blank">Jaarverslag secretaris 2009&gt;&gt;</a></li>
	<li><a href="JaarverslagSociet2009.pdf" target="_blank">Jaarverslag soci&euml;teitscommissaris 2009&gt;&gt;</a></li>
	<li><a href="jaarverslagtoer2009.pdf" target="_blank">Jaarverslag toercommissie 2009&gt;&gt;</a></li>
	<li><a href="jaarverslagweds2009.pdf" target="_blank">Jaarverslag wedstrijdcommissaris 2009&gt;&gt;</a></li>
</ul>

</div>
</body>
</html>