<form class="account" action="index.php" method="post">
	<h2>Register</h2>
	<label for="nick">Nick:</label>
	<input type="text" name="nick" id="nick" value="" required pattern="[A-Za-z0-9]+">
	<label for="email">Email:</label>
	<input type="email" name="email" id="email" value="" required >
	<label for="password">Password:</label>
	<input type="password" name="password" id="password" value="">
	<input type="submit" name="enter" id="enter" value="Register" />
	<output id ="output" >
		<?php
			if( isset($_SESSION['userMessage'])) echo $_SESSION['userMessage'];
		?>
	</output>
</form>