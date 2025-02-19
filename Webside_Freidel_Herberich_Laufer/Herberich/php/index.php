<?php
  require_once('PHP/init.php');     // Initialisierung der Anwendung
  require_once('PHP/account.php');  // Einbindung der Account-Funktionalitäten aus account.php
  //Die folgenden methodendefinition befindet sich in accaunt.php.
  if(isset($_POST['button500'])) AddUserCoin(500);      //Dem Spieler werden nach druck auf den Button in coinsWindow.php 500 Coins hinzugefügt.
  if(isset($_POST['button1000'])) AddUserCoin(1000);    //Dem Spieler werden nach druck auf den Button in coinsWindow.php 1000 Coins hinzugefügt.
  if(isset($_POST['button2000'])) AddUserCoin(2000);    //Dem Spieler werden nach druck auf den Button in coinsWindow.php 2000 Coins hinzugefügt.
  if(isset($_POST['button4000'])) AddUserCoin(4000);    //Dem Spieler werden nach druck auf den Button in coinsWindow.php 4000 Coins hinzugefügt.
  if(isset($_POST['button10000'])) AddUserCoin(10000);  //Dem Spieler werden nach druck auf den Button in coinsWindow.php 10000 Coins hinzugefügt.

  //Frontend actions
  //regestrieren
  if(isset($_POST['enter']) && $_POST['enter']== 'Register')//wenn enter gesetzt wurde und der übergebene wert
    if(isValidNick($_POST['nick']) && isValidEmail($_POST['email']))  //Wenn der niick und die email noch nicht verwendet wurde.
      register(strip_tags($_POST['nick']), strip_tags($_POST['email']), strip_tags($_POST['password'])); // Diese Funktion entfernt alle HTML- und PHP-Tags aus dem Parameter, um XSS-Angriffe zu verhindern.

  //einloggen
  if(isset($_POST['enter']) && $_POST['enter'] == 'Login')  //wenn enter gesetzt wurde und der übergebene wert
    if(isValidNick( $_POST['nick']))  //Wenn der nick richtig ist                      
      login( strip_tags($_POST['nick']), strip_tags($_POST['password']) );//  Diese Funktion entfernt alle HTML- und PHP-Tags aus dem Parameter, um XSS-Angriffe zu verhindern.

  //ausloggen
  if( isset($_GET['action'] ) && $_GET['action'] == "logout" )  //Wenn der logout button gedrückt wurde
    logout(); //Methode Logout wird aufgerufen in accaunt.php

?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <!-- Metadatenbereich -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Einbindung des Style -->
    <link rel="stylesheet" href="style.CSS">
</head>
 <body>
    <?php if($_SESSION['currentshopID'] == -1)
          include_once 'PHP/navbar.php';  //im normalen gebrauch wird die nav bar zuerst geladen
     ?>
    <!-- Die einbettung des videos im Hintergrund -->
    <video width="100vw" height="100vh"  autoplay="" loop="" muted="" playsinline=""> 
      <source src="videos/turntable1.mp4" type="video/mp4">
      Ihr Browser unterstützt keine HTML5-Video.
    </video>
    
    <?php
      //Wenn die acces session variable gesetzt wurde und diese dem user oder dem Admin zugeortnet ist.
      if(isset($_SESSION['access']) && ($_SESSION['access'] == "user" || $_SESSION['access'] == "admin")){ 
        include 'PHP/PHP_Forms/accountOverviewWindow.php'; // Dis Profilübersicht wird geladen
        if(($_GET['action'] != 'coins'))
          include 'PHP/PHP_Forms/playerInventory.php'; //Das spieler inventar wird geladen
        if($_SESSION['currentshopID'] != -1)
          include_once 'PHP/navbar.php';
          
        //Die Action wird durch einen link in navbar aufgerufen.
        if($_GET['action'] == 'coins') // die action wird durch navbar.php ausgelöst
          include 'PHP/PHP_Forms/coinsWindow.php';  //Das fenster zum Münzen kaufen wird eingebunden
        // Wenn der Admin sich angemeldet hat 
        if($_SESSION['access'] == "admin"){
          //falls später mal ein Admin besondere rechte haben soll
        }
      }
      else if(isset($_GET['action'])){                //wenn kein benutzer Angemeldet ist oder er sich ausloggt
        if($_GET['action'] == "register")             //Wenn der regestrierungsknopf in navbar gedrückt wird.
          include 'PHP/PHP_Forms/registerWindow.php'; //Es wird die form registerWindow.php eingebunden.
        else if( $_GET['action'] == 'login')          //Wenn der login knopf in navbar gedrückt wird.
          include 'PHP/PHP_Forms/loginWindow.php';    //Es wird die form loginWindow.php eingebunden.
      }
    ?>
    <!-- Die Nav Bar wird in die seite eingebunden -->
    <?php include 'PHP/navbar.php'; ?>  
  </body>
    <script>
      let nickName = "<?php if(isset($_SESSION['nick'])){echo $_SESSION['nick'];}?>";
      if(nick != null && nick !="")
        document.getElementById("nick").innerText = "Angemeldet als "+ nickName;
    </script>

</html>