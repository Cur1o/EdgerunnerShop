<?php 
    function whipeTrader($shopID){  //Function um alle items aus dem Händler Inventar zu löschen. Wird beim ausloggen des nutzeers ausgefüphrt in index.php.
        $conn = dbConnect();        //Datenbankverbindung Aufbauen.
        try{
            $query = $conn->prepare('DELETE FROM user_resources WHERE user_resources.userid = ?');  //Alle Inventarslots des shops werden gelöscht.
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );            //Prepared statment indem der Shop desen Inventar gelöscht werden soll übergeben wird.
            $query->execute();                                          //Datenbank Löschung durchführen.                                          
            $conn = null;                                               //Datenbankverbindung trennen.
        }catch(Exception $e){                                           //Wenn etwas bei der Datenbankverbindung nicht klappt.
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Dem Benutzer wir ein fehler ausgegeben.
            $conn=null;                                                 //Datenbankverbiindung wird getrennt.
            return false;                                               //Es wird false zurückgegeben.
        }
        refillTrader($shopID);      //Das Inventar des Shops wird mit den shop spezifischen sachen aufgefgüllt.
    }
    function refillTrader($shopID){ //function um das entsprechende. 
        if($shopID == 1)            //Wenn ShopID 1 ist.               
            $itemtype = $shopID;    //der item typ wird aus 1 gesetzt was weapons in der Datenbank entspricht.
        elseif($shopID == 2)        //Wenn ShopID 2 ist.
            $itemtype = $shopID;    //der item typ wird aus 2 gesetzt was armor in der Datenbank entspricht.
        elseif($shopID == 3)        //Wenn ShopID 3 ist.
            $itemtype = $shopID;    //der item typ wird aus 3 gesetzt was items in der Datenbank entspricht.
        $conn = dbConnect();        //Datenbankverbindung wird aufgebaut.
        try{
            $query = $conn->prepare('INSERT INTO user_resources (productid, userid, count)
                                    SELECT id, ?, 1
                                    FROM products
                                    WHERE itemtype = ?;');
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );    //Prepared Statment für die aktuelle shopID.
            $query->bindParam( 2, $itemtype, PDO::PARAM_INT );  //Prepared Statment für den aktuellen $itemtype.
            $query->execute();                                  //Datenbankbefehl wird ausgeführt.
        }catch(Exception $e){                                   //Falls die Datenbankverbindung fehlschlägt.
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());  //Fehlermeldung an den nurtzer.
            $conn=null;     //Datenbankverbindung wird getrennt.
            return false;   //es wird false zurückgegeben.
        }
        $conn = null;       //Datenbankverbindung wird getrennt.
    }
?>