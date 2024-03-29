<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'matcie') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Werkstroom materiaalcommissie - Bekijken/bewerken</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="../scripts/kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php
echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// reeds ingevulde waardes ophalen (indien aanwezig)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
	$query = "SELECT * from schades WHERE ID='$id';";
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Ophalen van schade mislukt: ". mysqli_error());
	}
	$row = mysqli_fetch_assoc($result);
	$name = $row['Naam'];
	// bootnaam
	$boat_id = $row['Boot_ID'];
	if ($boat_id == 0) {
		$boat = "algemeen";
	} else {
		$query2 = "SELECT Naam from boten WHERE ID=$boat_id;";
		$result2 = mysqli_query($link, $query2);
		$row2 = mysqli_fetch_assoc($result2);
		$boat = $row2['Naam'];
	}
	//
	$note = $row['Oms_lang'];
	$feedback = $row['Feedback'];
	$action = $row['Actie'];
	$action_holder = $row['Actiehouder'];
	$prio = $row['Prio'];
	$real = $row['Realisatie'];
	$date_ready = $row['Datum_gereed'];
	$date_ready_sh = DBdateToDate($date_ready);
	$repair = $row['Noodrep'];
	$notes = $row['Opmerkingen'];
}

// Annuleren gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['name'], $_POST['boatid'], $_POST['note'], $_POST['feedback'], $_POST['action'],  $_POST['action_holder'], $_POST['prio'], $_POST['real'], $_POST['date_ready_sh'], $_POST['repair'], $_POST['notes'], $name, $boat_id, $note, $feedback, $action, $action_holder, $prio, $real, $date_ready_sh, $repair, $notes);
	echo "<a href='admin_schade.php'>Terug naar de werkstroom&gt;&gt;</a></p>";
}

if (isset($_POST['delete'])) {
	$query = "DELETE FROM `schades` WHERE ID='$id';";
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Verwijderen schade mislukt: ". mysqli_error());
	} else {
		echo "<p>Schade succesvol definitief verwijderd.<br>";
		echo "<a href='admin_schade.php'>Terug naar de werkstroom&gt;&gt;</a></p>";
	}
}

if (isset($_POST['insert'])) {
	$name = $_POST['name'];
	$boat_id = $_POST['boat_id'];
	$note = addslashes($_POST['note']);
	$feedback = addslashes($_POST['feedback']);
	$action = addslashes($_POST['action']);
	$action_holder = $_POST['action_holder'];
	$prio = $_POST['prio'];
	$real = $_POST['real'];
	$date_ready_sh = $_POST['date_ready_sh'];
	$date_ready = DateToDBdate($date_ready_sh);
	if ($real == 100 && $date_ready == "0000-00-00") $date_ready = $today_db;
	$repair = addslashes($_POST['repair']);
	$notes = addslashes($_POST['notes']);
	if (isset($id)) {
		$query = "UPDATE `schades` SET Datum_gew='$today_db', Naam='$name', Boot_ID='$boat_id', Oms_lang='$note', Feedback='$feedback', Actie='$action', Actiehouder='$action_holder', Prio='$prio', Realisatie='$real', Datum_gereed='$date_ready', Noodrep='$repair', Opmerkingen='$notes' WHERE ID='$id';";
	} else {
		$query = "INSERT INTO `schades` (Datum, Datum_gew, Naam, Boot_ID, Oms_lang, Feedback, Actie, Actiehouder, Prio, Realisatie, Datum_gereed, Noodrep, Opmerkingen) VALUES ('$today_db', '$today_db', '$name', '$boat_id', '$note', '$feedback', '$action', '$action_holder', '$prio', '$real', '$date_ready', '$repair', '$notes');";
	}
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Aanmaken/bewerken schade mislukt: ". mysqli_error());
	} else {
		echo "<p>Schade succesvol aangemaakt/bewerkt.<br>";
		echo "<a href='admin_schade.php'>Terug naar de werkstroom&gt;&gt;</a></p>";
	}
}

