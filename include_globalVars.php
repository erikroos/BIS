<?php

// inlezen configfile
$filename_basis = "bis.conf";
if (file_exists($filename_basis)) {
	$filename = $filename_basis;
} else {
	$filename = "../".$filename_basis; // probeer een dir lager
	if (!file_exists($filename)) {
		$filename = "../../".$filename_basis; // probeer nog een dir lager
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
	if ($line == false || $line[0] != "#") { // laatste regel en commentaar niet parsen
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
		if ($fields[0] == "salt") $salt = $fields[1];
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
