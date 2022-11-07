<?php
include_once("include_globalVars.php");
include_once("include_helperMethods.php");

// connect to DB for following query
$link = getDbLink($database_host, $database_user, $database_pass, $database);

// stop alle MPB-gevende bestuursleden in een array
$mpb_array = array();
$mpb_array_sh = array();
$mpb_array_mail = array();
// leeg bestuurslid bovenaan lijst, zodat je bij een inschrijving bij het veld 'MPB' ook een lege waarde kunt kiezen (geen MPB nodig):
array_push($mpb_array, "");
array_push($mpb_array_sh, "");
array_push($mpb_array_mail, "");
$query = 'SELECT Functie, Naam, Email FROM bestuursleden WHERE MPB=1';
$result = mysqli_query($link, $query);
if (!$result) {
	die('Ophalen van bestuursleden mislukt: ' . mysqli_error());
}
while ($row = mysqli_fetch_assoc($result)) {
	array_push($mpb_array, $row['Functie']);
	array_push($mpb_array_sh, $row['Functie']." (".$row['Naam'].")");
	array_push($mpb_array_mail, $row['Email']);
}

mysqli_close($link);
