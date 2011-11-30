<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_boardMembers.php");
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
    <title><? echo $systeemnaam; ?> - Admin - Vlootbeheer - Uit de Vaart toevoegen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="../scripts/kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

$fail = false;

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href=\"./admin_spits.php\">Terug naar het spitsrooster</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$spits_id = 0;
if ($_GET['id']) {
	$spits_id = $_GET['id'];
	$query = "SELECT MPB, Datum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, Email from ".$opzoektabel." WHERE Spits=$spits_id ORDER BY Datum;";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen van informatie mislukt.". mysql_error());
	} else {
		// uit eerste record kun je alles al halen, behalve -bij meer dan 1 inschrijving- de einddatum
		$row = mysql_fetch_assoc($result);
		$mpb = $row['MPB'];
		$startdate = $row['Datum'];
		$startdate = DBdateToDate($startdate);
		$start_time = $row['Begintijd'];
		$start_time_fields = explode(":", $start_time);
		$start_time_hrs = $start_time_fields[0];
		$start_time_mins = $start_time_fields[1];
		$end_time = $row['Eindtijd'];
		$end_time_fields = explode(":", $end_time);
		$end_time_hrs = $end_time_fields[0];
		$end_time_mins = $end_time_fields[1];
		$boat_id = $row['Boot_ID'];
		// bootnaam
		$query_boatname = "SELECT Naam from boten WHERE ID=$boat_id;";
		$result_boatname = mysql_query($query_boatname);
		$row_boatname = mysql_fetch_assoc($result_boatname);
		$boat = $row_boatname['Naam'];
		//
		$pname = $row['Pnaam'];
		$name = $row['Ploegnaam'];
		$email = $row['Email'];
		$enddate = $row['Datum'];
		while ($row = mysql_fetch_assoc($result)) {
			$enddate = $row['Datum'];
		}
		$enddate = DBdateToDate($enddate);
	}
}

if ($_POST['cancel']) {
	echo "<p>Er zal niets worden aangemaakt/gewijzigd.</p>";
	exit();
}

