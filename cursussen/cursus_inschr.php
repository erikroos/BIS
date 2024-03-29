<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Cursussen - Inschrijven voor een cursus</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM cursussen WHERE ID='$id';";
    $result = mysqli_query($link, $query);
    if (!$result) {
        die("Ophalen van cursusgegevens mislukt." . mysqli_error());
    } else {
        $rows_aff = mysqli_affected_rows($link);
        if ($rows_aff > 0) {
            $row = mysqli_fetch_assoc($result);
            $startdate = $row['Startdatum'];
            $startdate_sh = strtotime($startdate);
            $type = $row['Type'];
            $description = $row['Omschrijving'];
            $org_email = $row['Mailadres'];
        } else {
            die("Ophalen van cursusgegevens mislukt.");
        }
    }
}

$skiff2 = false;
if (isset($type) && (preg_match("/skiff-2/", $type) || preg_match("/Skiff-2/", $type) || preg_match("/skiff 2/", $type) || preg_match("/Skiff 2/", $type))) {
    $skiff2 = true;
}

$fail = false;

// Annuleren gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['name'], $_POST['demand'], $_POST['email'], $_POST['telph'], $name, $demand, $email, $telph);
	echo "<p>U wordt niet aangemeld.<br>";
	echo "<a href='index.php'>Terug naar het cursusscherm&gt;&gt;</a></p>";
}

if (isset($_POST['insert'])) {
	$name = $_POST['name'];
	$demand = $_POST['demand'];
	$email = $_POST['email'];
	$telph = $_POST['telph'];
	
	if (!CheckName($name)) {
		$fail_msg_name = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	if ($skiff2 && empty($demand)) {
		$fail_msg_demand = "Bij skiff-2 dient u op te geven hoe u aan de instructie-eis voldaan heeft.";
	}
	if (!$telph || !$email) {
		$fail_msg_contact = "U dient zowel een telefoonnummer als een e-mailadres op te geven.";
	} else {
		if (!check_phone_dutch($telph)) {
			$fail_msg_telph = "U dient een geldig 10-cijferig telefoonnummer, met streepje, in te voeren.";
		}
		if (!CheckEmail($email)) {
			$fail_msg_email = "U dient een geldig e-mailadres in te voeren.";
		}
	}
	
	if (isset($fail_msg_name) || isset($fail_msg_demand) || isset($fail_msg_contact) || isset($fail_msg_telph) || isset($fail_msg_email)) {
        $fail = true;
    }
	
	if (!$fail) {
		$query = "INSERT INTO `cursus_inschrijvingen` (Naam, Demand, Ex_ID, Email, TelNr) VALUES ('$name', '$demand', '$id', '$email', '$telph');";
		$result = mysqli_query($link, $query);
		if (!$result) {
			die("Inschrijven voor cursus mislukt: " . mysqli_error());
		} else {
			$intro = "Beste cursist,<br /><br />Bedankt voor uw aanmelding. Wij hebben onderstaande gegevens ontvangen en nemen z.s.m. per email contact met u op. U ontvangt dan nadere informatie omtrent de cursus.<br /><br />Met vriendelijke groet,<br />De Instructiecommissie<br /><br />KGR De Hunze<br />Praediniussingel 32<br />9711 AG Groningen<br /><br />www.hunze.nl<br /><br />";
			$message = "Naam: ".$name."<br>";
			$query2 = "SELECT Startdatum, Type FROM `cursussen` WHERE ID='$id';";
			$result2 = mysqli_query($link, $query2);
			$row2 = mysqli_fetch_assoc($result2);
			$startdate_db = $row2['Startdatum'];
			$type = $row2['Type'];
			$message .= "Cursus: ".$type."<br />";
			$message .= "Beginnend op: " . DBdateToDate($startdate_db)."<br />";
			if ($demand) $message .= "Tegenprestatie: ".$demand."<br />";
			if ($telph) $message .= "Telefoonnummer: ".$telph."<br />";
			if ($email) $message .= "E-mailadres: ".$email."<br />";
			// Verstuur naar cursist zelf
			if ($email) {
                SendEmail($email, "Bevestiging cursusaanmelding", $intro.$message);
            }
			// Verstuur naar organisatie
			if ($org_email != "instructie@hunze.nl") {
                SendEmail($org_email, "Nieuwe cursusaanmelding", $message);
            }
			SendEmail("instructie@hunze.nl", "Nieuwe cursusaanmelding", $message);
			echo "<p>Hartelijk dank voor uw aanmelding! Deze is doorgegeven aan het betreffende lid van de Instructiecommissie.<br />Als u zelf een e-mailadres had opgegeven, krijgt u een kopie van uw inschrijving via e-mail.<br />";
			echo "<a href='index.php'>Terug naar het cursusscherm&gt;&gt;</a></p>";
		}
	}
}

// Formulier
if ((!isset($_POST['insert']) && !isset($_POST['cancel'])) || $fail) {
	echo "<p><b>Aanmeldformulier voor ".$type." beginnend op ".strftime('%A %d-%m-%Y', $startdate_sh)."&nbsp;".$description;
	echo "<form name='form' action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"" . (isset($name) ? $name : '') . "\" size=45 /></td>";
	if (isset($fail_msg_name)) echo "<td><em>$fail_msg_name</em></td>";
	echo "</tr>";
	
	// tegenprestatie (alleen bij skiff-2)
	if ($skiff2) {
		echo "<tr><td colspan=3><em>Om deel te kunnen nemen aan de cursus skiff-2, dient u instructie gegeven te hebben.<br />Omschrijf a.u.b. kort welke instructie u hebt gegeven, wanneer en bij wie.</em></td></tr>";
		echo "<tr><td>Instructie-eis:</td>";
		echo "<td><input type=\"text\" name=\"demand\" value=\"" . (isset($demand) ? $demand : '') . "\" size=\"100\" maxlength=\"100\" /></td>";
		if (isset($fail_msg_demand)) echo "<td><em>$fail_msg_demand</em></td>";
		echo "</tr>";
	}
	
	echo "<tr><td colspan=3><em>U dient beide onderstaande velden in te vullen.<br>De gegevens worden niet op de cursuspagina getoond, maar alleen doorgegeven aan de Instructiecommissie.</em></td></tr>";
	
	// telefoonnr.
	echo "<tr><td>Telefoonnummer (10 cijfers, met streepje):</td>";
	echo "<td><input type=\"text\" name=\"telph\" value=\"" . (isset($telph) ? $telph : '') . "\" size=11 /></td>";
	if (isset($fail_msg_contact)) {
		echo "<td><em>$fail_msg_contact</em></td>";
	} else {
		if (isset($fail_msg_telph)) echo "<td><em>$fail_msg_telph</em></td>";
	}
	echo "</tr>";
	
	// e-mail
	echo "<tr><td>E-mailadres:</td>";
	echo "<td><input type=\"text\" name=\"email\" value=\"" . (isset($email) ? $email : '') . "\" size=45 /></td>";
	if (isset($fail_msg_email)) echo "<td><em>$fail_msg_email</em></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Inschrijven\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}

mysqli_close($link);
?>
</div>
</body>
</html>
