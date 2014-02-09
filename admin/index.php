<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Admin</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

echo "<div>";

echo "<p><strong>Vloot</strong><br>";
echo "<a href='./admin_vloot.php'>Beheer vloot</a><br>";
echo "<a href='./admin_types.php'>Beheer boottypes</a><br>";
echo "<a href='./admin_graden.php'>Beheer roeigraden</a><br>";
echo "<a href='./admin_rappo.php'>Bootgebruikrapportages</a>";
echo "</p>";

echo "<p><strong>Spitsrooster</strong><br>";
echo "<a href='./admin_spits.php'>Beheer spitsrooster</a>";
echo "</p>";

echo "<p><strong>Bestuur</strong><br>";
echo "<a href='./admin_mededeling.php'>Bestuursmededelingen</a><br>";
echo "<a href='./admin_bestuur.php'>Beheer bestuursleden</a>";
echo "</p>";

echo "</div>";

?>

</div>
</body>
</html>