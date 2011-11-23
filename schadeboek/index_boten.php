<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	echo "Fout: database niet gevonden.<br>";
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Schadeboek Boten</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<!-- Datatables -->
	<style type="text/css" title="currentStyle"> 
		@import "../scripts/datatables/demo_page.css";
		@import "../scripts/datatables/demo_table.css";
	</style> 
	<script type="text/javascript" language="javascript" src="../scripts/datatables/jquery.js"></script> 
	<script type="text/javascript" language="javascript" src="../scripts/datatables/jquery.dataTables.js"></script> 
	<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#schades').dataTable( {
				"bPaginate": true,
				"sPaginationType": "full_numbers",
				"bLengthChange": true,
				"bAutoWidth": true,
				"bFilter": true,
				"bSort": true,
				"aaSorting": [[ 0, "desc" ]],
				"oLanguage": {
					"sLengthMenu": "Toon _MENU_ meldingen per pagina",
					"sZeroRecords": "Niets gevonden",
					"sInfo": "_START_ tot _END_ van _TOTAL_ meldingen",
					"sInfoEmpty": "Er zijn geen meldingen om te tonen",
					"sInfoFiltered": "(gefilterd uit _MAX_ meldingen)",
					"sSearch": "Zoek:",
					"oPaginate": {
						"sFirst":    "Eerste",
						"sPrevious": "Vorige",
						"sNext":     "Volgende",
						"sLast":     "Laatste"
					}
				}
			} );
		} );
	</script> 
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><h1>Schadeboek Boten</h1></p>";
echo "<p><a href='schade_boten_toev.php'>Nieuwe schade melden&gt;&gt;</a><br>";
echo "<a href='../index.php'>Naar BIS&gt;&gt;</a><br>";
echo "<a href='./bis_logout.php'>Uitloggen&gt;&gt;</a></p>";
echo "<p>Lijst van schades die bij de Materiaalcommissie in behandeling zijn:</p>";

$query = "SELECT Datum, Naam, Boot_ID, Oms_lang, Feedback from schades;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van schades mislukt.". mysql_error());
}
echo "<table id='schades' border=\"1\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#AAB8D5\">";
echo "<thead><tr><th><div style=\"text-align:left\">Melddatum (jjjj-mm-dd)</div></th><th><div style=\"text-align:left\">Naam melder</div></th><th><div style=\"text-align:left\">Boot/ergometer</div></th><th width=250><div style=\"text-align:left\">Omschrijving</div></th><th width=250><div style=\"text-align:left\">Terugkoppeling MatCie</div></th></tr></thead><tbody>";
$c = 0;
while ($row = mysql_fetch_assoc($result)) {
	$date = $row['Datum'];
	$name = $row['Naam'];
	// bootnaam
	$boat_id = $row['Boot_ID'];
	if ($boat_id == 0) {
		$boat = "algemeen";
	} else {
		$query2 = "SELECT Naam from boten WHERE ID=$boat_id;";
		$result2 = mysql_query($query2);
		$row2 = mysql_fetch_assoc($result2);
		$boat = $row2['Naam'];
	}
	//
	$note = $row['Oms_lang'];
	if (!$note) $note = "&nbsp;";
	$feedback = $row['Feedback'];
	if (!$feedback) $feedback = "&nbsp;";
	echo "<tr>";
	echo "<td><div style=\"text-align:left\">$date</div></td>";	
	echo "<td><div style=\"text-align:left\">$name</div></td>";
	echo "<td><div style=\"text-align:left\">$boat</div></td>";
	echo "<td><div style=\"text-align:left\">$note</div></td>";
	echo "<td><div style=\"text-align:left\">$feedback</div></td>";
	echo "</tr>";
	$c++;
}
echo "</tbody></table>";

mysql_close($link);

?>
</div>
</body>
</html>