<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../mail.php");
include_once("../include_globalVars.php");
include_once("../include_helperMethods.php");

$link = mysql_connect($database_host, $database_user, $database_pass);
if (!mysql_select_db($database, $link)) {
	die('Fout: database niet gevonden.');
}

setlocale(LC_TIME, 'nl_NL');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>BotenInschrijfSysteem - Cursussen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<p><h1>Cursussen</h1></p>
<p><a href='../index.php'>Naar BIS&gt;&gt;</a><br />
<a href='bis_logout.php'>Uitloggen&gt;&gt;</a></p>
	
<?php
$query = "SELECT ID, Startdatum, Type, ToonOpSite FROM cursussen WHERE Startdatum>'$today_db' AND ToonOpSite=1 ORDER BY Startdatum;";
$result = mysql_query($query);
if (!$result) {
	die('Ophalen van cursusdata mislukt: ' . mysql_error());
} 
$rows_aff = mysql_affected_rows($link);
if ($rows_aff > 0) { ?>
	<p><strong>Kies een cursus:</strong></p>
	<select name="course" id="course" onchange='changeInfo();'>
	<option value="0" selected="selected">&nbsp;</option>
	<?php while ($row = mysql_fetch_assoc($result)) {
		$id = $row['ID'];
		$type = $row['Type'];
		$exstartdate = $row['Startdatum'];
		$exstartdate_sh = strtotime($exstartdate);
		echo "<option value='$id'>".$type." beginnend op ".strftime('%A %d-%m-%Y', $exstartdate_sh)."</option>";
	}
} else { ?>
	<p>Er zijn op dit moment geen cursussen waarvoor u zich kunt inschrijven.</p>
<?php }
mysql_close($link);
?>
</select>

<div id="courselist"></div>

<p><strong>Mededelingen</strong></p>
<ul>
	<li>De inschrijving sluit &eacute;&eacute;n week voorafgaand aan de cursus.</li>
	<li>Opgave betekent deelname.</li>
	<li>U kunt zich tot uiterlijk een week voor de start van de cursus terugtrekken. Stuur daarvoor een e-mail aan <a href="mailto:instructie@hunze.nl">instructie@hunze.nl</a>.
</ul>

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
	
	function changeInfo(){
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