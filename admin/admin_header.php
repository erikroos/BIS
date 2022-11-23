<?php
// check login
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes') {
	header("Location: admin_login.php");
	exit();
}

include_once('../include_globalVars.php');
include_once('../include_boardMembers.php');
include_once('../include_helperMethods.php');

$link = getDbLink($database_host, $database_user, $database_pass, $database);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $systeemnaam; ?> - Admin - <?php echo $locationHeader; ?></title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
    <link type="text/css" href="../css/bis.css" rel="stylesheet" />
    <script type="text/javascript" src="../scripts/kalender.js"></script>
    <script type="text/javascript" src="../scripts/sortable.js"></script>
</head>
<body>
<div class="maindiv">

<p>
	<strong>Welkom in de Admin-sectie van BIS</strong>
	<?php if (isset($backLink)): ?>
		[<?php echo $backLink; ?>]
	<?php endif; ?>
	[<a href='./admin_logout.php'>Uitloggen</a>]
</p>
