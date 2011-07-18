<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'instrcie') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Instructiecommissie - Bekijk/beheer deelnemers</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_cursussen.php'>Terug naar cursus-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$mode = $_GET['mode'];
$id = $_GET['id'];
$part_id = $_GET['part_id'];

if ($mode == "d" && $part_id) {
	$query = "DELETE FROM cursus_inschrijvingen WHERE ID='$part_id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Verwijderen van deelnemer mislukt.". mysql_error());
	}
	echo "Verwijderen van deelnemer gelukt.<br />";
	echo "<a href='admin_cursisten.php?id=$id'>Terug naar de deelnemerspagina&gt;&gt;</a>";
	exit;
}

echo "<p>Deelnemers</p>";

$query = "SELECT * FROM cursus_inschrijvingen WHERE Ex_ID='$id';";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van kandidaten mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Naam</div></th><th><div style=\"text-align:left\">Tegenprestatie</div></th><th><div style=\"text-align:left\">Telefoon</div></th><th><div style=\"text-align:left\">E-mail</div></th><th></th></tr>";
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$part_id = $row['ID'];
	$name = $row['Naam'];
	$demand = $row['Demand'];
	$telph = $row['TelNr'];
	$email = $row['Email'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">$demand</div></td>";
	echo "<td><div style=\"text-align:left\">$telph</div></td>";
	echo "<td><div style=\"text-align:left\">$email</div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_cursisten.php?mode=d&id=$id&part_id=$part_id'>Verwijder</a></div></td>";
	echo "</tr>";
}
echo "</table>";
?>

</div>
</body>
</html>