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
    <title>BotenInschrijfSysteem - Klachtenboek Boten</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
	<!-- Datatables -->
	<style type="text/css" title="currentStyle"> 
		@import "../scripts/datatables/demo_page.css";
		@import "../scripts/datatables/demo_table.css";
	</style> 
	<script type="text/javascript" language="javascript" src="../scripts/datatables/jquery.js"></script> 
	<script type="text/javascript" language="javascript" src="../scripts/datatables/jquery.dataTables.js"></script> 
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<?php

echo "<p><h1>Klachtenboek Boten</h1></p>";
echo "<p><a href='schade_boten_toev.php'>Nieuwe klacht/schademelding&gt;&gt;</a><br>";
echo "<a href='../index.php'>Naar BIS&gt;&gt;</a><br>";
echo "<a href='./bis_logout.php'>Uitloggen&gt;&gt;</a></p>";
echo "<p>Lijst van klachten die bij de Materiaalcommissie in behandeling zijn:</p>";

$query = "SELECT Datum, Naam, Boot_ID, Oms_lang, Feedback from schades;";
$result = mysql_query($query);
if (!$result) {
	die("Ophalen van klachten mislukt.". mysql_error());
}
echo "<div style='width:700px'><table id='schades'>";
echo "<thead><tr><th>Melddatum (jjjj-mm-dd)</th><th>Naam melder</th><th>Boot/ergometer</th><th><div>Omschrijving</th><th>Terugkoppeling MatCie</th></tr></thead><tbody>";
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
	echo "<td>$date</td>";	
	echo "<td>$name</td>";
	echo "<td>$boat</td>";
	echo "<td>$note</td>";
	echo "<td>$feedback</td>";
	echo "</tr>";
	$c++;
}
echo "</tbody></table></div>";

mysql_close($link);

?>
</div>
</body>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	$('#schades').dataTable( {
		"bPaginate": true,
		"sPaginationType": "full_numbers",
		"bLengthChange": true,
		"bAutoWidth": false,
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
		},
		"aoColumns" : [
			{"sWidth": '100px'},
			{"sWidth": '100px'},
			{"sWidth": '100px'},
			{"sWidth": '250px'},
			{"sWidth": '150px'}
		]
	} );
} );
</script> 

</html>