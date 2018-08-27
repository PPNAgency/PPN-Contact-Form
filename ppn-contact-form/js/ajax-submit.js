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