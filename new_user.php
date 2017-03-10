<?php include_once("php/create_account.php"); ?>

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

		<p>Creating new account. See status below:</p>
		<?php
			//include_once("php/create_account.php");
			
			// citation: http://stackoverflow.com/questions/4366730/check-if-string-contains-specific-words
			if (strpos($message1, 'Added') !== false && strpos($message2, 'Added') !== false) {
				echo "Success! Please continue to the sign in page.";
			}
			else {
				echo "Account creation failure. Please try again with a new email address.";
			}
		?>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php"); ?> 
	</div>
	
</body>   
</html>    
