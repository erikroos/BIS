<?php

include_once("include_globalVars.php");
include_once("include_helperMethods.php");

setlocale(LC_TIME, 'nl_NL');

$link = getDbLink($database_host, $database_user, $database_pass, $database);

// mail is sent after 00:05 everyday, so get values for yesterday
$yday_ts = strtotime('-1 days');
$yesterday = date('Y-m-d', $yday_ts);
$yesterday_sh = strftime('%A %d-%m-%Y', $yday_ts);

// datum voor over een week
$inoneweek_ts = strtotime('+7 days');
$inoneweek = date('Y-m-d', $inoneweek_ts);

// tellen t.b.v. statistieken
$tot_ins = 0;
$tot_ins_old = 0;
$query = "SELECT COUNT(*) AS TotIns FROM ".$opzoektabel." WHERE Verwijderd=0;";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Tellen mislukt.". mysqli_error());
} else {
	$row = mysqli_fetch_assoc($result);
	$tot_ins = $row['TotIns'];
}
$query = "SELECT COUNT(*) AS TotInsOld FROM ".$opzoektabel."_oud WHERE Verwijderd=0;";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Tellen mislukt.". mysqli_error());
} else {
	$row = mysqli_fetch_assoc($result);
	$tot_ins_old = $row['TotInsOld'];
}
$query = "INSERT INTO ".$stattabel." (Peildatum, TotIns, TotInsOud) VALUES ('$yesterday', $tot_ins, $tot_ins_old);";
mysqli_query($link, $query);

// verlopen Uit de Vaartjes overhevelen
$query = "UPDATE uitdevaart SET Verwijderd=1 WHERE (Einddatum>'0000-00-00' AND Einddatum<'$today_db');";
$result = mysqli_query($link, $query);
if (!$result) {
	$message = "1. Be&euml;indigen van oude uit-de-vaart-meldingen uit de BIS-database mislukt.<br>";
} else {
	$message = "1. Be&euml;indigen van oude uit-de-vaart-meldingen uit de BIS-database geslaagd.<br>";
}

// cursussen die over een week starten, afsluiten:
// 'inschrijving gesloten' achter cursusnaam en quotum = aantal deelnemers
$query = "SELECT ID, Type FROM cursussen WHERE Startdatum='$inoneweek';";
$result = mysqli_query($link, $query);
if ($result) {
	$c = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$course_id = $row['ID'];
		$type = $row['Type'];
		if (!preg_match("/inschrijving\sgesloten/", $type)) {
			$type_new = $type." - inschrijving gesloten";
		} else {
			$type_new = $type;
		}
		
		$query2 = "SELECT COUNT(*) AS NrOfExi FROM `cursus_inschrijvingen` WHERE Ex_ID='$course_id';";
		$result2 = mysqli_query($link, $query2);
		$row2 = mysqli_fetch_assoc($result2);
		$quotum_new = $row2['NrOfExi'];
		
		$query3 = "UPDATE cursussen SET Type='$type_new', Quotum='$quotum_new' WHERE ID='$course_id';";
		$result3 = mysqli_query($link, $query3);
		if (!$result3) {
			$message .= "2. Afsluiten van cursus die over een week begint mislukt.<br>";
		} else {
			$message .= "2. Afsluiten van cursus die over een week begint geslaagd.<br>";
		}
		$c++;
	}
	if ($c == 0) $message .= "2. Geen cursussen die over een week beginnen om af te sluiten.<br>";
} else {
	$message .= "2. Afsluiten van cursussen die over een week beginnen mislukt.<br>";
}

// migratie oude inschrijvingen naar _oud
mysqli_query($link, "LOCK TABLES ".$opzoektabel." WRITE, ".$opzoektabel."_oud WRITE;");
$query = "INSERT INTO ".$opzoektabel."_oud SELECT * FROM ".$opzoektabel." WHERE Datum<'$today_db';";
$result = mysqli_query($link, $query);
if (!$result) {
	$message .= "3. Overhevelen van oude inschrijvingen uit de BIS-database mislukt.<br>";
} else {
	$message .= "3. Overhevelen van oude inschrijvingen uit de BIS-database geslaagd.<br>";
}
$query = "DELETE FROM ".$opzoektabel." WHERE Datum<'$today_db';";
$result = mysqli_query($link, $query);
if (!$result) {
	$message .= "4. Verwijderen van oude inschrijvingen uit de BIS-database mislukt.<br>";
} else {
	$message .= "4. Verwijderen van oude inschrijvingen uit de BIS-database geslaagd.<br>";
}
$query = "DELETE FROM ".$opzoektabel." WHERE Spits>0 AND Datum<='$today_db';";
$result = mysqli_query($link, $query);
if (!$result) {
	$message .= "5. Verwijderen van niet-bevestigde spitsinschrijvingen uit de BIS-database mislukt.<br>";
} else {
	$message .= "5. Verwijderen van niet-bevestigde spitsinschrijvingen uit de BIS-database geslaagd.<br>";
}
mysqli_query($link, "UNLOCK TABLES;");

//SendEmail("bis@hunze.nl", "Resultaat nightly migration van $yesterday_sh", $message);

mysqli_close($link);
