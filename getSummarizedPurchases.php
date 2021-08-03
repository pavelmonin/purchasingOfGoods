<?php
header("Content-Type: application/json; charset=UTF-8");
    $obj = json_decode($_POST["x"], false);

    require('mysql_connect.php');
    $conn = new mysqli($host, $user, $password, $db);

    $sql ="CALL uspGetSummarizedPurchasesByPeriod('$obj->start', '$obj->end')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $outp = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($outp);
?>