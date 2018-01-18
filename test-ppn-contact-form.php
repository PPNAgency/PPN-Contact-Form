<html>
<head>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>


</head>
<body>

<div class="container">

<div class="row">
	<div class="col">
			<h2>Contact Us</h2>
	</div>
</div


<div class="col-md-6 mx-auto">

<?php

include("ppn-contact-form/ppn-contact-form.php");

?>

</div>


</body>
</html>


<!--

<div class="col-md-6 mx-auto">
		<form action="mail.php" method="POST">
		  <div class="form-group">
		    <input type="name" class="form-control" id="name" aria-describedby="name" placeholder="Name">
		  </div>
		   <div class="form-group">
		    <input type="surname" class="form-control" id="surname" aria-describedby="surname" placeholder="Surname">
		  </div>
		  <div class="form-group">
		    <input type="e-mail" class="form-control" id="e-mail" aria-describedby="e-mail" placeholder="E-mail">
		  </div>
		  <div class="form-group">
		    <textarea class="form-control" id="message" rows="3" placeholder="Message"></textarea>
		  </div>
		  <div class="form-check">
		    <label class="form-check-label">
		      <input type="checkbox" class="form-check-input">
		      By sending this message, I authorise the use of my personal data (In compliance with AoL 196/03 and successive notifications)
		    </label>
		  </div>
		  <div class="text-center">
		  <button type="send" class="btn btn-primary">Send</button>
		  </div>
		</form>
	</div>
-->
