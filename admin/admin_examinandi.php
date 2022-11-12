<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'excie') {
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

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$id = $_GET['id'];

if ($mode == "d") {
	$query = "DELETE FROM examen_inschrijvingen WHERE ID=" . $_GET['part_id'];
	$result = mysql_query($query);
	header('Location: admin_examinandi.php?id=' . $id);
	exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Examencommissie - Bekijk/beheer deelnemers</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_examens.php'>Terug naar examen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>
<p>Deelnemers</p>

<?php
$query = "SELECT * FROM examen_inschrijvingen WHERE Ex_ID=" . $id;
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van kandidaten mislukt.". mysql_error());
}
echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">datum</div></th><th><div style=\"text-align:left\">tijd</div></th><th><div style=\"text-align:left\">Naam</div></th><th><div style=\"text-align:left\">10-11</div></th><th><div style=\"text-align:left\">11-12</div></th><th><div style=\"text-align:left\">s-1</div></th><th><div style=\"text-align:left\">s-2</div></th><th><div style=\"text-align:left\">s-3</div></th><th><div style=\"text-align:left\">w-1</div></th><th><div style=\"text-align:left\">w-2</div></th><th><div style=\"text-align:left\">s</div></th><th><div style=\"text-align:left\">S</div></th><th><div style=\"text-align:left\">g-1</div></th><th><div style=\"text-align:left\">g-2</div></th><th><div style=\"text-align:left\">g-3</div></th><th><div style=\"text-align:left\">T-1</div></th><th><div style=\"text-align:left\">T-2</div></th><th><div style=\"text-align:left\">examinator</div></th><th><div style=\"text-align:left\">tel.nr.</div></th><th><div style=\"text-align:left\">email</div></th><th><div style=\"text-align:left\">instr.eis</div></th><th><div style=\"text-align:left\">resultaat</div></th><th><div style=\"text-align:left\">webm</div></th><th><div style=\"text-align:left\">captain</div></th><th></th></tr>";
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
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "theorie-1") {echo "v";} else {echo "&nbsp;";}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($grade == "theorie-2") {echo "v";} else {echo "&nbsp;";}
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

mysql_close($link);

?>

</div>
</body>
</html>
