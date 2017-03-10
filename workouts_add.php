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
			include_once("resources/menu/menu.php");
			echo get_main_menu();
		?>
	</div>
	
	
	
	<div id="section">

		<h3>My Workouts!</h3>
		
		<?php
			include_once("php/workouts.php");
			$user_id = $_SESSION['user_id'];
			$routine_id = $_POST['routine_id'];
			$date = $_POST[$routine_id.'_date'];
			$notes = $_POST[$routine_id.'_notes'];
			
			if (isset($routine_id)){
				$message = insert_new_workout($user_id, $routine_id, $date, $notes);
				echo $message;
			}
			//header("Location: workouts.php");

		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
        <script>
            location.replace("workouts.php");
        </script>	
</body>   
</html>    
