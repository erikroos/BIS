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
    <title><?php echo $systeemnaam; ?> - Admin - Bestuursmededeling verwijderen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_mededeling.php'>Terug naar mededelingen-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$id = $_GET['id'];
$mode = $_GET['mode'];

if ($mode == "Dearch") {
	$source = "mededelingen_oud";
	$target = "mededelingen";
} else {
	$source = "mededelingen";
	$target = "mededelingen_oud";
}

if ($mode == "Del") {
	$query = "DELETE FROM mededelingen WHERE ID='$id';";
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Verwijderen mislukt.". mysqli_error());
	} else {
		echo "Mededeling succesvol verwijderd.<br>";
	}
} else {
	$query = "INSERT INTO ".$target." SELECT * FROM ".$source." WHERE ID='$id';";
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("(De-)archiveren mislukt.". mysqli_error());
	} else {
		$query2 = "DELETE FROM ".$source." WHERE ID='$id';";
		$result2 = mysqli_query($link, $query2);
		if (!$result2) {
			die("De-)archiveren mislukt.". mysqli_error());
		} else {
			echo "Mededeling succesvol ge(de)archiveerd.<br>";
		}
	}
}

mysqli_close($link);
?>

</div>
</body>
</html>
