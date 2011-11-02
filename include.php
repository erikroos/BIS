<?php

// inlezen configfile
$filename_basis = "bis.conf";
if (file_exists($filename_basis)) {
	$filename = $filename_basis;
} else {
	$filename = "../".$filename_basis; // probeer een dir lager
	if (!file_exists($filename)) {
		$filename = "../../".$filename_basis; // probeer nóg een dir lager
		if (!file_exists($filename)) {
			$filename = "bis/".$filename_basis; // probeer in BIS-dir (tbv page 15 en 22)
			if (!file_exists($filename)) {
				$filename = "../bis/".$filename_basis; // probeer in BIS-dir (tbv page 15 en 22)
				if (!file_exists($filename)) {
					echo "Fout: configuratiebestand niet gevonden.<br>";
					exit();
				}
			}
		}
	}
}
$fh = fopen($filename, "r");
while (!feof($fh)) {
	$line = fgets($fh);
	if ($line[0] != "#") { // commentaar niet parsen
		$line = trim($line);
		$fields = explode("=", $line, 2);
		if ($fields[0] == "database_host") $database_host = $fields[1];
		if ($fields[0] == "database_user") $database_user = $fields[1];
		if ($fields[0] == "database_pass") $database_pass = $fields[1];
		if ($fields[0] == "database") $database = $fields[1];
		if ($fields[0] == "login_database_user") $login_database_user = $fields[1];
		if ($fields[0] == "login_database_pass") $login_database_pass = $fields[1];
		if ($fields[0] == "login_database") $login_database = $fields[1];
		if ($fields[0] == "opzoektabel") $opzoektabel = $fields[1];
		if ($fields[0] == "stattabel") $stattabel = $fields[1];
		if ($fields[0] == "systeemnaam") $systeemnaam = $fields[1];
		if ($fields[0] == "mailadres") $mailadres = $fields[1];
		if ($fields[0] == "loginnaam_hulp") $loginnaam_hulp = $fields[1];
		if ($fields[0] == "loginwachtwoord_hulp") $loginwachtwoord_hulp = $fields[1];
		if ($fields[0] == "login_admin_admin_wachtwoord") $login_admin_admin_wachtwoord = $fields[1];
		if ($fields[0] == "login_admin_matcie_wachtwoord") $login_admin_matcie_wachtwoord = $fields[1];
		if ($fields[0] == "login_admin_excie_wachtwoord") $login_admin_excie_wachtwoord = $fields[1];
		if ($fields[0] == "login_admin_instrcie_wachtwoord") $login_admin_instrcie_wachtwoord = $fields[1];
		if ($fields[0] == "login_admin_gebcie_wachtwoord") $login_admin_gebcie_wachtwoord = $fields[1];
		if ($fields[0] == "homepage") $homepage = $fields[1];
		if ($fields[0] == "homepagenaam") $homepagenaam = $fields[1];
		if ($fields[0] == "standaardcategorie") $standaardcategorie = $fields[1];
		if ($fields[0] == "standaardgraad") $standaardgraad = $fields[1];
		if ($fields[0] == "toonweer") $toonweer = $fields[1];
		if ($fields[0] == "koudwaterprotocol") $koudwaterprotocol = $fields[1];
		if ($fields[0] == "csslink") $csslink = $fields[1];
		if ($fields[0] == "examenregels") $examenregels = $fields[1];
		if ($fields[0] == "mededelingenpagina") $mededelingenpagina = $fields[1];
	}
}
fclose($fh);

// connecteren naar DB speciaal voor inschrijfboek
$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}

// datum/tijd
$today = date('d-m-Y');
$today_db = date('Y-m-d');
$thehour = date('G');
$theminute = date('i');
$themonth = date('m');
$theyear = date('Y');
$thetime = $thehour.":".$theminute;
$theminute_quarts = $theminute + (15 - ($theminute % 15 ));
$thehour_q = $thehour;
if ($theminute_quarts == 60) {
	if ($thehour_q < 23) {
		$theminute_quarts = 0;
		$thehour_q += 1;
	} else {
		$theminute_quarts = 45;
	}
}

