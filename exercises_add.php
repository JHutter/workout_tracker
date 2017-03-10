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

		<h3>New Exercise</h3>
		
		<?php
			include_once("php/exercises.php");
			$exercise_name = $_POST['new_exercise'];
			$exercise_name_modifier = $_POST['new_exercise_modifier'];
			$goals = array();
			foreach ($_POST['goal_ids'] as $goal_id){
				$goals[] = $goal_id;
			}
			
			$message = insert_new_exercise($exercise_name, $exercise_name_modifier);
			if (count($goals) > 0){
				insert_new_exercise_goals($exercise_name, $exercise_name_modifier, $goals);
			}
			
			//header("Location: exercises.php");
		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
        <script>
            location.replace("exercises.php");
        </script>	
</body>   
</html>    
