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
    <title><?php echo $systeemnaam; ?> - Admin - Boottype toevoegen/wijzigen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php
echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_types.php'>Terug naar boottypemenu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// ingeval van editen bestaand boottype
if (isset($_GET['type'])) {
    $type_ex = $_GET['type']; // no need to decode
    $query = "SELECT * FROM `types` WHERE Type='$type_ex' LIMIT 1;";
    $result = mysqli_query($link, $query);
    if ($result) {
        $rows_aff = mysqli_affected_rows($link);
        if ($rows_aff > 0) {
            $row = mysqli_fetch_assoc($result);
            $type = $row['Type'];
            $cat = $row['Categorie'];
            $sort = $row['Roeisoort'];
        }
    }
}

// Annuleren gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['type'], $_POST['cat'], $_POST['sort'], $type, $cat, $sort);
}

if (isset($_POST['insert'])) {
	$type = $_POST['type'];
	$cat = $_POST['cat'];
	$sort = $_POST['sort'];
	if (isset($type_ex)) {
		$query = "UPDATE `types` SET Type='$type', Categorie='$cat', Roeisoort='$sort' WHERE Type='$type_ex';";
	} else {
		$query = "INSERT INTO `types` (Type, Categorie, Roeisoort) VALUES ('$type', '$cat', '$sort');";
	}
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Invoeren/wijzigen boottype mislukt.". mysqli_error());
	} else {
		echo "<p>Boottype succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if (!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) {
	echo "<p><b>Boottype invoeren/wijzigen</b></p>";
	echo "<form name='form' action=\"". $_SERVER['REQUEST_URI'] . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Type:</td>";
	echo "<td><input type=\"text\" name=\"type\" value=\"" . (isset($type) ? $type : '') . "\" size=10 /></td>";
	echo "</tr>";
	
	// categorie
	echo "<tr><td>Categorie:</td>";
	echo "<td><input type=\"text\" name=\"cat\" value=\"" . (isset($cat) ? $cat : '') . "\" size=40 /></td>";
	echo "</tr>";
	
	echo "<tr><td colspan=2><em>Meerdere types kunnen deel uitmaken van dezelfde categorie</em></td></tr>";
	
	// roeisoort
	echo "<tr><td>Roeisoort (boord/scull):</td>";
	echo "<td><input type=\"text\" name=\"sort\" value=\"" . (isset($sort) ? $sort : '') . "\" size=10 /></td>";
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
