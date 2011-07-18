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
    <title><? echo $systeemnaam; ?> - Admin - Vlootbeheer - Uit de Vaart be&euml;indigen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

$udv_id = $_GET['udv_id'];
$boot_id = $_GET['boot_id'];

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href=\"./admin_inuitdevaart.php?id=$boot_id\">Terug naar in/uit de vaart van deze boot</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$query = "UPDATE uitdevaart SET Verwijderd=1, Einddatum='$today_db' WHERE ID=$udv_id;"; 
mysql_query($query);
if (!$result) {
	die("Be&euml;indigen mislukt.". mysql_error());
} else {
	echo "Uit de Vaart succesvol be&euml;indigd.";
}

?>

</div>
</body>
</html>