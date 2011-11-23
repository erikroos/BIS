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

setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Examens - Inschrijven voor een examen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
<?php

$id = $_GET['id'];

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['name'], $_POST['grade'], $_POST['age'], $_POST['email'], $_POST['telph'], $name, $grade, $email, $telph);
	$fail = FALSE;
	echo "<p>U wordt niet aangemeld.<br>";
	echo "<a href='index.php'>Terug naar het examenscherm&gt;&gt;</a></p>";
}

if ($_POST['insert']){
	$name = $_POST['name'];
	$grade = $_POST['grade'];
	$age = $_POST['age'];
	$email = $_POST['email'];
	$telph = $_POST['telph'];
	
	if (!CheckName($name)) {
		$fail_msg_name = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	if (!$telph && !$email) {
		$fail_msg_contact = "U dient minimaal ofwel een telefoonnnummer ofwel een e-mailadres op te geven.";
	} else {
		if ($telph && !check_phone_dutch($telph)) {
			$fail_msg_telph = "U dient een geldig 10-cijferig telefoonnummer, met streepje, in te voeren.";
		}
		if ($email && !CheckEmail($email)) {
			$fail_msg_email = "U dient een geldig e-mailadres in te voeren.";
		}
	}
	
	if ($fail_msg_name || $fail_msg_contact || $fail_msg_telph || $fail_msg_email) $fail = TRUE;
	
	if (!$fail) {
		$query = "INSERT INTO `examen_inschrijvingen` (Naam, Graad, Leeftijd, Ex_ID, Email, TelNr) VALUES ('$name', '$grade', '$age', '$id', '$email', '$telph');";
		$result = mysql_query($query);
		if (!$result) {
			die("Inschrijven voor examen mislukt.".mysql_error());
		} else {
			$message = "Naam: ".$name."<br>";
			$message .= "Leeftijd: ".$age."<br>";
			$message .= "Te behalen graad: ".$grade."<br>";
			$query2 = "SELECT Datum FROM `examens` WHERE ID='$id';";
			$result2 = mysql_query($query2);
			$row2 = mysql_fetch_assoc($result2);
			$date_db = $row2['Datum'];
			$message .= "Op: ".DBdateToDate($date_db)."<br>";
			$message .= "Telefoonnummer: ".$telph."<br>";
			$message .= "E-mailadres: ".$email."<br>";
			//SendEmail("tom.erik.roos@gmail.com", "Nieuwe examenaanmelding", $message);
			SendEmail("examens@hunze.nl", "Nieuwe examenaanmelding", $message);
			SendEmail("instructie@hunze.nl", "Nieuwe examenaanmelding", $message);
			echo "<p>Hartelijk dank voor uw aanmelding! Deze is doorgegeven aan de Examencommissie.<br>";
			echo "<a href='index.php'>Terug naar het examenscherm&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Aanmeldformulier</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"$name\" size=45 /></td>";
	if ($fail_msg_name) echo "<td><em>$fail_msg_name</em></td>";
	echo "</tr>";
	
	// leeftijdscategorie
	echo "<tr><td>Leeftijdscategorie:</td>";
	echo "<td><input type=\"radio\" name=\"age\" value=\"jeugd t/m 14 jaar\" ";
	if ($age == 'jeugd t/m 14 jaar') echo "checked='checked'";
	echo "/>jeugd t/m 14 jaar</td></tr>";
	echo "<tr><td></td><td><input type=\"radio\" name=\"age\" value=\"junioren 15 t/m 18 jaar\" ";
	if ($age == 'junioren 15 t/m 18 jaar') echo "checked='checked'";
	echo "/>junioren 15 t/m 18 jaar</td></tr>";
	echo "<tr><td></td><td><input type=\"radio\" name=\"age\" value=\"senioren vanaf 18 jaar\" ";
	if ($age == 'senioren vanaf 18 jaar' || $age == '') echo "checked='checked'";
	echo "/>senioren vanaf 18 jaar</td></tr>";
	echo "<tr><td></td><td><input type=\"radio\" name=\"age\" value=\"veteranen 50+\" ";
	if ($age == 'veteranen 50+') echo "checked='checked'";
	echo "/>veteranen 50+</td></tr>";
	echo "<tr><td></td><td></td></tr>";
	
	// graad
	echo "<tr><td>Te behalen graad:</td>";
	echo "<td><select name=\"grade\" />";
	$query = "SELECT Graden FROM examens WHERE ID='$id';";
	$grade_result = mysql_query($query);
	if (!$grade_result) {
		die("Ophalen van examengraden mislukt.".mysql_error());
	} else {
		if ($row = mysql_fetch_assoc($grade_result)) {
			$grades_db = $row[Graden];
			$grades = split(",", $grades_db);
			foreach($grades as $curr_grade) {
				echo "<option value=\"".$curr_grade."\" ";
				if ($grade == $curr_grade) echo "selected";
				echo "/>".$curr_grade;
			}
		}
	}
	echo "</select></td>";
	echo "</tr>";
	
	echo "<tr><td colspan=3><em>U dient minstens &eacute;&eacute;n van onderstaande velden in te vullen.<br>De gegevens worden niet op de examenpagina getoond, maar alleen doorgegeven aan de Examencommissie.</em></td></tr>";
	
	// telefoonnr.
	echo "<tr><td>Telefoonnummer (10 cijfers, met streepje):</td>";
	echo "<td><input type=\"text\" name=\"telph\" value=\"$telph\" size=11 /></td>";
	if ($fail_msg_contact) {
		echo "<td><em>$fail_msg_contact</em></td>";
	} else {
		if ($fail_msg_telph) echo "<td><em>$fail_msg_telph</em></td>";
	}
	echo "</tr>";
	
	// e-mail
	echo "<tr><td>E-mailadres:</td>";
	echo "<td><input type=\"text\" name=\"email\" value=\"$email\" size=45 /></td>";
	if ($fail_msg_email) echo "<td><em>$fail_msg_email</em></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Inschrijven\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}

mysql_close($link);

?>
</div>
</body>
</html>
