<?php
    //PHP Anfang ------------------------------------------------------------------------------------------------------------------------------------------------
    require_once('PHP/init.php');       //Die php datei init.php muss einmal included worden sein
    require_once('PHP/account.php');    //Die php datei account.php muss einmal included worden sein
    

    if($_SESSION['currentshopID'] == 0)     //id 0 in der datenbank gehört dem Admin der auch ein inventar besitzt.
        $_SESSION['currentshopID'] = -1;    //Es gibt keinen benutzer mit der id -1. Der Nutzen : Der shop bleibt ausgeblendet.

    //Erstellt das Spieler Inventar -----------------------------------------------------------------------------------------------------------------------------
    function getItemsInInventory(){         //Wird unten im selben dokument aufgerufen.
        $conn = dbConnect();                //Datenbankverbindung aufbauen.
        try{
            // Alle Inventar Slot id's von dem spieler abrufen.
            $query = $conn->prepare('SELECT productId, id FROM user_resources WHERE userId = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );            //Prepared statment für sicherheit. Es wird die aktuelle spieler id eingesetzt.
            $query->execute();                                                  //Datenbankabfrage ausführen
            if($data = $query->fetchAll(PDO::FETCH_ASSOC)){                     //Wenn daten von der Datenbank zurück kommen.
                echo '<div class="inventorySlotContainer">';                    //Der container um die inventar slots herum wird erstellt.
                echo '<h1 class="inventoryName">'.$_SESSION['nick'].'</h1>';    //Der Name des Spielers wir eingebunden in das Inventar.
                foreach($data as $item)                                         //Für jedes Item im Datensatz wird ein Inventory slot erstellt.
                {
                    $itemID = $item['productId'];       //Die Product Id wird in eine Variable geschrieben die anschließend übergeben wird.
                    $slotID = $item['id'];              //Die slot Nummer/id wird in einer variable gespeichert um sie zu übergeben.
                    include_once 'inventorySlot.php';   //Das php dokument inventorySlot.php das die Spieler Inventarslots erstellt wird eingbunden.
                    getItem($itemID,$slotID);           //Eine Methode aus dem eingebundenen Script inventorySlot.php wird aufgerufen es werden 
                                                        //die zuvor gespeicherten daten übergeben.
                } 
                echo '</div>';                                                  //div Tag wird gesachlossen.
            }else {                                                             //falls keine daten zurückkommen soll trotzdem doie Inventar Box mit Namen Angezeigt werden.
                echo '<div class="inventorySlotContainer">';                    //Erstellt die Inventar Box.
                echo '<h1 class="inventoryName">'.$_SESSION['nick'].'</h1>';    //Schreibt den aktuellen Namen des Spielers in die Box.
                echo '<form>Dein Inventar ist Leer</form>';                     //Nachricht an den nutzer das sein inventar leer ist.
                echo '</div>';                                                  //div Tag wird gesachlossen.
            }
            $conn = null;                                               //Datenbankverbindung wird getrennt
        }catch(Exception $e){                                           //Falls die Verbindung mit der Datenbank fehlschlägt 
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Dem Benutzer wird ein Fehler ausgegeben 
            $conn=null;                                                 //Datenbankverbindung wird getrennt
            return false;                                               //Es wird false zurück gegeben 
        }
    }
    //ENDE: Spier Inventar -----------------------------------------------------------------------------------------------------------------------------------------------------------
    
    //Erstellt das Shop Inventar ----------------------------------------------------------------------------------------------------------------------------------------------------
    function GetShopInventory($shopID){ //Wird nachdem überprüft wurde ob oder welcher schop geöfnet ist unten im dokument aufgerufen.
        $conn = dbConnect();            //Datenbankverbindung wird aufgebaut    
        try{
            $query = $conn->prepare('SELECT productId, id FROM user_resources WHERE userId = ?');
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );                //Prepared statment für sicherheit. Es wird die aktuelle Shop id eingesetzt.
            $query->execute();                                              //Datenbankabfrage ausführen
            if($data = $query->fetchAll(PDO::FETCH_ASSOC)){                 //Wenn daten von der Datenbank zurück kommen.
                echo '<div class="inventorySlotContainer">';                //Der container um die inventar slots herum wird erstellt.
                if($shopID == 1)
                    echo '<h1 class="inventoryName">Waffenshop</h1>';       //Der Name des 1 Shops wir eingebunden (Waffenshop)in das Inventar.
                if($shopID == 2)
                    echo '<h1 class="inventoryName">Kleidungsshop</h1>';    //Der Name des 2 Shops wir eingebunden (Kleidungsshop)in das Inventar.
                if($shopID == 3)
                    echo '<h1 class="inventoryName">ItemShop</h1>';         //Der Name des 3 Shops wir eingebunden (ItemShop) in das Inventar.
                foreach($data as $item)
                {
                    $itemID = $item['productId'];       //Die Product Id wird in eine Variable geschrieben die anschließend übergeben wird.              
                    $slotID = $item['id'];              //Die slot Nummer/id wird in einer variable gespeichert um sie zu übergeben.
                    include_once 'traderSlot.php';      //Das php dokument traderSlot.php das die Trader Inventarslots erstellt wird eingbunde.
                    getTraderItem($itemID,$slotID);     //Eine Methode aus dem eingebundenen Script traderSlot.php wird aufgerufen es werden 
                                                        //die zuvor gespeicherten daten übergeben.
                }
                echo '</div>';                                                  //div Tag wird gesachlossen.          
            }elseif($_SESSION['currentshopID'] != -1){                          //falls keine daten zurückkommen soll trotzdem doie Inventar Box mit Namen Angezeigt werden
                echo '<div class="inventorySlotContainer">';                    //Erstellt die Inventar Box.
                echo '<h1 class="inventoryName">Shop '.$shopID.'</h1>';         //Schreibt den aktuellen Namen des Spielers in die Box.
                echo'<form>Der Shop hat zur zeit keine neuen angebote</form> '; //Nachricht an den nutzer das sein inventar leer ist.
                echo '</div>';                                                  //div Tag wird gesachlossen.
            }
            $conn = null;                                               //Datenbankverbindung wird getrennt  
        }catch(Exception $e){                                           //Falls die Verbindung mit der Datenbank fehlschlägt
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Dem Benutzer wird ein Fehler ausgegeben
            $conn=null;                                                 //Datenbankverbindung wird getrennt
            return false;                                               //Es wird false zurück gegeben 
        }
    }
    //ENDE: Shop Inventar ------------------------------------------------------------------------------------------------------------------------------------------------------------ 
    
    //Items im Shop kaufen -----------------------------------------------------------------------------------------------------------------------------------------------------------
    function buyItem($slotID,$price) {  //Methode wird unten im Dokument Aufgerufen.
        $conn = dbConnect();            //Datenbankverbindung wird Aufgebaunt.
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE id = ?');   //Das Item wechselt den besitzer iun der Datenbank
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );    //Prepared Statment für Sicherheit Die aktuelle Spieler Id wird übergeben.
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );            //Prepared Statment für Sicherheit Die slot id des zu änderdem Slots wird übergeben.
            $query->execute();                                          //Die datenbankänderung wird ausgeführt.
            RemoveUserCoins($price);                                    //Metode zum Coins abziehen in accaunt.php wird aufgerufen. Der preis wird übergeben.
            $coinsvalue = $_SESSION['EdgeCoins'];                       //Coins Value        
        }catch(Exception $e){                                           //Wenn die Datenbankverbindung fehlschlägt.
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Fehlerausgabe an den Nutzer.
            $conn=null;     //Datenbankverbindung wird getrennt.
            return false;   //Es wird false zurückgegeben.
        }
        $conn = null;       //Datenbanverbindung wird getrennt.
    }
    //ENDE: SHOP kaufen ---------------------------------------------------------------------------------------
    
    //Items im Shop verkaufen ---------------------------------------------------------------------------------
    function sellItem($slotID,$price) { //Methode wird unten im Dokument Aufgerufen.
        $conn = dbConnect();            //Datenbankverbindung wird Aufgebaunt.
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE id = ? ');  //Das Item wechselt den besitzer iun der Datenbank
            $query->bindParam( 1, $_SESSION['currentshopID'], PDO::PARAM_INT ); //Prepared Statment für Sicherheit Die aktuelle Shop Id wird übergeben.
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );                    //Prepared Statment für Sicherheit Die Slot id des zu änderdem Slots wird übergeben.
            $query->execute();                                                  //Die datenbankänderung wird ausgeführt.
            AddUserCoin($price);                                                //Metode zum Coins hinzuzufügen in accaunt.php wird aufgerufen. Der Wert wird übergeben.
            $coinsvalue = $_SESSION['EdgeCoins'];                               //Coins Value     
        }catch(Exception $e){                                                   //Wenn die Datenbankverbindung fehlschlägt.
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());          //Fehlerausgabe an den Nutzer.
            $conn=null;     //Datenbankverbindung wird getrennt.
            return false;   //Es wird false zurückgegeben.
        }
        $conn = null;       //Datenbanverbindung wird getrennt.
    } 
    //ENDE: Shop verkaufen -----------------------------------------------------------------------------------        
    //ENDE: PHP ----------------------------------------------------------------------------------------------
