<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include.php");
include_once("../mail.php");
setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Cursussen</title>
    <link type="text/css" href="../<? echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<p><h1>Cursussen</h1></p>
<p><a href='../index.php'>Naar BIS&gt;&gt;</a><br />
<a href='./bis_logout.php'>Uitloggen&gt;&gt;</a></p>

<p><strong>Mededelingen</strong><br />
De inschrijving sluit &eacute;&eacute;n week voorafgaand aan de cursus.</p>

<p><strong>Kies een cursus:</strong></p>
<select name="course" id="course" onchange='ChangeInfo();'>
<option value=0 selected="selected">&nbsp;</option>
<?php
$query = "SELECT ID, Startdatum, Type, ToonOpSite FROM cursussen WHERE Startdatum>'$today_db' AND ToonOpSite=1 ORDER BY Startdatum;";
$result = mysql_query($query);
if (!$result) {
	echo("Ophalen van cursusdata mislukt.".mysql_error());
} else {
	$rows_aff = mysql_affected_rows($link);
	if ($rows_aff > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$id = $row['ID'];
			$type = $row['Type'];
			$exstartdate = $row['Startdatum'];
			$exstartdate_sh = strtotime($exstartdate);
			echo "<option value='$id'>".$type." beginnend op ".strftime('%A %d-%m-%Y', $exstartdate_sh)."</option>";
		}
	}
}
?>
</select>

<div id="courselist"></div>

</div>

<script type="text/javascript">
	
	// Get the HTTP Object
	function getHTTPObject(){
	  	if (window.ActiveXObject)  {
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		else if (window.XMLHttpRequest)  {
			return new XMLHttpRequest();
		}
		else {
			alert("Uw browser ondersteunt geen Ajax, wat voor de werking van BIS vereist is.");
			return null;
		}
	}
	
	function ChangeInfo(){
		httpObject = getHTTPObject();
		if (httpObject != null) {
			httpObject.open("GET", "show_course.php?id="+document.getElementById("course").value, true);
			httpObject.onreadystatechange = setOutput;
			httpObject.send(null);
		}
	}
	
	function setOutput(){
		if (httpObject.readyState == 4 && httpObject.status == 200) {
			var course_info = document.getElementById("courselist");
			course_info.innerHTML = httpObject.responseText;
		}
	}
</script>

</body>
</html>