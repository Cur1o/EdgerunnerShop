	<h2>Admin</h2>
	<label for="userList">User:</label>
	<div id="userList"></div>

	<label for="productList">Produkt Id:</label>
	<div id="productList"></div>

	<form id="product"  action="index.php" method="post" enctype="multipart/form-data">
		<h3>Produkt hizufügen</h3>
		<label for="productId">Produkt Id:</label>
		<input type="text" name="productId" id="productId" value="" required>

		<label for="isConsumeable">Is Consumeable:</label>
		<input type="checkbox" name="isConsumeable" id="isConsumeable">

		<label for="name">Produktname:</label>
		<input name="name" id="name" value="" required>

		<label for="description">Beschreibung:</label>
		<textarea name="description" id="description" value="" required></textarea>

		<label for="file" >Produktbild (optional)- 512 x 512, max. 250kB </label>
		<input type="file" id="file" accept="image/png, image/jpg" name="file">


		<label for="price">Preis:</label>
		<input name="price" id="price" value="" required>

		<input type="submit" name="enter" value="Neues Produkt hinzufügen" />
	</form>
	<button id="addProductButton" onclick="addProduct();" >Änderung abbrechen</button>
	<output >
		<?php
			if( isset($_SESSION['userMessage'])) echo $_SESSION['userMessage'];
		?>
	</output>