?>
<section class="playerInventoryFrame">
        <?php 
            // Überprüft ob daten von den items die verkauft oder gekauft wurden exestieren uns speichet diese zur verwendung
            if(isset($_POST['slotID'])&& isset($_POST['isShop'])&& isset($_POST['price'])){ //Wenn die 3 Übergebenen varisablen gesetzt sind 
                $slotID = $_POST['slotID'];                 //Die übergebene slotID wird zum übergeben in eine Vatiable gespeichert
                $price = $_POST['price'];                   //Der übergebene Preis wird zum übergeben in einer Variablle gespeichert 
                if ($_POST['isShop'] == 0 )                 //Wenn der button in Inventory slot geedrückt wurde und dieser von keinem shop kommmt 
                sellItem($slotID,$price);                   //Item wird verkauft bzw zum Händlerinventar transveriert.
                elseif($_SESSION['EdgeCoins'] >= $price)    //Wenn der Spieler genug EdgeCoins hat um das item zu kaufen
                buyItem($slotID,$price);                    //Das Item wird vom Shop gekauft bzw in das Spieler Inventar trasferiert.
                else                                        //Wenn der spieler zu wenig geld hat 
                {
                ?>
                    <script>    //Aufforderung an den Spieler mehr Münzen zu kaufen
                        alert("Du hast zu wenig Edge Coins bitte kaufe mehr indem du oben auf deine coins drückst oder verkaufe ein par Items aus deinem Inventar")
                    </script>
                <?php
                }   
            }
            if($_GET['action'] == 'noShop'){                //wenn auf die Startseite zurückgekehrt wird
                $shopIDGlobal = -1;                         //-1 weil keine id -1 in der Datenbank exestiert. Aufruf von navbar.php(EdgerunnerMarket)
                $_SESSION['currentshopID'] = $shopIDGlobal; //Die -1 wird in die session eiungetragen
            } 
            if($_GET['action'] == 'Shop1'){                 //Wenn der 1 Shop geöffnet wird. Aufruf von navbar.php (Waffen)
                $shopIDGlobal = 1;                          //shop id wird auf 1 gesetzt entspricht in der datenbank dem platz 1
                $_SESSION['currentshopID'] = $shopIDGlobal; //Die aktuelle session shop id wird gewsetzt

            }    
            if($_GET['action'] == 'Shop2'){                 //Wenn der 1 Shop geöffnet wird. Aufruf von navbar.php (Schutz)
                $shopIDGlobal = 2;                          //shop id wird auf 1 gesetzt entspricht in der datenbank dem platz 2
                $_SESSION['currentshopID'] = $shopIDGlobal; //Die aktuelle session shop id wird gewsetzt
            }  
            if($_GET['action'] == 'Shop3'){                 //Wenn der 1 Shop geöffnet wird. Aufruf von navbar.php (sonstiges)
                $shopIDGlobal = 3;                          //shop id wird auf 1 gesetzt entspricht in der datenbank dem platz 3
                $_SESSION['currentshopID'] = $shopIDGlobal; //Die aktuelle session shop id wird gewsetzt
            }
            getItemsInInventory();                          //Das spieler Inventar wird Erstellt und eingebunden
            GetShopInventory($_SESSION['currentshopID']);   //Das Shop Inventar wird erstellt und eingebunden
        ?>
</section>