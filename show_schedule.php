<?php
include_once("include_globalVars.php");
include_once("include_helperMethods.php");

setlocale(LC_TIME, 'nl_NL');

$link = getDbLink($database_host, $database_user, $database_pass, $database);

if (isset($_GET['date_to_show'])) {
	$date_to_show = $_GET['date_to_show'];
}
$date_to_show_db = DateToDBdate($date_to_show);

if (isset($_GET['start_hrs_to_show'])) $start_hrs_to_show = $_GET['start_hrs_to_show'];
if (isset($_GET['start_mins_to_show'])) $start_mins_to_show = $_GET['start_mins_to_show'];
if ($start_mins_to_show == 0) $start_mins_to_show = "00";
$start_time_to_show = $start_hrs_to_show.":".$start_mins_to_show;
$start_block = TimeToBlocks($start_time_to_show);

if (isset($_GET['cat_to_show'])) $cat_to_show = $_GET['cat_to_show'];

if (isset($_GET['grade_to_show'])) $grade_to_show = $_GET['grade_to_show'];

echo "<div style=\"margin-left:10px; margin-right:10px\">";
$date_tmp = strtotime($date_to_show_db);
$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
echo "<h1>".strtoupper($date_sh)." vanaf $start_time_to_show: $cat_to_show ($grade_to_show)</h1>";

// tabel-weergave (boten x tijdstippen) van inschrijvingen op gekozen dag
$restrict_query_type = "";
$query = "SELECT Type from types WHERE Categorie='$cat_to_show';";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van types mislukt." . mysqli_error());
}
$c = 0;
while ($row = mysqli_fetch_assoc($result)) {
	if ($c > 0) $restrict_query_type .= " OR ";
	$restrict_query_type .= "Type='".$row['Type']."'";
	$c++;
}
$restrict_query_grade = "";
if ($grade_to_show != 'alle') {
	$restrict_query_grade = " AND boten.Roeigraad='$grade_to_show'";
}
$query = "SELECT boten.ID AS ID, Naam, Gewicht, Type, boten.Roeigraad FROM boten JOIN roeigraden ON boten.Roeigraad=roeigraden.Roeigraad WHERE Datum_eind IS NULL AND (".$restrict_query_type.") ".$restrict_query_grade." ORDER BY roeigraden.ID, Naam;";

