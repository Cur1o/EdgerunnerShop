<?php

//Eingaben validieren ----------------------------------------------------------------------
function isValidPassword( $password ){
	 
    if( !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]).{10,}$/", $password ))
	{		
		userMessage('Bitte gib gültiges Password an!');
		return false;
	}	
	return true;
}

function isValidNick( $nick ){
	 
    if( empty($nick) )
	{		
		userMessage('Bitte gib gültigen Nick an!');
		return false;
	}	
	return true;
}

function isValidEmail( $email ){
	
	if( !filter_var( $email, FILTER_VALIDATE_EMAIL) )
	{		
		userMessage('Bitte gib eine Email Adresse ein!');
		return false;
	}	
	return true;
}
//Eingabe veledieren ende -------------------------------------------------------------------

//Login und Register Methoden----------------------------------------------------------------

//Register

function register($nick, $email, $password){
	
	if( !emailIsUnique($email) || !nickIsUnique($nick))
		return false;
		
	$conn = dbConnect();
	
	$passwordHash = password_hash( $password, PASSWORD_DEFAULT);
	
	try{
		$query = $conn->prepare('INSERT INTO users(nick, email, userpassword)
		                                           VALUES (?,?,?) ;');
		$query->bindParam(1, $nick, PDO::PARAM_STR);	
        $query->bindParam(2, $email, PDO::PARAM_STR);	
        $query->bindParam(3, $passwordHash, PDO::PARAM_STR);		
												   
		$query->execute();
        
		if( isset($_POST['isUnity']) )
	    {
		   echo('{ "response":"success", 
			       "message":"Erfolgreich registriert als '.$nick.'" }' );			       
	    }
		else{
			userMessage("Du bist registriert als $nick");			
		}
									   
	}
	catch(Exception $e){
		userMessage('Es ist Fehler aufgetreten' /*.$e->getMessage()*/ );
	}
	 $conn = null;
	
}

//Login

function login($nick, $password){
	
	$conn = dbConnect();
	
	try{
		$query = $conn->prepare('SELECT  id, nick, userpassword, access, EdgeCoins
		                         FROM users Where nick = ?');
		$query->bindParam(1, $nick, PDO::PARAM_STR);	     											   
		$query->execute();        										   
	}
	catch(Exception $e){
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
	$conn = dbConnect();
	
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
	$conn = dbConnect();
	
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