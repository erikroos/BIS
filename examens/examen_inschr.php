<?php

// check login
session_start();
if (!isset($_GET['delId'])) { // Als uitschrijf-link geklikt, dan geen autorisatie nodig
	if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
		header("Location: ./bis_login.php");
		exit();
	}
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	echo 'Fout: database niet gevonden.';
	exit();
}

setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Examens - Inschrijven voor een examen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
<?php

// Uitschrijf-link geklikt
if (isset($_GET['delId'])) {
	$qr = mysql_query('SELECT Naam, Datum, Omschrijving 
			FROM examen_inschrijvingen 
			JOIN examens ON examen_inschrijvingen.Ex_ID = examens.ID
			WHERE UniekeHash = "' . $_GET['delId'] . '"');
	if (mysql_affected_rows() > 0) {
		$row = mysql_fetch_assoc($qr);
		$message = '<p>Kandidaat ' . $row['Naam'] . ' heeft zich uitgeschreven voor het examen ' . 
			$row['Omschrijving']  . ' op ' . DBdateToDate($row['Datum']) . '</p>';
		SendEmail("examens@hunze.nl", "Verwijderde examenaanmelding", $message);
		SendEmail("instructie@hunze.nl", "Verwijderde examenaanmelding", $message);
		mysql_query('DELETE FROM examen_inschrijvingen WHERE UniekeHash = "' . $_GET['delId'] . '"');
		echo "<p>Uw inschrijving is verwijderd en de Examencommissie is op de hoogte gesteld.<br />
			U kunt dit scherm nu sluiten.</p>";
		exit;
	} else {
		echo '<p>Onbekende inschrijving.</p>';
		exit;
	}
}

$id = $_GET['id'];

// init
if (!isset($_POST['cancel']) && !isset($_POST['insert'])) {
	$fail = false;
}

// knop gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['name'], $_POST['grade'], $_POST['age'], $_POST['email'], $_POST['telph'], $name, $grade, $email, $telph);
	$fail = false;
	echo "<p>U wordt niet aangemeld.<br />";
	echo "<a href='index.php'>Terug naar het examenscherm&gt;&gt;</a></p>";
}

if (isset($_POST['insert'])) {
	$name = $_POST['name'];
	$grade = $_POST['grade'];
	$age = $_POST['age'];
	$email = $_POST['email'];
	$telph = $_POST['telph'];
	
	if (!CheckName($name)) {
		$fail_msg_name = "U dient een geldige voor- en achternaam op te geven. Let op: de apostrof (') wordt niet geaccepteerd.";
	}
	if (!$telph && !$email) {
		$fail_msg_contact = "U dient minimaal ofwel een telefoonnnummer ofwel een e-mailadres op te geven.";
	} else {
		if ($telph && !check_phone_dutch($telph)) {
			$fail_msg_telph = "U dient een geldig 10-cijferig telefoonnummer, met streepje, in te voeren.";
		}
		if ($email && !CheckEmail($email)) {
			$fail_msg_email = "U dient een geldig e-mailadres in te voeren.";
		}
	}
	
	if (isset($fail_msg_name) || isset($fail_msg_contact) || isset($fail_msg_telph) || isset($fail_msg_email)) {
		$fail = true;
	}
	
	if (!isset($fail) || $fail == false) {
		$hash = 0;
		while ($hash == 0) {
			 $hash = generateHash();
		}
		$query = "INSERT INTO `examen_inschrijvingen` (Naam, Graad, Leeftijd, Ex_ID, Email, TelNr, UniekeHash) VALUES ('$name', '$grade', '$age', '$id', '$email', '$telph', '$hash');";
		$result = mysql_query($query);
		if (!$result) {
			die("Inschrijven voor examen mislukt." . mysql_error());
		} else {
			$query2 = "SELECT Datum FROM `examens` WHERE ID='$id';";
			$result2 = mysql_query($query2);
			$row2 = mysql_fetch_assoc($result2);
			$date_db = DBdateToDate($row2['Datum']);
			
			// Mail kandidaat, met uitschrijflink
			if ($email) {
				$message = 'U bent aangemeld voor het examen op ' . $date_db . '<br />' .
					'Mocht u zich willen uitschrijven, klik dan <a href="' . $homepage . '/bis/examens/examen_inschr.php?delId=' . $hash . '">hier</a>';
				SendEmail($email, "Bevestiging examenaanmelding", $message);
			}
			
			// Mail hotemetoten
			$message = "Naam: ".$name."<br>";
			$message .= "Leeftijd: ".$age."<br>";
			$message .= "Te behalen graad: ".$grade."<br>";
			$message .= "Op: " . $date_db . "<br>";
			$message .= "Telefoonnummer: ".$telph."<br>";
			$message .= "E-mailadres: ".$email."<br>";
			SendEmail("examens@hunze.nl", "Nieuwe examenaanmelding", $message);
			//SendEmail("instructie@hunze.nl", "Nieuwe examenaanmelding", $message); //21-4-2014 uitgezet op verzoek van Dagmar
			
			echo "<p>Hartelijk dank voor uw aanmelding! Deze is doorgegeven aan de Examencommissie.<br>";
			echo "<a href='index.php'>Terug naar het examenscherm&gt;&gt;</a></p>";
			exit;
		}
	}
}

