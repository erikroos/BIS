<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("include_globalVars.php");
include_once("include_boardMembers.php");
include_once("include_helperMethods.php");
include_once("inschrijving_methods.php");

setlocale(LC_TIME, 'nl_NL');

$bisdblink = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $bisdblink)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - inschrijving maken/bewerken</title>
    <link type="text/css" href="<? echo $csslink; ?>" rel="stylesheet" />
	<script type="text/javascript" src="scripts/kalender.js"></script>
</head>
<body>
<?php

$NR_OF_CONCEPTS = 6; // LET OP: aanpassen als het aantal Concept-ergo's verandert! (ivm blokinschrijving)
$fail_msg = "";
$spits = 0;

// var'en die alleen maar dienen om weer door te schuiven naar index; sanity check aldaar
$cat_to_show = $_GET['cat_to_show'];
$grade_to_show = $_GET['grade_to_show'];

$id = $_GET['id']; // 0 indien nieuwe inschrijving
if ($id < 0 || !is_numeric($id)) { // check op ID
	echo "Er is iets misgegaan.";
	echo "<br /><a href=\"./index.php\">Ga terug naar BIS&gt;&gt;</a></p>";
	exit();
}

if ($id > 0) { // bestaande/gelijksoortige inschrijving: haal de var'en op t.b.v. show_availability
	$again = (isset($_GET['again']) ? $_GET['again'] : 0);
	$query = "SELECT * FROM ".$opzoektabel." WHERE Volgnummer='$id';";
	$result = mysql_query($query);
	if ($result) {
		$rows_aff = mysql_affected_rows($bisdblink);
		if ($rows_aff > 0) {
			$row = mysql_fetch_assoc($result);
			if (isset($_POST['date'])) { 
				$date = $_POST['date'];
			} else {
				$date_db = $row['Datum'];
				$date = DBdateToDate($date_db);
			}
			if (isset($_POST['start_time_hrs']) && isset($_POST['start_time_mins'])) {
				$start_time = $_POST['start_time_hrs'].":".$_POST['start_time_mins'];
			} else {
				$start_time = $row['Begintijd'];
			}
			if (isset($_POST['end_time_hrs']) && isset($_POST['end_time_mins'])) {
				$end_time = $_POST['end_time_hrs'].":".$_POST['end_time_mins'];
			} else {
				$end_time = $row['Eindtijd'];
			}
			if (isset($_POST['boat_id'])) {
				$boat_id = $_POST['boat_id'];
			} else {
				if (!$again) $boat_id = $row['Boot_ID'];
			}
			if (isset($_POST['pname'])) {
				$pname = $_POST['pname'];
			} else {
				$pname = $row['Pnaam'];
			}
			if (isset($_POST['name'])) {
				$name = $_POST['name'];
			} else {
				$name = $row['Ploegnaam'];
			}
			if (isset($_POST['email'])) {
				$email = $_POST['email'];
			} else {
				$email = $row['Email'];
			}
			if (isset($_POST['mpb'])) {
				$mpb = $_POST['mpb'];
			} else {
				$mpb = $row['MPB'];
			}
			$spits = $row['Spits'];
		} else {
			echo "Deze inschrijving bestaat niet.";
			echo "<br /><a href=\"./index.php\">Ga terug naar BIS&gt;&gt;</a></p>";
			exit();
		}
	} else {
		echo "De inschrijving kan niet gevonden worden.";
		echo "<br /><a href=\"./index.php\">Ga terug naar BIS&gt;&gt;</a></p>";
		exit();
	}
}
if ($id == 0) { // nieuwe inschrijving: haal de var'en op t.b.v. show_availability
	if (isset($_POST['boat_id'])) { 
		$boat_id = $_POST['boat_id'];
	} else {
		$boat_id = $_GET['boat_id'];
	}
	if (isset($_POST['date'])) {
		$date = $_POST['date'];
	} else {
		$date = $_GET['date'];
	}
	if (isset($_POST['start_time_hrs']) && isset($_POST['start_time_mins'])) {
		$start_time = $_POST['start_time_hrs'].":".$_POST['start_time_mins'];
	} else {
		$start_time = $_GET['time_to_show'];
	}
	if (isset($_POST['end_time_hrs']) && isset($_POST['end_time_mins'])) {
		$end_time = $_POST['end_time_hrs'].":".$_POST['end_time_mins'];
	}
}

