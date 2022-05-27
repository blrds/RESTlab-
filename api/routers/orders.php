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
        if(count($urlData) === 1){
            $orderId = $urlData[0];
            $stmt = $order->read($orderId);
        }elseif(empty($urlData)){
            $stmt = $order->readAll();
        }elseif(count($urlData)===2 && $urlData[0]=="customer"){
            $stmt=$order->readCustomer($urlData[1]);
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
    
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем товар в базу...
        $order->insert($formData);
        // Выводим ответ клиенту
        $stmt=$order->last();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $order_item=array(
                'method' => 'POST',
                "id"=>$id,
                'formData' => $formData
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
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id товара
        $orderId = $urlData[0];
        // Обновляем все поля товара в базе...
        $order->replace($orderId, $formData);    
        // Выводим ответ клиенту
        $stmt=$order->read($orderId);
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
    if ($method === 'PATCH' && count($urlData) === 1) {
        // Получаем id товара
        $orderId = $urlData[0];
        
        // Обновляем только указанные поля товара в базе...
        $order->update($orderId, $formData);
        // Выводим ответ клиенту
        $stmt=$order->read($orderId);
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
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id товара
        $orderId = $urlData[0];
     
        // Удаляем товар из базы...
        $order->delete($orderId);
        // Выводим ответ клиенту
        $order_arr=array(
            'method' => 'DELETE',
            'id' => $orderId
        );
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