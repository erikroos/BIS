<?phpinclude_once("include_helperMethods.php");include_once("include_globalVars.php");setlocale(LC_TIME, 'nl_NL');function deleteReservation($res_id) {		global $database_host;	global $database_user;	global $database_pass;	global $database;	global $opzoektabel;		$email_to = "";	$messages = array();		$bisdblink = getDbLink($database_host, $database_user, $database_pass, $database);	if (!$bisdblink) {		$messages[] = "Fout: database niet gevonden.";		return array("success" => false, "messages" => $messages, "category" => "Dubbelvieren", "grade" => "alle", "action" => "del");	}		// haal gegevens niet uit form maar uit DB, om fraude te voorkomen	$query = "SELECT Email, Boot_ID, Datum, Begintijd FROM ".$opzoektabel." WHERE Volgnummer='$res_id';";	$result = mysqli_query($bisdblink, $query);	if ($result) {		$row = mysqli_fetch_assoc($result);		$email_to = $row['Email'];		$boot_id = $row['Boot_ID'];		// bootnaam + cat. & grade bepalen n.a.v. boot die wordt verwijderd		$query_bootnaam = "SELECT Naam, Roeigraad, `Type` from boten WHERE ID=" . $boot_id . ";";		$result_bootnaam = mysqli_query($bisdblink, $query_bootnaam);		$row_bootnaam = mysqli_fetch_assoc($result_bootnaam);		$boot = $row_bootnaam['Naam'];		$grade = $row_bootnaam['Roeigraad'];		$type = $row_bootnaam['Type'];		$query2 = "SELECT Categorie FROM types WHERE `Type`='" . $type . "';";		$result2 = mysqli_query($bisdblink, $query2);		if ($result2) {			$row2 = mysqli_fetch_assoc($result2);			$cat = $row2['Categorie'];		}		//		$db_datum = $row['Datum'];		$date_tmp = strtotime($db_datum);		$date_sh = strftime('%A %d-%m-%Y', $date_tmp);		$starttijd = $row['Begintijd'];		$mail_message = "Uw inschrijving van '$boot' op $date_sh vanaf ".substr($starttijd, 0, 5)." is zojuist uit BIS verwijderd.";	}	// the deleting itself	$query = "UPDATE ".$opzoektabel." SET Verwijderd=1 WHERE Volgnummer='$res_id';";	$result = mysqli_query($bisdblink, $query);	if (!$result) {		$success = false;		$messages[] = "Het verwijderen van de inschrijving is mislukt: " . mysqli_error();	} else {		$success = true;		$messages[] = "De inschrijving is verwijderd.";		$messages[] = "NB: de gegevens zijn ter controle bewaard.";		if (SendEmail($email_to, "Verwijdering inschrijving", $mail_message)) {			$messages[] = "NB: er is ter controle een e-mail gestuurd aan de oorspronkelijke inschrijver.";		}	}	mysqli_close($bisdblink);	return array("success" => $success, "messages" => $messages, "category" => $cat, "grade" => $grade, "action" => "del");}function makeReservation($id, $boat_id, $name, $team_name, $email, $mpb, $date, $start_time_hrs, $start_time_mins, $end_time_hrs, $end_time_mins, $ergo_lo = 0, $ergo_hi = 0) {		global $database_host;	global $database_user;	global $database_pass;	global $database;	global $opzoektabel;	global $koudwaterprotocol;	global $today_db;	global $thehour;	global $theminute;	global $themonth;		$NR_OF_CONCEPTS = 8; // LET OP: aanpassen als het aantal Concept-ergo's verandert! (ivm blokinschrijving)		$bisdblink = getDbLink($database_host, $database_user, $database_pass, $database);	if (!$bisdblink) {		$messages[] = "Fout: database niet gevonden.";		return array("success" => false, "messages" => $messages);	}		$messages = array();		// check persoonsnaam	if (!CheckName($name)) {		$messages[] = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";	}	// email is niet verplicht, maar moet wel correct zijn	if ($email && !CheckEmail($email)) {		$messages[] = "U dient een geldig e-mailadres op te geven.";	}	// check date	$date_db = 0;	if (!$date || !CheckTheDate($date) || ($mpb != "Societeit" && !InRange($date, 10))) {		$messages[] = "U dient een (geldige) inschrijfdatum op te geven, van vandaag tot over maximaal 10 dagen.";	} else {		$date_db = DateToDBdate($date);		if (strtotime($date_db) < strtotime($today_db)) {			$messages[] = "Een inschrijving kan niet in het verleden plaatsvinden.";		}	}	// check time	if (!is_numeric($start_time_hrs) || $start_time_hrs < 6 || $start_time_hrs > 23) {		$messages[] = "Ongeldig start-uur.";	}	if (!is_numeric($end_time_hrs) || $end_time_hrs < 6 || $end_time_hrs > 23) {		$messages[] = "Ongeldig eind-uur.";	}	if ($start_time_mins != "00" && $start_time_mins != "0" && $start_time_mins != "15" && $start_time_mins != "30" && $start_time_mins != "45") {		$messages[] = "Ongeldige start-minuten.";	}	if ($end_time_mins != "00" && $end_time_mins != "0" && $end_time_mins != "15" && $end_time_mins != "30" && $end_time_mins != "45") {		$messages[] = "Ongeldige eind-minuten.";	}	$start_time = $start_time_hrs.":".$start_time_mins;		$end_time = $end_time_hrs.":".$end_time_mins;		$duration = (($end_time_hrs - $start_time_hrs) * 60) + ($end_time_mins - $start_time_mins);	if ($duration <= 0) {		$messages[] = "De eindtijd van een inschrijving dient later dan de begintijd te zijn.";	}	if ($date_db == $today_db && (($start_time_hrs < $thehour) || (($start_time_hrs == $thehour) && ($start_time_mins < $theminute)))) {		$messages[] = "Een inschrijving kan niet in het verleden beginnen.";	}	// check ergo-blok	if (!is_numeric($ergo_lo) || !is_numeric($ergo_hi) || $ergo_lo < 0 || $ergo_lo > $NR_OF_CONCEPTS || $ergo_hi < 0 || $ergo_hi > $NR_OF_CONCEPTS) {		$messages[] = "Nummering van de Concept-ergometers klopt niet.";	}	$ergo_range = $ergo_hi - $ergo_lo;	if ($ergo_range < 0) {		$messages[] = "Het blok moet lopen van de laagst- t/m de hoogst-genummerde Concept-ergometer.";		$ergo_lo = 0;		$ergo_hi = 0;	}	// check boat	if (!is_numeric($boat_id) || $boat_id == 0) {		$boat = "";		$messages[] = "U heeft geen boot geselecteerd.";	} else {		$query_bootnaam = "SELECT Naam FROM boten WHERE ID=$boat_id;";		$result_bootnaam = mysqli_query($bisdblink, $query_bootnaam);		$row_bootnaam = mysqli_fetch_assoc($result_bootnaam);		$boat = $row_bootnaam['Naam'];	}	// cat. & grade bepalen n.a.v. boot die wordt ingeschreven	$query = "SELECT Roeigraad, `Type` FROM boten WHERE ID='" . $boat_id . "';";	$result = mysqli_query($bisdblink, $query);	if ($result) {		$row = mysqli_fetch_assoc($result);		$grade = $row['Roeigraad'];		$type = $row['Type'];		$query2 = "SELECT Categorie FROM types WHERE `Type`='" . $type . "';";		$result2 = mysqli_query($bisdblink, $query2);		if ($result2) {			$row2 = mysqli_fetch_assoc($result2);			$cat = $row2['Categorie'];		}	}	// check op uit de vaart	$query = "SELECT * 		FROM uitdevaart 		WHERE Verwijderd=0 		AND Boot_ID='$boat_id' 		AND Startdatum<='$date_db' 		AND (Einddatum='0' OR Einddatum='0000-00-00' OR Einddatum IS NULL OR Einddatum>='$date_db');";	$result = mysqli_query($bisdblink, $query);	if (!$result) {		$messages[] = "Ophalen van uit de vaart-informatie mislukt.";	} else {		$rows_aff = mysqli_affected_rows($bisdblink);		if ($rows_aff > 0) $messages[] = "Deze boot is op deze dag uit de vaart.";	}	// check MPB	// stop eerst alle MPB-gevende bestuursleden in een array	$mpb_array = array();	$query = "SELECT Functie FROM bestuursleden WHERE MPB=1;";	$result = mysqli_query($bisdblink, $query);	if (!$result) {		$messages[] = "Ophalen van bestuursleden mislukt.";	}	while ($row = mysqli_fetch_assoc($result)) {		array_push($mpb_array, $row['Functie']);	}	if ($mpb != "" && !in_array($mpb, $mpb_array)) {		$messages[] = "Onjuiste MPB-gever opgegeven.";	}	$controle = 0;	if ($duration > 120) {		if ($mpb == "") $messages[] = "U schrijft voor langer dan 2 uur in. Hiervoor is MPB benodigd.";		$controle = 1;	}	if (!InRange($date, 3)) {		 if ($mpb == "") $messages[] = "U schrijft meer dan 3 dagen vantevoren in. Hiervoor is MPB benodigd.";		 $controle = 2;	}	if ($grade == "MPB") {		if ($mpb == "") $messages[] = "U schrijft een MPB-boot in. Hiervoor is MPB benodigd.";		$controle = 3;	}		$action = "make";	if ($id > 0) {		$action = "alter";	}		// If one or more errors were made, return already	if (sizeof($messages) > 0) {		$messages[] = "Uw inschrijving is mislukt vanwege de genoemde fout(en).";		return array("success" => false, "messages" => $messages, "category" => $cat, "grade" => $grade, "action" => $action);	}		$success = false;	$fail_cnt = 0;	for ($e = $ergo_lo; $e <= $ergo_hi; $e++) {	    // T.b.v. blokinschrijving ergometers (with normal reservation, ergo_lo = ergo_hi = e = 0)		$fail = false;		if ($e > 0) {			$boat = "Concept " . $e;			$query_ergonaam = "SELECT ID FROM boten WHERE Naam='" . $boat . "';";			$result_ergonaam = mysqli_query($bisdblink, $query_ergonaam);			$row_ergonaam = mysqli_fetch_assoc($result_ergonaam);			$boat_id = $row_ergonaam['ID'];		}		// Check inschrijving tegen de database		$query = "SELECT * FROM ".$opzoektabel." WHERE Verwijderd=0 AND Volgnummer <> '$id' AND ((Begintijd >= '$start_time' AND Begintijd < '$end_time') OR (Eindtijd > '$start_time' AND Eindtijd <= '$end_time') OR (Begintijd <= '$start_time' AND Eindtijd >= '$end_time')) AND Datum = '$date_db' AND Boot_ID = '$boat_id';";		$result = mysqli_query($bisdblink, $query);		if (!$result) {			$messages[] = "Het controleren van uw inschrijving is mislukt.";			$fail = true;		} else {			$rows_aff = mysqli_affected_rows($bisdblink);			if ($rows_aff > 0) {				$messages[] = "Uw inschrijving van " . $boat . " is mislukt omdat deze conflicteert met een al bestaande inschrijving.";				$fail = true;			}		}				// Ingeval van het bewerken van een bestaande inschrijving, eerst oude uit DB verwijderen		$mail_gestuurd = false;		if ($id > 0 && $fail == false) {			$email_to = "";			// haal gegevens niet uit form maar uit DB, om fraude te voorkomen			$query2 = "SELECT Email, Boot_ID, Datum, Begintijd, Spits FROM " . $opzoektabel . " WHERE Volgnummer = " . $id . ";";			$result2 = mysqli_query($bisdblink, $query2);			if ($result2) {				$row = mysqli_fetch_assoc($result2);				$email_to = $row['Email'];				$boot_id = $row['Boot_ID'];				// retrieve boat name				$query_bootnaam = "SELECT Naam FROM boten WHERE ID=" . $boot_id . ";";				$result_bootnaam = mysqli_query($bisdblink, $query_bootnaam);				$row_bootnaam = mysqli_fetch_assoc($result_bootnaam);				$boot = $row_bootnaam['Naam'];				//				$db_datum = $row['Datum'];				$date_tmp = strtotime($db_datum);				$date_sh = strftime('%A %d-%m-%Y', $date_tmp);				$starttijd = $row['Begintijd'];				$spitsnr = $row['Spits'];				if ($spitsnr > 0) {					$mail_message = "Uw spitsblok van '$boot' op $date_sh vanaf ".substr($starttijd, 0, 5)." is zojuist bevestigd.";				} else {					$mail_message = "Uw inschrijving van '$boot' op $date_sh vanaf ".substr($starttijd, 0, 5)." is zojuist gewijzigd.";				}			}			$query = "UPDATE " . $opzoektabel . " SET Verwijderd = 1 WHERE Volgnummer = " . $id . ";";			$result = mysqli_query($bisdblink, $query);			if (!$result) {				$messages[] = "Het verwijderen van de oude inschrijving is mislukt.";				$fail = true;			} else {				if (SendEmail($email_to, "Wijziging of bevestiging inschrijving", $mail_message)) {					$mail_gestuurd = true;				}			}		}				// Het inserten		if ($fail == false) { // current (ergo) reservation is OK			// inschrijving wordt ingevoerd of gewijzigd			$today_db = date('Y-m-d');			$team_name = addslashes($team_name); // speciale tekens in ploegnaam "redden"			$team_name = preg_replace("/\"/", "'", $team_name); // dubbele quotes omzetten naar enkele, omdat anders het tooltip-scriptje gek wordt			$query = "INSERT INTO " . $opzoektabel . " (Datum, Inschrijfdatum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, Email, MPB, Spits, Controle) VALUES ('$date_db', '$today_db', '$start_time', '$end_time', '$boat_id', '$name', \"$team_name\", '$email', '$mpb', '0', '$controle');";			$result = mysqli_query($bisdblink, $query);			if (!$result) {				$messages[] = "Uw inschrijving is mislukt.";			} else {				$success = true;				$date_tmp = strtotime($date_db);				$date_sh = strftime('%A %d-%m-%Y', $date_tmp);				$messages[] = "Beste " . $name . ", uw inschrijving van '" . $boat . "' op " . $date_sh . " van " . substr($start_time, 0, 5) . " tot " . substr($end_time, 0, 5) . " is gelukt.";				if ($controle) {					$messages[] = "NB: uw inschrijving is vanwege MPB gelogd en zal door het opgegeven bestuurslid worden gecontroleerd.";				}				if ($mail_gestuurd) {					$messages[] = "NB: er is ter controle een e-mail gestuurd aan de oorspronkelijke inschrijver.";				}				if ($koudwaterprotocol && ($themonth < 4 || $themonth > 9) && $cat != "Ergometers en bak") {					// Mind: this is the only message that contains HTML formatting					$messages[] = "<span style='font-size:150%'><strong>LET OP!</strong> Wees in de winter voorzichtig i.v.m. het koude water. Heeft u het <a href='http://www.hunze.nl/drupal/sites/default/files/Koudwaterprotocol.pdf' target='_blank'>koudwater-protocol</a> al gelezen?</span>";				}			}		} else {			if ($e == 0) {				$messages[] = "Uw inschrijving is mislukt vanwege de genoemde fout(en).";			} else {				$fail_cnt++;			}		}	} // end for-loop ergo_lo - ergo_hi		if ($fail_cnt > 0) {		$messages[] = "Let op: &eacute;&eacute;n of meer van de inschrijvingen in uw blok zijn mislukt.";	}		mysqli_close($bisdblink);	return array("success" => $success, "messages" => $messages, "category" => $cat, "grade" => $grade, "action" => $action);}