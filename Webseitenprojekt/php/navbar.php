<?php 
	$coinsvalue = $_SESSION['EdgeCoins'];
?>
<nav>
	<div class="HomepageLink">
		<a href="index.php" name="HomepageName">Edgerunner Market</a>
	</div>
	
	<div>
		<img src="images/EdgeCoin3.svg" type="image/svg" alt="Coins" class="coinLogo">
		<a href="index.php?action=coins" ><?php echo($coinsvalue) ?></a>
		<a href="index.php?action=login" >Login</a>
		<a href="index.php?action=register" >Register</a>
	</div>
</nav>