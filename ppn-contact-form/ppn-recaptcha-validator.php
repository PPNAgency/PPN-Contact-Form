<?php 

$ini = parse_ini_file("conf.ini",TRUE);
$configuration = $ini["configuration"];

$recaptcha_secret = isset($configuration["recaptcha_secret"]) ? $configuration["recaptcha_secret"] : "";
$g_recaptcha_response = isset($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : "";

if(($recaptcha_secret!="") && ($g_recaptcha_response!=""))
{
	$query_url = "https://www.google.com/recaptcha/api/siteverify?secret=";
	$query_url.= $recaptcha_secret;
	$query_url.= "&response=";
	$query_url.= $g_recaptcha_response;
	$query_url.= "&remoteip=";
	$query_url.= $_SERVER['REMOTE_ADDR'];

	// Call Google API
	$response = file_get_contents($query_url);

	$response = json_decode($response);
	if($response->success==true)
	{
		// Google API says reCaptcha is ok
		echo 1;
		die();
	}
	else
	{
		// Google API says reCaptcha fails...
		echo 0;
		die();
	}
}
else
{
	// Recaptcha secret is not specified... or
	// Recaptcha user verification token is not specified... 
	// Validation aborted....

	echo 0;
	die();
}

?>
