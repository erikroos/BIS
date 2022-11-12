<?php
// check login
session_start();
if (!isset($_SESSION['authorized_bis']) || $_SESSION['authorized_bis'] != 'yes') {
	header("Location: bis_login.php");
	exit();
}

include_once("../include_globalVars.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Uitloggen</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
    <link type="text/css" href="../css/bis.css" rel="stylesheet" />
</head>
<body>

<?php unset($_SESSION['authorized_bis']); ?>

<div class="middle_box">
<p><strong>U bent uitgelogd - tot ziens!</strong></p>
<p><a href='index.php'>Klik hier om opnieuw in te loggen&gt;&gt;</a><br>
<a href='<?php echo $homepage; ?>'>Klik hier om naar <?php echo $homepagenaam; ?> te gaan&gt;&gt;</a></p>
</div>

</body>
</html>
