<?php
session_start();
error_reporting(0);

function dbConnect(){
	$host = 'localhost';	//der aktuelle host
	$dbname ='db_cybershop';
	$user = 'root';
	$pass = '';
	
	try{
		$DB = new PDO("mysql:host=$host; dbname=$dbname;", $user, $pass);
		$DB->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $DB;
	}
	catch( PDOException $error)
	{
	die("Sorry ein Fehler ist aufgetreten, prüfen Sie Ihre Internetverbindung.Error:".$error);
	}
}

function isAuthorized()
{
	return (isset($_SESSION['nick']) 
	        && isset($_SESSION['access']) 
			&& ($_SESSION['access'] != 'locked'));
}

function isAuthorizedAsAdmin()
{
	return (isset($_SESSION['nick']) 
	        && isset($_SESSION['access']) 
			&& ($_SESSION['access'] == 'admin'));
}

//User messages------------------------------------------------------------------------------

if(!isset($_SESSION['userMessage']))
         $_SESSION['userMessage']="";

function userMessage( $message ){
	$_SESSION['userMessage'] .= "$message<br>";

	if(isset($_POST['isUnity']))
	{
		echo( '{ "response":"error", "message":"'.$message.'"
			,"id":-1, "access":"locked", "nick":"" }' );
	}
}

?>