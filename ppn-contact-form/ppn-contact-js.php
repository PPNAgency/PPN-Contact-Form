<?php

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
		$form_js = "<script type=\"text/javascript\">";

		if($recaptcha) $form_js.="var recaptcha = true;";
		else $form_js.="var recaptcha = false;";

		$form_js.=$script;
		$form_js.= "</script>";
	}

	print $form_js;

?>