<?php include_once("php/session.php"); ?>
<!DOCTYPE html>
 <head>
	<title>
		<?php
			include("resources/text/title.php");
			//include_once("php/session.php");
			//ini_set('display_errors', 'On');
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

		<h3>Add/Edit Goals!</h3>
		<?php
			include_once("php/goals.php");
			$goals = get_table_add_goals($_SESSION['user_id']);
			echo($goals);
		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
	
</body>   
</html>    
