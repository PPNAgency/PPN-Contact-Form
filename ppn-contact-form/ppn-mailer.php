<?php 


include('./PHPMailer/PHPMailer.php');
include('./PHPMailer/SMTP.php');



$ini = parse_ini_file("conf.ini",TRUE);
$configuration = $ini["configuration"];

$ajax_submit = isset($configuration["ajax_submit"]) ? $configuration["ajax_submit"] : false;

// reCaptcha server side validation

$recaptcha = isset($configuration["recaptcha"]) ? $configuration["recaptcha"] : false;

if($recaptcha)
{
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
			if($response->success==false)
			{
				// Google API says reCaptcha fails...
				if($ajax_submit)
				{
					echo -1;
				}	
				else
				{
					header("location: ".$configuration["recaptcha_error_page"]);
				}
				die();
			}
		}
		else
		{
			// Recaptcha secret is not specified... or
			// Recaptcha user verification token is not specified... 
			// Mailing must be aborted....

			if($ajax_submit)
			{
				echo -1;
			}	
			else
			{
				header("location: ".$configuration["recaptcha_error_page"]);
			}
			die();
		}
}

function templateTagResolver($templateString)
{
	global $ini;

	// Form tag substitution
	foreach ($ini as $key => $form_field) 
	{
		if($key !=="configuration") 
		{
			if(isset($_POST[$key]))
				$templateString = str_replace("{{".$key."}}", $_POST[$key], $templateString);
		}


	}

	// Tag speciali
	$date = date('j/n/Y');
	$time = date('h:i A');
	$templateString = str_replace("{{date}}", $date, $templateString);
	$templateString = str_replace("{{time}}", $time, $templateString);



	return $templateString;
}

$mailer = new PHPMailer();

$mailer->CharSet = "UTF-8";

if(isset($configuration["smtp"]) && $configuration["smtp"] == true)
{
	$mailer->IsSMTP();  // telling the class to use SMTP
	$mailer->Host     = $configuration["smtp_host"];
	$mailer->SMTPAuth = true;
	$mailer->Username = $configuration["smtp_user"];
	$mailer->Password = $configuration["smtp_password"];
}

$mailer->isHTML(true);
$mailer->setFrom($configuration["from_email"],$configuration["from_name"]);

/*


   ____                   _                    _   
  / ___|   ___    _ __   | |_    __ _    ___  | |_ 
 | |      / _ \  | '_ \  | __|  / _` |  / __| | __|
 | |___  | (_) | | | | | | |_  | (_| | | (__  | |_ 
  \____|  \___/  |_| |_|  \__|_ \__,_|  \___|  \__|
 | ____|  _ __ ___     __ _  (_) | |               
 |  _|   | '_ ` _ \   / _` | | | | |               
 | |___  | | | | | | | (_| | | | | |               
 |_____| |_| |_| |_|  \__,_| |_| |_|               
                                                   


*/

// Phase 1: Send the contact email to the Customer Service

if(isset($_POST["otheremailaddress"]))
{
	$mailer->addAddress($_POST["otheremailaddress"],$_POST["othernames"]);
}
else
{	
	foreach ($configuration["destination_email"] as $key => $value) 
	{
		$mailer->addAddress($value,$configuration["destination_name"][$key]);
	}
}

// Check for CC & BCC recipient 

foreach ($configuration["cc_email"] as $key => $value) 
{
	$mailer->addCC($value,$configuration["cc_name"][$key]);
}

foreach ($configuration["bcc_email"] as $key => $value) 
{
	$mailer->addBCC($value,$configuration["bcc_name"][$key]);
}

// Phase 2: Read the email template from file

$email_template_file = "./templates/".$configuration["email_template"];
$email_template = file_get_contents($email_template_file);

if($email_template===FALSE)
{
	if($ajax_submit)
	{
		echo 0;
	}	
	else
	{
		header("location: ".$configuration["error_page"]);
	}

	die();
}

$email_body = templateTagResolver($email_template);
$email_subject = templateTagResolver($configuration["email_subject"]);

//echo $email_body;
//echo $email_subject;

$mailer->Body= $email_body;
$mailer->Subject = $email_subject;

//$mailer->SMTPDebug = 2;

/*
echo "<pre>";
print_r($mailer);
echo "</pre>";
*/

$sendResult = $mailer->Send();

if(!$sendResult)
{
	if($ajax_submit)
	{
		echo 0;
	}	
	else
	{
		header("location: ".$configuration["error_page"]);
	}
	die();
}


/*


  _   _           _     _    __   _                  _     _                   
 | \ | |   ___   | |_  (_)  / _| (_)   ___    __ _  | |_  (_)   ___    _ __    
 |  \| |  / _ \  | __| | | | |_  | |  / __|  / _` | | __| | |  / _ \  | '_ \   
 | |\  | | (_) | | |_  | | |  _| | | | (__  | (_| | | |_  | | | (_) | | | | |  
 |_|_\_|  \___/   \__| |_| |_|_  |_|  \___|  \__,_|  \__| |_|  \___/  |_| |_|  
 | ____|  _ __ ___     __ _  (_) | |                                           
 |  _|   | '_ ` _ \   / _` | | | | |                                           
 | |___  | | | | | | | (_| | | | | |                                           
 |_____| |_| |_| |_|  \__,_| |_| |_|                                           
                                                                               

*/


if( isset($configuration["user_email_template"]) && isset($_POST["email"]))
{

	$user_email_template_file = "./templates/".$configuration["user_email_template"];
	$user_email_template = file_get_contents($user_email_template_file);

	if($user_email_template===FALSE)
	{
		if($ajax_submit)
		{
			echo 0;
		}	
		else
		{
			header("location: ".$configuration["error_page"]);
		}
		die();
	}

	$email_body = templateTagResolver($user_email_template);
	$email_subject = templateTagResolver($configuration["user_email_subject"]);

	$mailer->Body= $email_body;
	$mailer->Subject = $email_subject;

	$mailer->clearAllRecipients();
	$mailer->addAddress($_POST["email"],"");



	$sendResult = $mailer->Send();

	if(!$sendResult)
	{
		if($ajax_submit)
		{
			echo 0;
		}	
		else
		{
			header("location: ".$configuration["error_page"]);
		}		
		die();
	}

}

/*
   ____                               _          _            
  / ___|   ___    _ __ ___    _ __   | |   ___  | |_    ___   
 | |      / _ \  | '_ ` _ \  | '_ \  | |  / _ \ | __|  / _ \  
 | |___  | (_) | | | | | | | | |_) | | | |  __/ | |_  |  __/  
  \____|  \___/  |_| |_| |_| | .__/  |_|  \___|  \__|  \___|  
                             |_|                              
*/

if($ajax_submit)
{
	echo 1;
}
else
{                             
	header("location: ".$configuration["success_page"]);
}
?>

