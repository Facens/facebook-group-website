<?php
// Enter your email address below
$emailAddress = 'info@startupscene.it';

// Using session to prevent flooding:

session_name('quickFeedback');
session_start();

// If the last form submit was less than 10 seconds ago,
// or the user has already sent 10 messages in the last hour

if(	$_SESSION['lastSubmit'] && ( time() - $_SESSION['lastSubmit'] < 10 || $_SESSION['submitsLastHour'][date('d-m-Y-H')] > 10 )){
	die('Aspetta qualche minuto prima di rimandare un msg.');
}

$_SESSION['lastSubmit'] = time();
$_SESSION['submitsLastHour'][date('d-m-Y-H')]++;

require "script/class.phpmailer.php";

if(ini_get('magic_quotes_gpc')){
    // If magic quotes are enabled, strip them
    $_POST['message'] = stripslashes($_POST['message']);
}

if(mb_strlen($_POST['message'],'utf-8') < 5){
    die('Messaggio troppo corto.');
}

$msg = nl2br(strip_tags($_POST['message']));

// Using the PHPMailer class

$mail = new PHPMailer();
$mail->IsMail();

// Adding the receiving email address
$mail->AddAddress($emailAddress);

$mail->Subject = 'Feedback da ISS';
$mail->MsgHTML($msg);

$mail->AddReplyTo('noreply@'.$_SERVER['HTTP_HOST'], 'Quick Feedback Form');
$mail->SetFrom('noreply@'.$_SERVER['HTTP_HOST'], 'Quick Feedback Form');

$mail->Send();

echo 'Grazie!';

?>