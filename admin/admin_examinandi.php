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
    <title><? echo $systeemnaam; ?> - Examencommissie - Bekijk/beheer deelnemers</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_examens.php'>Terug naar examen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$mode = $_GET['mode'];
$id = $_GET['id'];
$part_id = $_GET['part_id'];

if ($mode == "d" && $part_id) {
	$query = "DELETE FROM examen_inschrijvingen WHERE ID='$part_id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Verwijderen van deelnemer mislukt.". mysql_error());
	}
	echo "Verwijderen van deelnemer gelukt.<br>";
	echo "<a href='admin_examinandi.php?id=$id'>Terug naar de deelnemerspagina&gt;&gt;</a>";
	exit;
}

echo "<p>Deelnemers</p>";

$query = "SELECT * FROM examen_inschrijvingen WHERE Ex_ID='$id';";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van kandidaten mislukt.". mysql_error());
}
echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">datum</div></th><th><div style=\"text-align:left\">tijd</div></th><th><div style=\"text-align:left\">Naam</div></th><th><div style=\"text-align:left\">10-11</div></th><th><div style=\"text-align:left\">11-12</div></th><th><div style=\"text-align:left\">s-1</div></th><th><div style=\"text-align:left\">s-2</div></th><th><div style=\"text-align:left\">s-3</div></th><th><div style=\"text-align:left\">w-1</div></th><th><div style=\"text-align:left\">w-2</div></th><th><div style=\"text-align:left\">s</div></th><th><div style=\"text-align:left\">S</div></th><th><div style=\"text-align:left\">g-1</div></th><th><div style=\"text-align:left\">g-2</div></th><th><div style=\"text-align:left\">g-3</div></th><th><div style=\"text-align:left\">examinator</div></th><th><div style=\"text-align:left\">tel.nr.</div></th><th><div style=\"text-align:left\">email</div></th><th><div style=\"text-align:left\">instr.eis</div></th><th><div style=\"text-align:left\">resultaat</div></th><th><div style=\"text-align:left\">webm</div></th><th><div style=\"text-align:left\">captain</div></th><th></th></tr>";
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$part_id = $row['ID'];
	$name = $row['Naam'];
	$grade = $row['Graad'];
	$telph = $row['TelNr'];
	$email = $row['Email'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "skiff-1") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "skiff-2") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "skiff-3") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "wherry-1") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "wherry-2") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "kleine-s") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "grote-S") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "giek-1") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "giek-2") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "giek-3") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">$telph</div></td>";
	echo "<td><div style=\"text-align:left\">$email</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><div style=\"text-align:left\">&nbsp;</div></td>";
	echo "<td><a href='admin_examinandi.php?mode=d&id=$id&part_id=$part_id'>Verwijder</a></td>";
	echo "</tr>";
}
echo "</table>";
echo "&nbsp;";

?>

</div>
</body>
</html>