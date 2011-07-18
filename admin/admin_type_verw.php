<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Admin - Boottype verwijderen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_types.php'>Terug naar boottypemenu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$type = $_GET['type'];

$query = "DELETE FROM `types` WHERE Type='$type';";
$result = mysql_query($query);
if (!$result) {
	die("Verwijderen boottype mislukt.". mysql_error());
} else {
	echo "Verwijderen boottype gelukt.<br>";
}

?>

</div>
</body>
</html>