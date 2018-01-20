$( "#ppn-contact-form" ).on( "submit", function( event ) 
{
  	

  	console.log( $( this ).serialize() );

  	if(recaptcha==true)
  	{

      // Step 1: Client side validation 

  		var g_recaptcha_response = $("#g-recaptcha-response").val();

  		if(g_recaptcha_response==="")
  		{
  			$("#ppn-contact-form-recaptcha").fadeIn();
        event.preventDefault();
  			return;
  		}

  	}

    // If you are here.... cheers! Proceed to normal submit...

  	

  	
 });