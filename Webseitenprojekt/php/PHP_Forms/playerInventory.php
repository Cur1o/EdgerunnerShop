<?php
    require_once('PHP/init.php');
    require_once('PHP/account.php');
    require_once('PHP/admin.php');
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
            }else {
                echo '<div class="inventorySlotContainer">';
                echo '<h1 class="inventoryName">'.$_SESSION['nick'].'</h1>';
                echo '<form>Dein Inventar ist Leer</form>';
                echo '</div>';
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
            }elseif($_SESSION['currentshopID'] != -1){
                echo '<div class="inventorySlotContainer">';
                echo '<h1 class="inventoryName">Shop '.$shopID.'</h1>';
                echo'<form>Der Shop hat zur zeit keine neuen angebote</form> ';
                echo '</div>';
            }
            $conn = null;
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
    }
    //Buy an item from the shop
    function buyItem($slotID,$price) {
        $conn = dbConnect();
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE user_resources.id = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
            RemoveUserCoins($price);
            $coinsvalue = $_SESSION['EdgeCoins'];
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    }
    //sell an item to the shop
    function sellItem($slotID,$price) {
        $conn = dbConnect();
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE id = ? ');
            $query->bindParam( 1, $_SESSION['currentshopID'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
            AddUserCoin($price);
            $coinsvalue = $_SESSION['EdgeCoins'];
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
            // Überprüft ob daten von den items die verkauft oder gekauft wurden exestieren uns speichet diese zur verwendung
            if(isset($_POST['slotID'])&& isset($_POST['isShop'])&& isset($_POST['price'])){
                $slotID = $_POST['slotID'];
                $price = $_POST['price'];
                if ($_POST['isShop'] == 0 )
                sellItem($slotID,$price);// wenn die gesendete slot id NICHT von einem shop kommt
                elseif($_SESSION['EdgeCoins'] >= $price)
                buyItem($slotID,$price);// wenn die gesendete slot id von einem shop kommt
                else
                {
                ?>
                    <script>
                        alert("Du hast zu wenig edge coins bitte kaufe mehr indem du oben auf deine coins drückst")
                    </script>
                <?php
                }   
            }
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
            getItemsInInventory();
            GetShopInventory($_SESSION['currentshopID']);
        ?>
</section>