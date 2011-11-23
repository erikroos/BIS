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
$fail = FALSE;
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
	$again = $_GET['again'];
	$query = "SELECT * FROM ".$opzoektabel." WHERE Volgnummer='$id';";
	$result = mysql_query($query);
	if ($result) {
		$rows_aff = mysql_affected_rows($bisdblink);
		if ($rows_aff > 0) {
			$row = mysql_fetch_assoc($result);
			if ($_POST['date']) { 
				$date = $_POST['date'];
			} else {
				$date_db = $row['Datum'];
				$date = DBdateToDate($date_db);
			}
			if ($_POST['start_time_hrs'] && $_POST['start_time_mins']) {
				$start_time = $_POST['start_time_hrs'].":".$_POST['start_time_mins'];
			} else {
				$start_time = $row['Begintijd'];
			}
			if ($_POST['end_time_hrs'] && $_POST['end_time_mins']) {
				$end_time = $_POST['end_time_hrs'].":".$_POST['end_time_mins'];
			} else {
				$end_time = $row['Eindtijd'];
			}
			if ($_POST['boat_id']) {
				$boat_id = $_POST['boat_id'];
			} else {
				if (!$again) $boat_id = $row['Boot_ID'];
			}
			if ($_POST['pname']) {
				$pname = $_POST['pname'];
			} else {
				$pname = $row['Pnaam'];
			}
			if ($_POST['name']) {
				$name = $_POST['name'];
			} else {
				$name = $row['Ploegnaam'];
			}
			if ($_POST['email']) {
				$email = $_POST['email'];
			} else {
				$email = $row['Email'];
			}
			if ($_POST['mpb']) {
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
	if ($_POST['boat_id']) { 
		$boat_id = $_POST['boat_id'];
	} else {
		$boat_id = $_GET['boat_id'];
	}
	if ($_POST['date']) {
		$date = $_POST['date'];
	} else {
		$date = $_GET['date'];
	}
	if ($_POST['start_time_hrs'] && $_POST['start_time_mins']) {
		$start_time = $_POST['start_time_hrs'].":".$_POST['start_time_mins'];
	} else {
		$start_time = $_GET['time_to_show'];
	}
	if ($_POST['end_time_hrs'] && $_POST['end_time_mins']) {
		$end_time = $_POST['end_time_hrs'].":".$_POST['end_time_mins'];
	}
}

// sanity check op boot
if (!$again && (!is_numeric($boat_id) || $boat_id < 0)) {
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

if (!$end_time) {
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

// Blok waarin bestaande inschrijvingen getoond worden
echo "<div id=\"AvailabilityInfo\">";
require_once('./show_availability.php');
echo "</div>";
//

// reconnect
$bisdblink = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $bisdblink)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}

echo "<div style=\"margin-left:10px; margin-right:10px\">";
// CONTROLE EN VERWERKING VAN INSCHRIJVING
if ($_POST['cancel']){
	echo "<p>De inschrijving zal niet worden aangemaakt of gewijzigd.<br />";
	echo "<a href=\"./index.php?date_to_show=$date&amp;start_time_to_show=$start_time&amp;cat_to_show=$cat_to_show&amp;grade_to_show=$grade_to_show\">Klik hier om terug te gaan naar het inschrijfblad&gt;&gt;</a></p>";
} // cancel
	
if ($_POST['delete']){
	$email_to = "";
	// haal gegevens niet uit form maar uit DB, om fraude te voorkomen
	$query2 = "SELECT Email, Boot_ID, Datum, Begintijd FROM ".$opzoektabel." WHERE Volgnummer='$id';";
	$result2 = mysql_query($query2);
	if ($result2) {
		$row = mysql_fetch_assoc($result2);
		$email_to = $row['Email'];
		$boot_id = $row['Boot_ID'];
		// bootnaam
		$query_bootnaam = "SELECT Naam from boten WHERE ID=$boot_id;";
		$result_bootnaam = mysql_query($query_bootnaam);
		$row_bootnaam = mysql_fetch_assoc($result_bootnaam);
		$boot = $row_bootnaam['Naam'];
		//
		$db_datum = $row['Datum'];
		$date_tmp = strtotime($db_datum);
		$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
		$starttijd = $row['Begintijd'];
		$message = "Uw inschrijving van '$boot' op $date_sh vanaf ".substr($starttijd, 0, 5)." is zojuist uit BIS verwijderd.";
	}
	$query = "UPDATE ".$opzoektabel." SET Verwijderd=1 WHERE Volgnummer='$id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Het verwijderen van de inschrijving is mislukt.". mysql_error());
	} else {
		echo "<p>De inschrijving is verwijderd.<br />NB: de gegevens zijn ter controle bewaard.";
		if (SendEmail($email_to, "Verwijdering inschrijving", $message)) {
			echo "<br />NB: er is ter controle een e-mail gestuurd aan de oorspronkelijke inschrijver.";
		}
	}
	echo "<br /><a href=\"./index.php?date_to_show=$date&amp;start_time_to_show=$start_time&amp;cat_to_show=$cat_to_show&amp;grade_to_show=$grade_to_show\">Klik hier om terug te gaan naar het inschrijfblad&gt;&gt;</a></p>";
} // delete
	
