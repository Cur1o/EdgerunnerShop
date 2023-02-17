<?php 
    function getItem($itemID, $slotID){
        $conn = dbConnect(); 
        try{
            $query = $conn->prepare('SELECT products.name, 
            products.description, products.image, 
            products.price, products.damage , products.itemtype
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
                $sellprice = $price/2;
                $damage = $data[0]['damage']; 
                
                // Display the data on the page
                echo 
                "<form class='inventorySlot' method ='post' action='index.php'>
                        <img src='$image' alt='Product Image'>
                        <div>
                            <h1>$name</h1>          
                            <p>Wert: $sellprice</p>
                        </div>
                        <div>
                            <p>$description</p>";
                        if($data[0]['itemtype'] == 'weapon')
                            echo"<p>schaden: $damage</p>";
                   echo"</div>
                        <div> ";
                    if($_SESSION['currentshopID'] >= 1){
                    echo"
                        
                            <input type='hidden' name='slotID' value='$slotID'>
                            <input type='hidden' name='isShop' value='0'>
                            <input type='hidden' name='price' value='$sellprice'>
                            <button type='submit' class='inventorySlot'>-</button>
                        
                    ";}
                echo"</div>
                    </form>";

                
            }
            $conn = null;
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;   
        }
    }
    // function removeItem(){
    // }
?>
