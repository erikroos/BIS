<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("include_globalVars.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Hulp</title>
    <link type="text/css" href="<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>

<a href="http://github.com/erikroos/BIS"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/e6bef7a091f5f3138b8cd40bc3e114258dd68ddf/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub"></a>

<div style="width:600px; margin-left:10px; margin-top:10px">
<h1>Meer informatie over BIS</h1>

<p><a href="./index.php">Terug naar BIS&gt;&gt;</a></p>

<p>Voor meer informatie, opmerkingen, vragen en/of suggesties kunt u ook contact opnemen met Erik Roos door te mailen naar het volgende adres:
<? echo "<a href='mailto:".$mailadres."'>".$mailadres."</a>"; ?>
</p>

<strong>API en app</strong>
<p>Voor meer informatie over de BIS-API, zie <a href="api/doc.html">de documentatie</a>.<br />
Download <a href="http://hunze.nl/sites/default/files/Hunze%20BIS%20v120820.apk" target="_blank">hier</a> de Android-app van BIS.</p>

<strong>Algemeen - hoofdscherm</strong>
<ul>
  <li>Klik in het schema ter hoogte van de gewenste boot en tijd om een inschrijving te maken. NB: grijsgekleurde boten zijn uit de vaart.</li>
  <li>Klik op een ingeschreven blok om dit te bevestigen, te bekijken of te wijzigen. NB: grijze blokken kunnen niet (meer) gewijzigd worden.</li>
  <li>Oranje blokken zijn vooraf door het bestuur ingevoerde spitsblokken. Deze dienen uiterlijk een dag vantevoren bevestigd te worden.</li>
</ul>

<strong>Algemeen - inschrijfscherm</strong>
<ul>
  <li>Voer de juiste gegevens in en druk tot slot op 'inschrijven' of 'opslaan'.</li>
  <li>Bij een spitsblok de gegevens controleren en op 'bevestigen' drukken.</li>
  <li>Met een druk op 'sluiten' rechtsbovenin gaat u terug naar het inschrijfblad zonder de inschrijving te hebben gemaakt of gewijzigd.</li>
  <li>Kies, in het geval van een bestaande inschrijving, eventueel 'verwijderen' om deze te wissen.</li>
</ul>

<strong>Enkele veel gestelde vragen:</strong>
<ul><li><em>Hoe zit het met BIS en het spitsrooster?</em></li></ul>
Het spitsrooster wordt door het bestuur vantevoren in BIS ingevoerd. U hoeft de aan u toegewezen (oranjegekleurde) blokken alleen nog te bevestigen door ze aan te klikken en op 'Bevestigen' te drukken. Dit kan van drie dagen tot een dag vantevoren. Daarna komt een spitsblok te vervallen.

<ul><li><em>Waarom loopt de kalender maar tot het einde van de maand?</em></li></ul>
Als u op het symbooltje naast een datum-invoerveld klikt, opent in een venster een kalendertje waarin u makkelijk de juiste datum aan kunt klikken. Deze kalender opent in de huidige maand. Bovenaan de kalender bevinden zich vier knoppen, waarvan '>' de belangrijkste is. Hiermee gaat de kalender namelijk een maand vooruit. U kunt dan dus data uit de volgende maand selecteren.

<ul><li><em>Waarom kan ik een e-mailadres opgeven?</em></li></ul>
In principe kan iemand anders een door u gemaakte inschrijving wijzigen of wissen, maar als u een e-mailadres opgeeft bij inschrijving, zult u hiervan altijd via e-mail bericht krijgen. U kunt dan zelf controleren wat er met uw inschrijving gebeurd is. NB: ook al wist iemand anders in het inschrijfscherm uw e-mailadres, u krijgt toch een e-mail, omdat BIS uw adres uit de database haalt.

<ul><li><em>Waarom kan ik meer dan 3 dagen vooruit inschrijven?</em></li></ul>
U kunt maximaal 10 dagen vooruit inschrijven. Dit in verband met het tijdig kunnen reserveren van examens, toertochten en wedstrijden. Let wel: inschrijvingen die meer dan 3 dagen vantevoren gedaan worden, dienen van een MPB voorzien te zijn en worden ter controle aan het opgegeven bestuurslid gemeld. 

<ul><li><em>Kan iemand zomaar mijn inschrijving wissen?</em></li></ul>
In principe kan dit, maar als u een e-mailadres opgeeft bij inschrijving, zult u hiervan altijd bericht krijgen. U kunt dan zelf controleren wat er met uw inschrijving gebeurd is.

</div>
</body>
</html>