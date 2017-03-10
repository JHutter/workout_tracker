<?php include_once("php/session.php"); ?>
<!DOCTYPE html>
 <head>
	<title>
		<?php
			include("resources/text/title.php");
			//include_once("php/session.php");
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
			echo get_main_menu();
		?>
	</div>
	
	
	
	<div id="section">

		<h3>Success!</h3>
		<p>Thanks for signing in. Choose a menu option above.  </p>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
	
</body>   
</html>    
