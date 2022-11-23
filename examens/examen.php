<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../mail.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Exameninschrijving</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<h1>Exameninschrijving</h1>
<p><a href='index.php'>Naar examenoverzicht&gt;&gt;</a><br />
<a href='./bis_logout.php'>Uitloggen&gt;&gt;</a></p>

<?php
$id = $_GET['id'];
$result = mysqli_query($link, 'SELECT Datum, Quotum, Omschrijving FROM examens WHERE ID = ' . $id);
if (!$result) {
	die('Ophalen van examengegevens mislukt: ' . mysqli_error());
} else {
	if ($row = mysqli_fetch_assoc($result)) {
		$exdate = $row['Datum'];
		$exdate_sh = strtotime($exdate);
		$quotum = $row['Quotum'];
		$description = $row['Omschrijving'];
		echo "<p><strong>Inschrijven voor " . $description . ' op ' . strftime('%A %d-%m-%Y', $exdate_sh) . "</strong>";
		echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\"><tr><th>&nbsp;</th><th>Naam</th><th>Examen</th></tr>";
		$result2 = mysqli_query($link, 'SELECT Naam, Graad FROM examen_inschrijvingen WHERE Ex_ID = ' . $id);
		if (!$result2) {
			echo("Ophalen van exameninschrijvingen mislukt.".mysqli_error());
		} else {
			$rows_aff2 = mysqli_affected_rows($link);
			$c2 = 0;
			while ($row2 = mysqli_fetch_assoc($result2)) {
				echo "<tr><td>".($c2+1)."</td><td>".$row2['Naam']."</td><td>".$row2['Graad']."</td></tr>";
				$c2++;
			}
			while ($c2 < $quotum) {
				echo "<tr><td>".($c2+1)."</td><td><a href='examen_inschr.php?id=$id'>Aanmelden&gt;&gt;</a></td><td>&nbsp;</td></tr>";
				$c2++;
			}
		}
		echo "</table></p>";
	}
}
