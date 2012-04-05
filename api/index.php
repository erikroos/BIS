<?php

include_once("../include_globalVars.php");
include_once("../inschrijving_methods.php");

setlocale(LC_TIME, 'nl_NL');

$request_method = strtolower($_SERVER['REQUEST_METHOD']);
if ($request_method == 'post') {
	if (validateToken($_POST)) {
		if ($_POST['delete'] == 1) {
			handleDeleteRequest($_POST, $database_host, $database_user, $database_pass, $database, $opzoektabel);
		} else {
			handlePostRequest($_POST, $database_host, $database_user, $database_pass, $database, $opzoektabel);
		}
	} else {
		die(sendResponse(401));
	}
} else {
	if ($request_method == 'get') {
		if ($_GET['getToken'] == 1) {
			if (validateApiLogin($_GET, $database_host, $login_database_user, $login_database_pass, $login_database, $database)) {
				sendResponse(200, makeToken());
			} else {
				die(sendResponse(401));
			}
		} else {
			if (validateToken($_GET)) {
				handleGetRequest($_GET, $database_host, $database_user, $database_pass, $database);
			} else {
				die(sendResponse(401));
			}
		}
	}
}
exit;

function makeToken() {
	$ip = $_SERVER['REMOTE_ADDR'];
	$timestamp = floor(time() / 3600);
	return md5($ip.$salt.$timestamp);
}

function validateApiLogin($data, $database_host_, $login_database_user_, $login_database_pass_, $login_database_, $database_) {
	// Get username -> password from DB (md5)
	$password = getPass($data['username'], $database_host_, $login_database_user_, $login_database_pass_, $login_database_, $database_);
	// Compare password from DB to password from request
	// Note: getPass() returns empty string if username unknown in DB, so check for that!!
	if ($password != "" && $password == $data['password']) {
		return true;
	} else {
		return false;
	}
}

function validateToken($data) {
	if (makeToken() == $data['token']) {
		return true;
	} else {
		return false;
	}
}

function getPass($user_, $database_host_, $login_database_user_, $login_database_pass_, $login_database_, $database_) {
	
	// Drupal-DB selecteren
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
	
	mysql_close($link_drupal);
	return $pass_db;
}

function handleGetRequest($data, $database_host_, $database_user_, $database_pass_, $database_) {
	if ($data['entity'] == "bestuursleden" ||
		$data['entity'] == "boten" ||
		$data['entity'] == "inschrijvingen" ||
		$data['entity'] == "inschrijvingen_oud" ||
		$data['entity'] == "test_inschrijvingen" ||
		$data['entity'] == "test_inschrijvingen_oud" ||
		$data['entity'] == "mededelingen" ||
		$data['entity'] == "roeigraden" ||
		$data['entity'] == "types" ||
		$data['entity'] == "uitdevaart")
	{
		$record_list = getEntityRecords($data['entity'], $data['date'], $database_host_, $database_user_, $database_pass_, $database_);
		sendResponse(200, json_encode($record_list), 'application/json');
	} else {
		die(sendResponse(403));
	}
}

function handlePostRequest($data, $database_host_, $database_user_, $database_pass_, $database_, $opzoektabel_) {
	$fail_msg = "";
	$res_id = $data['res_id'];
	$boat_id = $data['boat_id'];
	$name = $data['name'];
	$team_name = $data['team_name'];
	$email = $data['email'];
	$mpb = $data['mpb'];
	$date = $data['date'];
	$start_time_hrs = $data['start_time_hrs'];
	$start_time_mins = $data['start_time_mins'];
	$end_time_hrs = $data['end_time_hrs'];
	$end_time_mins = $data['end_time_mins'];
	$ergo_lo = $data['ergo_lo'];
	if ($ergo_lo == "") $ergo_lo = 0;
	$ergo_hi = $data['ergo_hi'];
	if ($ergo_hi == "") $ergo_hi = 0;
	$response = makeReservation($database_host_, $database_user_, $database_pass_, $database_, $opzoektabel_, $fail_msg, true, $res_id, 0, $boat_id, $name, $team_name, $email, $mpb, $date, $start_time_hrs, $start_time_mins, $end_time_hrs, $end_time_mins, $ergo_lo, $ergo_hi);
	if ($fail_msg != "") {
		sendResponse(200, "<p>".$fail_msg."</p>");
	} else {
		sendResponse(200, $response);
	}
}

function handleDeleteRequest($data, $database_host_, $database_user_, $database_pass_, $database_, $opzoektabel_) {
	$res_id = $data['res_id'];
	sendResponse(200, deleteReservation($database_host_, $database_user_, $database_pass_, $database_, $opzoektabel_, $res_id));
}

function getEntityRecords($entity, $date, $database_host_, $database_user_, $database_pass_, $database_) {
	$records = array();
	// BIS-DB selecteren
	$link = mysql_connect($database_host_, $database_user_, $database_pass_);
	if (!mysql_select_db($database_, $link)) {
		echo "Fout: database niet gevonden.<br>";
		exit();
	}
	$where = "";
	if ($entity == "inschrijvingen" ||
		$entity == "inschrijvingen_oud" ||
		$entity == "test_inschrijvingen" ||
		$entity == "test_inschrijvingen_oud") {
		if (!$date) {
			die(sendResponse(400, "<p>Fout: geen datum opgegeven</p>"));
		} else {
			$where = " WHERE Datum='$date'";
		}
	}
	$query = "SELECT * FROM ".$entity.$where.";";
	$result = mysql_query($query);
	if ($result) {
		while ($row = mysql_fetch_assoc($result)) {
			array_push($records, $row);
		}
	}
	mysql_close($link);
	return $records;
}

function getStatusCodeMessage($status)
{
	$codes = Array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	);

	return (isset($codes[$status])) ? $codes[$status] : '';
}

function sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
	$status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMessage($status);
	// set the status
	header($status_header);
	// set the content type
	header('Content-type: ' . $content_type);

	// pages with body are easy
	if($body != '')
	{
		// send the body
		echo $body;
		exit;
	}
	// we need to create the body if none is passed
	else
	{
		// create some body messages
		$message = '';

		// this is purely optional, but makes the pages a little nicer to read
		// for your users.  Since you won't likely send a lot of different status codes,
		// this also shouldn't be too ponderous to maintain
		switch($status)
		{
			case 401:
				$message = 'You must be authorized to view this page.';
				break;
			case 404:
				$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
				break;
			case 500:
				$message = 'The server encountered an error processing your request.';
				break;
			case 501:
				$message = 'The requested method is not implemented.';
				break;
		}

		// servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
		$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
					<html>
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
							<title>' . $status . ' ' . getStatusCodeMessage($status) . '</title>
						</head>
						<body>
							<h1>' . getStatusCodeMessage($status) . '</h1>
							<p>' . $message . '</p>
							<hr />
							<address>' . $signature . '</address>
						</body>
					</html>';

		echo $body;
		exit;
	}
}

?>
