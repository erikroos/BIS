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
    <title><? echo $systeemnaam; ?> - Admin - Rapportages</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<script type="text/javascript" src="sortable.js"></script>
</head>
<body>

<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

if (!$_POST['submit']) {
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	// jaar
	echo "<p>Jaar (jjjj): ";
	echo "<input type=\"text\" name=\"jaar\" value=\"$jaar\" size=\"4\" /></p>";
	echo "<p><input type=\"submit\" name=\"submit\" value=\"Toon rapport\" /></p>";
	echo "</form>";
}

if ($_POST['submit']) {
	$jaar = $_POST['jaar'];

	echo "<p><strong>Gebruikstotalen voor het jaar ".$jaar."</strong></p>";
	echo "<p><em>Klik op een kolomkop om de tabel op die kolom te sorteren. Eerste keer klikken: oplopend; tweede keer: aflopend.</em></p>";
	echo "<p><em>Let op: het totaal aantal dagen uit de vaart kan ingeval van overlappende uit-de-vaart-periodes hoger zijn dan in werkelijkheid.</em></p>";
	echo "<table class=\"sortable\" id=\"jaarrapport\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"bordercolor:#AAB8D5\">";
	echo "<tr><td>Naam</td><td>Type</td><td>Roeisoort</td>";
	for ($i = 1; $i < 13; $i++) {
		echo "<td width=\"15\">$i</td>";
	}
	echo "<td width=\"15\">Totaal gebruik</td>";
	echo "<td># uit de vaart</td><td>Tot. dagen uit de vaart</td></tr>";
	
	$start_year = $jaar."-01-01";
	$end_year = $jaar."-12-31";
	$query1 = "SELECT boten.ID AS ID, Naam, types.Type AS Type, Roeisoort from boten JOIN types ON boten.Type = types.Type WHERE Datum_start <= '$end_year' AND (Datum_eind IS NULL OR Datum_eind >= '$start_year') ORDER BY Type;";
	$result1 = mysql_query($query1);
	if (!$result1) {
		die("Ophalen van boten mislukt.". mysql_error());
	}
	while ($row = mysql_fetch_assoc($result1)) {
		$boot_id = $row['ID'];
		$boot = $row['Naam'];
		$type = $row['Type'];
		$sort = $row['Roeisoort'];
		$tot = 0;
		echo "<tr><td><strong>$boot</strong></td><td>$type</td><td>$sort</td>";
		for ($maand = 1; $maand < 13; $maand++) {
			$query2 = "SELECT COUNT(*) AS MonthlyTot FROM ".$opzoektabel."_oud WHERE Verwijderd=0 AND Boot_ID='$boot_id' AND DATE_FORMAT(Datum,'%Y')=$jaar AND DATE_FORMAT(Datum,'%c')=$maand;";
			$result2 = mysql_query($query2);
			if (!$result2) {
				die("Tellen van inschrijvingen mislukt.". mysql_error());
			} else {
				$row = mysql_fetch_assoc($result2);
				$maand_tot = $row['MonthlyTot'];
				echo "<td>$maand_tot</td>";
				$tot += $maand_tot;
			}
		}
		echo "<td><strong>$tot</strong></td>";
		
		//Uit de vaart
		$query3 = "SELECT * FROM uitdevaart WHERE Boot_ID='$boot_id' AND ((Startdatum >= '$start_year' AND Startdatum <= '$end_year') OR (Einddatum <= '$end_year' AND Einddatum > $start_year) OR (Startdatum <= '$start_year' AND Einddatum = '0000-00-00'));";
		$result3 = mysql_query($query3);
		if (!$result3) {
			die("Ophalen van uit de vaart-meldingen mislukt.". mysql_error());
		} else {
			$nr_of_udvs = 0;
			$tot_duration = 0;
			while ($row = mysql_fetch_assoc($result3)) {
				$nr_of_udvs++;
				$end_date = $row['Einddatum'];
				if ($end_date == '0000-00-00') $end_date = $today_db;
				$end_date_parts = explode("-", $end_date);
				$start_date_parts = explode("-", $row['Startdatum']);
				// Bij meerjarige udv's, alleen gedeelte in gewenste jaar meetellen:
				if ($end_date_parts[0] > $jaar) $end_date_parts = explode("-", $end_year);
				if ($start_date_parts[0] < $jaar) $start_date_parts = explode("-", $start_year);
				$end_date = gregoriantojd($end_date_parts[1], $end_date_parts[2], $end_date_parts[0]);
				$start_date = gregoriantojd($start_date_parts[1], $start_date_parts[2], $start_date_parts[0]);
				$tot_duration += abs($end_date - $start_date);
			}
			echo "<td>$nr_of_udvs</td><td>$tot_duration</td></tr>";
		}
	}
	echo "</table>";
}

?>

</div>
</body>
</html>