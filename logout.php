<?php
	if (!isset($_SESSION)){
		session_start();
	}
	//if (isset($_SESSION)){
		session_destroy();
	//}
	//header("Location: index.php");
?>
<script>
	location.replace("index.php");
</script>
