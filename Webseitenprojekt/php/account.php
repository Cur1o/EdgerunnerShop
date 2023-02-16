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
	function isValidNick($nick){
		if(empty($nick))	//wenn es leer ist
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
		if(!emailIsUnique($email) || !nickIsUnique($nick)) return false;	//wenn die e-mail/der nick schonmal verwendet wurde wird false zurückgegeben

		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
		$passwordHash = password_hash( $password, PASSWORD_DEFAULT);	//das passwort wird gehasht und dies in eine variable gelegt

		try{
			$query = $conn->prepare('INSERT INTO users (nick, email, userpassword) VALUES (:nick, :email, :userpassword);');	//ein sql befehl wird an die Datenbank gesendet
			$query->bindParam(':nick', $nick, PDO::PARAM_STR);	//die variablen werden an die platzhalter gebunden
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->bindParam(':userpassword', $passwordHash, PDO::PARAM_STR);
			$query->execute();	//die querry abfrage wird ausgeführt
			echo "New record created successfully";	//nachricht wird ausgegeben
			userMessage("Du bist registriert als".$nick."!");
			login($nick, $password);	//nach dem registrieren wird der nutzer automatisch eingeloggt
		}
		catch(Exception $e){//falls ein fehler auftritt wird der teil ausgeführt und der Fehler ausgegeben
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
		}
		$conn = null;	//die Datenbank connection wird wieder gelöst bzw auf Null gesetzt
	}

	//Login
	function login($nick, $password){	//es werden die von nutzer eigegebenen variablen angenummen
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php
		try{
			$query = $conn->prepare('SELECT  id, nick, userpassword, access, EdgeCoins FROM users Where nick = ?');// ein sql befehl wird an die Datenbank gesendet
			$query->bindParam(1, $nick, PDO::PARAM_STR);	//es wird versucht den nick namen aus der datenbank zu hohlen     											   
			$query->execute(); //die querry abfrage wird ausgeführt        										   
		}
		catch(Exception $e){	//wenn der Name nicht gefunden wurde wird der spieler darüber benachrichtigt
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
		}
		
		if($data = $query->fetch(PDO::FETCH_ASSOC)){
			if(password_verify($password, $data['userpassword'])){	//überprüft ob ein paswort mit dem hash übereinstimmt
				if(password_needs_rehash( $data['userpassword'], PASSWORD_DEFAULT)){	//vergleicht onb das passwort einen neuen hash  braucht und gibt die hash methode an
					try{
						$query = $conn->prepare('UPDATE users SET userpassword = ? WHERE nick = ?;');	// verbindet sich mit demn passwort in der Datenbank um dieses im folgenden zu ändern.					
						$query->bindParam(1, $newPasswordHash, PDO::PARAM_STR);							 				
						$query->bindParam(2, $nick, PDO::PARAM_STR);							
						$query->execute();	//führt die querry abfrage aus
					}catch(Exception $e){
						userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
						$conn=null;
						return false;
					}
				}

				//logged in 
				//die Daten aus der Datenbank werden in die Sessin geschrieben---------------------------------------------
				$_SESSION['nick'] = $data['nick'];			//Daten für den nick namen
				$_SESSION['access'] = $data['access'];		//Daten für den zugang
				$_SESSION['id'] = $data['id'];				//Daten für die player Id
				$_SESSION['EdgeCoins']= $data['EdgeCoins'];	//Daten für die EdgeCoins

				$conn = null;								//verbindung wird aufgelöst varable 
				return true;
			}else{	//Passwort oder Nick falsch
				userMessage('Deine Eingaben sind falsch');	//Hier keine genauen Informationen rausgeben
				$conn = null;
				return false;	//verbindung fehlgeschlagen
			}
		}
		//Passwort oder Nick falsch
		userMessage('Deine Eingaben sind falsch');	//Hier keine genauen Informationen rausgeben
		$conn = null;
		return false;
	}

	function emailIsUnique($email){
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php

		try{
			
			$query = $conn->prepare('SELECT id FROM users WHERE email = ? ;');
			$query->bindParam(1, $email, PDO::PARAM_STR);
			$query->execute();

			if($query->rowCount() > 0){
				userMessage("Diese Emailadresse wird bereits verwendet");
				$conn = null;
				return false;
			}
		}catch (Exception $e){
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
			$conn = null;
			return false;
		}
		$conn = null;
		return true;
	}
	// Überprüfung ob der nick einmalig ist der eingegeben wurde
	function nickIsUnique($nick){
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php

		try{
			$query = $conn->prepare('SELECT id FROM users WHERE nick = ? ;');
			$query->bindParam(1, $nick, PDO::PARAM_STR);
			$query->execute();

			if($query->rowCount() > 0){
				userMessage("Dieser Nickname wird bereits verwendet");
				$conn = null;
				return false;
			}
		}catch (Exception $e){
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
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
		unset( $_SESSION['EdgeCoins'] );
		unset( $_SESSION['currentshopID'] );
		session_destroy();
	}

	function AddUserCoin($coins)
	{
		$newCoins = $_SESSION['EdgeCoins'] += $coins;
		$conn = dbConnect();
		try{
			//'UPDATE users SET EdgeCoins = ? WHERE id = ?;
			$query = $conn->prepare('UPDATE users SET EdgeCoins = ? WHERE id = ?;');
			$query->bindParam(1,$newCoins, PDO::PARAM_STR);
			$query->bindParam(2,$_SESSION['id'], PDO::PARAM_STR);
			$query->execute();
		}catch(Exception $e){
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
			$conn = null;
			return false;
		}
	}

	function RemoveUserCoins($coins){
		$newCoins = $_SESSION['EdgeCoins'] -= $coins;
	}
?>