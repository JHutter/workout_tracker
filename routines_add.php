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
			include_once("resources/menu/menu.php");
			echo get_main_menu();
		?>
	</div>
	
	
	
	<div id="section">

		<h3>New Routine</h3>
		
		<?php
			include_once("php/routines.php");
			$routine_name = $_POST['new_routine'];
			$exercises = array();
			foreach ($_POST['exercise_ids'] as $exercise_id){
				$exercises[] = $exercise_id;
			}
			
			$message = insert_new_routine($routine_name);
			if (count($exercises) > 0){
				$results = insert_new_routine_exercises($routine_name, $exercises);
			}
			
			//header("Location: routines.php");
		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
        <script>
            location.replace("routines.php");
        </script>	
</body>   
</html>    
