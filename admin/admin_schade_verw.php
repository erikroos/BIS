<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'matcie') {
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
    <title><?php echo $systeemnaam; ?> - Werkstroom Materiaalcommissie - Archiveren</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$id = $_GET['id'];
if (isset($_GET['mode'])) $mode = $_GET['mode'];

if (isset($mode)) {
	$source = "schades_oud";
	$target = "schades";
} else {
	$source = "schades";
	$target = "schades_oud";
}

$query = "INSERT INTO " . $target . " SELECT * FROM " . $source . " WHERE ID='$id';";
$result = mysqli_query($link, $query);
if (!$result) {
	die("(De)archiveren mislukt.". mysqli_error());
} else {
	$query2 = "DELETE FROM " . $source . " WHERE ID='" . $id . "';";
	$result2 = mysqli_query($link, $query2);
	if (!$result2) {
		die("De)archiveren mislukt.". mysqli_error());
	} else {
		echo "Schade succesvol ge(de)archiveerd.<br>";
		echo "<a href='admin_schade.php" . (isset($mode) ? "?mode=$mode" : "") . "'>Terug naar de werkstroom&gt;&gt;</a></p>";
	}
}

mysqli_close($link);
?>

</div>
</body>
</html>
