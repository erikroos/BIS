<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $systeemnaam; ?> - Admin - Boottypes</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><strong>Welkom in de Admin-sectie van BIS</strong> [<a href='./index.php'>Terug naar admin-menu</a>] [<a href='./admin_logout.php'>Uitloggen</a>]</p>";
echo "<p><div><a href='./admin_type_toev.php'>Boottype toevoegen&gt;&gt;</a></div></p>";

$query = "SELECT * from types;";
$result = mysqli_query($link, $query);
if (!$result) {
	die("Ophalen van boottypes mislukt.". mysqli_error());
}
echo "<br><table class=\"basis\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<tr><th><div style=\"text-align:left\">Type</div></th><th><div style=\"text-align:left\">Categorie</div></th><th><div style=\"text-align:left\">Roeisoort</div></th><th colspan=2><div style=\"text-align:left\">&nbsp;</div></th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
	$type = $row['Type'];
	$cat = $row['Categorie'];
	$sort = $row['Roeisoort'];
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$type</div></td>";
	echo "<td><div style=\"text-align:left\">$cat</div></td>";
	echo "<td><div style=\"text-align:left\">$sort</div></td>";
	echo "<td><div><a href=\"./admin_type_toev.php?type=$type\">Wijzigen</a></div></td>";
	echo "<td><div style=\"text-align:left\"><a href='admin_type_verw.php?type=$type'>Verwijderen</a></div></td>";
	echo "</tr>";
}
echo "</table>";

mysqli_close($link);
?>

</div>
</body>
</html>
