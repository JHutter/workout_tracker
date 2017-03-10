<?php include_once("php/session.php");?>
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

		<h3>Add/Edit Goals!</h3>
		<?php
			ini_set('display_errors', 'On');
			include_once("php/goals.php");
			//$goals = get_table_add_goals($_SESSION['user_id']);
			//echo($goals);
			$goal_id = $_POST['goal_id'];
			if ($goal_id == -1){
				echo("<p>Add new goal to goals and user goals</p>");
				$goal_name = $_POST['goal_name'];
				$user_id = $_SESSION['user_id'];
				$progress = $_POST['progress'];
				$message = insert_goal($goal_name, $user_id, $progress);
				echo($message);
			}
			else {
				$progress = $_POST[$goal_id.'_progress'];
				add_or_edit_my_goals($user_id, $goal_id, $progress);
			}
			//header("Location: goals.php");
		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
        <script>
            location.replace("goals.php");
        </script>	
</body>   
</html>    
