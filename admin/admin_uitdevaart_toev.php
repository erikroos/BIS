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
    <title><?php echo $systeemnaam; ?> - Admin - Vlootbeheer - Uit de Vaart toevoegen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
	<script language="JavaScript" src="../scripts/kalender.js"></script>
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php
$fail = false;

$boot_id = $_GET['id'];
$query = "SELECT Naam FROM boten WHERE ID=$boot_id;";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
$name = $row['Naam'];

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href=\"./admin_inuitdevaart.php?id=$boot_id\">Terug naar in/uit de vaart van deze boot</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";

$reason = "Uit de vaart";

if (isset($_POST['cancel'])) {
	echo "<p>Er zal niets worden aangemaakt.</p>";
	exit();
}

if (isset($_POST['submit'])) {
	// startdatum
	$startdate = $_POST['startdate'];
	if (CheckTheDate($startdate)) {
		$startdate_db = DateToDBdate($startdate);
	} else {
		$fail_msg_startdate = "U dient een geldige startdatum op te geven.";
	}
	
	// einddatum
	$enddate = $_POST['enddate'];
	if (!$enddate) {
		$enddate_db = '';
	} else {
		if (CheckTheDate($enddate)) {
			$enddate_db = DateToDBdate($enddate);
		} else {
			$fail_msg_enddate = "U dient of dit veld leeg te laten of een geldige einddatum op te geven.";
		}
	}
	
	// datumvolgorde
	if ($enddate_db != '') {
		if (strtotime($enddate_db) < strtotime($startdate_db)) {
			$fail_msg_date = "De einddatum dient na de begindatum te liggen.";
		}
	}
	
	// geen check op reden
	$reason = $_POST['reason'];
	
	// als niet gefaald, Uit de Vaart invoeren
	if (isset($fail_msg_startdate) || isset($fail_msg_enddate) || isset($fail_msg_date)) {
		$fail = true;
	} else {
		if ($enddate_db != '') {
			$query = "INSERT INTO uitdevaart (Boot_ID, Startdatum, Einddatum, Reden, Verwijderd) VALUES ('" . $boot_id . "', '" . $startdate_db . "', '" . $enddate_db . "', '" . $reason . "', 0);"; 
		} else {
			$query = "INSERT INTO uitdevaart (Boot_ID, Startdatum, Reden, Verwijderd) VALUES ('" . $boot_id . "', '" . $startdate_db . "', '" . $reason . "', 0);"; 
		}
		$result = mysqli_query($link, $query);
		if (!$result) {
			die("Invoeren mislukt.". mysqli_error());
		} else {
			echo "Uit de Vaart succesvol ingevoerd.";
			// mensen mailen die deze boot hadden ingeschreven
			$datepart_query = "";
			if ($enddate_db != '') {
				$datepart_query = "AND Datum <= '$enddate_db' ";
			}
			$query2 = "SELECT Email, Datum, Begintijd FROM ".$opzoektabel." WHERE Boot_ID = '$boot_id' AND Datum >= '$startdate_db' ".$datepart_query." AND Spits=0 AND Verwijderd=0;";
			$result2 = mysqli_query($link, $query2);
			if ($result2) {
				while ($row = mysqli_fetch_assoc($result2)) {
					$email_to = $row['Email'];
					$db_datum = $row['Datum'];
					$date_tmp = strtotime($db_datum);
					$date_sh = strftime('%A %d-%m-%Y', $date_tmp);
					$starttijd = $row['Begintijd'];
					$message = "Uw inschrijving van $date_sh vanaf ".substr($starttijd, 0, 5)." komt te vervallen omdat '$name' zojuist uit de vaart gemeld is.";
					SendEmail($email_to, "Wijziging inschrijving", $message);
				}
				echo "<br>Degenen die hadden ingeschreven, zijn via e-mail op de hoogte gesteld.";
			}
		}
	}
}

// HET FORMULIER
if ((!isset($_POST['submit']) && !isset($_POST['cancel'])) || $fail) {
	
	echo '<form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
	echo "<table><tr>";
	
	// startdatum
	if (isset($fail_msg_date)) echo "<td colspan=2><em>$fail_msg_date</em></td></tr><tr>";
	echo "<td>Startdatum (dd-mm-jjjj):</td>";
	echo "<td><input type='text' name='startdate' id='startdate' size='8' maxlength='10' value='" . (isset($startdate) ? $startdate : '') . "'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.startdate');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	if (isset($fail_msg_startdate)) echo "<td><em>$fail_msg_startdate</em></td>";
	echo "</tr><tr>";
	
	// evt. einddatum
	echo "<td>Einddatum (dd-mm-jjjj), of leeg:</td>";
	echo "<td><input type='text' name='enddate' id='enddate' size='8' maxlength='10' value='" . (isset($enddate) ? $enddate : '') . "'>";
	echo "&nbsp;<a href=\"javascript:show_calendar('form.enddate');\" onmouseover=\"window.status='Kalender';return true;\" onmouseout=\"window.status='';return true;\"><img src='../res/kalender.gif' alt='kalender' width='19' height='17' border='0'></a></td>";
	if (isset($fail_msg_enddate)) echo "<td><em>$fail_msg_enddate</em></td>";
	echo "</tr><tr>";
	
	// reden
	echo "<td>Reden:</td>";
	echo "<td><input type=\"text\" name=\"reason\" value=\"$reason\" size=30 /></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"submit\" value=\"Invoeren\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
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
