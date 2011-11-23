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
    <title><? echo $systeemnaam; ?> - Admin - Boottype toevoegen/wijzigen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_types.php'>Terug naar boottypemenu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// ingeval van editen bestaand boottype
$type_ex = $_GET['type'];
$query = "SELECT * FROM `types` WHERE Type='$type_ex' LIMIT 1;";
$result = mysql_query($query);
if ($result) {
	$rows_aff = mysql_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysql_fetch_assoc($result);
		$type = $row['Type'];
		$cat = $row['Categorie'];
		$sort = $row['Roeisoort'];
	}
}

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['type'], $_POST['cat'], $_POST['sort'], $type, $cat, $sort);
	$fail = FALSE;
}

if ($_POST['insert']){
	$type = $_POST['type'];
	$cat = $_POST['cat'];
	$sort = $_POST['sort'];
	if ($type_ex) {
		$query = "UPDATE `types` SET Type='$type', Categorie='$cat', Roeisoort='$sort' WHERE Type='$type_ex';";
	} else {
		$query = "INSERT INTO `types` (Type, Categorie, Roeisoort) VALUES ('$type', '$cat', '$sort');";
	}
	$result = mysql_query($query);
	if (!$result) {
		die("Invoeren/wijzigen boottype mislukt.". mysql_error());
	} else {
		echo "<p>Boottype succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Boottype invoeren/wijzigen</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Type:</td>";
	echo "<td><input type=\"text\" name=\"type\" value=\"$type\" size=10 /></td>";
	echo "</tr>";
	
	// categorie
	echo "<tr><td>Categorie:</td>";
	echo "<td><input type=\"text\" name=\"cat\" value=\"$cat\" size=40 /></td>";
	echo "</tr>";
	
	echo "<tr><td colspan=2><em>Meerdere types kunnen deel uitmaken van dezelfde categorie</em></td></tr>";
	
	// roeisoort
	echo "<tr><td>Roeisoort (boord/scull):</td>";
	echo "<td><input type=\"text\" name=\"sort\" value=\"$sort\" size=10 /></td>";
	echo "</tr>";
	
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