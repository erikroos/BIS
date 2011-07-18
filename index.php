<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("include.php");
if ($toonweer) include_once("../xmlnews.php");
setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?></title>
    <link type="text/css" href="<? echo $csslink; ?>" rel="stylesheet" />
	<link type="text/css" href="bis.css" rel="stylesheet" />
	<script type="text/javascript" src="./kalender.js"></script>
	<script type="text/javascript" src="./Script.js"></script>
</head>
<body>
<script type="text/javascript" src="./wz_tooltip.js"></script>
<?php

$date_to_show = 0;
if ($_GET['date_to_show']) {
	$date_to_show = $_GET['date_to_show'];
}
if ($date_to_show == 0 || !CheckTheDate($date_to_show)) { // altijd sanity check
	$date_to_show = $today;
}
$date_to_show_db = DateToDBdate($date_to_show);

$start_hrs_to_show = -1;
$start_mins_to_show = -1;
if ($_GET['start_time_to_show']) {
	$start_time_to_show = $_GET['start_time_to_show'];
	$start_time_fields = explode(":", $start_time_to_show);
	$start_hrs_to_show = $start_time_fields[0];
	$start_mins_to_show = $start_time_fields[1];
}
if ($start_hrs_to_show == -1 || $start_mins_to_show == -1 ||
    !($start_hrs_to_show >= 6 && $start_hrs_to_show <= 23) ||
	!(start_mins_to_show == 0 || start_mins_to_show == 15 || start_mins_to_show == 30 || start_mins_to_show == 45)
) { // sanity check
	if ($date_to_show == $today) {
		if ($thehour_q < 6) {
			$start_hrs_to_show = 6;
			$start_mins_to_show = 0;
		} else {
			$start_hrs_to_show = $thehour_q;
			$start_mins_to_show = $theminute_quarts;
		}
	} else {
		$start_hrs_to_show = 9;
		$start_mins_to_show = 0;
	}
}
if ($start_mins_to_show == 0) $start_mins_to_show = "00";
$start_time_to_show = $start_hrs_to_show.":".$start_mins_to_show;
$start_block = TimeToBlocks($start_time_to_show);

$cat_to_show = $standaardcategorie;
if ($_GET['cat_to_show']) {
	$cat_to_show = $_GET['cat_to_show'];
}
if (!in_array($cat_to_show, $cat_array)) { // sanity check
	$cat_to_show = $standaardcategorie;
}

$grade_to_show = $standaardgraad;
if ($_GET['grade_to_show']) {
	$grade_to_show = $_GET['grade_to_show'];
}
if (!in_array($grade_to_show, $grade_array)) { // sanity check
	$grade_to_show = $standaardgraad;
}

$fail = FALSE;

echo "<div style=\"padding-left:15px; padding-right:15px; padding-top: 5px; padding-bottom: 5px; background-color:#FFFF99\">";
$date_tmp = strtotime($today_db);
$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
echo "<strong>Welkom</strong>, ".$_SESSION['login']."<br />";
echo "<strong>Het is vandaag: </strong><span style=\"font-style:italic\">$date_sh</span>";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"CurrAct\" value=\"Nu op water\" onclick=\"window.location.href='current_act.php'\" />";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"Schadeboek\" value=\"Schades\" onclick=\"window.location.href='schadeboek/index.php'\" />";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"Cursussen\" value=\"Cursussen\" onclick=\"window.location.href='cursussen/index.php'\" />";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"Examens\" value=\"Examens\" onclick=\"window.location.href='examens/index.php'\" />";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"Documenten\" value=\"Documenten\" onclick=\"window.location.href='documenten/index.php'\" />";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"Hulp\" value=\"Hulp\" onclick=\"window.location.href='bis_info.php'\" />";
echo "&nbsp;&copy;2008-".$theyear." Erik Roos&nbsp;<a href='mailto:".$mailadres."'>".$mailadres."</a>";
echo "&nbsp;<input style=\"font-size:10px\" type=\"button\" name=\"Uitloggen\" value=\"UIT\" onclick=\"window.location.href='bis_logout.php'\" />";
echo "</div>";

