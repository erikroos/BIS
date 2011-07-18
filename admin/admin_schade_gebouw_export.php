<?php
include_once("../include.php");

$mode = $_GET['mode'];
$table = 'schades_gebouw';
if ($mode) $table .= "_oud";
$csv = "'"; // put ' in front of ID, so Excel doesn't complain about the SYLK format

$r = mysql_query("SHOW COLUMNS FROM ".$table);
while ($row = mysql_fetch_assoc($r)) {
	$csv .= $row['Field']."\t";
}
$csv = substr($csv, 0, -1)."\n";

$r = mysql_query("SELECT ".$table.".ID, Datum, Datum_gew, ".$table.".Naam AS Meldernaam, Oms_lang, Feedback, Actie, Actiehouder, Prio, Realisatie, Datum_gereed, Noodrep, Opmerkingen FROM ".$table);
while ($row = mysql_fetch_assoc($r)) {
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

exit;

?>
