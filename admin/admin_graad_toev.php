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
if (isset($_GET['id'])) {
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
}

// Annuleren gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['grade'], $_POST['show'], $_POST['color'], $_POST['exable'], $grade, $show, $color, $exable);
}

if (isset($_POST['insert'])) {
	$grade = $_POST['grade'];
	$show = isset($_POST['show']) ? $_POST['show'] : 0;
	$color = $_POST['color'];
	$exable = isset($_POST['exable']) ? $_POST['exable'] : 0;
	if (isset($id)) {
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
		die("Invoeren/wijzigen roeigraad $grade mislukt.". mysqli_error());
	} else {
		echo "<p>Roeigraad $grade succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if (!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) {
	echo "<p><b>Roeigraad invoeren/wijzigen</b></p>";
	echo "<form name='form' action=\"". $_SERVER['REQUEST_URI'] . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Roeigraad:</td>";
	echo "<td><input type=\"text\" name=\"grade\" value=\"" . (isset($grade) ? $grade : '') . "\" size=10 /></td>";
	echo "</tr>";
	
	// functie
	echo "<tr><td>Zichtbaar in BIS?</td>";
	echo "<td><input type=\"checkbox\" name=\"show\" value=1 ";
	if (isset($show) && $show == 1) echo "checked";
	echo "/></td>";
	echo "</tr>";
	
	// mail
	echo "<tr><td>Achtergrondkleur in BIS-botentabel:</td>";
	echo "<td><input type=\"text\" name=\"color\" value=\"" . (isset($color) ? $color : '') . "\" size=7 /></td>";
	echo "</tr>";
	
	// MPB
	echo "<tr><td>Kan examen in worden gedaan?</td>";
	echo "<td><input type=\"checkbox\" name=\"exable\" value=1 ";
	if (isset($exable) && $exable == 1) echo "checked";
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
