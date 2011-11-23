<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes' || $_SESSION['restrict'] != 'gebcie') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Werkstroom gebouwcommissie - Bekijken/bewerken</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="../scripts/kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

// reeds ingevulde waardes ophalen (indien aanwezig)
$id = $_GET['id'];
if ($id) {
	$query = "SELECT * from schades_gebouw WHERE ID='$id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen van schade mislukt.". mysql_error());
	}
	$row = mysql_fetch_assoc($result);
	$name = $row['Naam'];
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

// init
if (!$_POST['cancel'] && !$_POST['insert'] && !$_POST['delete']) {
	$fail = FALSE;
}

// knop gedrukt
if ($_POST['cancel']){
	unset($_POST['name'], $_POST['note'], $_POST['feedback'], $_POST['action'],  $_POST['action_holder'], $_POST['prio'], $_POST['real'], $_POST['date_ready_sh'], $_POST['repair'], $_POST['notes'], $name, $boat_id, $note, $feedback, $action, $action_holder, $prio, $real, $date_ready_sh, $repair, $notes);
	$fail = FALSE;
	echo "<a href='admin_schade_gebouw.php'>Terug naar de werkstroom&gt;&gt;</a></p>";
}

if ($_POST['delete']){
	$query = "DELETE FROM `schades_gebouw` WHERE ID='$id';";
	$result = mysql_query($query);
	if (!$result) {
		die("Verwijderen schade mislukt.". mysql_error());
	} else {
		echo "<p>Schade succesvol definitief verwijderd.<br>";
		echo "<a href='admin_schade_gebouw.php'>Terug naar de werkstroom&gt;&gt;</a></p>";
	}
}

if ($_POST['insert']){
	$name = $_POST['name'];
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
	if ($id) {
		$query = "UPDATE `schades_gebouw` SET Datum_gew='$today_db', Naam='$name', Oms_lang='$note', Feedback='$feedback', Actie='$action', Actiehouder='$action_holder', Prio='$prio', Realisatie='$real', Datum_gereed='$date_ready', Noodrep='$repair', Opmerkingen='$notes' WHERE ID='$id';";
	} else {
		$query = "INSERT INTO `schades_gebouw` (Datum, Datum_gew, Naam, Oms_lang, Feedback, Actie, Actiehouder, Prio, Realisatie, Datum_gereed, Noodrep, Opmerkingen) VALUES ('$today_db', '$today_db', '$name', '$note', '$feedback', '$action', '$action_holder', '$prio', '$real', '$date_ready', '$repair', '$notes');";
	}
	$result = mysql_query($query);
	if (!$result) {
		die("Aanmaken/bewerken schade mislukt.". mysql_error());
	} else {
		echo "<p>Schade succesvol aangemaakt/bewerkt.<br>";
		echo "<a href='admin_schade_gebouw.php'>Terug naar de werkstroom&gt;&gt;</a></p>";
	}
}

// Formulier
if ((!$_POST['insert'] && !$_POST['delete'] && !$_POST['cancel']) || $fail) {
	echo "<p><b>Schademelding aanmaken/bewerken</b></p>";
	echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"$name\" size=45 /></td>";
	echo "</tr>";
	
	// mededeling
	echo "<tr><td>Omschrijving (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"note\" rows=4 cols=50/>$note</textarea></td>";
	echo "</tr>";
	
	// feedback
	echo "<tr><td>Feedback MatCie aan melder (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"feedback\" rows=4 cols=50/>$feedback</textarea></td>";
	echo "</tr>";
	
	// actie
	echo "<tr><td>Actie (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"action\" rows=4 cols=50/>$action</textarea></td>";
	echo "</tr>";
	
	// actiehouder
	echo "<tr><td>Actiehouder:</td>";
	echo "<td><input type=\"text\" name=\"action_holder\" value=\"$action_holder\" size=45 /></td>";
	echo "</tr>";
	
	// prioriteit
	echo "<tr><td>Prioriteit (1-3, 1 is hoogst):</td>";
	echo "<td><select name=\"prio\" />";
	for ($i = 1; $i < 4; $i++) {
		echo "<option value=\"".$i."\" ";
		if ($prio == $i) echo "selected";
		echo "/>".$i;
	}
	echo "</select></td>";
	echo "</tr>";
	
	// realisatie
	echo "<tr><td>% gerealiseerd (0-100):</td>";
	echo "<td><input type=\"text\" name=\"real\" value=\"$real\" size=3 /></td>";
	echo "</tr>";
	
	// datum gereed
	echo "<td>Datum gereed (dd-mm-jjjj):</td>";
	echo "<td><input type='text' name='date_ready_sh' id='date_ready_sh' size='8' maxlength='10' value='$date_ready_sh'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.date_ready_sh');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	echo "</tr><tr>";
	
	// noodreparatie
	echo "<tr><td>Noodreparatie (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"repair\" rows=4 cols=50/>$repair</textarea></td>";
	echo "</tr>";
	
	// opmerkingen
	echo "<tr><td>Opmerkingen (max. 1000 tekens):</td>";
	echo "<td><textarea name=\"notes\" rows=4 cols=50/>$notes</textarea></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Invoeren\" />&nbsp;";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" />&nbsp;";
	echo "<input type=\"submit\" name=\"delete\" value=\"Verwijderen\" /></p>";
	echo "</form>";
	
	echo "<p><em>NB: Verwijderen alleen gebruiken ingeval van bijv. een onzin-melding. Anders de melding na afhandeling via de werkstroom archiveren.</em></p>";
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
