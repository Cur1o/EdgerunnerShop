<?php 
    function whipeTrader($shopID){
        $conn = dbConnect();
        try{
            $query = $conn->prepare('DELETE FROM user_resources WHERE user_resources.userid = ?');
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );
            $query->execute();
            $conn = null;
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        refillTrader($shopID);
    }
    function refillTrader($shopID){
        if($shopID == 1)
        {
            $itemtype = $shopID;
        }elseif($shopID == 2)
        {
            $itemtype = $shopID;
        }elseif($shopID == 3)
        {
            $itemtype = $shopID;
        }
        $conn = dbConnect();
        try{
            $query = $conn->prepare('INSERT INTO user_resources (productid, userid, count)
                                    SELECT id, ?, 1
                                    FROM products
                                    WHERE itemtype = ?;');
            $query->bindParam( 1, $shopID, PDO::PARAM_INT );
            $query->bindParam( 2, $itemtype, PDO::PARAM_INT );
            $query->execute();
        }catch(Exception $e){
            userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
            $conn=null;
            return false;
        }
        $conn = null;
    }

?>