// Formulier
if (!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) {
	echo "<p><b>Schademelding aanmaken/bewerken</b></p>";
	echo "<form name='form' action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"" . (isset($name) ? $name : '') . "\" size=45 /></td>";
	echo "</tr>";
	
	// boot
	echo "<tr><td>Boot/ergometer:</td>";
	echo "<td><select name=\"boat_id\">";
	echo "<option value=0 ";
	if (!isset($boat_id) || $boat_id == 0) echo "selected=\"selected\"";
	echo ">algemeen</option>";
	$query = "SELECT ID, Naam FROM boten WHERE Datum_eind IS NULL AND Type<>\"soc\" ORDER BY Naam;";
	$boats_result = mysqli_query($link, $query);
	if (!$boats_result) {
		die("Ophalen van vlootinformatie mislukt: ". mysqli_error());
	} else {
		while ($row = mysqli_fetch_assoc($boats_result)) {
			$curr_boat_id = $row['ID'];
			$curr_boat = $row['Naam'];
			echo "<option value=" . $curr_boat_id . " ";
			if (isset($boat_id) && $boat_id == $curr_boat_id) echo "selected=\"selected\"";
			echo ">" . $curr_boat . "</option>";
		}
	}
	echo "</select></td>";
	echo "</tr>";
	
	// mededeling
	echo "<tr><td>Omschrijving (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"note\" rows=4 cols=50/>" . (isset($note) ? $note : '') . "</textarea></td>";
	echo "</tr>";
	
	// feedback
	echo "<tr><td>Feedback MatCie aan melder (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"feedback\" rows=4 cols=50/>" . (isset($feedback) ? $feedback : '') . "</textarea></td>";
	echo "</tr>";
	
	// actie
	echo "<tr><td>Actie (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"action\" rows=4 cols=50/>" . (isset($action) ? $action : '') . "</textarea></td>";
	echo "</tr>";
	
	// actiehouder
	echo "<tr><td>Actiehouder:</td>";
	echo "<td><input type=\"text\" name=\"action_holder\" value=\"" . (isset($action_holder) ? $action_holder : '') . "\" size=45 /></td>";
	echo "</tr>";
	
	// prioriteit
	echo "<tr><td>Prioriteit (1-3, 1 is hoogst):</td>";
	echo "<td><select name=\"prio\" />";
	for ($i = 1; $i < 4; $i++) {
		echo "<option value=\"" . $i . "\" ";
		if (isset($prio) && $prio == $i) echo "selected";
		echo "/>" . $i;
	}
	echo "</select></td>";
	echo "</tr>";
	
	// realisatie
	echo "<tr><td>% gerealiseerd (0-100):</td>";
	echo "<td><input type=\"text\" name=\"real\" value=\"" . (isset($real) ? $real : '') . "\" size=3 /></td>";
	echo "</tr>";
	
	// datum gereed
	echo "<td>Datum gereed (dd-mm-jjjj):</td>";
	echo "<td><input type='text' name='date_ready_sh' id='date_ready_sh' size='8' maxlength='10' value='" . (isset($date_ready_sh) ? $date_ready_sh : '') . "'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.date_ready_sh');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	echo "</tr><tr>";
	
	// noodreparatie
	echo "<tr><td>Noodreparatie (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"repair\" rows=4 cols=50/>" . (isset($repair) ? $repair : '') . "</textarea></td>";
	echo "</tr>";
	
	// opmerkingen
	echo "<tr><td>Opmerkingen (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"notes\" rows=4 cols=50/>" . (isset($notes) ? $notes : '') . "</textarea></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Invoeren\" />&nbsp;";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" />&nbsp;";
	echo "<input type=\"submit\" name=\"delete\" value=\"Verwijderen\" /></p>";
	echo "</form>";
	
	echo "<p><em>NB: Verwijderen alleen gebruiken ingeval van bijv. een onzin-melding. Anders de melding na afhandeling via de werkstroom archiveren.</em></p>";
}

mysqli_close($link);
?>

</div>
</body>
</html>

<script type="javascript">
    function changeInfo(){
        return true;
    }
</script>
