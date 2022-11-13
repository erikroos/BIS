<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: ./bis_login.php");
	exit();
}

include_once("../include_globalVars.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Documenten voor leden</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">

<h1>Documenten voor leden</h1>
<p><a href='../index.php'>Naar BIS</a><br />
<a href='./bis_logout.php'>Uitloggen</a></p>

<p><em>Alle documenten zijn in het PDF-formaat en openen in een nieuw venster.</em></p>

    <!-- TODO: iterate through 'documenten' folder and use listing here. Add .htaccess to prevent unauthorized access. -->

<strong>Voorbeeld</strong>
<ul>
	<li><a href='test1.pdf' target='_blank'>Document 1</a></li>
    <li><a href='test2.pdf' target='_blank'>Document 2</a></li>
</ul>

</div>
</body>
</html>