// Formulier
if ((!isset($_POST['insert']) && !isset($_POST['cancel'])) || !isset($fail) || $fail == true) {
	echo "<p><b>Aanmeldformulier</b></p>";
	echo '<form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo '<td><input type="text" name="name" value="' . (isset($name) ? $name : '') . '" size="45" /></td>';
	if (isset($fail_msg_name)) {
		echo '<td><em>' . $fail_msg_name . '</em></td>';
	}
	echo "</tr>";
	
	// leeftijdscategorie
	echo "<tr><td>Leeftijdscategorie:</td>";
	echo "<td><input type=\"radio\" name=\"age\" value=\"jeugd t/m 14 jaar\" ";
	if (isset($age) && $age == 'jeugd t/m 14 jaar') {
		echo "checked='checked'";
	}
	echo "/>jeugd t/m 14 jaar</td></tr>";
	echo "<tr><td></td><td><input type=\"radio\" name=\"age\" value=\"junioren 15 t/m 18 jaar\" ";
	if (isset($age) && $age == 'junioren 15 t/m 18 jaar') {
		echo "checked='checked'";
	}
	echo "/>junioren 15 t/m 18 jaar</td></tr>";
	echo "<tr><td></td><td><input type=\"radio\" name=\"age\" value=\"senioren vanaf 18 jaar\" ";
	if (!isset($age) || $age == 'senioren vanaf 18 jaar') {
		echo "checked='checked'";
	}
	echo "/>senioren vanaf 18 jaar</td></tr>";
	echo "<tr><td></td><td><input type=\"radio\" name=\"age\" value=\"veteranen 50+\" ";
	if (isset($age) && $age == 'veteranen 50+') {
		echo "checked='checked'";
	}
	echo "/>veteranen 50+</td></tr>";
	echo "<tr><td></td><td></td></tr>";
	
	// graad
	echo "<tr><td>Te behalen graad:</td>";
	echo "<td><select name=\"grade\" />";
	$query = "SELECT Graden FROM examens WHERE ID='$id';";
	$grade_result = mysql_query($query);
	if (!$grade_result) {
		die("Ophalen van examengraden mislukt.".mysql_error());
	} else {
		if ($row = mysql_fetch_assoc($grade_result)) {
			$grades_db = $row[Graden];
			$grades = split(",", $grades_db);
			foreach($grades as $curr_grade) {
				echo "<option value=\"".$curr_grade."\" ";
				if (isset($grade) && $grade == $curr_grade) {
					echo "selected";
				}
				echo "/>".$curr_grade;
			}
		}
	}
	echo "</select></td>";
	echo "</tr>";
	
	echo "<tr><td>&nbsp;</td></tr><tr><td colspan=3><em>
		U dient minstens &eacute;&eacute;n van onderstaande velden in te vullen.<br />
		Als u een e-mailadres opgeeft, ontvangt u een bevestiging per e-mail, met daarin een link die u kunt gebruiken mocht u uw inschrijving weer ongedaan willen maken.<br />
		De gegevens worden niet op de examenpagina getoond, maar alleen doorgegeven aan de Examencommissie.</em>
		</td></tr>";
	
	// telefoonnr.
	echo "<tr><td>Telefoonnummer (10 cijfers, met streepje):</td>";
	echo '<td><input type="text" name="telph" value="' . (isset($telph) ? $telph : '') . '" size=11 /></td>';
	if (isset($fail_msg_contact)) {
		echo '<td><em>' . $fail_msg_contact . '</em></td>';
	} else {
		if (isset($fail_msg_telph)) {
			echo '<td><em>' . $fail_msg_telph . '</em></td>';
		}
	}
	echo "</tr>";
	
	// e-mail
	echo "<tr><td>E-mailadres:</td>";
	echo '<td><input type="text" name="email" value="' . (isset($email) ? $email : '') . '" size="45" /></td>';
	if (isset($fail_msg_email)) {
		echo '<td><em>' . $fail_msg_email . '</em></td>';
	}
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Inschrijven\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}
?>

</div>
</body>
</html>

<?php 
function generateHash() {
	$hash = generateUltraSecretActivationHash('67TYFGTYF%^RYGVNBS^&');
	$qr = mysql_query(sprintf('SELECT COUNT(*) AS hashCnt FROM examen_inschrijvingen WHERE UniekeHash = "%s"', $hash));
	if (mysql_affected_rows() > 0) {
		$row = mysql_fetch_assoc($qr);
		if ($row['hashCnt'] > 0) {
			return 0;
		}
	}
	return $hash;
}

function generateUltraSecretActivationHash($salt){
	$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$str = '';
	$count = strlen($charset);
	for($i=0;$i<65;$i++){
		mt_srand((double)microtime()*1000000);
		$str .= $charset[mt_rand(0, $count-1)];
	}

	$str .= $salt . time();

	for($i=0;$i<65;$i++){
		mt_srand((double)microtime()*1000000);
		$str .= $charset[mt_rand(0, $count-1)];
	}
	return md5($str);
}

?>
