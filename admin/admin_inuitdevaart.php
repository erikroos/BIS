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

setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Admin - Vlootbeheer - In/uit de vaart</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_vloot.php'>Terug naar vlootbeheer</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$boot_id = $_GET['id'];
$query = "SELECT Naam, Type from boten WHERE ID=$boot_id;";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$name = $row['Naam'];

echo '<p>Uit de Vaart-info voor: ' . $name . ' (' . $row['Type'] . ')</p>';
echo "<p><a href=\"./admin_uitdevaart_toev.php?id=$boot_id\">Toevoegen</a></p>";

// tabel
$query = "SELECT * from uitdevaart WHERE Verwijderd=0 AND Boot_ID=$boot_id;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van informatie mislukt.". mysql_error());
}
echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Startdatum</div></th><th><div style=\"text-align:left\">Einddatum</div></th><th><div style=\"text-align:left\">Reden</div></th><th><div style=\"text-align:left\">Aanpassen</div></th></tr>";
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$udv_id = $row['ID'];
	$startdate = $row['Startdatum'];
	$startdate_sh = DBdateToDate($startdate);
	$enddate = $row['Einddatum'];
	if ($enddate == '' || $enddate == null) {
		$enddate_sh = '';
	} else {
		$enddate_sh = DBdateToDate($enddate);
	}
	$reason = $row['Reden'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$startdate_sh</div></td>";	
	echo "<td><div style=\"text-align:left\">$enddate_sh</div></td>";
	echo "<td><div style=\"text-align:left\">$reason</div></td>";
	echo "<td><div><a href=\"./admin_uitdevaart_verw.php?udv_id=$udv_id&boot_id=$boot_id\">Be&euml;indigen</a></div></td>";
	echo "</tr>";
	$c++;
}
echo "</table>";
echo "<p><em>NB: Meldingen die over datum zijn, worden automatisch be&euml;indigd.</em></p>";

mysql_close($link);
?>

</div>
</body>
</html>