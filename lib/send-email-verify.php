<?php

function sendEmailVer($nameVer, $emailVer, $codeVer){

	$to = $emailVer;
	$subject = "Verify your account";

	$message = $nameVer.",\n\nClick here to verify your email address:\nhttp://www.studevs.com/verify-email.php?".$codeVer."\n\nThank you for signing up with StuDevs.";

	// More headers
	$headers .= 'From: <support@studevs.com>' . "\r\n";

	return mail($to,$subject,$message,$headers);

	
}
