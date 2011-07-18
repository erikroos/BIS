<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'excie') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Examencommissie</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$mode = $_GET['mode'];
$curval = $_GET['curval'];
$id = $_GET['id'];

if ($mode == "c" && $id) {
	if ($curval) {
		$query = "UPDATE examens SET ToonOpSite=0 WHERE ID='$id';";
	} else {
		$query = "UPDATE examens SET ToonOpSite=1 WHERE ID='$id';";
	}
	$result = mysql_query($query);
	if (!$result) {
		die("Tonen/verbergen van examen mislukt.". mysql_error());
	}
	echo "Tonen/verbergen van examen gelukt.<br>";
	echo "<a href='admin_examens.php'>Terug naar de examenpagina&gt;&gt;</a>";
	exit;
}
if ($mode == "d" && $id) {
	$query = "DELETE FROM examens WHERE ID='$id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Verwijderen van examen mislukt.". mysql_error());
	}
	echo "Verwijderen van examen gelukt.<br>";
	echo "<a href='admin_examens.php'>Terug naar de examenpagina&gt;&gt;</a>";
	exit;
}

echo "<p>Examencommissie</p>";
echo "<p><a href='admin_examen_toev.php'>Maak een nieuw examen aan&gt;&gt;</a></p>";

$query = "SELECT * FROM examens ORDER BY Datum;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van examens mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Datum</div></th><th><div style=\"text-align:left\">Omschrijving</div></th><th><div style=\"text-align:left\">Graden</div></th><th><div style=\"text-align:left\">Quotum</div></th><th><div style=\"text-align:left\">Open voor inschrijving</div></th><th colspan=4></th></tr>";
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$id = $row['ID'];
	$date = $row['Datum'];
	$date_sh = DBdateToDate($date);
	$description = $row['Omschrijving'];
	$grades_db = $row['Graden'];
	$quotum = $row['Quotum'];
	$show = $row['ToonOpSite'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$date_sh</div></td>";
	echo "<td><div style=\"text-align:left\">$description</div></td>";
	echo "<td><div style=\"text-align:left\">$grades_db</div></td>";
	echo "<td><div style=\"text-align:left\">$quotum</div></td>";
	if ($show) {
		echo "<td><div style=\"text-align:left\">ja";
	} else {
		echo "<td><div style=\"text-align:left\">nee";
	}
	echo "&nbsp;[<a href='admin_examens.php?mode=c&curval=$show&id=$id'>Wijzig</a>]</div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_examen_toev.php?id=$id'>Wijzigen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_examens.php?mode=d&id=$id'>Verwijderen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_examinandi.php?id=$id'>Bekijk/beheer deelnemers</a></div></td>";
	echo "</tr>";
}
echo "</table>";
?>

</div>
</body>
</html>