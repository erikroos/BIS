<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../mail.php");

$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	die('Fout: database niet gevonden.');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Examens</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<p><h1>Examens</h1></p>
<p><a href='../index.php'>Naar BIS&gt;&gt;</a><br />
<a href='./bis_logout.php'>Uitloggen&gt;&gt;</a></p>

<p><strong>Komende examens</strong><br />
<?php
$openExamens = false;
$query = "SELECT ID, Datum, Omschrijving, ToonOpSite FROM examens WHERE Datum > '" . $today_db . "' ORDER BY Datum";
$result = mysql_query($query);
if (!$result) {
	echo("Ophalen van examendata mislukt: " . mysql_error());
} else {
	if (mysql_affected_rows($link) > 0) {
		echo "De komende examendata zijn:</p><ul>";
		while ($row = mysql_fetch_assoc($result)) {
			echo '<li>' . $row['Omschrijving'] . ' op ' . strftime('%A %d-%m-%Y', strtotime($row['Datum']));
			if ($row['ToonOpSite']) {
				echo ': open voor <a href="examen.php?id=' . $row['ID'] . '">inschrijving</a>';
			} else {
				echo ': nog niet of niet meer open voor inschrijving';
			}
			echo '</li>';
		}
		echo "</ul>";
	} else {
		echo 'Er zijn de komende tijd geen examens ingepland.</p>';
	}
}
?>

<p><strong>Mededelingen</strong><br />
Er zijn met ingang van maart 2011 twee theorie-examens: T1 en T2<br />
Pas wanneer men een T1-'diploma' op zak heeft is het mogelijk elk '&#233;&#233;n'-niveau praktijkexamen (i.e. skiff-1, wherry-1, C-1 en giek-1) te doen op elk gewenst algemeen examen.<br />
Met een T2-'diploma' op zak kan men vervolgens alle overige roei- en stuurgraden doen.<br /><br />
Voor T1 moet men de volgende stof uit het Roei- en Examenreglement beheersen:<br />
Hoofdstuk 1 en 2 en bijlage A, B, C: 5 basiscommando's (zie beoordelingsschema), E + G<br /><br />
Voor T2 hoofdstuk 1 en 2 en alle bijlagen behalve H).</p>

<?php if ($examenregels == "hunze") echo"
<p><strong>Spelregels exameninschrijving</strong><br />
<ul>
  <li>Er kan per persoon per examen voor maximaal &eacute;&eacute;n te behalen graad worden ingeschreven.</li>
  <li>Er zal eerst theorie-examen (T-1 of T-2) gedaan moeten worden voordat je aan een praktijkexamen mag deelnemen.</li>
  <li>Inschrijven van boten en/of het regelen van roeiers bij een stuurexamen behoort tot de verantwoordelijkheid van de kandidaat.</li>
  <li>Controleer het <a href=\"../../sites/default/files/Roei&examenreglement_2011.doc\" target=\"_blank\">Roei- en Examenreglement</a> op theorie- en exameneisen.</li>
  <li>Op het <a href=\"../../sites/default/files/beoordelingalleexamens13_0.pdf\" target=\"_blank\">beoordelingsformulier</a> kunt u zien welke criteria de examinator hanteert bij het afnemen van het examen.</li>
  <li>Je kunt hier de <a href=\"../../sites/default/files/Examenvragen.doc\" target=\"_blank\">examenvragen</a> T1 en T2 bekijken.</li>
  <li>Zonder tegenbericht gaan examens altijd door.</li>
  <li>Enige dagen voor het examen ontvangt u een indeling van roeiers/tijdstippen/examinatoren.</li>
  <li>De duur van het theorie-examen is gemiddeld &#233;&#233;n uur.</li>
  <li>De duur van het praktijkexamen is gemiddeld ook &#233;&#233;n uur. </li>
</ul>"; ?>

</div>
</body>
</html>