// stop alle MPB-gevende bestuursleden in een array
$mpb_array = array();
$mpb_array_sh = array();
$mpb_array_mail = array();
// leeg bestuurslid bovenaan lijst, zodat je bij een inschrijving bij het veld 'MPB' ook een lege waarde kunt kiezen (geen MPB nodig):
array_push($mpb_array, "");
array_push($mpb_array_sh, "");
array_push($mpb_array_mail, "");
$query = "SELECT Functie, Naam, Email FROM bestuursleden WHERE MPB=1;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van bestuursleden mislukt.". mysql_error());
}
while ($row = mysql_fetch_assoc($result)) {
	array_push($mpb_array, $row['Functie']);
	array_push($mpb_array_sh, $row['Functie']." (".$row['Naam'].")");
	array_push($mpb_array_mail, $row['Email']);
}

// stop alle bootnamen in een array
$query = "SELECT Naam FROM boten;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van bootnamen mislukt.". mysql_error());
}
$boat_array = array();
while ($row = mysql_fetch_assoc($result)) {
	array_push($boat_array, $row['Naam']);
}

// stop alle bootcategorieën in een array
$query = "SELECT DISTINCT Categorie FROM types ORDER BY Categorie;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van categorieën mislukt.". mysql_error());
}
$cat_array = array();
while ($row = mysql_fetch_assoc($result)) {
	array_push($cat_array, $row['Categorie']);
}

// stop alle roeigraden in een array
$query = "SELECT Roeigraad FROM roeigraden WHERE ToonInBIS=1 ORDER BY ID;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van roeigraden mislukt.". mysql_error());
}
$grade_array = array();
while ($row = mysql_fetch_assoc($result)) {
	array_push($grade_array, $row['Roeigraad']);
}

// datum- en tijdfuncties

function TimeToBlocks($time_hhmmss) {
	$fields = explode(":", $time_hhmmss);
	return (($fields[0] - 6) * 4) + ($fields[1] / 15);
}

function BlocksToTime($time_blocks) {
	$hours = 6 + floor($time_blocks / 4);
	if ($hours < 10) $hours = "0".$hours;
	$quarters = ($time_blocks % 4) * 15;
	if ($quarters == 0) $quarters = "00";
	return $hours.":".$quarters;
}
	
function DateToDBdate($ddmmyyyy) {
	$fields = explode("-", $ddmmyyyy);
	return ($fields[2]."-".$fields[1]."-".$fields[0]);
}

function DBdateToDate($yyyymmdd) {
	$fields = explode("-", $yyyymmdd);
	return ($fields[2]."-".$fields[1]."-".$fields[0]);
}

// Controlefuncties

