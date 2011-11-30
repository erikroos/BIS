<?php

include_once("include_globalVars.php");

// connect to DB for following query
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

mysql_close($link);
?>
