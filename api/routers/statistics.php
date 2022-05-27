<?php
function route($method, $urlData, $formData) {
     
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once("api/config/database.php");
    include_once ("api/objects/order.php");
    
    $database = new Database();
    $db=$database->getConnection();
    $order = new Order($db);
    $order_arr=array();
    $len=count($urlData);
    $format=$urlData[$len-1];
    if($format=="xml")array_pop($urlData);
    // GET /orders/{orderId}
    if ($method === 'GET') {
        if(count($urlData) === 2){
            $from = $urlData[0];
            $to = $urlData[1];
            $stmt = $order->readDate($from,$to);
        }
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $order_item=array(
                "id"=>$id,
                "customer_id"=>$customer_id,
                "order_date"=>$order_date,
                "delivery_date"=>$delivery_date
            );
            array_push($order_arr, $order_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($order_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();}
        else echo json_encode($order_arr);
        return;
    }
    
    
    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    $answer=(array(
        'error' => 'Bad Request'
    ));
    if($format=="xml")echo xmlrpc_encode($answer);
    else echo json_encode($answer);
 
}
?>