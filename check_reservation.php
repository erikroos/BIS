<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("include_globalVars.php");
include_once("inschrijving_methods.php");

setlocale(LC_TIME, 'nl_NL');

$bisdblink = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $bisdblink)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}
	
if (isset($_GET['del'])){
	$id = $_GET['id'];
	$date = $_GET['date'];
	$start_time = $_GET['start_time'];
	$cat_to_show = $_GET['cat_to_show'];
	$grade_to_show = $_GET['grade_to_show'];
	$result = deleteReservation($id);
	echo json_encode($result);
}
	
if (isset($_GET['make'])){
	$id = $_GET['id'];
	$boat_id = $_GET['boat_id'];
	$pname = $_GET['pname'];
	$name = $_GET['name'];
	$email = $_GET['email'];
	$mpb = $_GET['mpb'];
	$date = $_GET['date'];
	$start_time_hrs = $_GET['start_time_hrs'];
	$start_time_mins = $_GET['start_time_mins'];
	$end_time_hrs = $_GET['end_time_hrs'];
	$end_time_mins = $_GET['end_time_mins'];
	$ergo_lo = $_GET['ergo_lo'];
	if ($ergo_lo == "") $ergo_lo = 0;
	$ergo_hi = $_GET['ergo_hi'];
	if ($ergo_hi == "") $ergo_hi = 0;
	$result = makeReservation($id, $boat_id, $pname, $name, $email, $mpb, $date, $start_time_hrs, 
							  $start_time_mins, $end_time_hrs, $end_time_mins, $ergo_lo, $ergo_hi);
	echo json_encode($result);
}