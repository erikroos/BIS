<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
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

setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><? echo $systeemnaam; ?> - Admin - Spitsrooster</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

$fail = FALSE;

$ploeg_te_tonen = "alle";
if ($_POST['ploeg_te_tonen']) {
	$ploeg_te_tonen = $_POST['ploeg_te_tonen'];
} else {
	if ($_GET['ploeg_te_tonen']) {
		$ploeg_te_tonen = $_GET['ploeg_te_tonen'];
	}
}

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

echo "<p>Actieve repeterende spitsblokken</p>";
echo "<p><a href=\"./admin_spits_toev.php\">Toevoegen&gt;&gt;</a></p>";

echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
echo "Beperk tot ploeg: <select name=\"ploeg_te_tonen\">";
echo "<option value=\"alle\"";
if ($ploeg_te_tonen == "alle") echo "selected=\"selected\"";
echo ">alle</option>";
echo "<option value=\"geen ploegnaam\"";
if ($ploeg_te_tonen == "geen ploegnaam") echo "selected=\"selected\"";
echo ">geen ploegnaam</option>";
$query = "SELECT DISTINCT Ploegnaam from ".$opzoektabel." WHERE Verwijderd=0 AND Spits>0 ORDER BY Ploegnaam;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van informatie mislukt.". mysql_error());
} else {
	while ($row = mysql_fetch_assoc($result)) {
		$ploegnaam = $row['Ploegnaam'];
		if ($ploegnaam != "") {
			echo"<option value=\"".$ploegnaam."\" ";
			if ($ploeg_te_tonen == $ploegnaam) echo "selected=\"selected\"";
			echo ">".$ploegnaam."</option>";
		}
	}
}
echo "</select>";
echo "<br /><br /><input type=\"submit\" name=\"submit_ploegnaam\" value=\"Toon spitsblokken\" />";
echo "</form><br /><br />";

// tabel
echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"bordercolor:#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">MPB</div></th>";
echo "<th><div style=\"text-align:left\">Startdatum</div></th>";
echo "<th><div style=\"text-align:left\">Einddatum</div></th>";
echo "<th><div style=\"text-align:left\">Starttijd</div></th>";
echo "<th><div style=\"text-align:left\">Eindtijd</div></th>";
echo "<th><div style=\"text-align:left\">Boot</div></th>";
echo "<th><div style=\"text-align:left\">Naam</div></th>";
echo "<th><div style=\"text-align:left\">Ploegnaam</div></th>";
echo "<th><div style=\"text-align:left\">E-mail</div></th>";
echo "<th colspan=\"2\"><div style=\"text-align:left\">Aanpassen</div></th></tr>";

$restrict_query = "";
if ($ploeg_te_tonen != "alle") {
	if ($ploeg_te_tonen == "geen ploegnaam") {
		$restrict_query = "AND Ploegnaam=\"\" ";
	} else {
		$restrict_query = "AND Ploegnaam=\"$ploeg_te_tonen\" ";
	}
}
$query = "SELECT DISTINCT Spits from ".$opzoektabel." WHERE Verwijderd=0 AND Spits>0 ".$restrict_query."ORDER BY Spits;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van informatie mislukt.". mysql_error());
} else {
	while ($row = mysql_fetch_assoc($result)) {
		$spits_id = $row['Spits'];
		$query2 = "SELECT MPB, Datum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, Email from ".$opzoektabel." WHERE Verwijderd=0 AND Spits=$spits_id ORDER BY Datum;";
		$result2 = mysql_query($query2);
		if (!$result2) {
			die("Ophalen van informatie mislukt.". mysql_error());
		} else {
		    // uit eerste record kun je alles al halen, behalve -bij meer dan 1 inschrijving- de einddatum
			$row2 = mysql_fetch_assoc($result2);
			$mpb = $row2['MPB'];
			$startdate = $row2['Datum'];
			$date_tmp = strtotime($startdate);
			$startdate_sh = strftime('%A %d-%m-%Y', $date_tmp);
			//$startdate_sh = DBdateToDate($startdate);
			$starttime = $row2['Begintijd'];
			$endtime = $row2['Eindtijd'];
			$boat_id = $row2['Boot_ID'];
			// bootnaam
			$query_boatname = "SELECT Naam from boten WHERE ID=$boat_id;";
			$result_boatname = mysql_query($query_boatname);
			$row_boatname = mysql_fetch_assoc($result_boatname);
			$boat = $row_boatname['Naam'];
			//
			$pname = $row2['Pnaam'];
			$name = $row2['Ploegnaam'];
			$email = $row2['Email'];
			$enddate = $row2['Datum'];
			while ($row2 = mysql_fetch_assoc($result2)) {
				$enddate = $row2['Datum'];
			}
			$date_tmp = strtotime($enddate);
			$enddate_sh = strftime('%A %d-%m-%Y', $date_tmp);
			//$enddate_sh = DBdateToDate($enddate);
			echo "<tr>";
			echo "<td><div style=\"text-align:left\">$mpb</div></td>";
			echo "<td><div style=\"text-align:left\">$startdate_sh</div></td>";	
			echo "<td><div style=\"text-align:left\">$enddate_sh</div></td>";
			echo "<td><div style=\"text-align:left\">$starttime</div></td>";
			echo "<td><div style=\"text-align:left\">$endtime</div></td>";
			echo "<td><div style=\"text-align:left\">$boat</div></td>";
			echo "<td><div style=\"text-align:left\">$pname</div></td>";
			echo "<td><div style=\"text-align:left\">$name</div></td>";
			echo "<td><div style=\"text-align:left\">$email</div></td>";
			echo "<td><div><a href=\"./admin_spits_toev.php?id=$spits_id\">Wijzigen</a></div></td>";
			echo "<td><div><a href=\"./admin_spits_verw.php?id=$spits_id\">Verwijderen</a></div></td>";
			echo "</tr>";
		}
	}
}

echo "</table>";

mysql_close($link);

?>

</div>
</body>
</html>