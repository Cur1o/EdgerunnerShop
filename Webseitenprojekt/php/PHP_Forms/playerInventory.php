<section>
    <article>
        <?php 
            $conn = dbConnect();
            try{
                // $query = $conn->prepare('SELECT productId FROM user_resources WHERE id = "'.$_SESSION['id'].'";');
                // $query->execute;
                $query = $conn->prepare('SELECT products.id, products.productId, products.name, 
                products.description, products.image, 
                products.price, user_resources.count, products.isConsumeable
                FROM products
                INNER JOIN user_resources
                ON products.id = user_resources.productId
                WHERE user_resources.userId = ?');
                $query->bindParam( 1, $id, PDO::PARAM_INT );
                $query->execute();

                if($data = $query->fetchAll(PDO::FETCH_ASSOC)){
                   foreach($data as $item)
                   {
                        $itemid = $item['id'];
                        
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