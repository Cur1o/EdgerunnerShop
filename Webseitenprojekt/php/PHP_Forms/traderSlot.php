<?php 
    function getTraderItem($itemID, $slotID){
        $conn = dbConnect(); //Datenbankverbindung aufbauen
        try{
            $query = $conn->prepare('SELECT products.name, 
            products.description, products.image, 
            products.price, products.damage, products.itemtype
            FROM products
            WHERE products.id = ?');
            $query->bindParam( 1, $itemID, PDO::PARAM_INT );    //alle obengenanten daten werden aus der datenbank für das item mit der angegebenen id geholt
            $query->execute();  //Datenbank abfrage ausführen
            if($data = $query->fetchAll(PDO::FETCH_ASSOC))
            {   
                // Hier wenden alle werte aus der datenbank in variablen geschrieben
                $name = $data[0]['name'];   //der Name aus der Datenbank wird gespeichert
                $description = $data[0]['description']; //Die beschreibung aus der datenbank wird gespeichert
                $image = $data[0]['image']; //der dateipfad zu den bildern aus der Datenbank wird gespeichert
                $price = $data[0]['price']; //der Preis aus der Datenbank wird gespeichert
                $damage = $data[0]['damage']; //der Schaden / der schutz und die effectdauer werden in damage gespeichert
                //Aufbau des inventar slots der im Invebntar angezeigt wird
                echo 
                "<form class='inventorySlot' method ='post' action='index.php'>
                        <img src='$image' alt='Product Image'>
                        <div>
                            <h1>$name</h1>          
                            <p>Preis: $price</p>
                        </div>
                        <div>
                            <p>$description</p>";
                        if($data[0]['itemtype'] == 'weapon')
                            echo"<p>schaden: $damage</p>";
                        if($data[0]['itemtype'] == 'amor')
                            echo"<p>schutz: $damage</p>";
                        if($data[0]['itemtype'] == 'item')
                            echo"<p>effektdauer: $damage</p>";
                        echo"</div>
                        <div>
                            <input type='hidden' name='slotID' value='$slotID'>
                            <input type='hidden' name='isShop' value='1'>
                            <input type='hidden' name='price' value='$price'>
                            <button type='submit' class='inventorySlot'>-$</button>
                        </div>
                </form>"; 
            }
            $conn = null;   //Verbindung zur datenbank wird getrennt
        }catch(Exception $e){   //wenn ein fehler bei der verbindung auuftritt
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //
            $conn=null; //Verbindung zur datenbank wird getrennt
            return false;   
        }
    }
?>
