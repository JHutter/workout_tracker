<?php
	/** check that a session has been created by signing in. if not, redirect to index
	 */
	// citation: http://click4knowledge.com/php-login-script-tutorial.html
	// citation: http://www.codingcage.com/2015/03/simple-login-and-signup-system-with-php.html
	session_start();
	$user_id = $_SESSION['user_id'];

	if(!isset($_SESSION['user_id']))
	{
		session_destroy();
		header("Location: index.php");
	}
?>