<!DOCTYPE html>
 <head>
	<title>
		<?php
			include("resources/text/title.php");
		?>
	</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div id="header">
		<h1>
			<?php
				include("resources/text/heading.php"); 
			?>
		</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("resources/menu/menu.php");
			echo get_login_menu();
			//session_destroy();
		?>
	</div>
	
	
	
	<div id="section">

		<p>Please fill out the form below:  </p>
		<?php
			include("resources/forms/signup.php");
		?>
		
		<p>Note: keep your email and password, as you will not be able to retrieve/change them later.</p>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
	
</body>   
</html>    
