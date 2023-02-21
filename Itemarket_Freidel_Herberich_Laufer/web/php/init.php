<?php
	session_start();	//Session starten
	error_reporting(0);	//Fehlerausgabe blockieren 

	function dbConnect(){
		$host = 'localhost';		//der aktuelle Host der auf die Datenbank zugreifen will
		$dbname ='db_cybershop';	//Der Name der Datenbank
		$user = 'root';				//Der Nutzer der auf die Datenbank zu greift
		$pass = '';					
		
		try{
			$DB = new PDO("mysql:host=".$host."; dbname=".$dbname.";", $user, $pass);	//Datenbankverbindung wird aufgebaut
			$DB->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );				//Attribute zur Fehlerausgabe werden gesetzt.
			return $DB;					//Die Datenbank wird zurückgegeben.
		}catch( PDOException $error){	//Wenn die Datenbankverbindung nicht funktioniert.
			die("Sorry ein Fehler ist aufgetreten, prüfen Sie Ihre Internetverbindung.Error:".$error);	//Fehlerausgabe an den Nutzer 
		}
	}

	function isAuthorized(){	//ob den Nutzer autorisiert ist sich anzumelden.
		return (isset($_SESSION['nick']) && isset($_SESSION['access']) && ($_SESSION['access'] != 'locked'));
	}

	function isAuthorizedAsAdmin(){	//ob der Nutzer als Admin angemeldet ist.
		return (isset($_SESSION['nick']) && isset($_SESSION['access']) && ($_SESSION['access'] == 'admin'));
	}

	//User messages------------------------------------------------------------------------------

	if(!isset($_SESSION['userMessage']))//Wenn in user message ein Wert gesetzt wurde wird dieser mit einem leeren String ersetzt
		$_SESSION['userMessage']="";	//Session Variable wird durch einen leeren String ersetzt.

	function userMessage( $message ){				//Die function wird oft aufgerufen um dem Nutzer einen Fehler auszugeben.
		$_SESSION['userMessage'] .= "$message<br>";	//nach der geschriebenen Nachricht wird ein Zeilenumbruch geschrieben
	}
?>