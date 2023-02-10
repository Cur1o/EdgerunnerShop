<form class="account" action="index.php" method="post">
	<h2>Login</h2>
	<label for="nick">Nick:</label>
	<input type="text" name="nick" id="nick" value="" required>   
	<label for="password">Password:</label>
	<input type="password" name="password" id="password" value="" required>   
	<input type="submit" name="enter" id="enter" value="Login" />
	<output >
		<?php
			if(isset($_SESSION['userMessage'])) echo $_SESSION['userMessage'];
		?>
	</output>
</form>