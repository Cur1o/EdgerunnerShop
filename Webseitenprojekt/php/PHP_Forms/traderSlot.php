<?php 
    function getTraderItem($itemID, $slotID){
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
                
                //$isConsumeable = $data[0]['isConsumeable'];
                // Display the data on the page
                echo 
                "<form class='inventorySlot' method ='get' action='index.php'>
                        <img src='$image' alt='Product Image'>
                        <div>
                            <h1>$name</h1>          
                            <p>Wert: $price</p>
                        </div>
                            <p>$description</p>
                        <div>
                            <input type='hidden' name='slotID' value='".$slotID."'>
                            <input type='hidden' name='isShop' value='1'>
                            <button type='submit' class='inventorySlot'>+</button>
                        </div>
                </form>";
                //echo "<p>Is Consumeable: $isConsumeable</p>";
            }
            $conn = null;
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;   
        }
    }
?>
