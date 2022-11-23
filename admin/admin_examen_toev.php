<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Admin - Examen toevoegen/wijzigen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
	<script language="javascript" src="../scripts/kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_examens.php'>Terug naar examen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

if (isset($_GET['id'])) {
	$id = $_GET['id']; // wijzigen bestaand examen
	if ($id < 0 || !is_numeric($id)) {
		echo "Er is iets misgegaan.";
		exit();
	}
	$grades = array();
	$query = "SELECT * FROM `examens` WHERE ID=" . $id;
	$result = mysqli_query($link, $query);
	if ($result) {
		if (mysqli_affected_rows($link) > 0) {
			$row = mysqli_fetch_assoc($result);
			$date_db = $row['Datum'];
			$date = DBdateToDate($date_db);
			$quotum = $row['Quotum'];
			$description = $row['Omschrijving'];
			$grades_db = $row['Graden'];
			$grades = split(",", $grades_db);
		}
	}
}

// init
if (!isset($_POST['cancel']) && !isset($_POST['insert'])) {
	$fail = false;
}

// knop gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['date'], $_POST['quotum'], $_POST['description'], $date, $quotum, $description);
	$fail = false;
	echo "<p>Invoer examen geannuleerd.<br><a href='admin_examens.php'>Terug naar de examenpagina&gt;&gt;</a></p>";
}

if (isset($_POST['insert'])) {
	$date = $_POST['date'];
	$date_db = DateToDBdate($date);
	$description = $_POST['description'];
	$grades_db = '';
	$query = "SELECT Roeigraad FROM roeigraden WHERE Examinabel=1 ORDER BY ID;";
	$grade_result = mysqli_query($link, $query);
	if (!$grade_result) {
		die("Ophalen van examengraden mislukt: " . mysqli_error());
	} else {
		$first_time = false;
		while ($row = mysqli_fetch_assoc($grade_result)) {
			$curr_grade = $row['Roeigraad'];
			if (array_key_exists($curr_grade, $_POST) && $_POST[$curr_grade] == "true") {
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
	if ($quotum <= 0 || !is_numeric($quotum)) {
		$fail_msg_quotum = "U dient een aantal groter dan 0 op te geven.";
	}
	if ($id) {
		$query = "SELECT COUNT(*) AS NrOfExi FROM `examen_inschrijvingen` WHERE Ex_ID=" . $id;
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_assoc($result);
		$nr_of_exi = $row['NrOfExi'];
		if ($nr_of_exi > $quotum) {
			$fail_msg_quotum = "Het quotum mag niet lager zijn dan het aantal reeds ingeschreven kandidaten.";
		}
	}
	
	if (isset($fail_msg_quotum)) {
		$fail = true;
	} else{
		if ($id) {
			$query = "UPDATE `examens` SET Datum='$date_db', Omschrijving='$description', Graden='$grades_db', Quotum='$quotum' WHERE ID='$id';";
		} else {
			$query = "INSERT INTO `examens` (Datum, Omschrijving, Graden, Quotum, ToonOpSite) VALUES ('$date_db', '$description', '$grades_db', '$quotum', '1');";
		}
		$result = mysqli_query($link, $query);
		if (!$result) {
			die("Invoeren/wijzigen examen mislukt: " . mysqli_error());
		} else {
			echo "<p>Examen succesvol toegevoegd/gewijzigd.<br><a href='admin_examens.php'>Terug naar de examenpagina&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) || (isset($fail) && $fail == true)) {
	echo "<p><b>Examen invoeren/wijzigen</b></p>";
	echo "<form name='form' action='" . $_SERVER['REQUEST_URI'] . "' method='post'>";
	echo "<table>";
	
	// datum
	echo "<tr><td>Datum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' name='date' id='date' size='8' maxlength='10' value='" . (isset($date) ? $date : '') . "'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.date');return true;\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	echo "</tr>";
	
	// omschrijving
	echo "<tr><td>Omschrijving (max. 45 tekens):</td>";
	echo "<td><input type='text' name='description' value='" . (isset($description) ? $description : '') . "' size=45 /></td>";
	echo "</tr>";
	
	// te behalen graden
	echo "<tr><td>Te behalen graden:</td>";
	$query = "SELECT Roeigraad FROM roeigraden WHERE Examinabel=1 ORDER BY ID;";
	$grade_result = mysqli_query($link, $query);
	if (!$grade_result) {
		die("Ophalen van examengraden mislukt: " . mysqli_error());
	} else {
		while ($row = mysqli_fetch_assoc($grade_result)) {
			$curr_grade = $row['Roeigraad'];
			echo "<td><input type='checkbox' name='" . $curr_grade . "' value='true' ";
			if (isset($grades) && in_array($curr_grade, $grades)) {
				echo "checked='checked'";
			}
			echo "/>" . $curr_grade . "</td></tr><tr><td></td>";
		}
	}
	echo "<td></td></tr>";
	
	// quotum
	echo "<tr><td>Quotum:</td>";
	echo "<td><input type='text' name='quotum' value='" . (isset($quotum) ? $quotum : '') . "' size=3 /></td>";
	if (isset($fail_msg_quotum)) {
		echo "<td><em>" . $fail_msg_quotum . "</em></td>";
	}
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
