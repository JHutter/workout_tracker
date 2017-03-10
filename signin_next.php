<?php include_once("php/login.php"); ?>

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
		?>
	</div>
	
	
	
	<div id="section">

		<p>Logging in...</p>
		<?php
			//include_once("php/login.php");
		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
	
</body>   
</html>    
