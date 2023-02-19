<?php
  require_once('PHP/init.php');
  require_once('PHP/account.php');

  if(isset($_POST['button500'])) AddUserCoin(500);
  if(isset($_POST['button1000'])) AddUserCoin(1000);
  if(isset($_POST['button2000'])) AddUserCoin(2000);
  if(isset($_POST['button4000'])) AddUserCoin(4000);
  if(isset($_POST['button10000'])) AddUserCoin(10000);

  //Frontend actions
  //register
  if(isset($_POST['enter']) && $_POST['enter']== 'Register')
    if(isValidNick($_POST['nick']) && isValidEmail($_POST['email']))
      register(strip_tags($_POST['nick']), strip_tags($_POST['email']), strip_tags($_POST['password']));

  //login
  if(isset($_POST['enter']) && $_POST['enter'] == 'Login')
    if(isValidNick( $_POST['nick']))
      login( strip_tags($_POST['nick']), strip_tags($_POST['password']) );

  //logout
  if( isset($_GET['action'] ) && $_GET['action'] == "logout" )
    logout();

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
      else if(isset($_GET['action'])){  //wenn kein benutzer Angemeldet ist oder er sich ausloggt
        if($_GET['action'] == "register") //Wenn der regestrierungsknopf in navbar gedrückt wird
          include 'PHP/PHP_Forms/registerWindow.php';
        else if( $_GET['action'] == 'login')
          include 'PHP/PHP_Forms/loginWindow.php';
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