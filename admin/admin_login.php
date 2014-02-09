<?php
include_once("../include_globalVars.php");

extract ($_REQUEST);
if ((isset($login) && $login == "admin" && isset($password) && $password == $login_admin_admin_wachtwoord) ||
    (isset($login) && $login == "matcie" && isset($password) && $password == $login_admin_matcie_wachtwoord) ||
	(isset($login) && $login == "excie" && isset($password) && $password == $login_admin_excie_wachtwoord) ||
	(isset($login) && $login == "instrcie" && isset($password) && $password == $login_admin_instrcie_wachtwoord) ||
	(isset($login) && $login == "gebcie" && isset($password) && $password == $login_admin_gebcie_wachtwoord)
) {
	session_start();
	$_SESSION['authorized'] = 'yes';
	if ($login == "matcie") {
		$_SESSION['restrict'] = 'matcie';
		header("Location: admin_schade.php");
	} else {
		if ($login == "excie") {
			$_SESSION['restrict'] = 'excie';
			header("Location: admin_examens.php");
		} else {
			if ($login == "instrcie") {
				$_SESSION['restrict'] = 'instrcie';
				header("Location: admin_cursussen.php");
			} else {
				if ($login == "gebcie") {
					$_SESSION['restrict'] = 'gebcie';
					header("Location: admin_schade_gebouw.php");
				} else {
					header("Location: index.php");
				}
			}
		}
	}
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title><?php echo $systeemnaam; ?> - Admin - Login</title>
    <link type="text/css" href="../<?php echo $csslink; ?>" rel="stylesheet" />
</head>
<body>
<div style="margin-left:10px; margin-top:10px">
	<form method="post" action="./admin_login.php">
	<table>
		<tr><td>Login-naam:</td><td><input type="text" name="login" /></td></tr>
		<tr><td>Wachtwoord:</td><td><input type="password" name="password" /></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" value="Inloggen" /></td></tr>
	</table>
	</form>
</div>
</body>
</html>