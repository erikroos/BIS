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

// connect to DB for following queries
$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
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

mysql_close($link);

?>