// sanity check op boot
if (!isset($again) && (!is_numeric($boat_id) || $boat_id < 0)) {
	echo "Deze boot bestaat niet.";
	echo "<br /><a href=\"./index.php\">Ga terug naar BIS&gt;&gt;</a></p>";
	exit();
}
// sanity check op datum
if (!CheckTheDate($date)) {
	echo "Datum ($date) klopt niet.";
	echo "<br /><a href=\"./index.php\">Ga terug naar BIS&gt;&gt;</a></p>";
	exit();
} else {
	$date_db = DateToDBdate($date);
}

// indien niet aanwezig, tijden alvast invullen met defaults:
if (!$start_time) {
	if ($date == $today) {
		if ($thehour_q < 6) {
			$start_time_hrs = 6;
			$start_time_mins = 0;
		} else {
			$start_time_hrs = $thehour_q;
			$start_time_mins = $theminute_quarts;
		}
	} else {
		$start_time_hrs = 9;
		$start_time_mins = 0;
	}
	$start_time = $start_time_hrs.":".$start_time_mins;
} else {
	$start_time_fields = explode(":", $start_time);
	$start_time_hrs = $start_time_fields[0];
	$start_time_mins = $start_time_fields[1];
}

if (!isset($end_time)) {
	if ($cat_to_show == "Ergometers en bak") {
		$end_time_hrs = min(23, $start_time_hrs + 1);
	} else {
		$end_time_hrs = min(23, $start_time_hrs + 2);
	}
	if ($start_time_hrs >= 22 && $end_time_hrs == 23) {
		$end_time_mins = 45;
	} else {
		$end_time_mins = $start_time_mins;
	}
	$end_time = $end_time_hrs.":".$end_time_mins;
} else {
	$end_time_fields = explode(":", $end_time);
	$end_time_hrs = $end_time_fields[0];
	$end_time_mins = $end_time_fields[1];
}

// disconnect from DB
mysql_close($bisdblink);

// Blok waarin bestaande inschrijvingen getoond worden (AJAX)
echo "<div id=\"AvailabilityInfo\">";
require_once('./show_availability.php');
echo "</div>";
//

echo "<div style=\"margin-left:10px; margin-right:10px\">";

// CONTROLE EN VERWERKING VAN INSCHRIJVING

if (isset($_POST['cancel'])){
	echo "<p>De inschrijving zal niet worden aangemaakt of gewijzigd.<br />";
	echo "<a href=\"./index.php?date_to_show=$date&amp;start_time_to_show=$start_time&amp;cat_to_show=$cat_to_show&amp;grade_to_show=$grade_to_show\">Klik hier om terug te gaan naar het inschrijfblad&gt;&gt;</a></p>";
}
	
if (isset($_POST['delete'])){
	echo deleteReservation($database_host, $database_user, $database_pass, $database, $opzoektabel, $id);
	echo "<br /><a href=\"./index.php?date_to_show=$date&amp;start_time_to_show=$start_time&amp;cat_to_show=$cat_to_show&amp;grade_to_show=$grade_to_show\">Klik hier om terug te gaan naar het inschrijfblad&gt;&gt;</a></p>";
}
	
