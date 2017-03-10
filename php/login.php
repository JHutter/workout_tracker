<?php
	/** log existing user in
	 */
	 
	// citation: http://click4knowledge.com/php-login-script-tutorial.html
	// citation: http://www.codingcage.com/2015/03/simple-login-and-signup-system-with-php.html
	//ini_set('display_errors', 'On');
	include_once("database.php");
	$database = new Database;
	session_start(); // Starting Session
	$email = $_POST['email'];
	$password = $_POST['password'];
	$error = "";
	
	$user_id_query = "SELECT `login`.`user_id` FROM `login` INNER JOIN `users` ON `users`.`user_id` = `login`.`user_id` WHERE `email` = ? AND `password` = ?";
	$params = array($email, $password);
	$cols = array_pad(array(), 1, '');
	$results = $database->db_select($user_id_query, $params, $cols);
	var_dump($results);
	if (count($results) == 0){
		$error = "Invalid email/password combination. Please try again.";
		echo $error;
	}
	else {
		$row = $results[0];
		$user_id = $row[0];
		$_SESSION['date'] = date("m-d-Y");
		$_SESSION['user_id'] = $user_id;
		$_SESSION['email'] = $email;
				
		$name_query = "SELECT `f_name` FROM `users` WHERE `user_id` = ?";
		$params = array($user_id);
		$cols = array_pad(array(), 1, '');
		$name_rows = $database->db_select($name_query, $params, $cols);
		$name_row = $name_rows[0];
		$user_name = $name_row[0];
		
		$_SESSION['user_name'] = $user_name;
		
		//header("Location: profile.php");
		
	}

?>
<script>
    location.replace("profile.php");
</script>
