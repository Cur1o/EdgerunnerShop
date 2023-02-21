<?php
	//Eingaben validieren ----------------------------------------------------------------------
	function isValidPassword( $password ){//Es wird überprüft ob das vom Nutzer eingegebene Passwort den Mindestanforderungen entspricht.
	 
    if( !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]).{10,}$/", $password ))	//Überprüft ob das passwort den Anforderungen entspricht.
	{		
		//wenn es kein match mit dem Nutzer-Passwort gibt.
		userMessage('Bitte gib gültiges Password an!');	//
		return false;
	}	
	return true;
}
	function isValidNick($nick){	//Überprüfung ob der Nick verfügbar ist.
		if(empty($nick))			//wenn es leer ist.
		{
			//dem Nutzer wird eine Nachricht ausgegeben dass er das richtige format für einen Namen verwenden soll.
			userMessage('Bitte gib gültigen Nick an!');	//Nachricht an den Nutzer
			return false;	//Es wird false zurückgegeben
		}
		return true;		//Es wird true zurückgegeben
	}

	function isValidEmail( $email ){
		if( !filter_var( $email, FILTER_VALIDATE_EMAIL) )	//Es wird überprüft ob das vom Nutzer eigegebene, nicht der form einer e-mail entspricht.
		{
			//Dem nutzer wir benachrichtigt eine Email einzugeben.
			userMessage('Bitte gib eine Email Adresse ein!');
			return false;
		}
		return true;
	}
	//Eingabe validieren ende -------------------------------------------------------------------

	//Login und Register Methoden----------------------------------------------------------------

	//Register
	function register($nick, $email, $password){	//es werden eigegebener nick email und passwort übergeben.
		if(!emailIsUnique($email) || !nickIsUnique($nick)) return false;	//wenn die e-mail/der nick schonmal verwendet wurde wird false zurückgegeben.

		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php.
		$passwordHash = password_hash( $password, PASSWORD_DEFAULT);	//das passwort wird gehasht und dies in eine variable gelegt.

		try{
			$query = $conn->prepare('INSERT INTO users (nick, email, userpassword) VALUES (:nick, :email, :userpassword);');	//ein sql befehl wird an die Datenbank gesendet.
			$query->bindParam(':nick', $nick, PDO::PARAM_STR);					//Prepared statment für den spieler nick.
			$query->bindParam(':email', $email, PDO::PARAM_STR);				//Prepared statment für die Spieler e-mail.
			$query->bindParam(':userpassword', $passwordHash, PDO::PARAM_STR);	//Prepared statment für das gehashte user passwort.
			$query->execute();											//die querry abfrage wird ausgeführt.
			echo "New record created successfully";						//nachricht wird ausgegeben.
			userMessage("Du bist registriert als".$nick."!");			//Nutzer wird benachrichtigt unter welchem namen er sich registriert hat.
			login($nick, $password);									//nach dem registrieren wird der nutzer automatisch eingeloggt.
			AddUserCoin(1500);											//Dem Nutzer werden 1500 Coins als startguthaben hinzugefügt.
	
		}catch(Exception $e){											//falls ein fehler auftritt wird der teil ausgeführt und der Fehler ausgegeben.
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());	//Fehlermeldung an Nutzer.
			$conn = null;	//Datenbankverbindung wird getrennt.
			return false;	//es gibt false zurück.
		}
		$conn = null;	//die Datenbank connection wird wieder gelöst bzw auf Null gesetzt.
	}

	//Login
	function login($nick, $password){	//es werden die vom Nutzer eigegebenen Variablen angenommen.
		$conn = dbConnect();			//verbindung zur Datenbank wird aufgebaut über die function in init.php.
		try{	
			$query = $conn->prepare('SELECT  id, nick, userpassword, access, EdgeCoins FROM users Where nick = ?');// ein sql befehl wird an die Datenbank gesendet.
			$query->bindParam(1, $nick, PDO::PARAM_STR);	//es wird versucht den Nutzernamen aus der Datenbank zu hohlen.    											   
			$query->execute(); 								//die query Abfrage wird ausgeführt.        										   
		}
		catch(Exception $e){	//wenn der Name nicht gefunden wurde wird der Spieler darüber benachrichtigt.
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
		}
		
		if($data = $query->fetch(PDO::FETCH_ASSOC)){
			if(password_verify($password, $data['userpassword'])){						//überprüft ob ein Passwort mit dem hash übereinstimmt.
				if(password_needs_rehash( $data['userpassword'], PASSWORD_DEFAULT)){	//vergleicht ob das Passwort einen neuen hash braucht und gibt die hash methode an.
					try{
						$query = $conn->prepare('UPDATE users SET userpassword = ? WHERE nick = ?;');	// verbindet sich mit demn Passwort in der Datenbank um dieses im folgenden zu ändern.					
						$query->bindParam(1, $newPasswordHash, PDO::PARAM_STR);	//Prepared statment für den neuen Passworthash.						 				
						$query->bindParam(2, $nick, PDO::PARAM_STR);			//Prepared statment für den Spielername.							
						$query->execute();										//Datenbankänderung ausführen.
					}catch(Exception $e){	//Wenn bei der Verbindung ein Fehler auftritt.
						userMessage('Es ist Fehler aufgetreten'.$e->getMessage());	//Fehlerausgabe an den Nutzer.
						$conn=null;		//Datenbankverbindung wird getrennt.
						return false;	//Es wird false zurückgegeben.
					}
					$conn=null;			//Datenbankverbindung wird getrennt.
				}

				//logged in 
				//die Daten aus der Datenbank werden in die Sessin geschrieben---------------------------------------------
				$_SESSION['nick'] = $data['nick'];			//Daten für den nick-Namen.
				$_SESSION['access'] = $data['access'];		//Daten für den Zugang.
				$_SESSION['id'] = $data['id'];				//Daten für die player Id.
				$_SESSION['EdgeCoins']= $data['EdgeCoins'];	//Daten für die EdgeCoins.
				$_SESSION['currentshopID'] = 0;				//Daten für die aktuelle shop id.(welcher Shop gerade geöfnet ist.)

				$conn = null;	//Verbindung wird aufgelöst Variable.
				return true;	//es wird true zurückgegeben.								
			}else{	//Passwort oder Nick falsch.
				userMessage('Deine Eingaben sind falsch');	//Hier keine genauen Informationen rausgeben.
				$conn = null;	//Datenbankverbindung wird getrennt.
				return false;	//es wird false zurückgegeben
			}
		}
		//Passwort oder Nick falsch.
		userMessage('Deine Eingaben sind falsch');	//Hier keine genauen Informationen rausgeben.
		$conn = null;	//Datenbankverbindung wird getrennt.
		return false;	//Es wird true zurückgegeben.
	}

	function emailIsUnique($email){
		$conn = dbConnect();	//verbindung zur Datenbank wir aufgebaut.
		try{
			
			$query = $conn->prepare('SELECT id FROM users WHERE email = ? ;');	//Überprüfung ob die email bereits schonmal verwendet wurde.
			$query->bindParam(1, $email, PDO::PARAM_STR);	//Prepared statment für die email.
			$query->execute();								//Datenbankabfrage wird ausgeführt.

			if($query->rowCount() > 0){	//Wenn bereits ein eintrag in der datenbank besteht.
				userMessage("Diese Emailadresse wird bereits verwendet");	//Fehlermeldung an den nutzter.
				$conn = null;	//Datenbankverbindung wird getrennt.
				return false;	//es wird false zurückgegeben.
			}
		}catch (Exception $e){	//Falls die Datenbankverbindung fehlschlägt.
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());	//Dem nutzer wird ein Fehler ausgegeben.
			$conn = null;	//Datenbankverbindung wird getrennt.
			return false;	//Es wird false zurückgegeben
		}
		$conn = null;
		return true;
	}
	// Überprüfung ob der nick einmalig ist der eingegeben wurde.
	function nickIsUnique($nick){
		$conn = dbConnect();	//verbindung zur Datenbank wird aufgebaut über deine function in init.php.
		try{
			$query = $conn->prepare('SELECT id FROM users WHERE nick = ? ;');
			$query->bindParam(1, $nick, PDO::PARAM_STR);	//Prepared statment für den spieler nick.
			$query->execute();								//Datenbankabfrage wird ausgeführt.

			if($query->rowCount() > 0){	//Wenn ein Datenbsatz zurückgegeben wird.
				userMessage("Dieser Nickname wird bereits verwendet");	//Fehlerausgabe an den Nutzer
				$conn = null;	//Datenbankverbindung wird getrennt.
				return false;	//Es wird false zurückgegeben.
			}
		}catch (Exception $e){	//Wenn die Datenbankverbindung fehlschlägt.
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());	//Fehlerausgabe an den Nutzer
			$conn = null;	//Datenbankverbindung wird getrennt.
			return false;	//Es wird false zurückgegeben.
		}
		$conn = null;	//Datenbankverbindung wird getrennt.
		return true;	//Es wird false zurückgegeben.
	}

	function logout(){
		include_once 'PHP_Forms/traderInventoryRefill.php';	//trader inventory refill wird includiert um die Methode whipeTrader aufzurufen.
		for ($i=1; $i < 4; $i++) { 	//Alle shops werden resetet und neu bestückt beim ausloggen
			whipeTrader($i);					//Alle trader werden zurückgesetzt.
		}
		unset( $_SESSION['nick'] );				//Die Session Variable nick wird gelöscht/geleert.
		unset( $_SESSION['access'] );			//Die Session Variable access wird gelöscht/geleert.
		unset( $_SESSION['image'] );			//Die Session Variable image wird gelöscht/geleert.
		unset( $_SESSION['id'] );				//Die Session Variable id wird gelöscht/geleert.
		unset( $_SESSION['EdgeCoins'] );		//Die Session Variable EdgeCoins wird gelöscht/geleert.
		unset( $_SESSION['currentshopID'] );	//Die Session Variable currentshopID wird gelöscht/geleert.
		session_destroy();						//Die session wird beendet
	}

	function AddUserCoin($coins)
	{
		$newCoins = $_SESSION['EdgeCoins'] += $coins;	//Der neue Kontostand wird berechnet und in einer Variable gespeicheret.
		$conn = dbConnect();	//Datenbankverbindung aufbauen.
		try{
			$query = $conn->prepare('UPDATE users SET EdgeCoins = ? WHERE id = ?;');	//Die neuen Coins des spielers werden in die Datenbank geschrieben.
			$query->bindParam(1,$newCoins, PDO::PARAM_STR);			//prepared statment für die neuen spieler coins.
			$query->bindParam(2,$_SESSION['id'], PDO::PARAM_STR);	//prepared statment für die spieler session id.
			$query->execute();										//Datenbankänderung wird ausgeführt.
		}catch(Exception $e){	//Falls die Datenbankverbindung fehlschlägt
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());	//Fehlerausgabe an den Nutzer
			$conn = null;	//Datenbankverbindung trennen.
			return false;	//Es wird false zurückgegeben.
		}
		$conn = null;		//Datenbankverbindung trennen.
	}

	function RemoveUserCoins($coins){
		$newCoins = $_SESSION['EdgeCoins'] -= $coins;	//Der neue Kontostand wird berechnet und in einer Variable gespeicheret.
		$conn = dbConnect();	//Datenbankverbindung aufbauen.
		try{
			$query = $conn->prepare('UPDATE users SET EdgeCoins = ? WHERE id = ?;');	//Die neuen Coins des spielers werden in die Datenbank geschrieben.
			$query->bindParam(1,$newCoins, PDO::PARAM_STR);			//prepared statment für die neuen spieler coins.
			$query->bindParam(2,$_SESSION['id'], PDO::PARAM_STR);	//prepared statment für die spieler session id.
			$query->execute();										//Datenbankänderung wird ausgeführt.
		}catch(Exception $e){	//Falls die Datenbankverbindung fehlschlägt
			userMessage('Es ist Fehler aufgetreten'.$e->getMessage());	//Fehlerausgabe an den nutzer
			$conn = null;	//Datenbankverbindung trennen.
			return false;	//Es wird false zurückgegeben.
		}
		$conn = null;		//Datenbankverbindung trennen.
	}
?>