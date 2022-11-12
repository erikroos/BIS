<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Schades/klachten</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
	<p><a href="index_boten.php">Ga naar het schadeboek voor de <strong>boten</strong>&gt;&gt;</a></p>
	<p><a href="index_gebouw.php">Ga naar het klachtenboek voor het <strong>gebouw</strong> en <strong>algemene zaken</strong>&gt;&gt;</a></p>
</div>
</body>
</html>
