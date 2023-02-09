<?php
require_once('init.php');

 $json = file_get_contents("php://input");

 if( !empty($json) ){
	 
	 $data = json_decode ($json, true);// convert json string to php object
	 //we can work with the data object, ie. e.g. read the content of action
	 //$data['action']
 }
 else{
	 sendError("Sorry, es ist ein Fehler aufgetreten. Überprüfe deine Internetverbindung 1.");
	 die;
 }

 /**
  * Send Json error message
  * @param string $errorMessage - error message string
  */
 function sendError( $errorMessage ){
	 $error = array('error' => $errorMessage);
	 echo json_encode($error);//converts php object to json string
 }

if(isset($data)){
	switch($data['action']){

		case "getResources": getResources($data['id']);
		                     break;

		case "setProduct":   setProduct($data['userId'], $data['id'],$data['name'], $data['price'],$data['count'] );
			                 break;

		case "getAllProducts": getAllProducts();
		break;		
	}
}

/**
 * get resources of user by id
 * @param string $id 
 */
function getResources( $id ){
	
	if( !isAuthorized() ){
        sendError("Keine Zugriffsrechte");
		return;
	}
	
	//Access is authorized
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	
	try{ // unsafe operation therefore in try-catch block
		
		$query = $conn->prepare('SELECT products.id, products.productId, products.name, 
		                         products.description, products.image, 
								 products.price, user_resources.count, products.isConsumeable
								 FROM products
								 INNER JOIN user_resources
								 ON products.id = user_resources.productId
								 WHERE user_resources.userId = ?							 
								');
		
		$query->bindParam( 1, $id, PDO::PARAM_INT );		
		$query->execute();	

		if($data = $query->fetchAll(PDO::FETCH_ASSOC))
		{
			echo ( json_encode($data) );
		}
	
        $conn = null; //always end connection
	}
	catch(Exception $e ){
		sendError("get users  resources failed");
		$conn = null;
	}	
}

/**
 * get all products in shop
 * 
 */
function getAllProducts( ){
	
	if( !isAuthorized() ){
        sendError("Keine Zugriffsrechte");
		return;
	}
	
	//Access is authorized
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	
	try{ // unsafe operation therefore in try-catch block
		
		$query = $conn->prepare('SELECT products.id, products.productId, products.name, products.description, 
		                                products.image, products.price, 0, products.isConsumeable 
										FROM products ;');
										
										
		$query->execute();	


		if($data = $query->fetchAll(PDO::FETCH_ASSOC))
		{
			echo ( json_encode($data) );
		}
        $conn = null; //always end connection
	}
	catch(Exception $e ){
		sendError("get all products failed");
		$conn = null;
	}	
}

/**
 * Set product
 * @param number $userId
 * @param number $id
 * @param string $resourceName
 * @param int $price
 * @param int $count
 */
function setProduct( $userId, $id, $name, $price, $count){

	if( !isAuthorized() ){
        sendError("Keine Zugriffsrechte");
		return;
	}
	
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	

	if( UserHasAlreadyBought($userId, $id) )
	{
		try{
			//hat schon mal gekauft 
			$query = $conn->prepare('UPDATE user_resources SET count = count + ? WHERE productId = ? AND userId = ?');	

			$query->bindParam( 1, $count , PDO::PARAM_INT );	
			$query->bindParam( 2, $id , PDO::PARAM_INT );	
			$query->bindParam( 3, $userId , PDO::PARAM_INT );					 
			$query->execute();	

			echo(  '{ "response":"success","id":'.$id.',"name":"'.$name.'", "price":'.$price.', "count":'.$count.'}' );					
			$conn = null;		
		}
		catch(Exception $e ){
			sendError("Es ist ein Fehler aufgetreten, überprüfe deine Internetverbindung");
			$conn = null;
			return null;
		}
	}
	else{  
		try{
		//noch nicht gekauft			
			$query = $conn->prepare('INSERT INTO user_resources( productId, userId, count ) 
			                         VALUES(?,?,?)');
			
			$query->bindParam( 1, $id , PDO::PARAM_INT );	
			$query->bindParam( 2, $userId , PDO::PARAM_INT );
			$query->bindParam( 3, $count , PDO::PARAM_INT );					 
			$query->execute();	

			echo(  '{ "response":"success","id":'.$id.',"name":"'.$name.'", "price":'.$price.', "count":'.$count.'}' );							
			$conn = null;		
			}
			catch(Exception $e ){
			sendError("Es ist ein Fehler aufgetreten, überprüfe deine Internetverbindung");
			$conn = null;
			return null;
			}
	}

	 return null;
}

function UserHasAlreadyBought( $userId, $id ){

	if( !isAuthorized() ){
        sendError("Keine Zugriffsrechte");
		return;
	}
	
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	
	try{
		//checken ob product schon gekauft, dh. die Zeile schon existiert
		$query = $conn->prepare('SELECT count FROM user_resources WHERE productId = ? AND userId = ?');	
	 
	  	$query->bindParam( 1, $id , PDO::PARAM_INT );	
		$query->bindParam( 2, $userId , PDO::PARAM_STR );					 
		$query->execute();	

        $count = $query->rowCount();
		$conn = null;
		return $count > 0;	
	}
	catch(Exception $e ){
		sendError("Es ist ein Fehler aufgetreten");
		$conn = null;
		return false;
	}
	 return false;
}

?>