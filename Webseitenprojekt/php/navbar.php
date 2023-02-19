<nav>
	<div class="HomepageLink">
		<!-- Hier wird die action noShop in playerInventory.php aufgerufen -->
		<a href="index.php?action=noShop" id="HomepageName">Edgerunner Market</a>	
	</div>
	
	<div>
		<!-- Hier werden die aktuellen Coins angezeigt die action coins führt zu  -->
		<a href="index.php?action=coins" ><?php  echo($coinsvalue); ?></a>
		
		<?php if(!isset($_SESSION['nick'])) {
			// Wenn der spieler noch nicht angemeldet ist wird ihm hier die möglichkeit gegeben sich 
			//anzumelden oder zu regestrieren die Actions führen zu index.php
			echo '
			<a href="index.php?action=login" >Login</a>
			<a href="index.php?action=register">Register</a>
			';
		}else{
			// Hier wird die action für Shop 1,2,3 in playerInventory.php aufgerufen 
			echo'
			<a href="index.php?action=Shop1">Waffen</a>
			<a href="index.php?action=Shop2">Schutz</a>
			<a href="index.php?action=Shop3">Sonstiges</a>
			';
		}?>
	</div>
</nav>
<?php 
	$coinsvalue = $_SESSION['EdgeCoins'];
?>