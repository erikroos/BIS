<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
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
    <title>BotenInschrijfSysteem - Klachtenboek Boten - Nieuwe klacht/schademelding</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
<?php

// init
if (!isset($_POST['cancel']) && !isset($_POST['insert'])) {
	$fail = FALSE;
}

// knop gedrukt
if (isset($_POST['cancel'])){
	unset($_POST['name'], $_POST['boat_id'], $_POST['note'], $name, $boat_id, $note);
	$fail = FALSE;
	echo "<p>De klacht zal niet worden gemeld.<br>";
	echo "<a href='index_boten.php'>Terug naar het klachtenoverzicht voor de boten&gt;&gt;</a></p>";
}

if (isset($_POST['insert'])){
	$name = $_POST['name'];
	$boat_id = $_POST['boat_id'];
	// bootnaam
	if ($boat_id == 0) {
		$boat = "algemeen";
	} else {
		$query2 = "SELECT Naam from boten WHERE ID=$boat_id;";
		$result2 = mysql_query($query2);
		$row2 = mysql_fetch_assoc($result2);
		$boat = $row2['Naam'];
	}
	//
	$note = addslashes($_POST['note']);
	
	if (!CheckName($name)) {
		$fail_msg_name = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	
	if (isset($fail_msg_name)) $fail = TRUE;
	
	if (!isset($fail)) {
		$query = "INSERT INTO `schades` (Datum, Naam, Boot_ID, Oms_lang) VALUES ('$today_db', '$name', '$boat_id', '$note');";
		$result = mysql_query($query);
		if (!$result) {
			die("Invoeren klacht mislukt.". mysql_error());
		} else {
		    // mail aan matcom
			$message = $name." heeft zojuist een klacht gedaan betreffende '".$boat."'.<br>";
			SendEmail("materiaal@hunze.nl", "Nieuwe klacht/schademelding", $message);
			// feedback op scherm
			echo "<p>Hartelijk dank voor uw melding! De klacht is doorgegeven aan de Materiaalcommissie.<br>";
			echo "Mocht u de melding nog nader willen toelichten of willen wijzigen, neemt u dan contact op via <a href='mailto:materiaal@hunze.nl'>e-mail</a>.<br>";
			echo "<a href='index_boten.php'>Terug naar het klachtenoverzicht voor de boten&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) || isset($fail)) {
	echo "<p><b>Klacht/schademelding invoeren</b></p>";
	echo "<form name='form' action=\"" . (isset($REQUEST_URI) ? $REQUEST_URI : "") . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"" . (isset($name) ? $name : "") . "\" size=45 /></td>";
	if (isset($fail_msg_name)) echo "<td><em>" . $fail_msg_name . "</em></td>";
	echo "</tr>";
	
	// boot
	echo "<tr><td>Boot/ergometer:</td>";
	echo "<td><select name=\"boat_id\">";
	// optie 'algemeen' verwijderd op verzoek van Karel Engbers d.d. 03-10-2011
	//echo "<option value=0 ";
	//if ($boat_id == 0) echo "selected=\"selected\"";
	//echo ">algemeen</option>";
	$query = "SELECT ID, Naam, Type FROM boten WHERE Datum_eind IS NULL AND Type<>\"soc\" ORDER BY Naam;";
	$boats_result = mysql_query($query);
	if (!$boats_result) {
		die("Ophalen van vlootinformatie mislukt.". mysql_error());
	} else {
		while ($row = mysql_fetch_assoc($boats_result)) {
			$curr_boat_id = $row['ID'];
			$curr_boat = $row['Naam'];
			$type = $row['Type'];
			echo "<option value=".$curr_boat_id." ";
			if (isset($boat_id) && $boat_id == $curr_boat_id) echo "selected=\"selected\"";
			echo ">".$curr_boat." (".$type.")</option>";
		}
	}
	echo "</select></td>";
	echo "</tr>";
	
	// mededeling
	echo "<tr><td>Omschrijving (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"note\" rows=4 cols=50/>" . (isset($note) ? $note : "") . "</textarea></td>";
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