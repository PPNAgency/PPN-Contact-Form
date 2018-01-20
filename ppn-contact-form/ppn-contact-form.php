<?php

/*

  ____    ____    _   _                                                               
 |  _ \  |  _ \  | \ | |                                                              
 | |_) | | |_) | |  \| |                                                              
 |  __/  |  __/  | |\  |                                                              
 |_|     |_|     |_| \_|                                                              
                                                                                      
   ____                   _                    _       _____    ___    ____    __  __ 
  / ___|   ___    _ __   | |_    __ _    ___  | |_    |  ___|  / _ \  |  _ \  |  \/  |
 | |      / _ \  | '_ \  | __|  / _` |  / __| | __|   | |_    | | | | | |_) | | |\/| |
 | |___  | (_) | | | | | | |_  | (_| | | (__  | |_    |  _|   | |_| | |  _ <  | |  | |
  \____|  \___/  |_| |_|  \__|  \__,_|  \___|  \__|   |_|      \___/  |_| \_\ |_|  |_|
                                                                                      

by Luca Giusti
For PPN.Agency
Version 0.1
16/01/2018

*/

//



//echo $package_dir;

// Load the ini file
$ini = parse_ini_file("ppn-contact-form/conf.ini",TRUE);
/* echo "<pre>";print_r($conf);echo "<pre>";*/

// Extract the configuration section

$configuration = $ini["configuration"];

$mailer_engine = isset($configuration["mailer_engine"]) ? $configuration["mailer_engine"] : "ppn-mailer.php";

$mailer_engine = "ppn-contact-form/".$mailer_engine;

$method = isset($configuration["method"]) ? $configuration["method"] : "POST";

$ajax_submit = isset($configuration["ajax_submit"]) ? $configuration["ajax_submit"] : false;

$recaptcha = isset($configuration["recaptcha"]) ? $configuration["recaptcha"] : false;


/*echo "<pre>";print_r($configuration);echo "<pre>";*/

$form_html = '';

// Building the form outer tag

// Include Google recaptcha if needed
if($recaptcha)
{
	$form_html.="<script src='https://www.google.com/recaptcha/api.js'></script>";
}


$form_html.="<form id=\"ppn-contact-form\" action=\"$mailer_engine\" method=\"$method\">";

foreach ($ini as $key => $form_field) 
{
	// skip the 'configuration' section and iterate through the field's definitions

	if($key !=="configuration") 
	{
		$type = $form_field["type"];
		$placeholder = isset($form_field["placeholder"]) ? $form_field["placeholder"] : "";
		$required = isset($form_field["required"]) ? $form_field["required"] : false;
		$label = isset($form_field["label"]) ? $form_field["label"] : "";

		$div_extra_class = isset($form_field["div_extra_class"]) ? $form_field["div_extra_class"] : "";

		//$input_div_class = isset($form_field["class"]) ? $form_field["class"] : "";
		//$label_class = isset($form_field["label_class"]) ? $form_field["label_class"] : "";

		//$form_html.=$type;

		switch ($type) 
		{
		 	case "text":
		 	case "email":
		 	case "password":

		 		$form_html .= "<div class=\"form-group\">";

		 		// Check for label

		 		if(!empty($label))
		 		{
		 			$form_html.= "<label class=\"control-label\" ";
		 			$form_html.= "for=\"$key\">$label</label>";
		 		}


		 		$form_html .= "<input type=\"$type\" id=\"$key\" name=\"$key\" placeholder=\"$placeholder\" ";
		 		$form_html .= "class=\"form-control\" ";
		 		if($required) $form_html.="required ";
		 		$form_html .= ">";

		 		$form_html .= "</div>";

		 		break;

		 	case 'textarea':

		 		$rows = isset($form_field["rows"]) ? $form_field["rows"] : 3;

				$form_html .= "<div class=\"form-group\">";

		 		if(!empty($label))
		 		{
		 			// Add label (with classes)

		 			$form_html.= "<label class=\"control-label\" ";
		 			$form_html.= "for=\"$key\">$label</label>";
		 		}

		 		$form_html.="<textarea class=\"form-control\" id=\"$key\" name=\"$key\" rows=\"$rows\"";
		 		if($required) $form_html.=" required></textarea>";
		 		else $form_html.="></textarea>";

		 		$form_html.="</div>";
		 		
		 		break;

		 	case 'checkbox':

		 		$form_html.="<div class=\"form-check\">";

		 		if(!empty($label))
		 		{
		 			// Add label (with classes)
		 			$form_html.= "<label class=\"form-check-label\">";
		 		}

		 		$form_html.="<input type=\"$type\" class=\"form-check-input\"";
		 		if($required) $form_html.=" required";
		 		$form_html.=">";

		 		if(!empty($label))
		 		{
		 			// Add label (with classes)
		 			$form_html.= "$label</label>";
		 		}

		 		$form_html.= "</div>";


		 		break;

		 	case 'recaptcha':

		 		if($recaptcha)
		 		{
		 			$recaptcha_html = "<div class=\"g-recaptcha\" data-sitekey=\"";
		 			$recaptcha_html.= $configuration["recaptcha_site_key"]."\"";
		 			$recaptcha_html.= " style=\"".$configuration["recaptcha_style"]."\"></div>";
		 			
		 			$form_html.=$recaptcha_html;

		 		}


		 		break;

		 	case 'submit':

		 		$form_html.="<div";
		 		if(!empty($div_extra_class)) $form_html.=" class=\"$div_extra_class\"";
		 		$form_html.=">";

		 		$form_html.="<button type=\"$type\" class=\"btn btn-primary\">$label</button>";

		 		$form_html.="</div>";




		 		break;
		 	
		 	default:
		 		# code...
		 		break;
		 } 
	}
}

// Alert generation (only in Ajax Mode)


if($ajax_submit)
{
	// Alert if mails were successfully sent

	$form_html.="<div id=\"ppn-contact-form-success\" class=\"alert alert-success\" role=\"alert\" style=\"display:none;\">";
	$form_html.=$configuration["success_message"];
	$form_html.="</div>";

	// Alert if an error occurs

	$form_html.="<div id=\"ppn-contact-form-error\"class=\"alert alert-danger\" role=\"alert\" style=\"display:none;\">";
	$form_html.=$configuration["error_message"];
	$form_html.="</div>";


}

if($recaptcha)
{
	// Alert if user do not fill recaptcha

	$form_html.="<div id=\"ppn-contact-form-recaptcha-fill\"class=\"alert alert-warning\" role=\"alert\" style=\"display:none;\">";
	$form_html.=$configuration["recaptcha_fill_message"];
	$form_html.="</div>";

	// Alert if recaptcha failed (after server side validation)

	$form_html.="<div id=\"ppn-contact-form-recaptcha-failed\"class=\"alert alert-danger\" role=\"alert\" style=\"display:none;\">";
	$form_html.=$configuration["recaptcha_failed_message"];
	$form_html.="</div>";

}




$form_html.="</form>";


// Ajax handler (if selected)

if($ajax_submit)
{
	$submit_script_file = "ppn-contact-form/js/ajax-submit.js";
}
else
{
	$submit_script_file = "ppn-contact-form/js/submit.js";
}

$script = file_get_contents($submit_script_file);

if($script)
{
	$form_html.="<script type=\"text/javascript\">";

	if($recaptcha) $form_html.="var recaptcha = true;";
	else $form_html.="var recaptcha = false;";

	$form_html.=$script;
	$form_html.="</script>";
}


// At last,  outputs the form

print($form_html);



?>
