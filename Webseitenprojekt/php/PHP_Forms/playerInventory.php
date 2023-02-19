<?php
    //PHP Anfang ------------------------------------------------------------------------------------------------------------------------------------------------
    require_once('PHP/init.php');       //Die php datei init.php muss einmal included worden sein
    require_once('PHP/account.php');    //Die php datei account.php muss einmal included worden sein
    require_once('PHP/admin.php');      //Die php datei admin.php muss einmal included worden sein

    if($_SESSION['currentshopID'] == 0)     //id 0 in der datenbank gehört dem Admin der auch ein inventar besitzt.
        $_SESSION['currentshopID'] = -1;    //Es gibt keinen benutzer mit der id -1. Der Nutzen : Der shop bleibt ausgeblendet.

    //Erstellt das Spieler Inventar -----------------------------------------------------------------------------------------------------------------------------
    function getItemsInInventory(){         //Wird unten im selben dokument aufgerufen.
        $conn = dbConnect();                //Datenbankverbindung aufbauen.
        try{
            // Alle Inventar Slot id's von dem spieler abrufen.
            $query = $conn->prepare('SELECT productId, id
            FROM user_resources
            WHERE user_resources.userId = ?');
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
            $query = $conn->prepare('SELECT productId, id FROM user_resources 
                                     WHERE user_resources.userId = ?');     //
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );                //
            $query->execute();                                              //
            if($data = $query->fetchAll(PDO::FETCH_ASSOC)){                 //
                echo '<div class="inventorySlotContainer">';                //
                echo '<h1 class="inventoryName">Shop '.$shopID.'</h1>';     //
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
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE user_resources.id = ?');
            $query->bindParam( 1, $_SESSION['id'], PDO::PARAM_INT );    //Prepared Statment für Sicherheit Die aktuelle Spieler Id wird übergeben.
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );            //Prepared Statment für Sicherheit Die slot id des zu enderdem Slots wird übergeben.
            $query->execute();                                          //Die datenbankänderung wird ausgeführt.
            RemoveUserCoins($price);                                    //Metode zum Coins abziehen in accaunt.php wird aufgerufen. Der preis wird übergeben.
            $coinsvalue = $_SESSION['EdgeCoins'];                       //Coins Value wiird gesetzt        
        }catch(Exception $e){                                           //Wenn die Datenbankverbindung fehlschlägt.
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Fehlerausgabe an den Nutzer.
            $conn=null;                                                 //Datenbankverbindung wird getrennt.
            return false;                                               //Es wird false zurückgegeben.
        }
        $conn = null;                                                   //Datenbanverbindung wird getrennt.
    }
    //ENDE: SHOP kaufen ---------------------------------------------------------------------------------------
    
    //Items im Shop verkaufen ---------------------------------------------------------------------------------
    function sellItem($slotID,$price) { //Methode wird unten im Dokument Aufgerufen.
        $conn = dbConnect();            //Datenbankverbindung wird Aufgebaunt.
        try{
            $query = $conn->prepare('UPDATE user_resources SET userId = ? WHERE user_resurces.id = ? ');
            $query->bindParam( 1, $_SESSION['currentshopID'], PDO::PARAM_INT );
            $query->bindParam( 2, $slotID, PDO::PARAM_INT );
            $query->execute();
            AddUserCoin($price);
            $coinsvalue = $_SESSION['EdgeCoins'];
        }catch(Exception $e){
            echo 'FEHLER';
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    } 
    //ENDE: Shop verkaufen -----------------------------------------------------------------------------------        
    //ENDE: PHP ----------------------------------------------------------------------------------------------
?>
<section class="playerInventoryFrame">
        <?php 
            // Überprüft ob daten von den items die verkauft oder gekauft wurden exestieren uns speichet diese zur verwendung
            if(isset($_POST['slotID'])&& isset($_POST['isShop'])&& isset($_POST['price'])){
                $slotID = $_POST['slotID'];
                $price = $_POST['price'];
                if ($_POST['isShop'] == 0 )                 
                sellItem($slotID,$price);                    
                elseif($_SESSION['EdgeCoins'] >= $price)    // Dass der Spieler wenn er ztu wenig münzen hat auch nichtsmehr kaufen kann
                buyItem($slotID,$price);                    // wenn die gesendete slot id von einem shop kommt
                else
                {
                ?>
                    <script>
                        alert("Du hast zu wenig edge coins bitte kaufe mehr indem du oben auf deine coins drückst")
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
                $_SESSION['currentshopID'] = $shopIDGlobal;

            }    
            if($_GET['action'] == 'Shop2'){                 //Wenn der 1 Shop geöffnet wird. Aufruf von navbar.php (Schutz)
                $shopIDGlobal = 2;                          //shop id wird auf 1 gesetzt entspricht in der datenbank dem platz 2
                $_SESSION['currentshopID'] = $shopIDGlobal;
            }  
            if($_GET['action'] == 'Shop3'){                 //Wenn der 1 Shop geöffnet wird. Aufruf von navbar.php (sonstiges)
                $shopIDGlobal = 3;                          //shop id wird auf 1 gesetzt entspricht in der datenbank dem platz 3
                $_SESSION['currentshopID'] = $shopIDGlobal;
            }
            getItemsInInventory();
            GetShopInventory($_SESSION['currentshopID']);
        ?>
</section>