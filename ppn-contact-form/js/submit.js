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

      // Step 2: Server side validation (synchronous-JAX)
      // INSECURE (TO REMOVE)
      /*

      var validated = false;

      $.ajax({
           type: "POST",
           async: false,
           url: "ppn-contact-form/ppn-recaptcha-validator.php",
           data: {'g-recaptcha-response': g_recaptcha_response}, 

           success: function(response)
           {
               if(response.trim()==="1")
               {
                  validated = true;
               }
               else
               {
                  validated = false;
                  $("#ppn-contact-form-recaptcha-failed").fadeIn();
                  
               }
           },

           error: function(response)
           {
               validated = false;
           }
         });

        // If validation failed STOP submitting (preventing the default event handling)

        if(validated==false) 
        {
          event.preventDefault();
          return;
        }
        */

  	}

    // If you are here.... cheers! Proceed to normal submit...

  	

  	
 });