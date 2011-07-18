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
    <title><? echo $systeemnaam; ?> - Admin - Bestuursmededelingen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

$mode = $_GET['mode'];

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";
echo "<p><div><a href='./admin_mededeling_toev.php'>Mededeling toevoegen&gt;&gt;</a></div></p>";

if (!$mode) {
	echo "<p><a href='admin_mededeling.php?mode=Arch'>Toon gearchiveerde mededelingen&gt;&gt;</a><br>";
} else {
	echo "<p><a href='admin_mededeling.php'>Toon actuele mededelingen&gt;&gt;</a><br>";
}

$source = "mededelingen";
if ($mode) $source .= "_oud";
$query = "SELECT * from ".$source." ORDER BY Datum DESC;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van mededelingen mislukt.". mysql_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Datum</div></th><th><div style=\"text-align:left\">Bestuurslid</div></th><th><div style=\"text-align:left\">Betreft</div></th><th><div style=\"text-align:left\">Mededeling</div></th><th><div style=\"text-align:left\">&nbsp;</div></th>";
if (!$mode) echo "<th><div style=\"text-align:left\">&nbsp;</div></th><th><div style=\"text-align:left\">&nbsp;</div></th>";
echo "</tr>";

$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$id = $row['ID'];
	$date_db = $row['Datum'];
	$date = DBdateToDate($date_db);
	$name = $row['Bestuurslid'];
	$summary = $row['Betreft'];
	$note = $row['Mededeling'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$date</div></td>";	
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">$summary</div></td>";
	echo "<td width=400px><div style=\"text-align:left overflow:auto\">$note</div></td>";
	if (!$mode) echo "<td><div><a href=\"./admin_mededeling_toev.php?id=$id\">Wijzigen</a></div></td>";
	if ($mode) {
		echo "<td><div style=\"text-align:left\"><a href='admin_mededeling_verw.php?id=$id&mode=Dearch'>De-archiveer</a></div></td>";
	} else {
		echo "<td><div style=\"text-align:left\"><a href='admin_mededeling_verw.php?id=$id&mode=Arch'>Archiveer</a></div></td>";
		echo "<td><div style=\"text-align:left\"><a href='admin_mededeling_verw.php?id=$id&mode=Del'>Verwijder</a></div></td>";
	}
	echo "</tr>";
	$c++;
}
echo "</table>";

?>

</div>
</body>
</html>