echo "<div style=\"padding-left:10px; padding-right:10px; background-color:#FFFF99; height:190px\">";
echo "<div id='infobalkLinks'>";
// Formulier voor selectie op vloot
echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
echo "<strong>Pas het inschrijfblad aan:</strong><br />";
echo "<table><tr>";
echo "<td>Datum (dd-mm-jjjj):</td>";
echo "<td><input type='text' name='date_to_show' size='8' maxlength='10' value='$date_to_show' onchange='ChangeInfo();' id='date_to_show' />";
echo "&nbsp;<a href=\"javascript:show_calendar('form.date_to_show');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='kalender.gif' width='19' height='17' border='0' alt='Kalender' /></a><br />";
echo "<input style=\"font-size:9px\" type=\"button\" name=\"change_date\" value=\"&lt;\" onclick=\"ChangeDate(-1); ChangeInfo();\" />";
echo "<input style=\"font-size:9px\" type=\"button\" name=\"change_date\" value=\"&gt;\" onclick=\"ChangeDate(1); ChangeInfo();\" />";
echo "<input style=\"font-size:9px\" type=\"button\" name=\"reset_date\" value=\"vandaag\" onclick=\"ResetDate(); ChangeInfo();\" />";
echo "</td></tr>";
echo "<tr>";
echo "<td>Vanaf tijdstip:</td>";
echo "<td><select name='start_hrs_to_show' onchange='ChangeInfo();' id='start_hrs_to_show'>";
for ($t = 6; $t < 24; $t++) {
	echo"<option value=\"".$t."\" ";
	if ($start_hrs_to_show == $t) echo "selected=\"selected\"";
	echo ">".$t."</option>";
}
echo "</select>";
echo "&nbsp;<select name='start_mins_to_show' onchange='ChangeInfo();' id='start_mins_to_show'>";
echo "<option value=\"00\" ";
if ($start_mins_to_show == 0) echo "selected=\"selected\"";
echo ">00</option>";
echo "<option value=\"15\" ";
if ($start_mins_to_show == 15) echo "selected=\"selected\"";
echo ">15</option>";
echo "<option value=\"30\" ";
if ($start_mins_to_show == 30) echo "selected=\"selected\"";
echo ">30</option>";
echo "<option value=\"45\" ";
if ($start_mins_to_show == 45) echo "selected=\"selected\"";
echo ">45</option>";
echo "</select></td>";
echo "</tr>";

echo "<tr>";
echo "<td>Categorie:</td>";
echo "<td><select name='cat_to_show' onchange='ChangeInfo();' id='cat_to_show'>";
	foreach($cat_array as $cat_db) {
		echo "<option value=\"$cat_db\" ";
		if ($cat_to_show == $cat_db) echo "selected=\"selected\"";
		echo ">$cat_db</option>";
	}
echo "</select></td>";
echo "</tr>";

echo "<tr>";
echo "<td>Roeigraad:</td>";
echo "<td><select name='grade_to_show' onchange='ChangeInfo();' id='grade_to_show'>";
	echo "<option value=\"alle\" ";
	if ($grade_to_show == "alle") echo "selected=\"selected\"";
	echo ">alle</option>";
	foreach($grade_array as $grade_db) {
		echo "<option value=\"$grade_db\" ";
		if ($grade_to_show == $grade_db) echo "selected=\"selected\"";
		echo ">$grade_db</option>";
	}
echo "</select></td>";
echo "</tr>";

echo "</table>";
echo "</form></div>"; // einde selectie-form

// bestuursinfo
echo "<div id='infobalkMidden'>";
echo "<strong>Bestuursmededelingen</strong><br />";
$query = "SELECT * FROM mededelingen ORDER BY Datum DESC LIMIT 1;"; // alleen recentste
$result = mysql_query($query);
if (!$result) {
	echo "Ophalen van bestuursmededelingen mislukt.".mysql_error();
} else {
	$rows_aff = mysql_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysql_fetch_assoc($result);
		$note_datum = DBdateToDate($row['Datum']);
		$bestuurslid = $row['Bestuurslid'];
		$summary = $row['Betreft'];
		$note = $row['Mededeling'];
		echo "Datum: $note_datum<br />Van: $bestuurslid<br />Betreft: $summary<br /><br />$note<br /><br /><a href=\"$mededelingenpagina\" target='_blank'>Alle mededelingen";
		$query2 = "SELECT COUNT(*) AS NrOfNotes FROM mededelingen;"; // alleen recentste
		$result2 = mysql_query($query2);
		$row2 = mysql_fetch_assoc($result2);
		$nr_notes = $row2['NrOfNotes'];
		if ($nr_notes) echo " (".$nr_notes.") ";
		echo "&gt;&gt;</a>";
	} else {
		echo "Op dit moment zijn er geen mededelingen.<br /><br />";
	}
}
echo "</div>";

if ($toonweer) { // optionele weerinfo
	echo "<div id='infobalkRechts'>";
	echo "<strong>Weer</strong><br />"; 
	echo xmlnews('http://www.gyas.nl/media/output/weer.rss',3,'_blank','br', 0);
	echo "</div>";
}

echo "</div>";

echo "<div id='ScheduleInfo' style='clear:left'>";
require_once("./show_schedule.php");
echo "</div>";

?>

<script type="text/javascript" src="./dates_and_ajax.js"></script>
</body>
</html>
