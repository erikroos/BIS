<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once('../include_globalVars.php');
include_once('../include_helperMethods.php');

$link = getDbLink($database_host, $database_user, $database_pass, $database);

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($mode == "c") {
	if ($_GET['curval'] == 1) {
		$query = 'UPDATE cursussen SET ToonOpSite=0 WHERE ID=' . $id;
	} else {
		$query = 'UPDATE cursussen SET ToonOpSite=1 WHERE ID=' . $id;
	}
	mysqli_query($link, $query);
	header('Location: admin_cursussen.php');
	exit;
}
if ($mode == "d") {
	$query = 'DELETE FROM cursussen WHERE ID=' . $id;
	$result = mysqli_query($link, $query);
	if (!$result) {
		die('Verwijderen van cursus mislukt: ' . mysqli_error());
	}
	echo "Verwijderen van cursus gelukt.<br>";
	echo "<a href='admin_cursussen.php'>Terug naar de cursuspagina&gt;&gt;</a>";
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $systeemnaam; ?> - Instructiecommissie</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
    <link type="text/css" href="../css/bis.css" rel="stylesheet" />
    <script type="text/javascript" src="../scripts/kalender.js"></script>
    <script type="text/javascript" src="../scripts/sortable.js"></script>
</head>
<body>
<div class="maindiv">

<p>
	<strong>Welkom in de Admin-sectie van BIS</strong>
	[<a href='./admin_logout.php'>Uitloggen</a>]
</p>
<p>Instructiecommissie</p>
<p><a href='admin_cursus_toev.php'>Maak een nieuwe cursus aan&gt;&gt;</a></p>

<?php
setlocale(LC_TIME, 'nl_NL');

$query = "SELECT * FROM cursussen ORDER BY Startdatum DESC";
$result = mysqli_query($link, $query);
if (!$result) {
	die('Ophalen van cursussen mislukt: ' . mysqli_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Startdatum</div></th><th><div style=\"text-align:left\">Einddatum</div></th><th><div style=\"text-align:left\">Type</div></th><th><div style=\"text-align:left\">Omschrijving</div></th><th><div style=\"text-align:left\">Mailadres</div></th><th><div style=\"text-align:left\">Quotum</div></th><th><div style=\"text-align:left\">Toon op site?</div></th><th colspan=4></th></tr>";
$c = 0;
while ($row = mysqli_fetch_assoc($result)) {
	$id = $row['ID'];
	$startdate = $row['Startdatum'];
	$startdate_sh = DBdateToDate($startdate);
	$enddate = $row['Einddatum'];
	$enddate_sh = DBdateToDate($enddate);
	$type = $row['Type'];
	$description = $row['Omschrijving'];
	$email = $row['Mailadres'];
	$quotum = $row['Quotum'];
	$show = $row['ToonOpSite'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$startdate_sh</div></td>";
	echo "<td><div style=\"text-align:left\">$enddate_sh</div></td>";
	echo "<td><div style=\"text-align:left\">$type</div></td>";
	echo "<td><div style=\"text-align:left\">$description</div></td>";
	echo "<td><div style=\"text-align:left\">$email</div></td>";
	echo "<td><div style=\"text-align:left\">$quotum</div></td>";
	if ($show) {
		echo "<td><div style=\"text-align:left\">ja";
	} else {
		echo "<td><div style=\"text-align:left\">nee";
	}
	echo "&nbsp;[<a href='admin_cursussen.php?mode=c&curval=$show&id=$id'>Wijzig</a>]</div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_cursus_toev.php?id=$id'>Wijzigen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_cursussen.php?mode=d&id=$id'>Verwijderen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_cursisten.php?id=$id'>Bekijk/beheer deelnemers</a></div></td>";
	echo "</tr>";
}
echo "</table>";
?>

<?php include 'admin_footer.php'; ?>
