<main>
	<?php
	function addProduct( $productId, $name, $description, $price, $consumeable){
		if(!isAuthorizedAsAdmin())
			return;

		$image = uploadProductImage($productId);
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php	
		try{
			if($image!=null)
				$query = $conn->prepare('INSERT INTO products( productId, name, description, image, price, isConsumeable ) VALUES('.$productId.',"'.$name.'","'.$description.'",'.$image.','.$price.','.$consumeable.');');
			else
				$query = $conn->prepare('INSERT INTO products( productId, name, description, price, isConsumeable ) VALUES('.$productId.',"'.$name.'","'.$description.'",'.$price.','.$consumeable.');');
			$query->execute();
			userMessage("Produkt: ".$productId." erfolgreich angelegt");
		}
		catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}

	function changeProduct( $productId, $name, $description, $price, $consumeable ){
		if(!isAuthorizedAsAdmin())
			return;

		$image = uploadProductImage($productId);
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php	
		try{
			if($image!=null)
				$query = $conn->prepare('UPDATE products SET name = "'.$name.'", description = "'.$description.'", image = '.$image.', price = '.$price.', isConsumeable = '.$consumeable.' WHERE productId = '.$productId.';');
			else
				$query = $conn->prepare('UPDATE products SET name = "'.$name.'", description = "'.$description.'",  price = '.$price.', isConsumeable = '.$consumeable.' WHERE productId = '.$productId.';');
			$query->execute();
			userMessage("Produkt: ".$productId." erfolgreich geändert");
		}catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}



	function deleteUser($nick){
		if(!isAuthorizedAsAdmin())
			return;
	
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
		try{
			$query = $conn->prepare('DELETE FROM users WHERE nick = "'.$nick.'";');
			$query->execute();
			userMessage("User ".$nick." gelöscht");
		}catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}

	function deleteProduct($productId)
	{
		if(!isAuthorizedAsAdmin())
			return;
	
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
		try{
			$query = $conn->prepare('DELETE FROM products WHERE productId = '.$productId.';');
			$query->execute();
			userMessage("Produkt: ".$productId." wurde gelöscht");
		}catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}

	function setUserAccess($nick, $access)
	{
		if(!isAuthorizedAsAdmin())
			return;
	
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
		try{
			$query = $conn->prepare('UPDATE users SET access = "'.$access.'" WHERE nick = "'.$nick.'";');
			$query->execute();

			userMessage("User ".$nick." Zugang: $access");
		}
		catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}

	function getUserList()
	{
		if(!isAuthorizedAsAdmin())
			return;
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php

		try{
			$query = $conn->prepare('SELECT nick, access FROM users; ');
			$query->execute();

			$count = $query->rowCount();

			if($count == 0)
				return '{}';
			if($data =  $query->fetchAll(PDO::FETCH_ASSOC))
				return json_encode($data);
		}catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}

	function getProductList()
	{
		if(!isAuthorizedAsAdmin())
			return;

		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php

		try{
			$query = $conn->prepare('SELECT * FROM products;');
			$query->execute();

			$count = $query->rowCount();

			if($count == 0)
				return '{}';
			if($data =  $query->fetchAll(PDO::FETCH_ASSOC))
				return json_encode($data);
		}
		catch(Exception $e){
			userMessage("Es ist ein Fehler aufgetreten: ".$e);
		}
		$conn = null;
		return;
	}


	function uploadProductImage( $productId ){
		if(!isAuthorized() || empty($_FILES['file']['name']) || empty($_FILES['file']['tmp_name'])){
			userMessage('Keine neues Bild hochgeladen');
			return;
		}

		$imageSize = 512;
		$maxFileSize = 250000;
		$targetDirectory = "ProductImages/";
		$fileExtension = strtolower( pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		$mimeType = $_FILES['file']['type'];

		//File validieren----------------------------------------------------------------
		//Dateiendung prüfen
		if($fileExtension !="png" && $fileExtension !="jpg" )
		{
			userMessage('Bitte nutze nur png oder jpg');
			return;
		}
		//Mimetype prüfen
		if($mimeType !="image/png" && $mimeType !="image/jpg")
		{
			userMessage('Bitte nutze eine Bilddatei (png, jpg)');
			return;
		}
		//Dateigröße prüfen
		if($_FILES['file']['size'] > $maxFileSize)
		{
			userMessage("Die maximale Dateigröße beträgt: $maxFileSize KB");
			return;
		}
		//Bildgröße prüfen
		$size = getimagesize($_FILES['file']['tmp_name']);
		if($size == null)
		{
			userMessage("Bitte verwende ein gültiges Bild");
			return;
		}else{
			if($size[0] != $imageSize || $size[1] != $imageSize)
			{
			userMessage("Bitte verwende ein gültiges Bild (512x512). Dein Bild ist " .$size[0]." x " .$size[1]." groß");
			return;
			}
		}

		//File ist ok für Upload---------------------------------------------------------
		//Bildurl zusammenstellen für das jeweilige Produkt eindeutig durch die productId
		$targetFile = $targetDirectory.$productId.'_productImage.'.$fileExtension;
		if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)){
			return $targetFile;
		}else{
			userMessage("Es ist ein Fehler beim hochladen des Bildes aufgetreten: ");
			return null;	
		}
	}
?>
</main>