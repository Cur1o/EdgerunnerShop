<?php

//Eingaben validieren ----------------------------------------------------------------------
function isValidPassword( $password ){
	 
    if( !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]).{10,}$/", $password ))	
	{		
		//wenn s kein match mit derm user passwort gibt
		userMessage('Bitte gib gültiges Password an!');
		return false;
	}	
	return true;
}

function isValidNick( $nick ){
	 
    if( empty($nick) )	//wenn es leer ist
	{		
		//dem nutzer wird eine Nachricht ausgegeben dass er das richtige format für einen Namen verwenden soll
		userMessage('Bitte gib gültigen Nick an!');
		return false;
	}	
	return true;
}

function isValidEmail( $email ){
	
	if( !filter_var( $email, FILTER_VALIDATE_EMAIL) )	//Es wird überprüft ob das vom Nutzer eigegebene, nicht der form einer e-mail entspricht
	{		
		//Dem nutzer wir benachrichtigt eine email einzugeben
		userMessage('Bitte gib eine Email Adresse ein!');
		return false;
	}	
	return true;
}
//Eingabe veledieren ende -------------------------------------------------------------------

//Login und Register Methoden----------------------------------------------------------------

//Register

function register($nick, $email, $password){	//es werden eigegebener nick email und passwort übergeben
	
	if( !emailIsUnique($email) || !nickIsUnique($nick))	//wenn die e-mail/der nick schonmal verwendet wurde wird false zurückgegeben
			return false;
			
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	$passwordHash = password_hash( $password, PASSWORD_DEFAULT);	//das passwort wird gehasht und dies in eine variable gelegt
	
	try{
		$query = $conn->prepare('INSERT INTO users(nick, email, userpassword) VALUES (?,?,?) ;'); //SQL Abfrage wird an die datenbank gesendet
		$query->bindParam(1, $nick, PDO::PARAM_STR);	//name der zu dem querry Abfrage hinzugefügt wird	
        $query->bindParam(2, $email, PDO::PARAM_STR);	//email die zu dem querry Abfrage hinzugefügt wird
        $query->bindParam(3, $passwordHash, PDO::PARAM_STR);	//passworthash der zu dem querry Abfrage hinzugefügt wird	
												   
		$query->execute();	//querry befehl wird ausgeführt
        
		if( isset($_POST['isUnity']) )	//zum späteren verwenden in Unity wird in dem zusamenhang nicht verwendet
	    {
		   echo('{ "response":"success", 
			       "message":"Erfolgreich registriert als '.$nick.'" }' );			       
	    }
		else{	//erfolgreiche regestrierung ausgeben
			userMessage("Du bist registriert als $nick");			
		}
									   
	}
	catch(Exception $e){//falls ein fehler auftritt wird der teil ausgeführt und der Fehler ausgegeben
		userMessage('Es ist Fehler aufgetreten' /*.$e->getMessage()*/ );
	}
	 $conn = null;	//die Datenbank connection wird wieder gelöst bzw auf Null gesetzt
	
}

//Login

function login($nick, $password){	//es werden die von nutzer eigegebenen variablen angenummen
	
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	
	try{
		$query = $conn->prepare('SELECT  id, nick, userpassword, access, EdgeCoins
		                         FROM users Where nick = ?');// ein sql befehl wird an die Datenbank gesendet
		$query->bindParam(1, $nick, PDO::PARAM_STR);	//es wird versucht den nick namen aus der datenbank zu hohlen     											   
		$query->execute(); //die querry abfrage wird ausgeführt        										   
	}
	catch(Exception $e){	//wenn der Name nicht gefunden wurde wird der spieler darüber benachrichtigt
		userMessage('Es ist Fehler aufgetreten' /*.$e->getMessage()*/ );	
	}
	
	if( $data = $query->fetch(PDO::FETCH_ASSOC))
	{

		if( password_verify($password, $data['userpassword']))
		{
			if(password_needs_rehash( $data['userpassword'], PASSWORD_DEFAULT))
			{
						
				try{
					$newPasswordHash = password_hash( $password, PASSWORD_DEFAULT );
					
					$query = $conn->prepare('UPDATE users SET userpassword = ? 
											 WHERE nick = ?;');
											 
					$query->bindParam(1, $newPasswordHash, PDO::PARAM_STR);					 				
					$query->bindParam(2, $nick, PDO::PARAM_STR);	
					
					$query->execute();
				}
				catch(Exception $e){				
					userMessage('Es ist Fehler aufgetreten' /*.$e->getMessage()*/ );
					$conn=null;
					return false;								
				}
					
			}	
			
		//logged in 

		$_SESSION['nick'] = $data['nick'];
		$_SESSION['access'] = $data['access'];
		// $_SESSION['image'] = $data['image'];
		$_SESSION['id'] = $data['id'];
		$_SESSION['EdgeCoins']= $data['EdgeCoins'];		//die aktuellen coins werden aktualisiert
		$conn = null;

		
		if( isset($_POST['isUnity']) )
	    {
		   echo(  '{ "response":"success", "message":"Erfolgreich eingeloggt"
			        ,"id":"'.$data['id'].'", "access":"'.$data['access'].'"
					,"nick":"'.$data['nick'].'" }' );
	    }
        return true;		
		}
		else
		{ 
	      //Passwort oder Nick falsch
	      userMessage('Deine Eingaben sind falsch'); //Hier keine genauen Informationen rausgeben
	      $conn = null;
	      return false;			
		}
	}
	//Passwort oder Nick falsch
	userMessage('Deine Eingaben sind falsch'); //Hier keine genauen Informationen rausgeben
	$conn = null;
	return false;		
}

function emailIsUnique( $email ){
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	
	try{
		$query = $conn->prepare('SELECT id FROM users WHERE email = ? ;');
		$query->bindParam(1, $email, PDO::PARAM_STR);
		$query->execute();
		
		$count = $query->rowCount();
		if($count > 0)
		{
			userMessage("Diese Emailadresse wird bereits verwendet");
			$conn = null;
			return false;
		}
	}
	catch (Exception $e){
		userMessage('Es ist Fehler aufgetreten' /*.$e->getMessage()*/ );
		$conn = null;
		return false;
	}
	
	$conn = null;
	return true;
	
}

function nickIsUnique($nick){
	$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
	
	try{
		$query = $conn->prepare('SELECT id FROM users WHERE nick = ? ;');
		$query->bindParam(1, $nick, PDO::PARAM_STR);
		$query->execute();
		
		$count = $query->rowCount();
		if($count > 0)
		{
			userMessage("Dieser Nickname wird bereits verwendet");
			$conn = null;
			return false;
		}
	}
	catch (Exception $e){
		userMessage('Es ist Fehler aufgetreten' /*.$e->getMessage()*/ );
		$conn = null;
		return false;
	}
	
	$conn = null;
	return true;	
}

function logout(){
	unset( $_SESSION['nick'] );
	unset( $_SESSION['access'] );
	unset( $_SESSION['image'] );
	unset( $_SESSION['id'] );
	session_destroy();	
}
?>