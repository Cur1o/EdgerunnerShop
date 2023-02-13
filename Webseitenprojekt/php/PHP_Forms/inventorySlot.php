<form class="inventorySlot">
    <?php 
        function getItem($itemID){
            $conn = dbConnect(); 
            try{
                $query = $conn->prepare('SELECT products.name, 
                products.description, products.image, 
                products.price, products.isConsumeable
                FROM products
                WHERE products.id = ?');
                $query->bindParam( 1, $itemID, PDO::PARAM_INT );
                $query->execute();
                if($data = $query->fetchAll(PDO::FETCH_ASSOC))
                {   
                    
                }
            }catch(Exception $e){
                
           }
        }
    ?>
    <input type="image" src="ProductImages/'..'" alt="500"/>
</form>