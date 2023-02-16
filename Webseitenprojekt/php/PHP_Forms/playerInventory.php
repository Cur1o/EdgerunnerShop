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
                //TODO:
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
        $conn = dbConnect();
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE user_resources.id = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    }
    //sell an item to the shop
    function sellItem($slotID) {
        $conn = dbConnect();
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE id = ? ');
            $query->bindParam( 1, $_SESSION['currentshopID'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
        }catch(Exception $e){
            echo 'FEHLER';
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    }

    function sortErrorInventory(){
        switch ($_SESSION['currentshopID']) {
            case '1':
                echo 'Der scho hat gerade nichts im angebot';    
                break;
            case '2':
                echo 'Der scho hat gerade nichts im angebot';
                break;
            case '3':
                echo 'Der scho hat gerade nichts im angebot';
                break;
            default:
               
                break;
        }
    }
          
    //end of PHP -------------------------------------------------------------------------------------------------------------------------------
?>
<section class="playerInventoryFrame">
        <?php 
            // Überprüft ob daten von den items die verkauft oder gekauft wurden exestieren uns speichet diese zur verwendung
            if(isset($_POST['slotID'])&& isset($_POST['isShop'])){
                $slotID = $_POST['slotID'];
                if ($_POST['isShop'] == 0)
                    sellItem($slotID);// wenn die gesendete slot id NICHT von einem shop kommt
                else
                    buyItem($slotID);// wenn die gesendete slot id von einem shop kommt
            }

            getItemsInInventory();
            if($_GET['action'] == 'noShop'){
                $shopIDGlobal = -1; 
                $_SESSION['currentshopID'] = $shopIDGlobal;

            } 
            if($_GET['action'] == 'Shop1'){
                $shopIDGlobal = 1; 
                $_SESSION['currentshopID'] = $shopIDGlobal;

            }    
            if($_GET['action'] == 'Shop2'){
                $shopIDGlobal = 2;
                $_SESSION['currentshopID'] = $shopIDGlobal;
            }
                
            if($_GET['action'] == 'Shop3'){
                $shopIDGlobal = 3;
                $_SESSION['currentshopID'] = $shopIDGlobal;
            }
            GetShopInventory($_SESSION['currentshopID']);
        ?>
</section>