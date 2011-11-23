<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("../include_globalVars.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Uitloggen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<?php

unset($_SESSION['authorized_bis']);

echo "<div style=\"position:absolute; top:30%; left:30%\">";
echo "<p><strong>U bent uitgelogd - tot ziens!</strong></p>";
echo "<p><a href='bis_login.php'>Klik hier om opnieuw in te loggen&gt;&gt;</a><br>";
echo "<a href='".$homepage."'>Klik hier om naar ".$homepagenaam." te gaan&gt;&gt;</a></p>";
echo "</div>";

?>

</body>
</html>
