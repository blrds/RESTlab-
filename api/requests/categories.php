<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/categoryProducts.php';

$database = new Database();
$db=$database->getConnection();
$category=$_GET["c"];

$cat = new CategoryProduct($db, $category);

$stmt = $cat->read();
$num = $stmt->rowCount();

if($num>0){
    $category_arr=array();
    $category_arr["records"]=array();

    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $category_item=array(
            "id"=>$id,
            "product_code"=>$product_code,
            "product_name"=>$product_name,
            "description"=>html_entity_decode($description),
            "standart_cost"=>$standart_cost,
            "category"=>$category
        );
        array_push($category_arr["records"], $category_item);
    }
    http_response_code(200);
    echo json_encode($category_arr);
}
else{
        http_response_code(404);
        echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}
?>