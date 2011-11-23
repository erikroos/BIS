<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'matcie') {
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
    <title><? echo $systeemnaam; ?> - Werkstroom materiaalcommissie - Archiveren</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$id = $_GET['id'];
$mode = $_GET['mode'];

if ($mode) {
	$source = "schades_oud";
	$target = "schades";
} else {
	$source = "schades";
	$target = "schades_oud";
}

$query = "INSERT INTO ".$target." SELECT * FROM ".$source." WHERE ID='$id';";
$result = mysql_query($query);
if (!$result) {
	die("(De-)archiveren mislukt.". mysql_error());
} else {
	$query2 = "DELETE FROM ".$source." WHERE ID='$id';";
	$result2 = mysql_query($query2);
	if (!$result2) {
		die("De-)archiveren mislukt.". mysql_error());
	} else {
		echo "Schade succesvol ge(de)archiveerd.<br>";
		echo "<a href='admin_schade.php?mode=$mode'>Terug naar de werkstroom&gt;&gt;</a></p>";
	}
}

mysql_close($link);

?>

</div>
</body>
</html>