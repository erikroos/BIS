<?php
$locationHeader = 'Bestuurslid verwijderen';
$backLink = '<a href="admin_bestuur.php">Terug naar bestuursmenu</a>';
include 'admin_header.php';

$function = $_GET['function'];
$query = 'DELETE FROM bestuursleden WHERE Functie = "' . $function . '"';
$result = mysqli_query($link, $query);
if (!$result) {
	die("Verwijderen bestuurslid mislukt: " . mysqli_error());
} else {
	echo "Verwijderen bestuurslid gelukt.<br>";
}

include 'admin_footer.php';
