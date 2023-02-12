<ul>
	<?php 
	$coinsvalue = $_SESSION['EdgeCoins'];
	?>

		<li><a href="index.php">Edgerunner Market</a></li>

	
	
		<li><a href="index.php?action=coins" ><?php echo($coinsvalue) ?></a></li>
		<?php if(!isset($_SESSION['nick'])) {
			echo '
			<li><a href="index.php?action=login" >Login</a></li>
			<li><a href="index.php?action=register">Register</a></li>
			';
		}else{
			echo'
			<li><a href="index.php?action=Shop1">Shop 1</a></li>
			<li><a href="index.php?action=Shop2">Shop 2</a></li>
			<li><a href="index.php?action=Shop3">Shop 3</a></li>
			';
		}?>
</ul>
