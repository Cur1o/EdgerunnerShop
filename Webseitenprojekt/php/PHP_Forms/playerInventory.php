<section>
    <article>
        <?php 
            $conn = dbConnect();
            try{
                // $query = $conn->prepare('SELECT productId FROM user_resources WHERE id = "'.$_SESSION['id'].'";');
                // $query->execute;
                $query = $conn->prepare('SELECT productId 
                FROM user_resources
                WHERE user_resources.userId = ?');
                $query->bindParam( 1, $id, PDO::PARAM_INT );
                $query->execute();

                if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
                   foreach($data as $item)
                   {
                        $itemid = $item['id'];
                        include 'PHP/PHP_Forms/inventorySlot.php';
                        getItem($itemid);
                   } 
                }
            }catch(Exception $e){
                userMessage('Es ist Fehler aufgetreten'.$e->getMessage());
                $conn=null;
                return false;
            }


            
        ?>
    </article> 
</section>