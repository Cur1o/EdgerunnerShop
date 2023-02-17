<?php
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
?>