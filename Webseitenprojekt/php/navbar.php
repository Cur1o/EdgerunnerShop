<?php 
	$coinsvalue = $_SESSION['EdgeCoins'];
?>
<nav>
	<div class="HomepageLink">
		<a href="index.php?action=noShop" id="HomepageName">Edgerunner Market</a>
	</div>
	
	<div>
		<a href="index.php?action=coins" ><?php echo($coinsvalue) ?></a>
		<?php if(!isset($_SESSION['nick'])) {
			echo '
			<a href="index.php?action=login" >Login</a>
			<a href="index.php?action=register">Register</a>
			';
		}else{
			echo'
			<a href="index.php?action=Shop1">Shop 1</a>
			<a href="index.php?action=Shop2">Shop 2</a>
			<a href="index.php?action=Shop3">Shop 3</a>
			';
		}?>
	</div>
</nav>
<?php 
	$coinsvalue = $_SESSION['EdgeCoins'];
?>