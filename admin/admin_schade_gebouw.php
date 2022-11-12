<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'gebcie') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Werkstroom Gebouwcommissie</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

$sortby = $_GET['sortby'];
if (!$sortby) $sortby = "Datum";

if (isset($_GET['mode'])) $mode = $_GET['mode'];

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

echo "<p>Werkstroom Gebouwcommissie</p>";
echo "<p><a href='admin_schade_gebouw_edit.php'>Maak zelf een schademelding aan&gt;&gt;</a></p>";
if (!isset($mode)) {
	echo "<p><a href='admin_schade_gebouw.php?mode=Arch'>Toon gearchiveerde schades&gt;&gt;</a><br>";
} else {
	echo "<p><a href='admin_schade_gebouw.php'>Toon actuele schades&gt;&gt;</a><br>";
}
echo "<a href='admin_schade_gebouw_export.php?mode=" . (isset($mode) ? $mode : "") . "'>Exporteer onderstaande als CSV-bestand&gt;&gt;</a></p>";

$source = "schades_gebouw";
if (isset($mode)) $source .= "_oud";
$query = "SELECT ".$source.".ID AS ID, Datum, Datum_gew, ".$source.".Naam AS Meldernaam, Oms_lang, Actiehouder, Prio, Realisatie, Datum_gereed FROM ".$source." ORDER BY ".$sortby.";";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van schades mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\"><a href='admin_schade_gebouw.php?sortby=Datum'>Melddatum</a></div></th><th><div style=\"text-align:left\"><a href='admin_schade_gebouw.php?sortby=Datum_gew'>Laatst gew.</a></div></th><th><div style=\"text-align:left\">Naam melder</div></th><th><div style=\"text-align:left\">Omschrijving</div></th><th><div style=\"text-align:left\">Actiehouder</div></th><th><div style=\"text-align:left\"><a href='admin_schade_gebouw.php?sortby=Prio'>Prio</a></div></th><th><div style=\"text-align:left\"><a href='admin_schade_gebouw.php?sortby=Realisatie'>Real. (%)</a></div></th><th><div style=\"text-align:left\">Gereed</div></th><th><div style=\"text-align:left\">&nbsp;</div></th>";
if (!isset($mode)) echo "<th><div style=\"text-align:left\">&nbsp;</div></th>";
echo "</tr>";
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$id = $row['ID'];
	$date = $row['Datum'];
	$date_sh = DBdateToDate($date);
	$date_gew = $row['Datum_gew'];
	$date_gew_sh = DBdateToDate($date_gew);
	if ($date_gew_sh == "00-00-0000") $date_gew_sh = "-";
	$name = $row['Meldernaam'];
	$note = $row['Oms_lang'];
	$action = $row['Actiehouder'];
	$prio = $row['Prio'];
	$real = $row['Realisatie'];
	$date_ready = $row['Datum_gereed'];
	$date_ready_sh = DBdateToDate($date_ready);
	if ($date_ready_sh == "00-00-0000") $date_ready_sh = "-";
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$date_sh</div></td>";
	echo "<td><div style=\"text-align:left\">$date_gew_sh</div></td>";
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">$note</div></td>";
	echo "<td><div style=\"text-align:left\">$action</div></td>";
	echo "<td><div style=\"text-align:left\">$prio</div></td>";
	echo "<td><div style=\"text-align:left\">$real</div></td>";
	echo "<td><div style=\"text-align:left\">$date_ready_sh</div></td>";
	if (!isset($mode)) echo "<td><div style=\"text-align:left\"><a href='admin_schade_gebouw_edit.php?id=$id'>Bekijk/<br>bewerk</a></div></td>";
	if (isset($mode)) {
		echo "<td><div style=\"text-align:left\"><a href='admin_schade_gebouw_verw.php?id=$id&mode=Arch'>De-arch.</a></div></td>";
	} else {
		echo "<td><div style=\"text-align:left\"><a href='admin_schade_gebouw_verw.php?id=$id'>Arch.</a></div></td>";
	}
	echo "</tr>";
	$c++;
}
echo "</table>";

mysql_close($link);

?>

</div>
</body>
</html>