function CheckEmail($email_to_check) {
	//trim verwijdert returns etc.
	$email_to_check = trim($email_to_check);
	// check syntax
	if (eregi("^[0-9a-z]([-_.~]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $email_to_check))
	{
		// pak domeinnaam
		list ($username, $domain) = split('@', $email_to_check);
		// kijk of er MX records in de DNS staan
		if (!checkdnsrr($domain, 'MX')) {
			return false;
		}
		return true;
	}
	return false;
}

function check_phone_dutch($phone_to_check) {
	return ereg ("^[0-9]{2}(([-][0-9][0-9])|([0-9][-][0-9])|([0-9][0-9][-]))[0-9]{6}$", $phone_to_check);
}

function SendEmail($email_to, $subject, $message) {
	if (!CheckEmail($email_to)) {
		//echo " Adres $email_to niet OK.";
		return false;
	}
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: BIS<bis@hunze.nl>\r\n"; // let op: globale variabelen uit conf werken hier niet!
	$startbody = "<html><head><title></title></head><body><font face=\"Arial\" size=\"2\"><p>";
	$endbody = "</p></font></body></html>";
	mail($email_to, $subject, $startbody.$message.$endbody, $headers);
	//echo " Mail verstuurd aan $email_to.";
	return true;
}

function CheckTheDate($ddmmyyyy) {
	if (preg_match("/^(0[1-9]|[12][[:digit:]]|3[01])-(0[1-9]|1[012])-([12][[:digit:]]{3})$/", $ddmmyyyy, $date_part) && checkdate($date_part[2], $date_part[1], $date_part[3])) { // kijk of goede range is en schrijf substrings weg naar maand-dag-jaar voor checkdate controle
		return TRUE;
	} else {
		return FALSE;
	}
}

function CheckName($name_to_check) {
	// aardigheidje voor der Feico
	if ($name_to_check == "F.P.J. Camphuis" || $name_to_check == "F.P.J.Camphuis" || $name_to_check == "F.P.J.C." || $name_to_check == "FPJC" || $name_to_check == "fpjc") return true;
	
	return ereg ("^[a-zA-Z][a-záàâäçéèêëóòôöíìîïúùûü]+([ -][a-zA-Z][a-záàâäçéèêëóòôöíìîïúùûü]+)*[ ][a-zA-Záàâäçéèêëóòôöíìîïúùûü][a-záàâäçéèêëóòôöíìîïúùûü]+([ -][a-zA-Záàâäçéèêëóòôöíìîïúùûü][a-záàâäçéèêëóòôöíìîïúùûü]+)*$", $name_to_check);
}

function InRange($ddmmyyyy, $nr_of_days) {
	$today = date('d-m-Y');
	$day1 = explode("-", $today);
	$day2 = explode("-", $ddmmyyyy);
	$start_date = gregoriantojd($day1[1], $day1[0], $day1[2]);
	$end_date = gregoriantojd($day2[1], $day2[0], $day2[2]);
	$days = $end_date - $start_date;
	if ($days >= 0 && $days <= $nr_of_days) {
		return true;
	}
	return false;
}

function getPass($user_, $link_, $database_host_, $login_database_user_, $login_database_pass_, $login_database_, $database_) {
	// TODO: doe iets aan al deze globale variabelen die echter in
	// deze functie niet bekend zijn en dus meegegeven moeten worden

	// Tijdelijk Drupal-DB selecteren
	$link_drupal = mysql_connect($database_host_, $login_database_user_, $login_database_pass_);
	if (!mysql_select_db($login_database_, $link_drupal)) {
		echo mysql_error()."<br />";
	}
	
	$query = "SELECT pass FROM users WHERE name='$user_';";
	$result = mysql_query($query);
	if ($result) {
		$row = mysql_fetch_assoc($result);
		$pass_db = $row['pass'];
	}
	
	// Terug naar BIS-DB
	mysql_close($link_drupal);
	if (!mysql_select_db($database_, $link_)) {
		echo mysql_error()."<br />";
	}
	
	return $pass_db;
}

function ValidateLogin($user_, $pass_, $link_, $database_host_, $login_database_user_, $login_database_pass_, $login_database_, $database_) {

	// TODO: doe iets aan al deze globale variabelen die echter in
	// deze functie niet bekend zijn en dus meegegeven moeten worden

	// Tijdelijk Drupal-DB selecteren
	$link_drupal = mysql_connect($database_host_, $login_database_user_, $login_database_pass_);
	if (!mysql_select_db($login_database_, $link_drupal)) {
		echo mysql_error()."<br />";
	}
	
	$query = "SELECT pass FROM users WHERE name='$user_';";
	$result = mysql_query($query);
	if (!$result) {
		echo mysql_error()."<br />";
	}
	$row = mysql_fetch_assoc($result);
	$pass_db = $row['pass'];
	$pass_given = md5($pass_);
	if ($pass_db == $pass_given) {
		// Terug naar BIS-DB
		mysql_close($link_drupal);
		mysql_select_db($database_, $link_);
		
		return true;
	}
	
	// Terug naar BIS-DB
	mysql_close($link_drupal);
	mysql_select_db($database_, $link_);
	
	return false;
}

?>