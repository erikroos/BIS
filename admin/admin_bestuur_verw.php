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
    <title><? echo $systeemnaam; ?> - Admin - Bestuurslid verwijderen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_bestuur.php'>Terug naar bestuursmenu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$function = $_GET['function'];

$query = "DELETE FROM `bestuursleden` WHERE Functie='$function';";
$result = mysql_query($query);
if (!$result) {
	die("Verwijderen bestuurslid mislukt.". mysql_error());
} else {
	echo "Verwijderen bestuurslid gelukt.<br>";
}

mysql_close($link);

?>

</div>
</body>
</html>