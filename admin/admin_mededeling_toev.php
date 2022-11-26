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
    <title><?php echo $systeemnaam; ?> - Admin - Bestuursmededeling toevoegen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_mededeling.php'>Terug naar mededelingen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// ingeval van editen bestaande mededeling
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($id && ($id < 0 || !is_numeric($id))) { // check op ID
        echo "Er is iets misgegaan.";
        exit();
    }
    $query = "SELECT * FROM `mededelingen` WHERE ID='$id';";
    $result = mysqli_query($link, $query);
    if ($result) {
        $rows_aff = mysqli_affected_rows($link);
        if ($rows_aff > 0) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['Bestuurslid'];
            $summary = $row['Betreft'];
            $note = $row['Mededeling'];
        }
    }
}

// Annuleren gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['name'], $_POST['summary'], $_POST['note'], $name, $summary, $note);
}

if (isset($_POST['insert'])) {
	$name = $_POST['name'];
	$summary = addslashes($_POST['summary']);
	$note = addslashes($_POST['note']);
	if (isset($id)) {
		$query = "UPDATE `mededelingen` SET Datum='$today_db', Bestuurslid='$name', Betreft='$summary', Mededeling='$note' WHERE ID='$id';";
	} else {
		$max1 = 1;
		$max2 = 1;
		$query = "SELECT MAX(ID) AS Max1 FROM `mededelingen`;";
		$result = mysqli_query($link, $query);
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			$max1 = $row['Max1'];
		}
		$query = "SELECT MAX(ID) AS Max2 FROM `mededelingen_oud`;";
		$result = mysqli_query($link, $query);
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			$max2 = $row['Max2'];
		}
		$new_id = max($max1, $max2) + 1;
		$query = "INSERT INTO `mededelingen` (ID, Datum, Bestuurslid, Betreft, Mededeling) VALUES ('$new_id', '$today_db', '$name', '$summary', '$note');";
	}
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Invoeren mededeling mislukt: ". mysqli_error());
	} else {
		echo "<p>Mededeling succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if (!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) {
	echo "<p><b>Bestuursmededeling invoeren</b></p>";
	echo "<form name='form' action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"" . (isset($name) ? $name : '') . "\" size=50 /></td>";
	echo "</tr>";
	
	// betreft
	echo "<tr><td>Betreft:</td>";
	echo "<td><input type=\"text\" name=\"summary\" value=\"" . (isset($summary) ? $summary : '') . "\" size=45 /></td>";
	echo "</tr>";
	
	// mededeling
	echo "<tr><td>Mededeling (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"note\" rows=4 cols=50/>" . (isset($note) ? $note : '') . "</textarea></td>";
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
