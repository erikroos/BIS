<?php
echo "
<script language=\"Javascript\" type=\"text/javascript\">
<!--
function transform(ext,domain,name) {
var tekst = ext + \".\" + domain + \"@\" + name + \":\" + \"otliam\";
return tekst.split(\"\").reverse().join(\"\");
}
//-->
</script>";

function protect_mail($mail_to_protect, $name_to_show) {
$mail_parts = explode("@", $mail_to_protect);
$name = strrev($mail_parts[0]);
$domain_ext = $mail_parts[1];
$domain_ext_parts = explode(".", $domain_ext, 2);
$ext = strrev($domain_ext_parts[1]);
$domain = strrev($domain_ext_parts[0]);
if ($_GET[location] == "folder_09") {
	$return = "<span style=\"color:#3366CC\" onClick=\"window.open(transform('".$ext."','".$domain."','".$name."')); return false;\" onMouseOver=\"window.status=transform('".$ext."','".$domain."','".$name."'); this.style.textDecoration='underline'; this.style.cursor='pointer'; this.style.color='#CC00CC'; return true;\" onMouseOut=\"window.status=''; this.style.textDecoration='none'; this.style.color='#3366CC'; return true;\">".$name_to_show."</span>"; }
	else {
	$return = "<span style=\"color:#4B6599\" onClick=\"window.open(transform('".$ext."','".$domain."','".$name."')); return false;\" onMouseOver=\"window.status=transform('".$ext."','".$domain."','".$name."'); this.style.textDecoration='underline'; this.style.cursor='pointer'; this.style.color='#6699FF'; return true;\" onMouseOut=\"window.status=''; this.style.textDecoration='none'; this.style.color='#4B6599'; return true;\">".$name_to_show."</span>";
	}
return $return;
}

// echo protect_mail("rob@robypma.nl", "mailadres van Rob");
?>