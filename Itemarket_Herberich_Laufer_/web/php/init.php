<?php
	session_start();	//Session starten
	error_reporting(0);	//Fehlerausgabe blockieren 

	function dbConnect(){
		$host = 'localhost';		//der aktuelle host der auf die Datenbank zugreifen will
		$dbname ='db_cybershop';	//Der name der Datenbank
		$user = 'root';				//Der nutzer der auf die datanban zu greift
		$pass = '';					
		
		try{
			$DB = new PDO("mysql:host=".$host."; dbname=".$dbname.";", $user, $pass);	//Datenbankverbindung wird aufgebaut
			$DB->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );				//Atrebute zur fehlerausgabe werden gesetzt.
			return $DB;					//Die Datenbank wird zuückgegeben.
		}catch( PDOException $error){	//Wenn die Datenbankverbindung nicht funktioniert.
			die("Sorry ein Fehler ist aufgetreten, prüfen Sie Ihre Internetverbindung.Error:".$error);	//fehlerausgabe an den nutzer 
		}
	}

	function isAuthorized(){	//ob den nutzer autoresiert ist sich anzumelden.
		return (isset($_SESSION['nick']) && isset($_SESSION['access']) && ($_SESSION['access'] != 'locked'));
	}

	function isAuthorizedAsAdmin(){	//ob der Nutzer als Admin angemeldet ist.
		return (isset($_SESSION['nick']) && isset($_SESSION['access']) && ($_SESSION['access'] == 'admin'));
	}

	//User messages------------------------------------------------------------------------------

	if(!isset($_SESSION['userMessage']))//Wenn in user mesage ein wert gesetzt wurde wird dieser mit einem leeren string ersetzt
		$_SESSION['userMessage']="";	//Session Variable wird dur einen leeren string ersetzt.

	function userMessage( $message ){				//Die function wird oft aufgerufen um dem nutzer einen fehler auszugeben.
		$_SESSION['userMessage'] .= "$message<br>";	//nach der geschriebenen Nachricht wird ein zeilenumbruch geschrieben
	}
?>