<link href="../../../CSS/basislayout.css" rel="stylesheet" type="text/css">
<h1><strong>De vloot</strong></h1>

<?php

$link = mysql_connect("rdbms.strato.de", "U521746", "H9u7n1z1e");
if (!mysql_select_db("DB521746", $link)) {
	echo "Fout: database niet gevonden.<br />";
	exit();
}

// Categorie-selectie door gebruiker
$cat_to_show = 'Skiffs en C1en';
if ($_POST['cat_to_show']) {
	$cat_to_show = $_POST['cat_to_show'];
}
echo "<form name='form' action=\"$REQUEST_URI\" method=\"post\">";
echo "Categorie:&nbsp;";
echo "<select name=\"cat_to_show\" />";
	$query = "SELECT DISTINCT Categorie FROM types WHERE Categorie<>'Societeit' ORDER BY Categorie;";
	$result = mysql_query($query);
	if (!$result) {
		die("Ophalen van categorie&euml;n mislukt.". mysql_error());
	}
	$c = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$cat_db = $row['Categorie'];
		echo "<option value=\"$cat_db\" ";
		if ($cat_to_show == $cat_db) echo "selected";
		echo "/> $cat_db";
		$c++;
	}
echo "</select>";
echo "<p><input type=\"submit\" name=\"submit\" value=\"Toon\" /> </p>";
echo "</form>";

$restrict_query_type = "";
$query = "SELECT Type FROM types WHERE Categorie='$cat_to_show';";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van types mislukt.". mysql_error());
}
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	if ($c > 0) $restrict_query_type .= " OR ";
	$restrict_query_type .= "Type='".$row['Type']."'";
	$c++;
}
$query = "SELECT Naam, Gewicht, Type, boten.Roeigraad FROM boten JOIN roeigraden ON boten.Roeigraad=roeigraden.Roeigraad WHERE Datum_eind IS NULL AND (".$restrict_query_type.") ORDER BY roeigraden.ID;";

// Mochten we ooit uit-de-vaart nog willen markeren:
//$query2 = "SELECT Reden FROM uitdevaart WHERE Verwijderd=0 AND Boot_ID='$boat_ids_array[$c]' AND Startdatum<='$date_to_show_db' AND (Einddatum='0' OR Einddatum='0000-00-00' OR Einddatum>='$date_to_show_db');";

$boats_result = mysql_query($query);
if (!$boats_result) {
	die("Ophalen van boten-informatie mislukt.". mysql_error());
} else {
	if (mysql_affected_rows() > 0) {
		echo "<table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
		echo "<tr>";
		echo "<th><div align=\"left\">Naam</div></th>";
		echo "<th><div align=\"left\">Gewicht</div></th>";
		echo "<th><div align=\"left\">Type</div></th>";
		echo "<th><div align=\"left\">Graad</div></th>";
		echo "</tr>";
		$c = 0;
		while ($row = mysql_fetch_assoc($boats_result)) {
			$boats_array[$c] = $row['Naam'];
			$boat_tmp = addslashes($boats_array[$c]);
			$weight = $row['Gewicht'];
			$type = $row['Type'];
			$grade = $row['Roeigraad'];
			switch ($grade) {
				case "skiff-1":
					$bgcolor = "#FFFF99";
					break;
				case "skiff-2":
					$bgcolor = "#AAFFAA";
					break;
				case "skiff-3":
					$bgcolor = "#737CA1";
					break;
				case "giek-1":
					$bgcolor = "#FFFF99";
					break;
				case "giek-2":
					$bgcolor = "#AAFFAA";
					break;
				case "giek-3":
					$bgcolor = "#737CA1";
					break;
				case "MPB":
					$bgcolor = "#FFC1C1";
					break;
				case "geen":
					$bgcolor = "#FFFF99";
					break;
			}
			echo "<tr>";
			echo "<th bgcolor=$bgcolor><div align=\"left\">$boats_array[$c]</div></th>";
			echo "<td bgcolor=$bgcolor><div align=\"left\">$weight kg</div></td>";
			echo "<td bgcolor=$bgcolor><div align=\"left\">$type</div></td>";
			echo "<td bgcolor=$bgcolor><div align=\"left\">$grade</div></td>";
			echo "</tr>";
			$c++;
		}
		echo "</table>";
	}
}
