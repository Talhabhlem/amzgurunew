<?php
require_once ("smtp/email.php");
$email = new amazonEmail();
$email->sendEmailWithAttachment($emailid='sandip5004@gmail.com',$file='email.txt');
?>