<?php
$locationHeader = 'Bestuurslid toevoegen/wijzigen';
$backLink = '<a href="admin_bestuur.php">Terug naar bestuursmenu</a>';
include 'admin_header.php';
?>

<?php
// ingeval van editen bestaande mededeling
if (isset($_GET['function'])) {
	$function_ex = $_GET['function'];
	$query = "SELECT * FROM `bestuursleden` WHERE Functie='$function_ex';";
	$result = mysqli_query($link, $query);
	if ($result) {
		$rows_aff = mysqli_affected_rows($link);
		if ($rows_aff > 0) {
			$row = mysqli_fetch_assoc($result);
			$name = $row['Naam'];
			$function = $row['Functie'];
			$mail = $row['Email'];
			$mpb = $row['MPB'];
		}
	}
}

// Annuleren gedrukt
if (isset($_POST['cancel'])) {
	unset($_POST['name'], $_POST['mail'], $_POST['mpb'], $_POST['function'], $name, $mail, $mpb, $function);
}

if (isset($_POST['insert'])) {
	$name = $_POST['name'];
	$function = $_POST['function'];
	$mail = $_POST['mail'];
	$mpb = 0;
	if (isset($_POST['mpb']) && $_POST['mpb'] == 1) $mpb = 1;
	if ($function_ex) {
		$query = "UPDATE `bestuursleden` SET Naam='$name', Functie='$function', Email='$mail', MPB='$mpb' WHERE Functie='$function_ex';";
	} else {
		$query = "INSERT INTO `bestuursleden` (Naam, Functie, Email, MPB) VALUES ('$name', '$function', '$mail', '$mpb');";
	}
	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Invoeren/wijzigen bestuurslid $name mislukt: ". mysqli_error());
	} else {
		echo "<p>Bestuurslid $name succesvol toegevoegd/gewijzigd.</p>";
	}
}

// Formulier
if (!isset($_POST['insert']) && !isset($_POST['delete']) && !isset($_POST['cancel'])) {
	echo "<p><b>Bestuurslid invoeren/wijzigen</b></p>";
	echo "<form name='form' action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\">";
	echo "<table>";
	
	// naam
	echo "<tr><td>Naam:</td>";
	echo "<td><input type=\"text\" name=\"name\" value=\"". (isset($name) ? $name : '') . "\" size=50 /></td>";
	echo "</tr>";
	
	// functie
	echo "<tr><td>Functie:</td>";
	echo "<td><input type=\"text\" name=\"function\" value=\"". (isset($function) ? $function : '') . "\" size=45 /></td>";
	echo "</tr>";
	
	// mail
	echo "<tr><td>E-mailadres:</td>";
	echo "<td><input type=\"text\" name=\"mail\" value=\"". (isset($mail) ? $mail : '') . "\" size=45 /></td>";
	echo "</tr>";
	
	// MPB
	echo "<tr><td>Geeft MPB?</td>";
	echo "<td><input type=\"checkbox\" name=\"mpb\" value=1 ";
	if (isset($mpb) && $mpb == 1) echo "checked";
	echo "/></td>";
	echo "</tr>";
	
	// knoppen
	echo "</table>";
	echo "<p><input type=\"submit\" name=\"insert\" value=\"Invoeren\" /> ";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Annuleren\" /></p>";
	echo "</form>";
}
?>

<?php include 'admin_footer.php';
