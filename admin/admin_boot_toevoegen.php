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
    <title><?php echo $systeemnaam; ?> - Admin - Vlootbeheer - Boot toevoegen/wijzigen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_vloot.php'>Terug naar vlootbeheer</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

if ($_GET['id']) { // bestaande boot
	$id = $_GET['id'];
	$query = "SELECT * FROM `boten` WHERE ID='$id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen bootinformatie mislukt. ". mysql_error());
	} else {
		$row = mysql_fetch_assoc($result);
		$naam = $row['Naam'];
		$gewicht = $row['Gewicht'];
		$type = $row['Type'];
		$roeigraad = $row['Roeigraad'];
	}
} else {
	// Default gewicht = '-'
	$gewicht = "-";
}

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['naam'], $_POST['gewicht'], $_POST['type'], $_POST['roeigraad'], $naam, $gewicht, $type, $roeigraad);
	$fail = FALSE;
}

if ($_POST['insert']){
	$naam_lb = $_POST['naam'];
	$naam = addslashes($naam_lb);
	$gewicht = $_POST['gewicht'];
	$type = $_POST['type'];
	$roeigraad = $_POST['roeigraad'];
	$datum_start = $today_db;
	if ($id) { // wijziging in bestaande boot
		$query = "UPDATE `boten` SET Naam='$naam', Gewicht='$gewicht', Type='$type', Roeigraad='$roeigraad' WHERE ID=$id;";
		$result = mysql_query($query);
		if (!$result) {
			die("Wijzigen $naam_lb mislukt.". mysql_error());
		} else {
			echo "<p>$naam_lb succesvol gewijzigd.</p>";
		}
	} else { // bij nieuwe boot, check op unieke naam-type-combi (incl. historie!)
		$query = "SELECT * FROM `boten` WHERE Naam='$naam' AND Type='$type';";
		$result = mysql_query($query);
		if (!$result) {
			die("Controleren naam $naam_lb mislukt. ". mysql_error());
		} else {
			$rows_aff = mysql_affected_rows($link);
			if ($rows_aff > 0) {
				echo "<p>$naam_lb van type $type niet uniek en dus niet toegestaan. Hierbij wordt ook gekeken naar namen van afgevoerde boten, sinds september 2009.</p>";
			} else {
				$query = "INSERT INTO `boten` (Naam, Gewicht, Type, Roeigraad, Datum_start) VALUES ('$naam', '$gewicht', '$type', '$roeigraad', '$datum_start');";
				$result = mysql_query($query);
				if (!$result) {
					die("Toevoegen $naam_lb mislukt. ". mysql_error());
				} else {
					echo "<p>$naam_lb succesvol toegevoegd.</p>";
				}
			}
		}
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Boot toevoegen/wijzigen</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"naam\" value=\"$naam\" size=50 /></td>";
	echo "<td><em>Svp alleen gewone letters en geen leestekens of apostroffen gebruiken!</em></td>";
	echo "</tr>";
	
	// gewicht
	echo "<tr><td>Gewicht:</td>";
	echo "<td><input type=\"text\" name=\"gewicht\" value=\"$gewicht\" size=3 /></td>";
	echo "</tr>";
	
	// type
	echo "<tr><td>Type:</td>";
	echo "<td><select name=\"type\">";
	$query = "SELECT Type from types;";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen van boottypes mislukt.". mysql_error());
	}
	$c = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$boottype = $row['Type'];
		echo "<option value=\"$boottype\" ";
		if ($type == $boottype) echo "selected=\"selected\"";
		echo ">$boottype</option>";
		$c++;
	}
	echo "</select></td></tr>";
		
	// roeigraad
	echo "<tr><td>Roeigraad:</td>";
	echo "<td><select name=\"roeigraad\">";
	$query = "SELECT Roeigraad FROM roeigraden WHERE ToonInBIS=1 ORDER BY ID;";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen van roeigraden mislukt.". mysql_error());
	}
	$c = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$grade = $row['Roeigraad'];
		echo "<option value=\"$grade\" ";
		if ($roeigraad == $grade) echo "selected=\"selected\"";
		echo ">$grade</option>";
		$c++;
	}
	echo "</select></td></tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Invoeren\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}

mysql_close($link);

?>

</div>
</body>
</html>
