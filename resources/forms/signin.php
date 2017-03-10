
<div id="login">
	<form action="signin_next.php" method="post" >
		<table class="plain_no_hover">
			<tr class="plain_no_hover">
				<td>Email: </td>
				<td><input type="email" name="email" required ></td>
			</tr>
			<tr class="plain_no_hover">
				<td>Password:</td>
				<td><input type="password" name="password" required></td>
			</tr>
			</table>
		<input type="submit" value="Sign In" class="button">
	</form>
	<script>var isError = '<?php echo $error; ?>'; if (isError != "") {alert('Invalid password or username. Please try again.');}</script>
</div>