$boats_result = mysqli_query($link, $query);
if (!$boats_result) {
	die("Ophalen van boten-informatie mislukt.". mysqli_error());
}
if (mysqli_affected_rows($link) == 0) {
	echo "<p>Niets gevonden.</p>";
} else {

if (!InRange($date_to_show, 10)) {
	echo "<p>Het is niet mogelijk of toegestaan op deze datum in te schrijven.</p>";
}
echo "<table class='scrollTable' id='scrollTable' cellpadding='0' cellspacing='0' border='0'>"; // omhullende tabel
echo "<tr>";
echo "<td>";
echo "<div class=\"corner\">"; // los celletje linksboven
echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "<tr>";
echo "<th><div>&nbsp;</div></th>";
echo "</tr>";
echo "</table>";
echo "</div>";
echo "</td>";
echo "<td>";
echo "<div class=\"headerRow\">"; // kopregel
echo "<table cellpadding=\"0\" cellspacing=\"0\">";
echo "<tr><th><div>&nbsp;</div></th>";
$hr = 7 + floor($start_block / 4);
$offset_blocks = 4 - ((($start_block / 4) - floor($start_block / 4)) * 4);
echo "<th colspan=\"$offset_blocks\" style=\"border-left: solid 2px #aaaaaa\"><div align=\"left\">$start_time_to_show&nbsp;&nbsp;&nbsp;</div></th>";
for ($c = $start_block + $offset_blocks; $c < 72; $c += 4, $hr++) {
	echo "<th colspan=\"4\" style=\"border-left: solid 2px #aaaaaa\"><div align=\"left\">$hr:00&nbsp;&nbsp;&nbsp;</div></th>";
}
echo "<th style=\"border-left: solid 2px #aaaaaa\"><div>&nbsp;</div></th></tr>";
echo "</table>";
echo "</div>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td valign=\"top\">";
echo "<div class=\"headerColumn\">";
echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "<tr><th><div>Naam (gewicht, type, graad)</div></th></tr>";
echo "<tr><th><div>&nbsp;</div></th></tr>";
$c = 0;
while ($row = mysqli_fetch_assoc($boats_result)) {
	$boat_ids_array[$c] = $row['ID'];
	$boats_array[$c] = $row['Naam'];
	$boat_tmp = addslashes($boats_array[$c]);
	$weight = $row['Gewicht'];
	$type = $row['Type'];
	$grade = $row['Roeigraad'];
	// check Uit de Vaart
	$available[$c] = 1;
	$reason[$c] = "";
	$query2 = "SELECT Reden 
		FROM uitdevaart 
		WHERE Verwijderd=0 
		AND Boot_ID='$boat_ids_array[$c]' 
		AND Startdatum<='$date_to_show_db' 
		AND (Einddatum='0' OR Einddatum='0000-00-00' OR Einddatum IS NULL OR Einddatum>='$date_to_show_db');";
	$result2 = mysqli_query($link, $query2);
	if (!$result2) {
		die("Ophalen van Uit de Vaart-informatie mislukt.". mysql_error());
	} else {
		$rows_aff = mysqli_affected_rows($link);
		if ($rows_aff > 0) {
			$available[$c] = 0;
			$row = mysql_fetch_assoc($result2);
			$reason[$c] = $row['Reden'];
		}
	}
	$query3 = "SELECT KleurInBIS FROM roeigraden WHERE Roeigraad='$grade';";
	$result3 = mysqli_query($link, $query3);
	if (!$result3) {
		die("Ophalen van kleuren mislukt.". mysqli_error());
	} else {
		$row3 = mysqli_fetch_assoc($result3);
		$bgcolor = $row3['KleurInBIS'];
	}
	echo "<tr><th ";
	if ($available[$c] && InRange($date_to_show, 10)) {
		echo "onclick=\"showInschrijving(0, " . $boat_ids_array[$c] . ", '" . $date_to_show . "', '" . $cat_to_show . "', '" . $grade_to_show . "', '" . $start_time_to_show . "');\"  onmouseover=\"this.style.backgroundColor='#FFFFFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor . "'\" bgcolor=\"" . $bgcolor . "\">";
	} else {
		echo "bgcolor=\"#999999\">";
	}
	echo "<div>$boats_array[$c] ($weight kg, $type, $grade)</div></th></tr>";
	$c++;
}
echo "<tr><th><div>&nbsp;</div></th></tr>";
echo "</table>";
echo "</div>";
echo "</td>";
echo "<td>";
echo "<div class=\"body\">";
echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">"; // hoofdtabel
// lege regel volgend op kopregel van botenkolom, met exact dezelfde inhoud als de regel erboven, alleen onzichtbaar:
echo "<tr><th><div>&nbsp;</div></th>";
$hr = 7 + floor($start_block / 4);
$offset_blocks = 4 - ((($start_block / 4) - floor($start_block / 4)) * 4);
echo "<th colspan=\"$offset_blocks\" style=\"border-left: solid 2px #aaaaaa\"><div style=\"visibility:hidden\" align=\"left\">$start_time_to_show&nbsp;&nbsp;&nbsp;</div></th>";
for ($c = $start_block + $offset_blocks; $c < 72; $c += 4, $hr++) {
	echo "<th colspan=\"4\" style=\"border-left: solid 2px #aaaaaa\"><div align=\"left\" style=\"visibility:hidden\">$hr:00&nbsp;&nbsp;&nbsp;</div></th>";
}
echo "<th style=\"border-left: solid 2px #aaaaaa\"><div>&nbsp;</div></th></tr>";
// lege regel met netjes 1 cel per kwartier, zodat de tabel altijd de juiste afmeting heeft:
echo "<tr><td bgcolor=\"#FFFFFF\"><div>&nbsp;</div></td>";
for ($c = $start_block; $c < 73; $c++) {
	echo "<td bgcolor=\"#FFFFFF\"";
	if ($c == $start_block || ($c / 4) == floor($c / 4)) {
		echo " style=\"border-left: solid 2px #aaaaaa\"";
	}
	echo "><div>&nbsp;&nbsp;</div></td>"; // max 2 chars per kwartierblokje!
}
echo "</tr>";

// per rij bootnaam inlezen, inschrijvingen daarvan ophalen en intekenen
$boatnr = 0;
while (isset($boats_array[$boatnr])) {
	echo "<tr><td><div>&nbsp;</div></td>";
	$latest_end_time_blocks = $start_block;
	if (!$available[$boatnr]) {
		// boot uit de vaart: hele regel grijs
		$span_size = 72 - $latest_end_time_blocks;
		$info_to_show_sh = substr($reason[$boatnr], 0, (2 * $span_size) - 1);
		echo "<td colspan=\"" . $span_size . "\" align=\"center\" bgcolor=\"#999999\" style=\"border-left: solid 2px #aaaaaa\"><div style=\"overflow:hidden\" align=\"center\" onmouseover=\"Tip('$reason[$boatnr]')\">$info_to_show_sh</div></td>";
	} else {
		$opzoektabel_tmp = $opzoektabel;
		if (strtotime($date_to_show_db) - strtotime($today_db) < 0) $opzoektabel_tmp .= "_oud";
		$query = "SELECT * FROM ".$opzoektabel_tmp." WHERE Verwijderd=0 AND Datum='$date_to_show_db' AND Eindtijd>'$start_time_to_show' AND Boot_ID='$boat_ids_array[$boatnr]' ORDER BY Begintijd;";
		$result = mysqli_query($link, $query);
		if (!$result) {
			die("Ophalen van inschrijvingen mislukt.". mysql_error());
		} else {
			$rows_aff = mysqli_affected_rows($link);
			if ($rows_aff > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$db_id = $row['Volgnummer'];
					$db_date = $row['Datum'];
					$date = DBdateToDate($db_date);
					$db_start_time = $row['Begintijd'];
					$db_end_time = $row['Eindtijd'];
					$db_pname = $row['Pnaam'];
					$db_name = $row['Ploegnaam'];
					$db_email = $row['Email'];
					$db_mpb = $row['MPB'];
					$db_spits = $row['Spits'];
					$db_blok = isset($row['Wedstrijdblok']) ? $row['Wedstrijdblok'] : 0;
					$db_start_time_blocks = TimeToBlocks($db_start_time);
					if ($db_start_time_blocks < $start_block) $db_start_time_blocks = $start_block;
					$db_end_time_blocks = TimeToBlocks($db_end_time);
					$fields = explode(":", $db_end_time, 3);
					$available_ins = 1; // hulpvar. waarmee je bijhoudt of inschr. nog editbaar is
					if (($date_to_show == $today) && (($fields[0] < $thehour) || (($fields[0] == $thehour) && ($fields[1] < $theminute)))) $available_ins = 0;
					// wit totaan huidige inschrijving
					$span_size = $db_start_time_blocks - $latest_end_time_blocks;
					if ($span_size < 0) $span_size = 0;
					for ($t = $latest_end_time_blocks; $t < $latest_end_time_blocks + $span_size; $t++) {
						$t_time = BlocksToTime($t);
						echo "<td bgcolor=\"#FFFFFF\"";
						if (InRange($date_to_show, 10)) {
							echo " onclick=\"showInschrijving(0, " . $boat_ids_array[$boatnr] . ", '" . $date_to_show . "', '" . $cat_to_show . "', '" . $grade_to_show . "', '" . $t_time . "');\"";
						}
						if ($t == $start_block || ($t / 4) == floor($t / 4)) {
							echo " style=\"border-left: solid 2px #aaaaaa\"";
						}
						echo "><div>&nbsp;</div></td>";
					}
					// gekleurd en met naam gedurende huidige inschrijving
					echo "<td align=\"center\"";
					if ($db_start_time_blocks == $start_block || ($db_start_time_blocks / 4) == floor($db_start_time_blocks / 4)) {
						echo " style=\"border-left: solid 2px #aaaaaa\"";
					}
					$span_size = $db_end_time_blocks - $db_start_time_blocks;
					$boat_tmp = addslashes($boats_array[$boatnr]);
					$db_name_tmp = addslashes($db_name);
					// Maak een string met de naam, ploegnaam en evt. MPB om in het blok te zetten, en kort deze af als het blok te kort is (overflow:hidden werkt niet in IE!!!)
					$info_to_show = "";
					if ($db_name) $info_to_show = $db_name." - ";
					$info_to_show .= $db_pname;
					if ($db_mpb) $info_to_show .= " (MPB: $db_mpb)";
					$info_to_show_sh = substr($info_to_show, 0, (2 * $span_size) - 1); // max 2 chars per kwartierblokje!
					$info_to_show = addslashes($info_to_show);
					// Geef blok weer in geel/oranje en klikbaar (beschikbaar) of grijs (niet meer editbaar)
					if ($available_ins && $db_blok == 0 && (($db_spits == 0 && InRange($date_to_show, 10)) || ($db_spits > 0 && InRange($date_to_show, 3)))) {
						echo " onclick=\"showInschrijving(" . $db_id . ", 0, '', '" . $cat_to_show . "', '" . $grade_to_show . "', '');\"";
						if ($db_spits > 0) {
							echo " bgcolor=\"#FF6600\"";
							$info_to_show .= " - Spitsblok nog te bevestigen";
						} else {
							echo " bgcolor=\"#CCFF99\"";
						}
					} else {
						echo " bgcolor=\"#999999\"";
					}
					echo " onmouseover=\"Tip('" . $info_to_show . "')\" colspan=\"". $span_size . "\"><div style=\"overflow:hidden\" align=\"center\">" . $info_to_show_sh . "</div></td>";
					// volgende witblok vanaf eindtijd huidige inschrijving!
					$latest_end_time_blocks = $db_end_time_blocks;
				} // end while (loop door alle inschrijvingen van de huidige boot)
			} // end if
		}
		// wit totaan einde regel 
		for ($t = $latest_end_time_blocks; $t < 72; $t++) {
			$t_time = BlocksToTime($t);
			echo "<td bgcolor=\"#FFFFFF\"";
			if (InRange($date_to_show, 10)) {
				echo " onclick=\"showInschrijving(0, " . $boat_ids_array[$boatnr] . ", '" . $date_to_show . "', '" . $cat_to_show . "', '" . $grade_to_show . "', '" . $t_time . "');\"";
			}
			if ($t == $start_block || ($t / 4) == floor($t / 4)) {
				echo " style=\"border-left: solid 2px #aaaaaa\"";
			}
			echo "><div>&nbsp;</div></td>";
		}
	} // end else (boot niet uit de vaart)
	echo "<td style=\"border-left: solid 2px #aaaaaa\"><div>&nbsp;</div></td>";
	echo "</tr>";
	$boatnr++;
} // end while (loop door alle boten)
// lege regel onderaan:
echo "<tr><td><div>&nbsp;</div></td>";
echo "<td colspan=\"$offset_blocks\" style=\"border-left: solid 2px #aaaaaa\"><div>&nbsp;</div></td>";
for ($c = $start_block + $offset_blocks; $c < 72; $c += 4) {
	echo "<td colspan=\"4\" style=\"border-left: solid 2px #aaaaaa\"><div>&nbsp;</div></td>";
}
echo "<td style=\"border-left: solid 2px #aaaaaa\"><div>&nbsp;</div></td>";
echo "</tr>";
echo "</table>"; // einde hoofdtabel
echo "</div>";
echo "</td>";
echo "</tr>";
echo "</table>"; // einde omhullende tabel
echo "</div>";
} // behoort bij IF aantal boten in selectie > 0

mysqli_close($link);
