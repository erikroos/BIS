<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
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
    <title><?php echo $systeemnaam; ?> - Admin - Vlootbeheer</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";
echo "<p><div><a href='./admin_boot_toevoegen.php'>Boot toevoegen&gt;&gt;</a></div></p>";

$query = "SELECT ID, Naam, Gewicht, Type, Roeigraad from boten WHERE Datum_eind IS NULL ORDER BY Naam;";
$boats_result = mysql_query($query);
if (!$boats_result) {
	die("Ophalen van boten-informatie mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Naam</div></th><th><div style=\"text-align:left\">Gewicht</div></th><th><div style=\"text-align:left\">Type</div></th><th><div style=\"text-align:left\">Roeigraad</div></th><th><div style=\"text-align:left\">Status</div></th><th colspan=3><div style=\"text-align:left\">Aanpassen</div></th></tr>";
$c = 0;
while ($row = mysql_fetch_assoc($boats_result)) {
	$id = $row['ID'];
	$name = $row['Naam'];
	$name_tmp = addslashes($name);
	$weight = $row['Gewicht'];
	$type = $row['Type'];
	$type_plus = preg_replace('/\+/', 'plus', $type); // +tekens redden bij overdracht via GET
	$grade = $row['Roeigraad'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$name</div></td>";	
	echo "<td><div style=\"text-align:left\">$weight</div></td>";
	echo "<td><div style=\"text-align:left\">$type</div></td>";
	echo "<td><div style=\"text-align:left\">$grade</div></td>";
	
	// in/uit de vaart
	echo "<td><div style=\"text-align:left\">";
	$query2 = sprintf('SELECT * 
			FROM uitdevaart 
			WHERE Verwijderd=0 
			AND Boot_ID=%d 
			AND Startdatum<="%s" 
			AND (Einddatum="0" OR Einddatum="0000-00-00" OR Einddatum IS NULL OR Einddatum>="%s")', 
				$id, $today_db, $today_db);
	$result2 = mysql_query($query2);
	if (!$result2) {
		die("Ophalen van Uit de Vaart-informatie mislukt.". mysql_error());
	} else {
		$rows_aff = mysql_affected_rows($link);
		if ($rows_aff > 0) {
			echo "UIT";
		} else {
			echo "IN";
		}
	}
	echo " de vaart</div></td>";
	// einde in/uit de vaart
	
	echo "<td><div><a href=\"./admin_inuitdevaart.php?id=$id\">In/uit de vaart</a>&nbsp;&nbsp;&nbsp;</div></td>";
	echo "<td><div><a href=\"./admin_boot_toevoegen.php?id=$id\">Wijzigen</a>&nbsp;&nbsp;&nbsp;</div></td>";
	echo "<td><div><a href=\"./admin_boot_verwijderen.php?id=$id\">Verwijderen</a></div></td>";
	echo "</tr>";
	$c++;
}
echo "</table>";

mysql_close($link);
?>

</div>
</body>
</html>