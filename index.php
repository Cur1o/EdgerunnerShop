
<?php
require_once('PHP/init.php');
require_once('PHP/account.php');
require_once('PHP/admin.php');

if(isset($_POST['500']))
{
    $cuurency += $_POST['500'];
    $_POST['500'] = null;
}

$currency = isset($_POST['currency']) ? $_POST['currency'] : 500;



echo "TEST".$currency;

 //Frontend actions
 //register
 if(isset($_POST['enter']) && $_POST['enter']== 'Register')
 {
	 if( isValidPassword( $_POST['password'] ) 
		    && isValidNick( $_POST['nick'] )
	      && isValidEmail( $_POST['email'] ) )
	 {
		register( strip_tags($_POST['nick']), strip_tags($_POST['email']), strip_tags($_POST['password']) );	 			 
	 }
 }

 //login
  if(isset($_POST['enter']) && $_POST['enter'] == 'Login')
  {
	 if(   
          isValidPassword( $_POST['password']) 
		      && isValidNick( $_POST['nick'] )
	   ) 
      {
        login( strip_tags($_POST['nick']), strip_tags($_POST['password']) );	 			 
      }
  }
 
  //logout
  if( isset($_GET['action'] ) && $_GET['action'] == "logout" )
  {
	 logout();
  }
  //TODO: Delete later
  //upload user image
  // if(isset($_POST['enter']) && $_POST['enter']== 'Upload Image' && isset($_SESSION['nick']))
  // {  
	//   uploadUserImage($_SESSION['nick']);
  // }

  //  //add product
  //  if(isset($_POST['enter']) && $_POST['enter']== 'Neues Produkt hinzufügen')
  //  { 
  //       $isConsumeable = 0;
  //       if(isset($_POST['isConsumeable']) && $_POST['isConsumeable'] ="on")
  //       $isConsumeable = 1;
  //       addProduct( strip_tags($_POST['productId']), strip_tags($_POST['name']), strip_tags($_POST['description']), strip_tags( $_POST['price']),  $isConsumeable);
  //  }

    //change product
    if(isset($_POST['enter']) && $_POST['enter']== 'Produkt ändern')
    {  
        $isConsumeable = 0;
        if(isset($_POST['isConsumeable']) && $_POST['isConsumeable'] ="on")
        $isConsumeable = 1;
         changeProduct( strip_tags($_POST['productId']), strip_tags($_POST['name']), strip_tags($_POST['description']), strip_tags( $_POST['price']), $isConsumeable);
    }

  //delete product
  if(isset($_POST['enter']) && $_POST['enter']== 'Produkt löschen')
  {  
       deleteProduct( strip_tags($_POST['productId']));
  }

    //delete user
    if(isset($_POST['enter']) && $_POST['enter']== 'User löschen')
    {  
         deleteUser( strip_tags($_POST['nick']));
    }

   //lock user
    if(isset($_POST['enter']) && $_POST['enter']== 'User sperren')
    {  
         setUserAccess( strip_tags($_POST['nick']),"locked");
    }
    
    //unlock user
    if(isset($_POST['enter']) && $_POST['enter']== 'User entsperren')
    {  
        setUserAccess( strip_tags($_POST['nick']),"user");
    }


  if( isset($_POST['isUnity'] ))
     die;

?>
<!DOCTYPE html>
<html lang="de">

  <head>
    <!-- Metadatenbereich -->
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.CSS">    
 </head>
  
 <body>
    <?php include 'PHP/navbar.php'; ?>
    <video width="100vw" height="100vh"  autoplay="" loop="" muted="" playsinline="">
    <source src="videos/turntable1.mp4" type="video/mp4">
    Ihr Browser unterstützt keine HTML5-Video.
    </video>
     <?php 	   
       if( isset($_SESSION['access'] ) 
             && ($_SESSION['access'] == "user" || $_SESSION['access'] == "admin" ))
       {       
            include 'PHP/PHP_Forms/accountOverviewWindow.php';   
            if ($_GET['action'] == 'coins') 
            {
                include 'PHP/PHP_Forms/coinsWindow.php';
            } 
            else if ($_GET['action'] == 'buyCoins')
            {
              
            }
            if($_SESSION['access'] == "admin"){
              include 'PHP/PHP_Forms/adminPanel.php';  

              $_SESSION['userList'] = getUserList();
              $_SESSION['productList'] = getProductList();

          }
        } 
       
       else if(isset($_GET['action']))
       {
          if($_GET['action'] == "register")
          {
              include 'PHP/PHP_Forms/registerWindow.php';
          }
          else if( $_GET['action'] == 'login')
          {
              include 'PHP/PHP_Forms/loginWindow.php';
          }  

       }
   ?>

  </body>
  <script src="JS/ajax.js"></script>
  <script src="JS/admin.js"></script>
  <script>
  
  let nickName = "<?php if( isset($_SESSION['nick'])){ echo $_SESSION['nick'];}  ?>";
  if(nick != null && nick !=""){
	  document.getElementById("nick").innerText = "Angemeldet als "+ nickName;
  }
  
  let image = "<?php if( isset($_SESSION['image'])){ echo $_SESSION['image'];}  ?>";
  if( image != null && nick !=""){
	  document.getElementById("userImage").src = image;
  }

  let userList = '<?php if( isset($_SESSION['userList'])){ echo $_SESSION['userList'];}  ?>';
  if(userList != null && userList !=""){
	  
         let users = JSON.parse(userList);
         for ( const user of users ) {
             addUserListItem(user.nick, user.access);
         }
  }

  let productList = '<?php if( isset($_SESSION['productList'])){ echo $_SESSION['productList'];}  ?>';
  if(productList != null && productList !=""){
	  
         let products = JSON.parse(productList);
         for ( const product of products ) {
             addProductListItem(product);
         }
  }





  </script>
  
  
</html>