if (isset($_POST['submit'])){
	$boat_id = $_POST['boat_id'];
	$pname = $_POST['pname'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$mpb = $_POST['mpb'];
	$date = $_POST['date'];
	$start_time_hrs = $_POST['start_time_hrs'];
	$start_time_mins = $_POST['start_time_mins'];
	$end_time_hrs = $_POST['end_time_hrs'];
	$end_time_mins = $_POST['end_time_mins'];
	$ergo_lo = $_POST['ergo_lo'];
	if ($ergo_lo == "") $ergo_lo = 0;
	$ergo_hi = $_POST['ergo_hi'];
	if ($ergo_hi == "") $ergo_hi = 0;
	echo makeReservation($database_host, $database_user, $database_pass, $database, $opzoektabel, $fail_msg, false, $id, $again, $boat_id, $pname, $name, $email, $mpb, $date, $start_time_hrs, $start_time_mins, $end_time_hrs, $end_time_mins, $ergo_lo, $ergo_hi);
}

// reconnect to DB
$bisdblink = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $bisdblink)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}

// HET FORMULIER
if ((!isset($_POST['submit']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) || $fail_msg != "") {
	echo "<h1>Inschrijving ";
	if ($id && !$again) {
		echo "bewerken";
	} else {
		if ($spits) {
			echo "bevestigen";
		} else {
			echo "maken";
		}
	}
	echo "</h1>";
	if ($fail_msg != "") {
		echo "<p><span class=\"update\">Uw inschrijving bevat de volgende fout(en):</span><br />";
		echo "<em>$fail_msg</em></p>";
	}
	echo "<form name='form' action=\"". (isset($REQUEST_URI) ? $REQUEST_URI : "") . "\" method=\"post\">";
	echo "<table><tr>";
	
	// ID, tbv AJAX
	echo "<td style=\"display:none\"><input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\" /></td></tr><tr>";
	
	// Ergo-blokinschrijving, alleen bij een nieuwe inschrijving van Concepts
	if (($id == 0 || $again) && substr($boat, 0, 7) == "Concept") {
		echo "<td colspan=\"2\">";
		echo "Schrijf in &eacute;&eacute;n keer meerdere Concept-ergometers in:<br />bijv. '3 t/m 5' voor Concepts 3, 4 en 5, of gewoon eentje, bijv. '2 t/m 2' voor alleen Concept 2.";
		echo "</td></tr><tr>";
		$ergo_lo = substr($boat, 8, 1);
		echo "<td colspan=2>Blokinschrijving: Concept ";
		echo "<select name=\"ergo_lo\">";
		for ($t = $ergo_lo; $t <= $NR_OF_CONCEPTS; $t++) {
			echo"<option value=\"".$t."\" ";
			if ($ergo_lo == $t) echo "selected=\"selected\"";
			echo ">".$t."</option>";
		}
		echo "</select> t/m ";
		if (!$ergo_hi) $ergo_hi = $ergo_lo;
		echo "<select name=\"ergo_hi\">";
		for ($t = $ergo_lo; $t <= $NR_OF_CONCEPTS; $t++) {
			echo"<option value=\"".$t."\" ";
			if ($ergo_hi == $t) echo "selected=\"selected\"";
			echo ">".$t."</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "</tr><tr>";
	}
	
	// Ingeval van blokinschrijving Concepts, geen mogelijkheid om andere boot te kiezen
	if (substr($boat, 0, 7) == "Concept") $hide = " style=\"display:none\"";
	echo "<td" . (isset($hide) ? $hide : "") . ">Boot/ergometer:</td>";
	echo "<td". (isset($hide) ? $hide : "") . "><select" . (isset($hide) ? $hide : "") . " name=\"boat_id\" onchange='ChangeInfo();' id=\"boat_id\">";
	echo "<option value=0 ";
	if ($boat_id == 0) echo "selected=\"selected\"";
	echo "></option>";
	$query = "SELECT boten.ID AS ID, Naam, Gewicht, Type, boten.Roeigraad FROM boten JOIN roeigraden ON boten.Roeigraad=roeigraden.Roeigraad WHERE Datum_eind IS NULL ORDER BY Type, roeigraden.ID, Naam;";
	$boats_result = mysql_query($query);
	if (!$boats_result) {
		die("Ophalen van vlootinformatie mislukt.". mysql_error());
	} else {
		$t = 0;
		while ($row = mysql_fetch_assoc($boats_result)) {
			$curr_boat_id = $row[ID];
			$curr_boat = $row[Naam];
			$curr_weight = $row[Gewicht];
			$curr_type = $row[Type];
			if ($curr_type != $curr_type_mem) {
				if ($t) echo "</optgroup>";
				echo "<optgroup label=\"".$curr_type."\">";
			}
			$curr_type_mem = $curr_type;
			$curr_grade = $row[Roeigraad];
			echo "<option value=\"".$curr_boat_id."\" ";
			if ($boat_id == $curr_boat_id) echo "selected=\"selected\"";
			echo ">".$curr_boat." (".$curr_weight." kg, ".$curr_grade.")</option>";
			$t++;
		}
		echo "</optgroup>";
	}
	echo "</select></td>";
	echo "</tr><tr>";
	
	// persoonsnaam
	echo "<td>Voor- en achternaam:</td>";
	echo "<td><input type=\"text\" name=\"pname\" value=\"" . (isset($pname) ? $pname : "") . "\" size=\"30\" /></td>";
	echo "</tr><tr>";
	
	// ploegnaam
	echo "<td>Ploegnaam/omschrijving (optioneel):</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"" . (isset($name) ? $name : "") . "\" size=\"30\" /></td>";
	echo "</tr><tr>";
	
	// e-mailadres
	echo "<td>E-mailadres (optioneel):</td>";
	echo "<td><input type=\"text\" name=\"email\" value=\"" . (isset($email) ? $email : "") . "\" size=\"30\" /></td>";
	echo "</tr><tr>";
	
	// mpb
	echo "<td>MPB (indien nodig):</td>";
	echo "<td><select name=\"mpb\">";
	$cnt = 0;
	foreach($mpb_array as $mpb_db) {
		echo "<option value=\"$mpb_db\" ";
		if (isset($mpb) && $mpb == $mpb_db) echo "selected=\"selected\"";
		echo ">$mpb_array_sh[$cnt]</option>";
		$cnt++;
	}
	echo "</select></td>";
	echo "</tr><tr>";
	
	// datum
	echo "<td>Datum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' onchange='ChangeInfo();' name='date' id='date' size='8' maxlength='10' value='$date' />";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.date');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='res/kalender.gif' alt='kalender' width='19' height='17' border='0' /></a></td>";
	echo "</tr><tr>";
	
	// begintijd
	echo "<td>Begintijd</td>";
	echo "<td><select name='start_time_hrs' onchange='ChangeInfo();' id='start_time_hrs'>";
		for ($t=6; $t<24; $t++) {
			echo"<option value=\"".$t."\" ";
			if ($start_time_hrs == $t) echo "selected=\"selected\"";
			echo ">".$t."</option>";
		}
	echo "</select>";
	echo "&nbsp;<select name='start_time_mins' onchange='ChangeInfo();' id='start_time_mins'>";
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
	echo "</tr><tr>";
	
	// eindtijd
	echo "<td>Eindtijd:</td>";
	echo "<td><select name='end_time_hrs' onchange='ChangeInfo();' id='end_time_hrs'>";
		for ($t=6; $t<24; $t++) {
			echo"<option value=\"".$t."\" ";
			if ($end_time_hrs == $t) echo "selected=\"selected\"";
			echo ">".$t."</option>";
		}
	echo "</select>";
	echo "&nbsp;<select name='end_time_mins' onchange='ChangeInfo();' id='end_time_mins'>";
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
	echo "</tr>";
	echo "</table>";
	
	// knoppen
	echo "<p><input type=\"submit\" name=\"submit\" value=\"";
	if ($id && !$again) {
		echo "Opslaan";
	} else {
		if ($spits) {
			echo "Bevestigen";
		} else {
			echo "Inschrijven";
		}
	}
	echo "\" /> ";
	if ($id) {
		echo "<input type=\"submit\" name=\"delete\" value=\"Verwijderen\" /> ";
	}
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}

mysql_close($bisdblink);

?>
</div>

<script type="text/javascript" src="scripts/ajax_inschrijving.js"></script>

</body>
</html>
