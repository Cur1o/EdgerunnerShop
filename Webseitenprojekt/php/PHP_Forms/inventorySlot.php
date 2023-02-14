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
                    // Access the columns of the selected product
                    $name = $data[0]['name'];
                    $description = $data[0]['description'];
                    $image = $data[0]['image'];
                    $price = $data[0]['price'];
                    $isConsumeable = $data[0]['isConsumeable'];
                
                    // Display the data on the page
                    echo "<h1>$name</h1>";
                    echo "<p>$description</p>";
                    echo "<img src='$image' alt='Product Image'>";
                    echo "<p>Price: $price</p>";
                    echo "<p>Is Consumeable: $isConsumeable</p>";
                }
                
            }catch(Exception $e){
                userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
                $conn=null;
                return false;   
            }
        }
    ?>
    <!-- <input type="image" src="ProductImages/'..'" alt="500"/> -->
</form>