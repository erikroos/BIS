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
    <title><? echo $systeemnaam; ?> - Admin - Bestuursmededeling toevoegen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_mededeling.php'>Terug naar mededelingen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// ingeval van editen bestaande mededeling
$id = $_GET['id'];
if ($id && ($id < 0 || !is_numeric($id))) { // check op ID
	echo "Er is iets misgegaan.";
	exit();
}
$query = "SELECT * FROM `mededelingen` WHERE ID='$id';";
$result = mysql_query($query);
if ($result) {
	$rows_aff = mysql_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysql_fetch_assoc($result);
		$name = $row['Bestuurslid'];
		$summary = $row['Betreft'];
		$note = $row['Mededeling'];
	}
}

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['name'], $_POST['summary'], $_POST['note'], $name, $summary, $note);
	$fail = FALSE;
}

if ($_POST['insert']){
	$name = $_POST['name'];
	$summary = addslashes($_POST['summary']);
	$note = addslashes($_POST['note']);
	if ($id) {
		$query = "UPDATE `mededelingen` SET Datum='$today_db', Bestuurslid='$name', Betreft='$summary', Mededeling='$note' WHERE ID='$id';";
	} else {
		$max1 = 1;
		$max2 = 1;
		$query = "SELECT MAX(ID) AS Max1 FROM `mededelingen`;";
		$result = mysql_query($query);
		if ($result) {
			$row = mysql_fetch_assoc($result);
			$max1 = $row['Max1'];
		}
		$query = "SELECT MAX(ID) AS Max2 FROM `mededelingen_oud`;";
		$result = mysql_query($query);
		if ($result) {
			$row = mysql_fetch_assoc($result);
			$max2 = $row['Max2'];
		}
		$new_id = max($max1, $max2) + 1;
		$query = "INSERT INTO `mededelingen` (ID, Datum, Bestuurslid, Betreft, Mededeling) VALUES ('$new_id', '$today_db', '$name', '$summary', '$note');";
	}
	$result = mysql_query($query);
	if (!$result) {
		die("Invoeren mededeling mislukt.". mysql_error());
	} else {
		echo "<p>Mededeling succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Bestuursmededeling invoeren</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"$name\" size=50 /></td>";
	echo "</tr>";
	
	// betreft
	echo "<tr><td>Betreft:</td>";
	echo "<td><input type=\"text\" name=\"summary\" value=\"$summary\" size=45 /></td>";
	echo "</tr>";
	
	// mededeling
	echo "<tr><td>Mededeling (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"note\" rows=4 cols=50/>$note</textarea></td>";
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