if ($_POST['submit']) {
	// bestuurslid
	$mpb = $_POST['mpb'];
	if (!$mpb) $fail_msg_mpb = "U dient uw functie te selecteren.";
	
	// startdatum
	$startdate = $_POST['startdate'];
	if (CheckTheDate($startdate)) {
		$startdate_db = DateToDBdate($startdate);
		if (strtotime($startdate_db) - strtotime($today_db) <= (24 * 3600)) {
			$fail_msg_startdate = "De startdatum moet minstens 2 dagen na vandaag liggen.";
		} 
	} else {
		$fail_msg_startdate = "U dient een geldige startdatum op te geven.";
	}
	
	// einddatum
	$enddate = $_POST['enddate'];
	if (CheckTheDate($enddate)) {
		$enddate_db = DateToDBdate($enddate);
	} else {
		$fail_msg_enddate = "U dient een geldige einddatum op te geven.";
	}
	
	// datumvolgorde
	if (strtotime($enddate_db) < strtotime($startdate_db)) {
		$fail_msg_date = "De einddatum dient na de begindatum te liggen.";
	}
	if (date("w", strtotime($enddate_db)) <> date("w", strtotime($startdate_db))) {
		$fail_msg_date = "De begin- en einddatum dienen op dezelfde weekdag te vallen.";
	}
	
	// tijden
	$start_time_hrs = $_POST['start_time_hrs'];
	$start_time_mins = $_POST['start_time_mins'];
	$start_time = $start_time_hrs.":".$start_time_mins;	
	$end_time_hrs = $_POST['end_time_hrs'];
	$end_time_mins = $_POST['end_time_mins'];
	$end_time = $end_time_hrs.":".$end_time_mins;	
	$duration = (($end_time_hrs - $start_time_hrs) * 60) + ($end_time_mins - $start_time_mins);
	if ($duration <= 0) {
		$fail_msg_time = "De eindtijd van een outing dient later dan de begintijd te zijn.";
	}
	
	// boot
	$boat_id = $_POST['boat_id'];
	
	// naam
	$pname = $_POST['pname'];
	if (!CheckName($pname)) {
		$fail_msg_pname = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	
	// ploegnaam
	$name = $_POST['name'];
	
	// e-mail
	$email = $_POST['email'];
	// niet verplicht, maar moet wel correct zijn
	if ($email && !CheckEmail($email)) {
		$fail_msg_email = "U dient een geldig e-mailadres op te geven.";
	}
	
	// als niet gefaald, repeterend spitsblok invoeren
	if ($fail_msg_startdate || $fail_msg_enddate || $fail_msg_date || $fail_msg_time || $fail_msg_pname || $fail_msg_email) {
		$fail = true;
	} else {
		if ($spits_id) {
			// wijzigen bestaand blok
			$query = "DELETE FROM ".$opzoektabel." WHERE Spits='$spits_id';";
			mysql_query($query);
			echo "Bestaande versie van dit spitsblok verwijderd.<br>";
		} else {
			// invoeren nieuw blok
			$query = "SELECT DISTINCT Spits FROM ".$opzoektabel." ORDER BY Spits;";
			$result = mysql_query($query);
			while ($row = mysql_fetch_assoc($result)) {
				$spits_id = $row['Spits'];
			}
			$spits_id += 1;
		}
		$name = addslashes($name); // speciale tekens in ploegnaam "redden"
		$day_tmp = explode("-", $startdate_db);
		$c_start = gregoriantojd($day_tmp[1], $day_tmp[2], $day_tmp[0]);
		$day_tmp = explode("-", $enddate_db);
		$c_end = gregoriantojd($day_tmp[1], $day_tmp[2], $day_tmp[0]);
		for ($c = $c_start; $c <= $c_end; $c += 7) {
			$day_tmp = jdtogregorian($c);
			$day_tmp2 = explode("/", $day_tmp);
			$date_ins_db = $day_tmp2[2]."-".$day_tmp2[0]."-".$day_tmp2[1];
			// Check inschrijving tegen de database
			$query = "SELECT * FROM ".$opzoektabel." WHERE ((Begintijd >= '$start_time' AND Begintijd < '$end_time') OR (Eindtijd > '$start_time' AND Eindtijd <= '$end_time') OR (Begintijd <= '$start_time' AND Eindtijd >= '$end_time')) AND Datum = '$date_ins_db' AND Boot_ID = '$boat_id';";
			$result = mysql_query($query);
			if (!$result) {
				echo "Het controleren van uw inschrijving is mislukt.<br>";
			} else {
				$rows_aff = mysql_affected_rows($link);
				if ($rows_aff > 0) {
					echo "Inschrijving $date_ins mislukt omdat deze conflicteert met een al bestaande inschrijving.<br>";
				} else {
					$query2 = "INSERT INTO ".$opzoektabel." (Datum, Inschrijfdatum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, Email, MPB, Spits, Controle) VALUES ('$date_ins_db', '$today_db', '$start_time', '$end_time', '$boat_id', '$pname', '$name', '$email', '$mpb', '$spits_id', '0');";
					$result2 = mysql_query($query2);
					$date_ins = DBdateToDate($date_ins_db);
					echo "Inschrijving $date_ins ";
					if ($result2) {
						echo "geslaagd.";
					} else {
						echo "mislukt.";
					}
					echo "<br>";
				}
			}
		}
		echo "<p><a href='./admin_spits.php?ploeg_te_tonen=$name'>Ga terug&gt;&gt;</a></p>";
	}
}

// HET FORMULIER
if ((!$_POST['submit'] && !$_POST['cancel']) || $fail) {
	echo "<p>Invoeren repeterend spitsblok</p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table><tr>";
	
	// bestuurslid
	echo "<td>Uw functie:</td>";
	echo "<td><select name=\"mpb\">";
	$cnt = 0;
	foreach($mpb_array as $mpb_db) {
		if ($cnt > 0) { // eerste veld is leeg
			echo "<option value=\"$mpb_db\" ";
			if ($mpb == $mpb_db) echo "selected=\"selected\"";
			echo ">$mpb_array_sh[$cnt]</option>";
		}
		$cnt++;
	}
	echo "</select></td>";
	echo "</tr>";
	if ($fail_msg_mpb) echo "<td colspan=2><em>$fail_msg_mpb</em></td>";
	echo "</tr><tr>";
	
	// startdatum
	if ($fail_msg_date) echo "<td colspan=2><em>$fail_msg_date</em></td></tr><tr>";
	echo "<td>Startdatum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' name='startdate' id='startdate' size='8' maxlength='10' value='$startdate'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.startdate');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	if ($fail_msg_startdate) echo "<td><em>$fail_msg_startdate</em></td>";
	echo "</tr><tr>";
	
	// einddatum
	echo "<td>Einddatum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' name='enddate' id='enddate' size='8' maxlength='10' value='$enddate'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.enddate');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	if ($fail_msg_enddate) echo "<td><em>$fail_msg_enddate</em></td>";
	echo "</tr><tr>";
	
	// starttijd
	echo "<td>Begintijd:</td>";
	echo "<td><select name=\"start_time_hrs\">";
		for ($t=6; $t<24; $t++) {
			echo"<option value=\"".$t."\" ";
			if ($start_time_hrs == $t) echo "selected=\"selected\"";
			echo ">".$t."</option>";
		}
	echo "</select>";
	echo "&nbsp;<select name=\"start_time_mins\">";
		echo "<option value=\"00\" ";
		if ($start_time_mins == 0) echo "selected=\"selected\"";
		echo ">00</option>";
		echo "<option value=\"15\" ";
		if ($start_time_mins == 15) echo "selected=\"selected\"";
		echo ">15</option>";
		echo "<option value=\"30\" ";
		if ($start_time_mins == 30) echo "selected=\"selected\"";
		echo ">30</option>";
		echo "<option value=\"45\" ";
		if ($start_time_mins == 45) echo "selected=\"selected\"";
		echo ">45</option>";
	echo "</select></td>";
	if ($fail_msg_time) echo "<td><em>$fail_msg_time</em></td>";
	echo "</tr><tr>";
	
	// eindtijd
	echo "<td>Eindtijd:</td>";
	echo "<td><select name=\"end_time_hrs\">";
		for ($t=6; $t<24; $t++) {
			echo"<option value=\"".$t."\" ";
			if ($end_time_hrs == $t) echo "selected=\"selected\"";
			echo ">".$t."</option>";
		}
	echo "</select>";
	echo "&nbsp;<select name=\"end_time_mins\">";
		echo "<option value=\"00\" ";
		if ($end_time_mins == 0) echo "selected=\"selected\"";
		echo ">00</option>";
		echo "<option value=\"15\" ";
		if ($end_time_mins == 15) echo "selected=\"selected\"";
		echo ">15</option>";
		echo "<option value=\"30\" ";
		if ($end_time_mins == 30) echo "selected=\"selected\"";
		echo ">30</option>";
		echo "<option value=\"45\" ";
		if ($end_time_mins == 45) echo "selected=\"selected\"";
		echo ">45</option>";
	echo "</select></td>";
	echo "</tr><tr>";
	
	// boot
	echo "<td>Boot/ergometer:</td>";
	echo "<td><select name=\"boat_id\">";
	$query = "SELECT ID, Naam FROM boten WHERE Datum_eind IS NULL AND Type<>\"soc\" ORDER BY Naam;";
	$boats_result = mysql_query($query);
	if (!$boats_result) {
		die("Ophalen van vlootinformatie mislukt.". mysql_error());
	} else {
		while ($row = mysql_fetch_assoc($boats_result)) {
			$curr_boat_id = $row[ID];
			$curr_boat = $row[Naam];
			echo"<option value=\"".$curr_boat_id."\" ";
			if ($boat_id == $curr_boat_id) echo "selected=\"selected\"";
			echo ">".$curr_boat."</option>";
		}
	}
	echo "</select></td>";
	echo "</tr><tr>";
	
	// PersoonsNaam (pname)
	echo "<td>Voor- en achternaam ploegcaptain:</td>";
	echo "<td><input type=\"text\" name=\"pname\" value=\"$pname\" size=30 /></td>";
	if ($fail_msg_pname) echo "<td><em>$fail_msg_pname</em></td>";
	echo "</tr><tr>";
	
	// Ploegnaam
	echo "<td>Ploegnaam (optioneel):</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"$name\" size=30 /></td>";
	echo "</tr><tr>";
	
	// e-mailadres
	echo "<td>E-mailadres (optioneel):</td>";
	echo "<td><input type=\"text\" name=\"email\" value=\"$email\" size=30 /></td>";
	if ($fail_msg_email) echo "<td><em>$fail_msg_email</em></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"submit\" value=\"Invoeren\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}

mysql_close($link);

?>

</div>
</body>
</html>

<script language="javascript">

function ChangeInfo(){
	return true;
}

</script>
