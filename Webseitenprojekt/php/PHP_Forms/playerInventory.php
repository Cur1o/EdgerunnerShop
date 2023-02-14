<section class="playerInventoryFrame">
    <div id="inventorySlotContainer">
        <?php
            $conn = dbConnect();
            try{
                $query = $conn->prepare('SELECT productId 
                FROM user_resources
                WHERE user_resources.userId = ?');
                $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );
                $query->execute();

                if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
                   foreach($data as $item)
                   {
                        $itemid = $item['productId'];
                        include 'inventorySlot.php';
                        getItem($itemid);
                   } 
                }else{
                    echo('No inventory slot');
                }
                $conn = null;
            }catch(Exception $e){
                userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
                $conn=null;
                return false;
            }
        ?>
    </div> 
</section>