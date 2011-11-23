<?php

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
		return false;
	}
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: BIS<bis@hunze.nl>\r\n"; 
	$startbody = "<html><head><title></title></head><body><font face=\"Arial\" size=\"2\"><p>";
	$endbody = "</p></font></body></html>";
	mail($email_to, $subject, $startbody.$message.$endbody, $headers);
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
	
	return ereg ("^[a-zA-Z][a-z���������������������]+([ -][a-zA-Z][a-z���������������������]+)*[ ][a-zA-Z���������������������][a-z���������������������]+([ -][a-zA-Z���������������������][a-z���������������������]+)*$", $name_to_check);
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

function ValidateLogin($user_, $pass_, $database_host_, $login_database_user_, $login_database_pass_, $login_database_) {

	// Drupal-DB selecteren
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
	mysql_close($link_drupal);
	$pass_given = md5($pass_);
	if ($pass_db == $pass_given) {
		return true;
	}
	return false;
}

?>