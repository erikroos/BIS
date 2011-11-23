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
    <title><? echo $systeemnaam; ?> - Admin - Examen toevoegen/wijzigen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="../scripts/kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_examens.php'>Terug naar examen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$id = $_GET['id']; // wijzigen bestaand examen
if ($id && ($id < 0 || !is_numeric($id))) { // check op ID
	echo "Er is iets misgegaan.";
	exit();
}
$grades = array();
$query = "SELECT * FROM `examens` WHERE ID='$id';";
$result = mysql_query($query);
if ($result) {
	$rows_aff = mysql_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysql_fetch_assoc($result);
		$date_db = $row['Datum'];
		$date = DBdateToDate($date_db);
		$quotum = $row['Quotum'];
		$description = $row['Omschrijving'];
		$grades_db = $row['Graden'];
		$grades = split(",", $grades_db);
	}
}

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['date'], $_POST['quotum'], $_POST['description'], $date, $quotum, $description);
	$fail = FALSE;
	echo "<p>Invoer examen geannuleerd.<br><a href='admin_examens.php'>Terug naar de examenpagina&gt;&gt;</a></p>";
}

if ($_POST['insert']){
	$date = $_POST['date'];
	$date_db = DateToDBdate($date);
	$description = $_POST['description'];
	
	$grades_db = '';
	$query = "SELECT Roeigraad FROM roeigraden WHERE Examinabel=1 ORDER BY ID;";
	$grade_result = mysql_query($query);
	if (!$grade_result) {
		die("Ophalen van examengraden mislukt.".mysql_error());
	} else {
		$first_time = false;
		while ($row = mysql_fetch_assoc($grade_result)) {
			$curr_grade = $row[Roeigraad];
			if ($_POST[$curr_grade] == "true") {
				if ($first_time == false) {
					$first_time = true;
				} else {
					$grades_db .= ",";
				}
				$grades_db .= $curr_grade;
			}
		}
	}
	
	$quotum = $_POST['quotum'];
	if ($quotum <= 0 || !is_numeric($quotum)) $fail_msg_quotum = "U dient een aantal groter dan 0 op te geven.";
	if ($id) {
		$query = "SELECT COUNT(*) AS NrOfExi FROM `examen_inschrijvingen` WHERE Ex_ID='$id'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$nr_of_exi = $row['NrOfExi'];
		if ($nr_of_exi > $quotum) $fail_msg_quotum = "Het quotum mag niet lager zijn dan het aantal reeds ingeschreven kandidaten.";
	}
	
	if ($fail_msg_quotum) {
		$fail = TRUE;
	} else{
		if ($id) {
			$query = "UPDATE `examens` SET Datum='$date_db', Omschrijving='$description', Graden='$grades_db', Quotum='$quotum' WHERE ID='$id';";
		} else {
			$query = "INSERT INTO `examens` (Datum, Omschrijving, Graden, Quotum, ToonOpSite) VALUES ('$date_db', '$description', '$grades_db', '$quotum', '1');";
		}
		$result = mysql_query($query);
		if (!$result) {
			die("Invoeren/wijzigen examen mislukt.". mysql_error());
		} else {
			echo "<p>Examen succesvol toegevoegd/gewijzigd.<br><a href='admin_examens.php'>Terug naar de examenpagina&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Examen invoeren/wijzigen</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// datum
	echo "<tr><td>Datum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' onchange='' name='date' id='date' size='8' maxlength='10' value='$date'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.date');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	echo "</tr>";
	
	// omschrijving
	echo "<tr><td>Omschrijving (max. 45 tekens):</td>";
	echo "<td><input type=\"text\" name=\"description\" value=\"$description\" size=45 /></td>";
	echo "</tr>";
	
	// te behalen graden
	echo "<tr><td>Te behalen graden:</td>";
	$query = "SELECT Roeigraad FROM roeigraden WHERE Examinabel=1 ORDER BY ID;";
	$grade_result = mysql_query($query);
	if (!$grade_result) {
		die("Ophalen van examengraden mislukt.".mysql_error());
	} else {
		while ($row = mysql_fetch_assoc($grade_result)) {
			$curr_grade = $row[Roeigraad];
			echo "<td><input type='checkbox' name='$curr_grade' value='true' ";
			if (in_array($curr_grade, $grades)) echo "checked='checked'";
			echo "/>$curr_grade</td></tr><tr><td></td>";
		}
	}
	echo "<td></td></tr>";
	
	// quotum
	echo "<tr><td>Quotum:</td>";
	echo "<td><input type=\"text\" name=\"quotum\" value=\"$quotum\" size=3 /></td>";
	if ($fail_msg_quotum) echo "<td><em>".$fail_msg_quotum."</em></td>";
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

<script language="javascript">

function ChangeInfo(){
	return true;
}

</script>
