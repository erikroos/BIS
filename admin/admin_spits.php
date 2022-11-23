<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
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
    <title><?php echo $systeemnaam; ?> - Admin - Spitsrooster</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
    <script type="text/javascript" src="../scripts/sortable.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php
$fail = false;
$ploeg_te_tonen = "alle";
if (isset($_POST['ploeg_te_tonen'])) {
	$ploeg_te_tonen = $_POST['ploeg_te_tonen'];
} else {
	if (isset($_GET['ploeg_te_tonen'])) {
		$ploeg_te_tonen = $_GET['ploeg_te_tonen'];
	}
}

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

echo "<p>Actieve repeterende spitsblokken</p>";
echo "<p><a href=\"./admin_spits_toev.php\">Toevoegen&gt;&gt;</a></p>";

echo '<form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
echo "Beperk tot ploeg: <select name=\"ploeg_te_tonen\">";
echo "<option value=\"alle\"";
if ($ploeg_te_tonen == "alle") echo "selected=\"selected\"";
echo ">alle</option>";
echo "<option value=\"geen ploegnaam\"";
if ($ploeg_te_tonen == "geen ploegnaam") echo "selected=\"selected\"";
echo ">geen ploegnaam</option>";
$query = "SELECT DISTINCT Ploegnaam from ".$opzoektabel." WHERE Verwijderd=0 AND Spits>0 ORDER BY Ploegnaam;";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van informatie mislukt.". mysqli_error());
} else {
	while ($row = mysqli_fetch_assoc($result)) {
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
echo "<table class=\"sortable\" id=\"spits\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"bordercolor:#AAB8D5\">";
echo "<tr><td>MPB</td>";
echo "<td>Startdatum</td>";
echo "<td>Einddatum</td>";
echo "<td>Starttijd</td>";
echo "<td>Eindtijd</td>";
echo "<td>Boot</td>";
echo "<td>Naam</td>";
echo "<td>Ploegnaam</td>";
echo "<td>E-mail</td>";
echo "<td colspan=\"2\"></td></tr>";

$restrict_query = "";
if ($ploeg_te_tonen != "alle") {
	if ($ploeg_te_tonen == "geen ploegnaam") {
		$restrict_query = "AND Ploegnaam=\"\" ";
	} else {
		$restrict_query = "AND Ploegnaam=\"$ploeg_te_tonen\" ";
	}
}
$query = sprintf('SELECT DISTINCT Spits 
		FROM %s 
		WHERE Verwijderd=0 
		AND Spits>0 
		%s 
		ORDER BY Spits', $opzoektabel, $restrict_query);
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van informatie mislukt: " . mysqli_error());
} else {
	while ($row = mysqli_fetch_assoc($result)) {
		$spits_id = $row['Spits'];
		$query2 = "SELECT MPB, Datum, Begintijd, Eindtijd, Boot_ID, Pnaam, Ploegnaam, Email from ".$opzoektabel." WHERE Verwijderd=0 AND Spits=$spits_id ORDER BY Datum;";
		$result2 = mysqli_query($link, $query2);
		if (!$result2) {
			die("Ophalen van informatie mislukt: " . mysqli_error());
		} else {
		    // uit eerste record kun je alles al halen, behalve -bij meer dan 1 inschrijving- de einddatum
			$row2 = mysqli_fetch_assoc($result2);
			$mpb = $row2['MPB'];
			$startdate = $row2['Datum'];
			$startdate_sh = strftime('%A %d-%m-%Y', strtotime($startdate));
			//$startdate_sh = DBdateToDate($startdate);
			$starttime = $row2['Begintijd'];
			$endtime = $row2['Eindtijd'];
			$boat_id = $row2['Boot_ID'];
			// bootnaam
			$query_boatname = "SELECT Naam from boten WHERE ID=$boat_id;";
			$result_boatname = mysqli_query($link, $query_boatname);
			$row_boatname = mysqli_fetch_assoc($result_boatname);
			$boat = $row_boatname['Naam'];
			//
			$pname = $row2['Pnaam'];
			$name = $row2['Ploegnaam'];
			$email = $row2['Email'];
			$enddate = $row2['Datum'];
			while ($row2 = mysqli_fetch_assoc($result2)) {
				$enddate = $row2['Datum'];
			}
			$enddate_sh = strftime('%A %d-%m-%Y', strtotime($enddate));
			echo "<tr>";
			echo "<td>$mpb</td>";
			echo "<td>$startdate_sh</td>";	
			echo "<td>$enddate_sh</td>";
			echo "<td>$starttime</td>";
			echo "<td>$endtime</td>";
			echo "<td>$boat</td>";
			echo "<td>$pname</td>";
			echo "<td>$name</td>";
			echo "<td>$email</td>";
			echo "<td><a href=\"./admin_spits_toev.php?id=$spits_id\">Wijzigen</a></td>";
			echo "<td><a href=\"./admin_spits_verw.php?id=$spits_id\">Verwijderen</a></td>";
			echo "</tr>";
		}
	}
}

echo "</table>";

mysqli_close($link);
?>

</div>
</body>
</html>
