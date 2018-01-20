$( "#ppn-contact-form" ).on( "submit", function( event ) 
{
  	event.preventDefault();

  	console.log( $( this ).serialize() );


    // reCaptcha validation (optional)

	if(recaptcha==true)
	{
    // Step 1: Client side validation done?

		var g_recaptcha_response = $("#g-recaptcha-response").val();

		if(g_recaptcha_response==="")
		{
			$("#ppn-contact-form-recaptcha-fill").fadeIn();
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
             //console.log("response:"+response);
             if(response.trim()==="1")
             {
                //console.log("true");
                validated = true;
             }
             else
             {
                //console.log("false");
                validated = false;
                $("#ppn-contact-form-recaptcha-failed").fadeIn();
                
             }
         },

         error: function(response)
         {
             validated = false;
         }
       });

      // If validation failed STOP submitting

      if(validated==false) 
      {
        console.log("Recaptcha server validation failed!");
        return;
      }

      console.log("Recaptcha server validation passed!");
      */
	}

	



  var url = $(this).attr("action");
  var fields = $(this).serialize();


	$.ajax({
           type: "POST",
           url: url,
           data: fields, 
           success: function(response)
           {
               console.log("ajax success:"+response);

               if(response.trim()==="1")
               {
               		$("#ppn-contact-form-success").fadeIn();
               }
               else if(response.trim()==="-1")
               {
               		$("#ppn-contact-form-recaptcha-failed").fadeIn();
               }
               else 
               {
                  $("#ppn-contact-form-error").fadeIn();
               }

           },
           error: function(response)
           {
           	   console.log("ajax error:"+response.responseText);
           	   $("#ppn-contact-form-error").fadeIn();
           }
         });

});