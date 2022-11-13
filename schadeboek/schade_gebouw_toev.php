<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Klachtenboek Gebouw/Algemeen - Nieuwe klacht/schademelding</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
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
	unset($_POST['name'], $_POST['note'], $name, $note);
	$fail = FALSE;
	echo "<p>De klacht zal niet worden gemeld.<br>";
	echo "<a href='index_gebouw.php'>Terug naar het klachtenoverzicht voor het gebouw/algemeen&gt;&gt;</a></p>";
}

if (isset($_POST['insert'])){
	$name = $_POST['name'];
	$note = addslashes($_POST['note']);
	
	if (!CheckName($name)) {
		$fail_msg_name = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	
	if (isset($fail_msg_name)) $fail = TRUE;
	
	if (!isset($fail)) {
		$query = "INSERT INTO `schades_gebouw` (Datum, Naam, Oms_lang) VALUES ('$today_db', '$name', '$note');";
		$result = mysqli_query($link, $query);
		if (!$result) {
			die("Invoeren klacht mislukt.". mysql_error());
		} else {
		    // mail aan gebcie
			$message = $name." heeft zojuist een klacht gedaan:<br>".$note."<br>";
			SendEmail("penningmeester@hunze.nl", "Nieuwe klacht/schademelding", $message);
			// feedback op scherm
			echo "<p>Hartelijk dank voor uw melding! De klacht is doorgegeven aan de Gebouwcommissie.<br>";
			echo "Mocht u de melding nog nader willen toelichten of willen wijzigen, neemt u dan contact op via <a href='mailto:penningmeester@hunze.nl'>e-mail</a>.<br>";
			echo "<a href='index_gebouw.php'>Terug naar het klachtenoverzicht voor het gebouw&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) || (isset($fail) && $fail == true)) {
	echo "<p><b>Klacht/schademelding invoeren</b></p>";
	echo "<form name='form' action=\"" . (isset($REQUEST_URI) ? $REQUEST_URI : "") . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"" . (isset($name) ? $name : "") . "\" size=45 /></td>";
	if (isset($fail_msg_name)) echo "<td><em>" . $fail_msg_name . "</em></td>";
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

mysqli_close($link);
?>
</div>
</body>
</html>
