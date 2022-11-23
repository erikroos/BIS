<?php
include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = getDbLink($database_host, $database_user, $database_pass, $database);

$mode = $_GET['mode'];
$table = 'schades';
if ($mode) $table .= "_oud";
$csv = "'"; // put ' in front of ID, so Excel doesn't complain abou the SYLK format

$r = mysqli_query($link, "SHOW COLUMNS FROM ".$table);
while ($row = mysqli_fetch_assoc($r)) {
	$csv .= $row['Field']."\t";
}
$csv = substr($csv, 0, -1)."\n";

$r = mysqli_query($link, "SELECT ".$table.".ID, Datum, Datum_gew, ".$table.".Naam AS Meldernaam, boten.Naam, Oms_lang, Feedback, Actie, Actiehouder, Prio, Realisatie, Datum_gereed, Noodrep, Opmerkingen FROM ".$table." JOIN boten ON ".$table.".Boot_ID=boten.ID");
while ($row = mysqli_fetch_assoc($r)) {
	$tmpline = "";
	foreach ($row as $value) {
		$value = preg_replace("/;/", ",", $value);
		$value = preg_replace("/\n/", " ", $value);
		$value = preg_replace("/\t/", " ", $value);
		$value = preg_replace("/\r/", " ", $value);
		$tmpline .= $value."\t";
	}
	$tmpline = substr($tmpline, 0, -1)."\n";
	$csv .= $tmpline;
}

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=".date("Ydm")."_".$table.".xls");
echo $csv;

mysqli_close($link);
exit;
