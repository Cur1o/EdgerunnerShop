<?php
    function getItem($itemID, $slotID){ //Methode getItem die von playerInventory.php aufgerufen wird um einen inventarslot zu erstellen.
        $conn = dbConnect();            //Datenbankverbindung aufbauen. 
        try{
            $query = $conn->prepare('SELECT products.name, 
            products.description, products.image, 
            products.price, products.itemValues , products.itemtype
            FROM products
            WHERE products.id = ?');
            $query->bindParam( 1, $itemID, PDO::PARAM_INT );    //alle obengenanten daten werden aus der datenbank für das item mit der angegebenen id geholt.
            $query->execute();                                  //Datenbank abfrage ausführen.
            if($data = $query->fetchAll(PDO::FETCH_ASSOC))      //Wenn daten zurück gekommen sind.
            {   
                // Hier wenden alle werte aus der datenbank in variablen geschrieben.
                $name = $data[0]['name'];               //der Name aus der Datenbank wird gespeichert.
                $description = $data[0]['description']; //Die beschreibung aus der datenbank wird gespeichert.
                $image = $data[0]['image'];             //der dateipfad zu den bildern aus der Datenbank wird gespeichert.
                $price = $data[0]['price'];             //der Preis aus der Datenbank wird gespeichert.
                $sellprice = $price/2;                  //Der wert der waffe nach dem kauf beträgt die hälfte deswegen wird der price halbiert.
                $itemValues = $data[0]['itemValues'];           //der Schaden / der schutz und die effectdauer werden in damage gespeichert.
                //Aufbau des inventar slots der im Inventar angezeigt wird.
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
                            echo"<p>schaden: $itemValues</p>";
                        if($data[0]['itemtype'] == 'amor')
                            echo"<p>schutz: $itemValues</p>";
                        if($data[0]['itemtype'] == 'item')
                            echo"<p>effektdauer: $itemValues</p>";
                   echo"</div>
                        <div> ";
                    //die daten die von dem slot übergeben werden müssen wird in playerInventory.php ausgewertet
                    if($_SESSION['currentshopID'] >= 1){//wenn die aktuelle shop id 1 oder größer ist 
                    echo"
                        <input type='hidden' name='slotID' value='$slotID'>
                        <input type='hidden' name='isShop' value='0'>
                        <input type='hidden' name='price' value='$sellprice'>
                        <button type='submit' class='inventorySlot'>+$</button>  
                    ";}
                echo"</div>
                    </form>";
            }
            $conn = null;                                               //Verbindung zur datenbank wird getrennt.
        }catch(Exception $e){                                           //wenn ein fehler bei der verbindung auuftritt.
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Dem Benutzer wird ein Fehler ausgegeben. 
            $conn=null;                                                 //Verbindung zur datenbank wird getrennt.
            return false;                                               //Es wird fale zurückgegeben.  
        }                                           
    }
?>
