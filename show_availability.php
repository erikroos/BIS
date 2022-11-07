<?php

include_once("include_globalVars.php");
include_once("include_helperMethods.php");

setlocale(LC_TIME, 'nl_NL');

$link = getDbLink($database_host, $database_user, $database_pass, $database);

if (isset($_GET['change'])) {
	if (isset($_GET['date'])) {
		$date = $_GET['date'];
		$date_db = DateToDBdate($date);
	}
	
	if (isset($_GET['start_time_hrs'])) $start_time_hrs = $_GET['start_time_hrs'];
	if (isset($_GET['start_time_mins'])) $start_time_mins = $_GET['start_time_mins'];
	$start_time = $start_time_hrs.":".$start_time_mins;
	
	if (isset($_GET['end_time_hrs'])) $end_time_hrs = $_GET['end_time_hrs'];
	if (isset($_GET['end_time_mins'])) $end_time_mins = $_GET['end_time_mins'];
	$end_time = $end_time_hrs.":".$end_time_mins;
	
	if (isset($_GET['boat_id'])) $boat_id = $_GET['boat_id'];
	
	$id = 0;
	if (isset($_GET['id'])) $id = $_GET['id'];
}
$date_tmp = strtotime($date_db);
$date_sh = strftime('%A %d-%m-%Y', $date_tmp);

echo "<div class='topbar'>";
echo "<div class='leftrightmargins'>";

// bootnaam
if ($boat_id == 0) {
	$boat = "";
} else {
	$query2 = "SELECT Naam from boten WHERE ID=$boat_id;";
	$result2 = mysqli_query($link, $query2);
	$row2 = mysqli_fetch_assoc($result2);
	$boat = $row2['Naam'];
}
//
	
// Toon bestaande inschrijvingen voor deze boot & dag
if ($boat_id > 0) {
	// stap 1: check op Uit de Vaart
	$query = "SELECT * 
		FROM uitdevaart 
		WHERE Verwijderd=0 
		AND Boot_ID='$boat_id' 
		AND Startdatum<='$date_db' 
		AND (Einddatum='0000-00-00' OR Einddatum IS NULL OR Einddatum>='$date_db');";
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Ophalen van Uit de Vaart-informatie mislukt.". mysqli_error());
	} else {
		$rows_aff = mysqli_affected_rows($link);
		if ($rows_aff > 0) {
			echo "<br /><span class=\"update\">'" . $boat . "' is uit de vaart op " . $date_sh . "!</span><br />";
		} else {
			$query = "SELECT * FROM ".$opzoektabel." WHERE Verwijderd=0 AND Volgnummer<>'$id' AND Datum='$date_db' AND Boot_ID='$boat_id' ORDER BY Begintijd;";
			$result = mysqli_query($link, $query);
			if (!$result) {
				die("Het ophalen van bestaande inschrijvingen is mislukt.". mysqli_error());
			} else {
				$rows_aff = mysqli_affected_rows($link);
				if ($rows_aff > 0) {
					echo "<br /><em>Bestaande (andere) inschrijvingen van '" . $boat . "' op " . $date_sh . ":</em><br /><br />";
					while ($row = mysqli_fetch_assoc($result)) {
						$db_start_time = substr($row['Begintijd'],0,5);
						$db_end_time = substr($row['Eindtijd'],0,5);
						$db_pname = $row["Pnaam"];
						$spits_toev = "";
						$conflict = "";
						// check op conflict
						$start1 = strtotime($start_time);
						$start2 = strtotime($db_start_time);
						$end1 = strtotime($end_time);
						$end2 = strtotime($db_end_time);
						if ($end1 > $start1) {
							if (($start2 >= $start1 && $start2 < $end1) ||
								($end2 > $start1 && $end2 <= $end1)) {
								$conflict = " <span class=\"update\">conflict!</span>";
							}
						}
						if ($row["Spits"]) $spits_toev = " (spitsblok nog te bevestigen)";
						echo "$db_start_time - $db_end_time door $db_pname".$spits_toev.$conflict."<br />";
					}
				} else {
					echo "<br /><em>Er zijn geen (andere) inschrijvingen van '" . $boat . "' op ". $date_sh . ".</em><br />";
				}
			}
		}
	}
}
echo "<br />";

// Toon aantallen inschrijvingen voor begintijd
$query = "SELECT COUNT(*) AS AantalBijStart FROM ".$opzoektabel." JOIN boten ON ".$opzoektabel.".Boot_ID=boten.ID WHERE Verwijderd=0 AND Datum='$date_db' AND ((Begintijd='$start_time' AND Boot_ID<>'$boat_id') OR Eindtijd='$start_time') AND boten.Type<>\"ergo\" AND boten.Type<>\"soc\";";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Het tellen van de inschrijvingen is mislukt.". mysqli_error());
} else {
	echo "<em>Aantal andere ploegen en/of skiffeurs op het vlot bij vertrek om $start_time: </em>";
	$rows_aff = mysqli_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysqli_fetch_assoc($result);
		echo $row["AantalBijStart"];
	} else {
		echo "geen";
	}
}
echo "<br />";

// Toon aantallen inschrijvingen voor eindtijd
$query = "SELECT COUNT(*) AS AantalBijEind FROM ".$opzoektabel." JOIN boten ON ".$opzoektabel.".Boot_ID=boten.ID WHERE Verwijderd=0 AND Datum='$date_db' AND (Begintijd='$end_time' OR (Eindtijd='$end_time' AND Boot_ID<>'$boat_id')) AND boten.Type<>\"ergo\" AND boten.Type<>\"soc\";";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Het tellen van de inschrijvingen is mislukt.". mysqli_error());
} else {
	echo "<em>Aantal andere ploegen en/of skiffeurs op het vlot bij terugkomst om $end_time: </em>";
	$rows_aff = mysqli_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysqli_fetch_assoc($result);
		echo $row["AantalBijEind"];
	} else {
		echo "geen";
	}
}
echo "<br /><br />";

echo "</div></div>";

mysqli_close($link);
