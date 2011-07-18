<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include("../include.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Admin - Uitloggen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

unset($_SESSION['authorized']);
unset($_SESSION['restrict']);
echo "<p><strong>U bent uitgelogd - tot ziens!</strong></p>";
echo "<p><a href='../index.php'>Klik hier om naar BIS te gaan&gt;&gt;</a><br>";
echo "<a href='admin_login.php'>Klik hier om opnieuw in te loggen&gt;&gt;</a><br>";
echo "<a href='".$homepage."'>Klik hier om naar ".$homepagenaam." te gaan&gt;&gt;</a></p>";

mysql_close($link);
?>

</div>
</body>
</html>