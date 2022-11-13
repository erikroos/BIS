<?php
include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);

setlocale(LC_TIME, 'nl_NL');

if ($_GET['id']) $id = $_GET['id']; // TODO check for XSS!

echo "<p><strong>Bekijken/aanmelden</strong></p>";

$query = "SELECT Startdatum, Einddatum, Type, Quotum, Omschrijving FROM cursussen WHERE id='$id';";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van cursusgegevens mislukt.".mysqli_error());
} else {
	$rows_aff = mysqli_affected_rows($link);
	if ($rows_aff > 0) {
		$row = mysqli_fetch_assoc($result);
		$exstartdate = $row['Startdatum'];
		$exstartdate_sh = strtotime($exstartdate);
		$type = $row['Type'];
		$quotum = $row['Quotum'];
		$description = $row['Omschrijving'];
		echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\"><tr><th>&nbsp;</th><th>Naam</th></tr>";
		$query2 = "SELECT Naam FROM cursus_inschrijvingen WHERE Ex_ID='$id';";
		$result2 = mysqli_query($link, $query2);
		if (!$result2) {
			echo("Ophalen van cursusinschrijvingen mislukt.".mysqli_error());
		} else {
			$rows_aff2 = mysqli_affected_rows($link);
			$c2 = 0;
			while ($row2 = mysqli_fetch_assoc($result2)) {
				echo "<tr><td>".($c2+1)."</td><td>".$row2['Naam']."</td></tr>";
				$c2++;
			}
			while ($c2 < $quotum) {
				echo "<tr><td>".($c2+1)."</td><td><a href='cursus_inschr.php?id=$id'>Aanmelden&gt;&gt;</a></td></tr>";
				$c2++;
			}
			echo "</table>";
		}
	}
}

mysqli_close($link);
