<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Schadeboek Gebouw - Nieuwe schade melden</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
<?php

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['name'], $_POST['note'], $name, $note);
	$fail = FALSE;
	echo "<p>De schade zal niet worden gemeld.<br>";
	echo "<a href='index_gebouw.php'>Terug naar het schadeoverzicht voor het gebouw&gt;&gt;</a></p>";
}

if ($_POST['insert']){
	$name = $_POST['name'];
	$note = addslashes($_POST['note']);
	
	if (!CheckName($name)) {
		$fail_msg_name = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	
	if ($fail_msg_name) $fail = TRUE;
	
	if (!$fail) {
		$query = "INSERT INTO `schades_gebouw` (Datum, Naam, Oms_lang) VALUES ('$today_db', '$name', '$note');";
		$result = mysql_query($query);
		if (!$result) {
			die("Invoeren schade mislukt.". mysql_error());
		} else {
		    // mail aan gebcie
			$message = $name." heeft zojuist een schade gemeld:<br>".$note."<br>";
			SendEmail("penningmeester@hunze.nl", "Nieuwe schademelding", $message);
			// feedback op scherm
			echo "<p>Hartelijk dank voor uw melding! De schade is doorgegeven aan de Gebouwcommissie.<br>";
			echo "Mocht u de melding nog nader willen toelichten of willen wijzigen, neemt u dan contact op via <a href='mailto:penningmeester@hunze.nl'>e-mail</a>.<br>";
			echo "<a href='index_gebouw.php'>Terug naar het schadeoverzicht voor het gebouw&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Schademelding invoeren</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"$name\" size=45 /></td>";
	if ($fail_msg_name) echo "<td><em>$fail_msg_name</em></td>";
	echo "</tr>";
	
	// mededeling
	echo "<tr><td>Omschrijving (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"note\" rows=4 cols=50/>$note</textarea></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Invoeren\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}

?>
</div>
</body>
</html>