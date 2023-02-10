<?php 
	$coinsvalue = $_SESSION['EdgeCoins'];
?>
<nav>
	<div class="HomepageLink">
		<a href="index.php" id="HomepageName">Edgerunner Market</a>
	</div>
	
	<div>
		<a href="index.php?action=coins" ><?php echo($coinsvalue) ?></a>
		<?php if(!isset($_SESSION['nick'])) {
			echo '
			<a href="index.php?action=login" >Login</a>
			<a href="index.php?action=register">Register</a>
			';
		}?>
	</div>
</nav>