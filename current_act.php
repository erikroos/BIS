<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("include_globalVars.php");
include_once("include_helperMethods.php");

setlocale(LC_TIME, 'nl_NL');

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Wie zijn er nu op het water?</title>
    <link type="text/css" href="<?php echo $csslink; ?>" rel="stylesheet" />
    <link type="text/css" href="css/bis.css" rel="stylesheet" />
</head>
<body>
<div style="width:600px; margin-left:10px; margin-top:10px">

<p><a href="./index.php">Terug naar BIS&gt;&gt;</a></p>

<?php

$date_tmp = strtotime($today_db);
$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
echo "<p><strong>Het is nu $date_sh, $thetime</strong></p>";

$query = "SELECT boten.ID AS ID, boten.Naam AS Boot, Pnaam, Ploegnaam, Eindtijd from ".$opzoektabel." JOIN boten ON ".$opzoektabel.".Boot_ID=boten.ID WHERE Verwijderd=0 AND Datum='$today_db' AND Begintijd<='$thetime' AND Eindtijd>='$thetime' AND boten.Type<>\"ergo\" AND boten.Type<>\"soc\" ORDER BY Eindtijd;";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van inschrijvingen mislukt.". mysqli_error());
} else {
	$rows_aff = mysqli_affected_rows($link);
	if ($rows_aff > 0) {
		echo "<p>";
		while ($row = mysqli_fetch_assoc($result)) {
			$boat_id = $row['ID'];
			$db_boat = $row['Boot'];
			$query2 = "SELECT * 
				FROM uitdevaart 
				WHERE Verwijderd=0 
				AND Boot_ID='$boat_id' 
				AND Startdatum<='$today_db' 
				AND (Einddatum='0' OR Einddatum='0000-00-00' OR Einddatum IS NULL OR Einddatum>='$today_db');";
			$result2 = mysqli_query($link, $query2);
			if (!$result2) {
				die("Ophalen van Uit de Vaart-informatie mislukt.". mysqli_error());
			} else {
				$rows_aff2 = mysqli_affected_rows($link);
				if ($rows_aff2 == 0) {
					$db_pname = $row['Pnaam'];
					$db_name = "(".$row['Ploegnaam'].")";
					if ($db_name == "()") $db_name = "";
					$db_endtime = substr($row['Eindtijd'], 0, 5);
					echo "$db_pname $db_name heeft nu de '$db_boat' ingeschreven, tot $db_endtime<br>";
				}
			}
		}
		echo "</p>";
	} else {
		echo "<p>Er zijn nu geen boten ingeschreven.</p>";
	}
}

$tot_ergo = 0;
$query = "SELECT count(*) AS TotErgo from ".$opzoektabel." JOIN boten ON ".$opzoektabel.".Boot_ID=boten.ID WHERE Verwijderd=0 AND Datum='$today_db' AND Begintijd<='$thetime' AND Eindtijd>='$thetime' AND boten.Type=\"ergo\";";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van inschrijvingen mislukt.". mysqli_error());
} else {
	$row = mysqli_fetch_assoc($result);
	$tot_ergo = $row['TotErgo'];
}
echo "<p>Aantal ergometers dat nu is ingeschreven: $tot_ergo</p>";
echo "</div>";

mysqli_close($link);
?>

</div>
</body>
</html>
