<?php
$locationHeader = 'Bestuur';
$backLink = '<a href="index.php">Terug naar admin-menu</a>';
include 'admin_header.php';
?>

<?php
echo "<p><div><a href='./admin_bestuur_toev.php'>Bestuurslid toevoegen&gt;&gt;</a></div></p>";

$query = "SELECT * from bestuursleden ORDER BY Functie;";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van bestuursleden mislukt.". mysqli_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Functie</div></th><th><div style=\"text-align:left\">Naam</div></th><th><div style=\"text-align:left\">Email</div></th><th><div style=\"text-align:left\">Geeft MPB?</div></th><th colspan=2><div style=\"text-align:left\">&nbsp;</div></th></tr>";

$c = 0;
while ($row = mysqli_fetch_assoc($result)) {
	$function = $row['Functie'];
	$name = $row['Naam'];
	$mail = $row['Email'];
	$mpb = $row['MPB'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$function</div></td>";	
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">$mail</div></td>";
	echo "<td><div style=\"text-align:left\">";
	if ($mpb) {
		echo "ja";
	} else {
		echo "nee";
	}
	echo "</div></td>";
	echo "<td><div><a href=\"./admin_bestuur_toev.php?function=$function\">Wijzigen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_bestuur_verw.php?function=$function'>Verwijderen</a></div></td>";
	echo "</tr>";
	$c++;
}
echo "</table>";
?>

<?php include 'admin_footer.php'; ?>
