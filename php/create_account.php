<?php
	/** create new user account
	 */
	
	//ini_set('display_errors', 'On');
	include_once("database.php");
	$f_name = "";
	$l_name = "";
	$email = "";
	$password = "";
	
	$f_name = $_POST['f_name'];
	$l_name = $_POST['l_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	$user_params = array($email, $f_name, $l_name);
	$database = new Database;

	$new_user_query = "INSERT INTO `users`(`email`, `f_name`, `l_name`) VALUES (?, ?, ?)";
	$table = "`users`";
	$message1 = $database->db_insert($new_user_query, $user_params, $table);
	
	// I didn't hash the password...
	$insert_password_query = "INSERT INTO `login` (`user_id`, `password`) SELECT `user_id`, ? FROM `users` WHERE `email` = ?";
	$params = array($password, $email);
	$table = "`login'";
	$message2 = $database->db_insert($insert_password_query, $params, $table);


?>
