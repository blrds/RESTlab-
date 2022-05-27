<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/productSells.php';

$database = new Database();
$db=$database->getConnection();
$product=$_GET["p"];

$sells = new ProductSells($db,$product);

$stmt = $sells->read();
$num = $stmt->rowCount();

if($num>0){
    $sells_arr=array();
    $sells_arr["records"]=array();

    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $sells_item=array(
            "id"=>$id,
            "product_code"=>$product_code,
            "product_name"=>$product_name,
            "category"=>$category,
            "order_date"=>$order_date
        );
        array_push($sells_arr["records"], $sells_item);
    }
    http_response_code(200);
    echo json_encode($sells_arr);
}
else{
        http_response_code(404);
        echo json_encode(array("message" => "Продажи не найдены."), JSON_UNESCAPED_UNICODE);
}
?>