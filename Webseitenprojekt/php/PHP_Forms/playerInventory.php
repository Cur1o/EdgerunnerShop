<?php
    require_once('PHP/init.php');
    require_once('PHP/account.php');
    require_once('PHP/admin.php');

    $_SESSION['buyItemSucess'] = false;

    function getItemsInInventory(){
        $conn = dbConnect();
        try{
            $query = $conn->prepare('SELECT productId, id
            FROM user_resources
            WHERE user_resources.userId = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
            $query->execute();
            if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
                echo '<div class="inventorySlotContainer">';
                echo '<h1 class="inventoryName">'.$_SESSION['nick'].'</h1>';
                foreach($data as $item)
                {
                    $itemID = $item['productId'];
                    $slotID = $item['id'];
                    include_once 'inventorySlot.php';
                    getItem($itemID,$slotID);
                } 
                echo '</div>';
            }else{
                echo('No inventory slot');
            }
            $conn = null;
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
    }
    //places the items in the
    function GetShopInventory($shopID){
        $conn = dbConnect();
        try{
            $query = $conn->prepare('SELECT productId, id FROM user_resources WHERE user_resources.userId = ?');
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );
            $query->execute();
            if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
                echo '<div class="inventorySlotContainer">';
                echo '<h1 class="inventoryName">Shop '.$shopID.'</h1>';
                foreach($data as $item)
                {
                    $itemID = $item['productId'];
                    $slotID = $item['id'];
                    
                    include_once 'traderSlot.php';
                    getTraderItem($itemID,$slotID);
                } 
                echo '</div>';
            }else{
                echo('No inventory slot');
            }
            $conn = null;
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
    }
    //Buy an item from the shop
    function buyItem($slotID) {
        echo'TESTING buy item: '.$slotID,$_SESSION['currentshopID'];
        $conn = dbConnect();
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE user_resources.id = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
            
            $_SESSION['buyItemSucess'] = true;



            // if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
            // }
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    }
    //sell an item to the shop
    function sellItem($slotID) {
        echo'TESTING sell item :'.$slotID,$_SESSION['currentshopID'];
        $conn = dbConnect();
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE id = ? ');
            $query->bindParam( 1, $_SESSION['currentshopID'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
            // if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
            // }
        }catch(Exception $e){
            echo 'FEHLER';
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    }
          
    //end of PHP -------------------------------------------------------------------------------------------------------------------------------
?>
<section class="playerInventoryFrame">
        <?php 
            
            
            getItemsInInventory();
            
            // Überprüft ob daten von den items die verkauft oder gekauft wurden exestieren uns speichet diese zur verwendung
            if(isset($_POST['slotID'])&& isset($_POST['isShop'])){
                $slotID = $_POST['slotID'];
                if ($_POST['isShop'] == 0)
                    sellItem($slotID);// wenn die gesendete slot id NICHT von einem shop kommt
                else
                    buyItem($slotID);// wenn die gesendete slot id von einem shop kommt

                //GetShopInventory($_SESSION['currentshopID']);
            }

            if($_GET['action'] == 'Shop1'){
                $shopIDGlobal = 1; 
                $_SESSION['currentshopID'] = $shopIDGlobal;
                GetShopInventory($_SESSION['currentshopID']);
                
            }
                
            if($_GET['action'] == 'Shop2'){
                $shopIDGlobal = 2;
                $_SESSION['currentshopID'] = $shopIDGlobal;
                GetShopInventory($_SESSION['currentshopID']);
                
            }
                
            if($_GET['action'] == 'Shop3'){
                $shopIDGlobal = 3;
                $_SESSION['currentshopID'] = $shopIDGlobal;
                GetShopInventory($_SESSION['currentshopID']);
               
            }
        ?>
</section>

<!-- waffen verkaufen sql 
(UPDATE user_resources SET userId = ? WHERE user_resources.id = ?) 
1 = shop user id 
2 = item slot id  


waffen loeschen 
"DELETE FROM user_resources WHERE `user_resources`.`userid` = ?"
1 = shopuser


-->