if ($_POST['submit']){

	// check PersoonsNaam
	$pname = $_POST['pname'];
	if (!CheckName($pname)) {
		$fail_msg1 = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	
	$name = $_POST['name']; // ploegnaam; kan van alles zijn; addslashes() komt verderop
	
	$email = $_POST['email'];
	// niet verplicht, maar moet wel correct zijn
	if ($email && !CheckEmail($email)) {
		$fail_msg_email = "U dient een geldig e-mailadres op te geven.";
	}
	
	$mpb = $_POST['mpb'];
	
	$date = $_POST['date'];
	$date_db = 0;
	if (!$date || !CheckTheDate($date) || ($mpb != "Societeit" && !InRange($date, 10))) {
		$fail_msg_date = "U dient een (geldige) inschrijfdatum op te geven, vanaf vandaag tot over maximaal 10 dagen.";
	} else {
		$date_db = DateToDBdate($date);
		if (strtotime($date_db) < strtotime($today_db)) {
			$fail_msg_date = "Een inschrijving kan niet in het verleden plaatsvinden.";
		}
	}
	
	$start_time_hrs = $_POST['start_time_hrs'];
	$start_time_mins = $_POST['start_time_mins'];
	$start_time = $start_time_hrs.":".$start_time_mins;	
	$end_time_hrs = $_POST['end_time_hrs'];
	$end_time_mins = $_POST['end_time_mins'];
	$end_time = $end_time_hrs.":".$end_time_mins;	
	$duration = (($end_time_hrs - $start_time_hrs) * 60) + ($end_time_mins - $start_time_mins);
	if ($duration <= 0) {
		$fail_msg2 = "De eindtijd van een inschrijving dient later dan de begintijd te zijn.";
	}
	if ($date_db == $today_db && (($start_time_hrs < $thehour) || (($start_time_hrs == $thehour) && ($start_time_mins < $theminute)))) {
		$fail_msg2 = "Een inschrijving kan niet in het verleden beginnen.";
	}
	
	// check ergo-blok
	$ergo_lo = $_POST['ergo_lo'];
	$ergo_hi = $_POST['ergo_hi'];
	$ergo_range = $ergo_hi - $ergo_lo;
	if ($ergo_range < 0) {
		$fail_msg_ergo = "Het blok moet lopen van de laagst- t/m de hoogst-genummerde Concept-ergometer.";
		$ergo_lo = 0;
		$ergo_hi = 0;
	}
	
	$boat_id = $_POST['boat_id'];
	// cat. bepalen n.a.v. boot die wordt ingeschreven; kan i.v.m. mogelijkheid tot boot-selectie anders zijn dan de geGETte
	$query = "SELECT Roeigraad, Type FROM boten WHERE ID='$boat_id';";
	$result = mysql_query($query);
	if ($result) {
		$row = mysql_fetch_assoc($result);
		$grade = $row['Roeigraad'];
		if ($grade_to_show != $grade) $grade_to_show = "alle"; // roeigraad op 'alle' zetten als gekozen boot andere graad heeft
		$type = $row['Type'];
		$query2 = "SELECT Categorie FROM types WHERE Type='$type';";
		$result2 = mysql_query($query2);
		if ($result2) {
			$row2 = mysql_fetch_assoc($result2);
			$cat = $row2['Categorie'];
			if ($cat_to_show != $cat) $grade_to_show = "alle"; // roeigraad op 'alle' zetten als gekozen boot ander type heeft
			$cat_to_show = $cat;
		}
	}
	if ($boat_id == 0) $fail_msg_boat = "U heeft geen boot geselecteerd.";
	// bootnaam
	if ($boat_id == 0) {
		$boat = "";
	} else {
		$query_bootnaam = "SELECT Naam FROM boten WHERE ID=$boat_id;";
		$result_bootnaam = mysql_query($query_bootnaam);
		$row_bootnaam = mysql_fetch_assoc($result_bootnaam);
		$boat = $row_bootnaam['Naam'];
	}
	//
	// check op uit de vaart
	$query = "SELECT * FROM uitdevaart WHERE Verwijderd=0 AND Boot_ID='$boat_id' AND Startdatum<='$date_db' AND (Einddatum='0' OR Einddatum='0000-00-00' OR Einddatum>='$date_db');";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen van Uit de Vaart-informatie mislukt.". mysql_error());
	} else {
		$rows_aff = mysql_affected_rows($bisdblink);
		if ($rows_aff > 0) $fail_msg_boat = "Deze boot is op deze dag uit de vaart.";
	}
	
	$controle = 0;
	if ($duration > 120) {
		if ($mpb == "") $fail_msg_mpb = "U schrijft voor langer dan 2 uur in. Hiervoor is MPB benodigd.";
		$controle = 1;
	}
	if (!InRange($date, 3)) {
		 if ($mpb == "") $fail_msg_mpb = "U schrijft meer dan 3 dagen vantevoren in. Hiervoor is MPB benodigd.";
		 $controle = 2;
	}
	if ($grade == "MPB") {
		if ($mpb == "") $fail_msg_mpb = "U schrijft een MPB-boot in. Hiervoor is MPB benodigd.";
		$controle = 3;
	}
	
	$fail_partial_cnt = 0;
	echo "<p>";
	for ($e = $ergo_lo; $e <= $ergo_hi; $e++) {
	    // T.b.v. blokinschrijving
		$fail_partial = false;
		if ($e > 0) {
			$boat = "Concept ".$e;
			$query_ergonaam = "SELECT ID FROM boten WHERE Naam='$boat';";
			$result_ergonaam = mysql_query($query_ergonaam);
			$row_ergonaam = mysql_fetch_assoc($result_ergonaam);
			$boat_id = $row_ergonaam['ID'];
		}
		// Check inschrijving tegen de database
		$query = "SELECT * FROM ".$opzoektabel." WHERE Verwijderd=0 AND Volgnummer <> '$id' AND ((Begintijd >= '$start_time' AND Begintijd < '$end_time') OR (Eindtijd > '$start_time' AND Eindtijd <= '$end_time') OR (Begintijd <= '$start_time' AND Eindtijd >= '$end_time')) AND Datum = '$date_db' AND Boot_ID = '$boat_id';";
		$result = mysql_query($query);
		if (!$result) {
			die("Het controleren van uw inschrijving is mislukt.". mysql_error());
			$fail = TRUE;
		} else {
			$rows_aff = mysql_affected_rows($bisdblink);
			if ($rows_aff > 0) {
				if ($e == 0) {
					$fail_msg3 = "Uw inschrijving van $boat is mislukt omdat deze conflicteert met een al bestaande inschrijving.";
				} else {
					echo "Uw inschrijving van $boat is mislukt omdat deze conflicteert met een al bestaande inschrijving.<br />";
					$fail_partial = true;
					$fail_partial_cnt += 1;
				}
			}
		}
		
		// Ingeval van het bewerken van een bestaande inschrijving, eerst oude uit DB verwijderen
		if ($id && !$again) {
			if (!($fail_msg1 || $fail_msg2 || $fail_msg3 || $fail_msg_date || $fail_msg_email || $fail_msg_mpb || $fail_msg_boat)) {
				$email_to = "";
				// haal gegevens niet uit form maar uit DB, om fraude te voorkomen
				$query2 = "SELECT Email, Boot_ID, Datum, Begintijd, Spits FROM ".$opzoektabel." WHERE Volgnummer='$id';";
				$result2 = mysql_query($query2);
				if ($result2) {
					$row = mysql_fetch_assoc($result2);
					$email_to = $row['Email'];
					$boot_id = $row['Boot_ID'];
					// bootnaam
					$query_bootnaam = "SELECT Naam FROM boten WHERE ID=$boot_id;";
					$result_bootnaam = mysql_query($query_bootnaam);
					$row_bootnaam = mysql_fetch_assoc($result_bootnaam);
					$boot = $row_bootnaam['Naam'];
					//
					$db_datum = $row['Datum'];
					$date_tmp = strtotime($db_datum);
					$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
					$starttijd = $row['Begintijd'];
					$spitsnr = $row['Spits'];
					if ($spitsnr > 0) {
						$message = "Uw spitsblok van '$boot' op $date_sh vanaf ".substr($starttijd, 0, 5)." is zojuist bevestigd.";
					} else {
						$message = "Uw inschrijving van '$boot' op $date_sh vanaf ".substr($starttijd, 0, 5)." is zojuist gewijzigd.";
					}
				}
				$mail_gestuurd = false;
				$query = "UPDATE ".$opzoektabel." SET Verwijderd=1 WHERE Volgnummer='$id';";
				$result = mysql_query($query);
				if (!$result) {
					die("Het verwijderen van de oude inschrijving is mislukt.". mysql_error());
				} else {
					if (SendEmail($email_to, "Wijziging of bevestiging inschrijving", $message)) {
						$mail_gestuurd = true;
					}
				}
			}
		}
		
		// Het inserten
		if ($fail_msg1 || $fail_msg2 || $fail_msg3 || $fail_msg_date || $fail_msg_email || $fail_msg_mpb || $fail_msg_boat || $fail_msg_ergo || $fail_partial) {
			if (!$fail_partial) $fail = TRUE;
		} else { // inschrijving wordt ingevoerd of gewijzigd
			$name = addslashes($name); // speciale tekens in ploegnaam "redden"
			$name = preg_replace("/\"/", "'", $name); // dubbele quotes omzetten naar enkele, omdat anders het tooltip-scriptje gek wordt
			$query = "INSERT INTO ".$opzoektabel." (Datum, Inschrijfdatum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, Email, MPB, Spits, Controle) VALUES ('$date_db', '$today_db', '$start_time', '$end_time', '$boat_id', '$pname', \"$name\", '$email', '$mpb', '0', '$controle');";
			$result = mysql_query($query);
			if (!$result) {
				die("Uw inschrijving is mislukt.". mysql_error());
				$fail = TRUE;
			} else {
				$date_tmp = strtotime($date_db);
				$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
				echo "Beste $pname, uw inschrijving van '$boat' op $date_sh van ".substr($start_time, 0, 5)." tot ".substr($end_time, 0, 5)." is gelukt.<br />";
				if ($controle) {
					echo "NB: uw inschrijving is vanwege MPB gelogd en zal door het opgegeven bestuurslid worden gecontroleerd.<br />";
				}
				if ($mail_gestuurd) {
					echo "NB: er is ter controle een e-mail gestuurd aan de oorspronkelijke inschrijver.<br />";
				}
			}
		}
	} // for
	if (!$fail) {
		if ($fail_partial_cnt > 0) echo "Een of meer van de inschrijvingen in uw blok zijn mislukt.<br />";
		if ($koudwaterprotocol && ($themonth < 4 || $themonth > 9) && $cat_to_show != "Ergometers en bak") {
			echo "<p style='font-size:150%'><strong>LET OP!</strong> Wees in de winter voorzichtig i.v.m. het koude water. Heeft u het <a href='../drupal/sites/default/files/Koudwaterprotocol.pdf' target='_blank'>koudwater-protocol</a> al gelezen?</p>";
		}
		echo "<a href=\"./index.php?date_to_show=$date&amp;start_time_to_show=$start_time&amp;cat_to_show=$cat_to_show&amp;grade_to_show=$grade_to_show\">Klik hier om terug te gaan naar het inschrijfblad&gt;&gt;</a><br />";
		// link t.b.v. nog een inschrijving met dezelfde gegevens, m.u.v. boot
		$query = "SELECT Volgnummer FROM ".$opzoektabel." WHERE Verwijderd=0 AND Datum='$date_db' AND Begintijd='$start_time' AND Boot_ID='$boat_id';";
		$result = mysql_query($query);
		if ($result) {
			$row = mysql_fetch_assoc($result);
			$id_again = $row['Volgnummer'];
			echo "<a href=\"./inschrijving.php?id=$id_again&amp;again=1&amp;date=$date&amp;cat_to_show=$cat_to_show&amp;grade_to_show=$grade_to_show&amp;time_to_show=$start_time\">Klik hier om een inschrijving te maken van een andere boot met dezelfde gegevens&gt;&gt;</a>";
		}
		echo "</p>";
		exit();
	}
}

