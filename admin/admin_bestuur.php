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
    <title><? echo $systeemnaam; ?> - Admin - Bestuur</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";
echo "<p><div><a href='./admin_bestuur_toev.php'>Bestuurslid toevoegen&gt;&gt;</a></div></p>";

$query = "SELECT * from bestuursleden ORDER BY Functie;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van bestuursleden mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Functie</div></th><th><div style=\"text-align:left\">Naam</div></th><th><div style=\"text-align:left\">Email</div></th><th><div style=\"text-align:left\">Geeft MPB?</div></th><th colspan=2><div style=\"text-align:left\">&nbsp;</div></th></tr>";

$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$function = $row['Functie'];
	$name = $row['Naam'];
	$mail = $row['Email'];
	$mpb = $row['MPB'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$function</div></td>";	
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">$mail</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($mpb) {
		echo "ja";
	} else {
		echo "nee";
	}
	echo "</div></td>";
	echo "<td><div><a href=\"./admin_bestuur_toev.php?function=$function\">Wijzigen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_bestuur_verw.php?function=$function'>Verwijderen</a></div></td>";
	echo "</tr>";
	$c++;
}
echo "</table>";

mysql_close($link);

?>

</div>
</body>
</html>