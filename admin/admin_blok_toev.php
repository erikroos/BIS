<?php
$locationHeader = 'Wedstrijdblokken - Wedstrijdblok toevoegen';
$backLink = '<a href="./admin_blokken.php">Terug naar de wedstrijdblokken</a>';
include 'admin_header.php';
?>

<?php
$fail = false;
$blok_id = 0;
if (isset($_GET['id'])) {
	$blok_id = $_GET['id'];
	$result = mysqli_query($link, sprintf('SELECT MPB, Datum, Begintijd, Eindtijd, boten.Naam AS Bootnaam, Pnaam 
			FROM %s
			JOIN boten ON %s.Boot_ID = boten.ID
			WHERE Wedstrijdblok = %d 
			ORDER BY Datum', $opzoektabel, $opzoektabel, $blok_id));
	if (!$result) {
		die('Ophalen van informatie mislukt: ' . mysqli_error());
	}
	// uit eerste record kun je alles al halen, behalve -bij meer dan 1 inschrijving- de einddatum
	$row = mysqli_fetch_assoc($result);
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
	$boat = $row['Bootnaam'];
	$pname = $row['Pnaam'];
	$enddate = $row['Datum'];
	while ($row = mysqli_fetch_assoc($result)) {
		$enddate = $row['Datum'];
	}
	$enddate = DBdateToDate($enddate);
}

if (isset($_POST['cancel'])) {
	header('Location: admin_blokken.php');
}

if (isset($_POST['submit'])) {
	// bestuurslid
	$mpb = $_POST['mpb'];
	if (!$mpb) {
		$fail_msg_mpb = "U dient uw functie te selecteren.";
	}
	// startdatum
	$startdate = $_POST['startdate'];
	if (CheckTheDate($startdate)) {
		$startdate_db = DateToDBdate($startdate);
		if (strtotime($startdate_db) - strtotime($today_db) < 0) {
			$fail_msg_startdate = "De startdatum moet op of na vandaag liggen.";
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
	// tijden
	$start_time_hrs = $_POST['start_time_hrs'];
	$start_time_mins = $_POST['start_time_mins'];
	$start_time = $start_time_hrs . ":" . $start_time_mins;	
	$end_time_hrs = $_POST['end_time_hrs'];
	$end_time_mins = $_POST['end_time_mins'];
	$end_time = $end_time_hrs . ":" . $end_time_mins;
	// datum-/tijdvolgorde
	if (strtotime($enddate_db . ' ' . $end_time) <= strtotime($startdate_db . ' ' . $start_time)) {
		$fail_msg_date = "Het einde van het blok dient na het begin te liggen.";
	}
	// boot
	$boat_id = $_POST['boat_id'];
	$result = mysqli_query($link, sprintf('SELECT Naam FROM boten WHERE ID = %d', $boat_id));
	if ($row = mysqli_fetch_assoc($result)) {
		$boatname = $row['Naam'];
	} else {
		die('Onbekende boot.');
	}
	// naam (omschrijving)
	$pname = $_POST['pname'];
	// als niet gefaald, wedstrijdblok invoeren
	if (isset($fail_msg_startdate) || isset($fail_msg_enddate) || isset($fail_msg_date)) {
		$fail = true;
	} else {
		if ($blok_id) {
			// wijzigen bestaand blok
			mysqli_query($link, sprintf('DELETE FROM %s WHERE Wedstrijdblok = %d', $opzoektabel, $blok_id));
			echo "Bestaande versie van dit wedstrijdblok verwijderd.<br />";
		} else {
			// invoeren nieuw blok
			$result = mysqli_query($link, sprintf('SELECT MAX(Wedstrijdblok) AS MaxId FROM %s', $opzoektabel));
			if ($row = mysqli_fetch_assoc($result)) {
				$blok_id = $row['MaxId'] + 1;
			} else {
				$blok_id = 1;
			}
		}
		$day_tmp = explode("-", $startdate_db);
		$c_start = gregoriantojd($day_tmp[1], $day_tmp[2], $day_tmp[0]);
		$day_tmp = explode("-", $enddate_db);
		$c_end = gregoriantojd($day_tmp[1], $day_tmp[2], $day_tmp[0]);
		for ($c = $c_start; $c <= $c_end; $c++) {
			// Datum
			$day_tmp = jdtogregorian($c);
			$day_tmp2 = explode("/", $day_tmp);
			$date_ins_db = $day_tmp2[2] . "-" . $day_tmp2[0] . "-" . $day_tmp2[1];
			// Tijden
			if ($c == $c_start) {
				$start_time_tmp = $start_time;
			} else {
				$start_time_tmp = '6:00';
			}
			if ($c == $c_end) {
				$end_time_tmp = $end_time;
			} else {
				$end_time_tmp = '23:45';
			}
			// Check inschrijving tegen de database
			$result = mysqli_query($link, 'SELECT * 
					FROM ' . $opzoektabel . ' 
					WHERE ((Begintijd >= "' . $start_time_tmp . '" AND Begintijd < "' . $end_time_tmp . '") 
						OR (Eindtijd > "' . $start_time_tmp . '" AND Eindtijd <= "' . $end_time_tmp . '") 
						OR (Begintijd <= "' . $start_time_tmp . '" AND Eindtijd >= "' . $end_time_tmp . '")) 
					AND Datum = "' . $date_ins_db . '" 
					AND Boot_ID = ' . $boat_id);
			if (!$result) {
				echo 'Het controleren van inschrijving ' . $date_ins . ' is mislukt.<br />';
			} else {
				$rows_aff = mysqli_affected_rows($link);
				if ($rows_aff > 0) {
					// Conflicten -> verwijderen en mailtje sturen
					while ($row = mysqli_fetch_assoc($result)) {
						$date_sh = strftime('%A %d-%m-%Y', strtotime($row['Datum']));
						$message = sprintf('Uw inschrijving op %s vanaf %s komt te vervallen omdat "%s" zojuist geblokt is voor een wedstrijd.', $date_sh, substr($row['Begintijd'], 0, 5), $boatname);
						SendEmail($row['Email'], "Verwijdering inschrijving", $message);
						mysqli_query($link, sprintf('DELETE FROM %s WHERE Volgnummer = %d', $opzoektabel, $row['Volgnummer']));
					}
					echo 'Conflicterende inschrijvingen verwijderd en e-mails verstuurd.<br />';
				}
				$result2 = mysqli_query($link, 'INSERT INTO ' . $opzoektabel . ' (Datum, Inschrijfdatum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, MPB, Spits, Wedstrijdblok, Controle) 
						VALUES ("' . $date_ins_db . '", "' . $today_db . '", "' . $start_time_tmp . '", "' . $end_time_tmp . '", ' . $boat_id . ', "' . $pname . '", "", "' . $mpb . '", 0, ' . $blok_id . ', 0)');
				$date_ins = strftime('%A %d-%m-%Y', strtotime($date_ins_db));
				echo 'Inschrijving ' . $date_ins . ' van ' . $start_time_tmp . ' tot ' . $end_time_tmp;
				if ($result2) {
					echo ' geslaagd.';
				} else {
					echo ' mislukt.';
				}
				echo '<br /><br />';
			}
		} // end for
		echo '<p><a href="admin_blokken.php?boot_te_tonen=' . $boatname . '">Ga terug&gt;&gt;</a></p>';
	}
}

// HET FORMULIER
if ((!isset($_POST['submit']) && !isset($_POST['cancel'])) || $fail) {
	echo "<p>Invoeren/bewerken wedstrijdblok</p>";
	echo '<form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
	echo "<table><tr>";
	
	// bestuurslid
	echo "<td>Uw functie:</td>";
	echo "<td><select name=\"mpb\">";
	$cnt = 0;
	foreach ($mpb_array as $mpb_db) {
		if ($cnt > 0) { // eerste veld is leeg
			echo "<option value=\"$mpb_db\" ";
			if (isset($mpb) && $mpb == $mpb_db) {
				echo "selected=\"selected\"";
			}
			echo ">$mpb_array_sh[$cnt]</option>";
		}
		$cnt++;
	}
	echo "</select></td>";
	echo "</tr>";
	if (isset($fail_msg_mpb)) {
		echo '<td colspan=2><em>' . $fail_msg_mpb . '</em></td>';
	}
	echo "</tr><tr>";
	
	// startdatum
	if (isset($fail_msg_date)) {
		echo '<td colspan=2><em>' . $fail_msg_date . '</em></td></tr><tr>';
	}
	echo "<td>Begindatum (dd-mm-jjjj):</td>";
	echo '<td><input type="text" name="startdate" id="startdate" size="8" maxlength="10" value="' . (isset($startdate) ? $startdate : '') . '">';
	echo '&nbsp;<a href="javascript:show_calendar(\'form.startdate\');" onmouseover="window.status=\'Kalender\';return true;" onmouseout="window.status=\'\';return true;"><img src="../res/kalender.gif" alt="kalender" width="19" height="17" border="0"></a></td>';
	if (isset($fail_msg_startdate)) {
		echo '<td><em>' . $fail_msg_startdate . '</em></td>';
	}
	echo "</tr><tr>";
	
	// starttijd
	echo "<td>Begintijd:</td>";
	echo "<td><select name=\"start_time_hrs\">";
		for ($t=6; $t<24; $t++) {
			echo"<option value=\"".$t."\" ";
			if (isset($start_time_hrs) && $start_time_hrs == $t) {
				echo "selected=\"selected\"";
			}
			echo ">".$t."</option>";
		}
	echo "</select>";
	echo "&nbsp;<select name=\"start_time_mins\">";
		echo "<option value=\"00\" ";
		if (isset($start_time_mins) && $start_time_mins == 0) {
			echo "selected=\"selected\"";
		}
		echo ">00</option>";
		echo "<option value=\"15\" ";
		if (isset($start_time_mins) && $start_time_mins == 15) {
			echo "selected=\"selected\"";
		}
		echo ">15</option>";
		echo "<option value=\"30\" ";
		if (isset($start_time_mins) && $start_time_mins == 30) {
			echo "selected=\"selected\"";
		}
		echo ">30</option>";
		echo "<option value=\"45\" ";
		if (isset($start_time_mins) && $start_time_mins == 45) {
			echo "selected=\"selected\"";
		}
		echo ">45</option>";
	echo "</select></td>";
	if (isset($fail_msg_time)) {
		echo '<td><em>' . $fail_msg_time . '</em></td>';
	}
	echo "</tr><tr>";
	
	// einddatum
	echo "<td>Einddatum (dd-mm-jjjj):</td>";
	echo '<td><input type="text" name="enddate" id="enddate" size="8" maxlength="10" value="' . (isset($enddate) ? $enddate : '') . '">';
	echo "&nbsp;<a href=\"javascript:show_calendar('form.enddate'); return true;\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	if (isset($fail_msg_enddate)) {
		echo '<td><em>' . $fail_msg_enddate . '</em></td>';
	}
	echo "</tr><tr>";
	
	// eindtijd
	echo "<td>Eindtijd:</td>";
	echo "<td><select name=\"end_time_hrs\">";
		for ($t=6; $t<24; $t++) {
			echo"<option value=\"".$t."\" ";
			if (isset($end_time_hrs) && $end_time_hrs == $t) {
				echo "selected=\"selected\"";
			}
			echo ">".$t."</option>";
		}
	echo "</select>";
	echo "&nbsp;<select name=\"end_time_mins\">";
		echo "<option value=\"00\" ";
		if (isset($end_time_mins) && $end_time_mins == 0) {
			echo "selected=\"selected\"";
		}
		echo ">00</option>";
		echo "<option value=\"15\" ";
		if (isset($end_time_mins) && $end_time_mins == 15) {
			echo "selected=\"selected\"";
		}
		echo ">15</option>";
		echo "<option value=\"30\" ";
		if (isset($end_time_mins) && $end_time_mins == 30) {
			echo "selected=\"selected\"";
		}
		echo ">30</option>";
		echo "<option value=\"45\" ";
		if (isset($end_time_mins) && $end_time_mins == 45) {
			echo "selected=\"selected\"";
		}
		echo ">45</option>";
	echo "</select></td>";
	echo "</tr><tr>";
	
	// boot
	echo "<td>Boot/ergometer:</td>";
	echo '<td><select name="boat_id">';
	$query = 'SELECT ID, Naam, Type FROM boten WHERE Datum_eind IS NULL AND Type <> "soc" ORDER BY Naam';
	$boats_result = mysqli_query($link, $query);
	if (!$boats_result) {
		die("Ophalen van vlootinformatie mislukt: " . mysqli_error());
	} else {
		while ($row = mysqli_fetch_assoc($boats_result)) {
			$curr_boat_id = $row['ID'];
			echo '<option value="' . $curr_boat_id . '" ';
			if (isset($boat_id) && $boat_id == $curr_boat_id) {
				echo 'selected="selected"';
			}
			echo '>' . $row['Naam'] . ' (' . $row['Type'] . ')</option>';
		}
	}
	echo "</select></td>";
	echo "</tr><tr>";
	
	// Omschrijving (pname)
	echo "<td>Omschrijving:</td>";
	echo '<td><input type="text" name="pname" value="' . (isset($pname) ? $pname : '') . '" size="30" /></td>';
	if (isset($fail_msg_pname)) {
		echo '<td><em>' . $fail_msg_pname . '</em></td>';
	}
	echo "</tr><tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"submit\" value=\"Invoeren\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}
?>

<?php include 'admin_footer.php';
