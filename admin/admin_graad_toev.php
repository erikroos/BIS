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
    <title><?php echo $systeemnaam; ?> - Admin - Roeigraad toevoegen/wijzigen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_graden.php'>Terug naar roeigradenmenu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// ingeval van editen bestaande roeigraad
$id = $_GET['id'];
$query = "SELECT * FROM `roeigraden` WHERE ID='$id' LIMIT 1;";
$result = mysqli_query($link, $query);
if ($result) {
	$rows_aff = mysqli_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysqli_fetch_assoc($result);
		$grade = $row['Roeigraad'];
		$show = $row['ToonInBIS'];
		$color = $row['KleurInBIS'];
		$exable = $row['Examinabel'];
	} else {
		$color = "#FFFF99"; // standaard geel kleurtje
	}
}

// init
if (!$_POST['cancel'] && !$_POST['insert']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['grade'], $_POST['show'], $_POST['color'], $_POST['exable'], $grade, $show, $color, $exable);
	$fail = FALSE;
}

if ($_POST['insert']){
	$grade = $_POST['grade'];
	$show = $_POST['show'];
	$color = $_POST['color'];
	$exable = $_POST['exable'];
	if ($id) {
		$query = "UPDATE `roeigraden` SET Roeigraad='$grade', ToonInBIS='$show', KleurInBIS='$color', Examinabel='$exable' WHERE ID='$id';";
	} else {
		$query = "SELECT MAX(ID) AS MaxID FROM `roeigraden`;";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_assoc($result);
		$id = $row['MaxID'] + 1;
		$query = "INSERT INTO `roeigraden` (ID, Roeigraad, ToonInBIS, KleurInBIS, Examinabel) VALUES ('$id', '$grade', '$show', '$color', '$exable');";
	}
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Invoeren/wijzigen roeigraad mislukt.". mysqli_error());
	} else {
		echo "<p>Roeigraad succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Roeigraad invoeren/wijzigen</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Roeigraad:</td>";
	echo "<td><input type=\"text\" name=\"grade\" value=\"$grade\" size=10 /></td>";
	echo "</tr>";
	
	// functie
	echo "<tr><td>Zichtbaar in BIS?</td>";
	echo "<td><input type=\"checkbox\" name=\"show\" value=1 ";
	if ($show == 1) echo "CHECKED";
	echo "/></td>";
	echo "</tr>";
	
	// mail
	echo "<tr><td>Achtergrondkleur in BIS-botentabel:</td>";
	echo "<td><input type=\"text\" name=\"color\" value=\"$color\" size=7 /></td>";
	echo "</tr>";
	
	// MPB
	echo "<tr><td>Kan examen in worden gedaan?</td>";
	echo "<td><input type=\"checkbox\" name=\"exable\" value=1 ";
	if ($exable == 1) echo "CHECKED";
	echo "/></td>";
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
