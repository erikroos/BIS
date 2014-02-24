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
    <link type="text/css" href="../css/bis.css" rel="stylesheet" />
</head>
<body>
<div class="maindiv">
	<p>
		<strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]
	</p>
	<p>
		<strong>Vloot</strong><br />
		<a href='./admin_vloot.php'>Boten</a><br />
		<a href='./admin_types.php'>Boottypes</a><br />
		<a href='./admin_graden.php'>Roeigraden</a><br />
		<a href='./admin_rappo.php'>Bootgebruikrapportages</a>
	</p>
	<p>
		<strong>Inschrijvingen</strong><br />
		<a href='./admin_spits.php'>Spitsrooster</a><br />
		<a href='./admin_blokken.php'>Wedstrijdblokken</a>
	</p>
	<p>
		<strong>Bestuur</strong><br>
		<a href='./admin_mededeling.php'>Bestuursmededelingen</a><br />
		<a href='./admin_bestuur.php'>Bestuursleden</a>
	</p>

</div>
</body>
</html>