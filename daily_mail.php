<?php

include_once("include.php");
setlocale(LC_TIME, 'nl_NL');

// mail is sent after 00:05 everyday, so get values for yesterday
$yday_ts = strtotime('-1 days');
$yesterday = date('Y-m-d', $yday_ts);
$yesterday_sh = strftime('%A %d-%m-%Y', $yday_ts);

for ($i = 1; $i < count($mpb_array); $i++) {
	$message = "Er waren geen bijzondere inschrijvingen.";
	$query = "SELECT * FROM $opzoektabel WHERE Verwijderd=0 AND Inschrijfdatum='$yesterday' AND MPB='$mpb_array[$i]';";
	$result = mysql_query($query);
	if (!$result) {
		$message = "Ophalen van inschrijvingen uit de BIS-database mislukt.";
	} else {
		$rows_aff = mysql_affected_rows($link);
		if ($rows_aff > 0) {
			$message = "";
			while ($row = mysql_fetch_assoc($result)) {
				$db_date = $row['Datum'];
				$date = strtotime($db_date);
				$date_sh = strftime('%A %d-%m-%Y', $date);
				$start_time = substr($row['Begintijd'], 0, 5);
				$end_time = substr($row['Eindtijd'], 0, 5);
				$pname = $row["Pnaam"];
				$name = $row["Ploegnaam"];
				if ($name == "") $name = "geen ploegnaam";
				// bootnaam
				$boat_id = $row["Boot_ID"];
				$query2 = "SELECT Naam from boten WHERE ID=$boat_id;";
				$result2 = mysql_query($query2);
				$row2 = mysql_fetch_assoc($result2);
				$boat = $row2['Naam'];
				//
				$email = $row["Email"];
				if ($email == "" ) $email = "geen e-mail bekend";
				$mpb = $row['MPB'];
				$message .= "'".$boat."' is ingeschreven door ".$pname." (".$name." - ".$email.") voor ".$date_sh." van ".$start_time." tot ".$end_time." met MPB: ".$mpb."<br>";
			}
		}
	}
	SendEmail($mpb_array_mail[$i], "Te controleren inschrijvingen van $yesterday_sh voor $mpb_array[$i]", $message);
}

mysql_close($link);
?>