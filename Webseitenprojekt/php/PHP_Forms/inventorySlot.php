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
                    $sellprice = $price/2;
                    $isConsumeable = $data[0]['isConsumeable'];

                    // Display the data on the page
                    echo 
                    "<form class='inventorySlot'>
                        <img src='$image' alt='Product Image'>
                        <div>
                            <h1>$name</h1>          
                            <p>Wert: $sellprice</p>
                        </div>

                        <p>$description</p>
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
        function removeItem(){

        }
    ?>
</form>