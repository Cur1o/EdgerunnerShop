<?php
    // getItemsInInventory();
    function getItemsInInventory(){
        $conn = dbConnect();
        try{
            $query = $conn->prepare('SELECT productId 
            FROM user_resources
            WHERE user_resources.userId = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
            $query->execute();
            if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
                echo '<div class="inventorySlotContainer">';
                echo '<h1 class="inventoryName">'.$_SESSION['nick'].'</h1>';
                foreach($data as $item)
                {
                    $itemid = $item['productId'];
                    include_once 'inventorySlot.php';
                    getItem($itemid);
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
    //Removes the given item from the inventory
    // function removeItem($itemID){
    //     $conn = dbConnect();
    //     try{
    //         $query = $conn->prepare('DELETE FROM user_resources WHERE user_resources.id = ?;');
    //         $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
    //         $query->execute();
    //     }catch(Exception $e){
    //         userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
    //         $conn=null;
    //         return false;
    //     }
    //     $conn=null;
    //     getItemsInInventory();
        
    // };
    //Adds the given Item to the player inventory 
    // function addItem($itemID){
    //     $conn = dbConnect();
    //     try{
    //         $query = $conn->prepare('INSERT INTO user_resources (id,productId, userId, count) VALUES (NULL,? ,? ,? )');
    //         $query->bindParam( 1, $itemID , PDO::PARAM_INT );
    //         $query->bindParam( 2, $_SESSION['id'], PDO::PARAM_INT );
    //         $query->bindParam( 3, 1, PDO::PARAM_INT );
    //         $query->execute();
    //     }catch(Exception $e){
    //         userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
    //         $conn=null;
    //         return false;
    //     }
    //     $conn = null;
    //     getItemsInInventory();
    // }
    function GetShopInventory($shop){
        include 'PHP/PHP_Forms/TraderInventory.php';
    }
?>
<section class="playerInventoryFrame">
        <?php 
            getItemsInInventory();
            
            // if($_GET['action'] == 'shop1')
            //     $shopNick = Waffen; 
            //     GetShopInventory($shopNick)
            // if($_GET['action'] == 'shop2')
            //     $shopNick = ;
            //     GetShopInventory($shop)
            // if($_GET['action'] == 'shop3')
            //     $shopNick = ;
            //      GetShopInventory($shop);
        ?>
</section>