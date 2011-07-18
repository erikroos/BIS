<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Admin - Roeigraden</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";
echo "<p><div><a href='./admin_graad_toev.php'>Roeigraad toevoegen&gt;&gt;</a></div></p>";

$query = "SELECT * from roeigraden ORDER BY ID;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van roeigraden mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Roeigraad</div></th><th><div style=\"text-align:left\">Zichtbaar in BIS?</div></th><th><div style=\"text-align:left\">Achtergrondkleur in BIS-botentabel</div></th><th><div style=\"text-align:left\">Kan examen in worden gedaan?</div></th><th colspan=2><div style=\"text-align:left\">&nbsp;</div></th></tr>";

$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$id = $row['ID'];
	$grade = $row['Roeigraad'];
	$show = $row['ToonInBIS'];
	$color = $row['KleurInBIS'];
	$exable = $row['Examinabel'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$grade</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($show) {
		echo "ja";
	} else {
		echo "nee";
	}
	echo "</div></td>";
	echo "<td><div style=\"text-align:left\">$color</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($exable) {
		echo "ja";
	} else {
		echo "nee";
	}
	echo "</div></td>";
	echo "<td><div><a href=\"./admin_graad_toev.php?id=$id\">Wijzigen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_graad_verw.php?id=$id'>Verwijderen</a></div></td>";
	echo "</tr>";
	$c++;
}
echo "</table>";

?>

</div>
</body>
</html>