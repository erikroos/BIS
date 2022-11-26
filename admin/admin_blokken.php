<?php
$locationHeader = 'Wedstrijdblokken';
$backLink = '<a href="index.php">Terug naar admin-menu</a>';
include 'admin_header.php';
?>

<p>Wedstrijdblokken zijn bedoeld om een boot te blokken in BIS wanneer deze bijvoorbeeld
voor een weekend naar een wedstrijd gaat. Omdat u een start- en een eindtijd kunt opgeven,
zijn ze geschikter hiervoor dan een uit-de-vaart-melding, die alleen per hele dagen gaat.</p>
<p><a href="admin_blok_toev.php">Wedstrijdblok toevoegen&gt;&gt;</a></p>

<?php
$fail = false;
$boot_te_tonen = 'alle';
if (isset($_POST['boot_te_tonen'])) {
	$boot_te_tonen = $_POST['boot_te_tonen'];
} else {
	if (isset($_GET['boot_te_tonen'])) {
		$boot_te_tonen = $_GET['boot_te_tonen'];
	}
}
?>

<form name="form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	Beperk tot boot:
	<select name="boot_te_tonen">
		<option value="alle" <?php if ($boot_te_tonen == "alle") echo 'selected="selected"'; ?>>alle</option>
	<?php
	$result = mysqli_query($link, sprintf('SELECT DISTINCT boten.Naam AS Bootnaam 
		FROM %s
		JOIN boten ON %s.Boot_ID = boten.ID
		WHERE Verwijderd = 0 
		AND Wedstrijdblok > 0 
		ORDER BY Bootnaam', $opzoektabel, $opzoektabel));
	if (!$result) {
		die("Ophalen van geblokte boten mislukt: " . mysqli_error());
	} else {
		while ($row = mysqli_fetch_assoc($result)) {
			$bootnaam = $row['Bootnaam'];
			if ($bootnaam != "") {
				echo '<option value="' . $bootnaam . '"';
				if ($boot_te_tonen == $bootnaam) {
					echo ' selected="selected"';
				}
				echo '>' . $bootnaam . '</option>';
			}
		}
	}
	?>
	</select>
	<input type="submit" name="submit_bootnaam" value="Toon" />
</form>
<br /><br />

<table class="sortable" id="wedstrijdblokken_ovz" border="1" cellpadding="6" cellspacing="0" style="bordercolor:#AAB8D5">
<tr>
	<td>MPB</td>
	<td>Omschrijving</td>
	<td>Startdatum</td>
	<td>Starttijd</td>
	<td>Einddatum</td>
	<td>Eindtijd</td>
	<td>Boot</td>
	<td colspan="2"></td>
</tr>

<?php
$restrict_query = '';
if ($boot_te_tonen != 'alle') {
	$restrict_query = 'AND boten.Naam = "' . $boot_te_tonen . '" ';
}
$query = sprintf('SELECT DISTINCT Wedstrijdblok 
		FROM %s
		JOIN boten ON %s.Boot_ID = boten.ID
		WHERE Verwijderd = 0 
		AND Wedstrijdblok > 0 
		%s 
		ORDER BY Wedstrijdblok', $opzoektabel, $opzoektabel, $restrict_query);
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van informatie mislukt: " . mysqli_error());
} else {
	while ($row = mysqli_fetch_assoc($result)) {
		$blok_id = $row['Wedstrijdblok'];
		$query2 = sprintf('SELECT MPB, Pnaam, Datum, Begintijd, Eindtijd, boten.Naam AS Bootnaam
			FROM %s
			JOIN boten ON %s.Boot_ID = boten.ID
			WHERE Verwijderd = 0 
			AND Wedstrijdblok = %d 
			ORDER BY Datum', $opzoektabel, $opzoektabel, $blok_id);
		$result2 = mysqli_query($link, $query2);
		if (!$result2) {
			die("Ophalen van informatie mislukt: " . mysqli_error());
		} else {
		    // uit eerste record kun je alles al halen, behalve -bij meer dan 1 inschrijving- de einddatum en -tijd
			$row2 = mysqli_fetch_assoc($result2);
			$startdate_sh = strftime('%A %d-%m-%Y', strtotime($row2['Datum']));
			$enddate = $row2['Datum'];
            $endtime = $row2['Eindtijd'];
			while ($row3 = mysqli_fetch_assoc($result2)) {
				$enddate = $row3['Datum'];
                $endtime = $row3['Eindtijd'];
			}
			$enddate_sh = strftime('%A %d-%m-%Y', strtotime($enddate));
			echo '<tr>';
			echo '<td>' . $row2['MPB'] . '</td>';
			echo '<td>' . $row2['Pnaam'] . '</td>';
			echo '<td>' . $startdate_sh . '</td>';
			echo '<td>' . $row2['Begintijd'] . '</td>';
			echo '<td>' . $enddate_sh . '</td>';
			echo '<td>' . $endtime . '</td>';
			echo '<td>' . $row2['Bootnaam'] . '</td>';
			echo '<td><a href="./admin_blok_toev.php?id=' . $blok_id . '">Wijzigen</a></td>';
			echo '<td><a href="./admin_blok_verw.php?id=' . $blok_id . '">Verwijderen</a></td>';
			echo '</tr>';
		}
	}
}
?>

</table>

<?php include 'admin_footer.php';
