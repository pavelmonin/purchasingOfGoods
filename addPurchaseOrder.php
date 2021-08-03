<?php 

        $data = $_POST['title'];


        require('mysql_connect.php');


        $sql = "call uspAddPurchaseOrder ('$data')";
        $query = $pdo->prepare($sql);
        $res=$query->execute();
        //////////////////////////////////////////
        $r = $query->fetch(PDO::FETCH_OBJ);
        if (($r->res == 0) && ($r->res != ""))
        {
            echo('Готово');
            
        }
        else {

            echo 'ошибка';

        }


?>