// HET FORMULIER
if ((!$_POST['submit'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
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
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table><tr>";
	if ($fail_msg3) echo "<td colspan=\"3\"><em>$fail_msg3</em></td></tr><tr>";
	
	// ID, tbv AJAX
	echo "<td style=\"display:none\" colspan=\"3\"><input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\" /></td></tr><tr>";
	
	// Ergo-blokinschrijving, alleen bij een nieuwe inschrijving van Concepts
	if (($id == 0 || $again) && substr($boat, 0, 7) == "Concept") {
		echo "<td colspan=\"3\">";
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
		if ($fail_msg_ergo) echo "<td><em>$fail_msg_ergo</em></td>";
		echo "</tr><tr>";
	}
	
	// Ingeval van blokinschrijving Concepts, geen mogelijkheid om andere boot te kiezen
	if (substr($boat, 0, 7) == "Concept") $hide = " style=\"display:none\"";
	echo "<td".$hide.">Boot/ergometer:</td>";
	echo "<td".$hide."><select".$hide." name=\"boat_id\" onchange='ChangeInfo();' id=\"boat_id\">";
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
	if ($fail_msg_boat) echo "<td".$hide."><em>$fail_msg_boat</em></td>";
	echo "</tr><tr>";
	
	// PersoonsNaam (pname)
	echo "<td>Voor- en achternaam:</td>";
	echo "<td><input type=\"text\" name=\"pname\" value=\"$pname\" size=\"30\" /></td>";
	if ($fail_msg1) echo "<td><em>$fail_msg1</em></td>";
	echo "</tr><tr>";
	
	// ploegnaam
	echo "<td>Ploegnaam/omschrijving (optioneel):</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"$name\" size=\"30\" /></td>";
	echo "</tr><tr>";
	
	// e-mailadres
	echo "<td>E-mailadres (optioneel):</td>";
	echo "<td><input type=\"text\" name=\"email\" value=\"$email\" size=\"30\" /></td>";
	if ($fail_msg_email) echo "<td><em>$fail_msg_email</em></td>";
	echo "</tr><tr>";
	
	// mpb
	echo "<td>MPB (indien nodig):</td>";
	echo "<td><select name=\"mpb\">";
	$cnt = 0;
	foreach($mpb_array as $mpb_db) {
		echo "<option value=\"$mpb_db\" ";
		if ($mpb == $mpb_db) echo "selected=\"selected\"";
		echo ">$mpb_array_sh[$cnt]</option>";
		$cnt++;
	}
	echo "</select></td>";
	if ($fail_msg_mpb) echo "<td colspan=2><em>$fail_msg_mpb</em></td>";
	echo "</tr><tr>";
	
	// datum
	echo "<td>Datum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' onchange='ChangeInfo();' name='date' id='date' size='8' maxlength='10' value='$date' />";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.date');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='res/kalender.gif' alt='kalender' width='19' height='17' border='0' /></a></td>";
	if ($fail_msg_date) echo "<td><em>$fail_msg_date</em></td>";
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
	if ($fail_msg2) {
		echo "<td><em>$fail_msg2</em><td>";
	}
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
