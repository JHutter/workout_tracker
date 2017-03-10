<?php
function get_login_menu(){
	$menu = '<a href="signin.php" class="button">
    Sign In    
	</a>
	<a href="signup.php" class="button">
    Sign Up    
	</a>';
	return $menu;
}

function get_login_widget_div() {	
		$widget_text = "<div id='widget'><strong>Welcome! </strong>";
		$widget_text .= "<br><strong>Name: </strong>{$_SESSION['user_name']}";
		$widget_text .= "<br><strong>Date: </strong>{$_SESSION['date']}";
		//$widget_text .= "<br><Br><div style='text-align: center; margin-left:0px; margin-right:15px;'><a href=../php/logout.php class=button style=min-width:100%;>    Logout     </a></div> ";
		$widget_text .= "</div>";
		
		return $widget_text;
	}

function get_main_menu(){
	$menu = get_login_widget_div();
	$menu .= '<a href="goals.php" class="button">
    Goals    
	</a>
	<a href="exercises.php" class="button">
    Exercises    
	</a>
	<a href="routines.php" class="button">
    Routines    
	</a>
	<a href="workouts.php" class="button">
    My Workouts    
	</a>
	</a>
	<a href="logout.php" class="button">
    Logout    
	</a>
	';
	return $menu;
}


?>
