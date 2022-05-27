<?php
function route($method, $urlData, $formData) {
     
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once("api/config/database.php");
    include_once ("api/objects/product.php");
    
    $database = new Database();
    $db=$database->getConnection();
    $product = new Product($db);
    $product_arr=array();
    $len=count($urlData);
    $format=$urlData[$len-1];
    if($format=="xml")array_pop($urlData);
    // GET /products/{productId}
    if ($method === 'GET') {
        if(count($urlData) === 1){
            $productId = $urlData[0];
            $stmt = $product->read($productId);
        }elseif(empty($urlData)){
            $stmt = $product->readAll();
        }
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $product_item=array(
                "id"=>$id,
                "product_name"=>$product_name,
                "price"=>$price
            );
            array_push($product_arr, $product_item);
        }
        
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($product_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($product_arr);
        return;
    }
    
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем товар в базу...
        $product->insert($formData);
        // Выводим ответ клиенту
        $stmt=$product->last();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $product_item=array(
                "id"=>$id,
                "product_name"=>$product_name,
                "price"=>$price
            );
            array_push($product_arr, $product_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($product_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($product_arr);
        return;
    }
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id товара
        $productId = $urlData[0];
        // Обновляем все поля товара в базе...
        $product->replace($productId, $formData);    
        // Выводим ответ клиенту
        $stmt=$product->read($productId);
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $product_item=array(
                "id"=>$id,
                "product_name"=>$product_name,
                "price"=>$price
            );
            array_push($product_arr, $product_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($product_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($product_arr);
     
        return;
    }
    if ($method === 'PATCH' && count($urlData) === 1) {
        // Получаем id товара
        $productId = $urlData[0];
        
        // Обновляем только указанные поля товара в базе...
        $product->update($productId, $formData);
        // Выводим ответ клиенту
        $stmt=$product->read($productId);
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $product_item=array(
                "id"=>$id,
                "product_name"=>$product_name,
                "price"=>$price
            );
            array_push($product_arr, $product_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($product_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($product_arr);
     
        return;
    }
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id товара
        $productId = $urlData[0];
     
        // Удаляем товар из базы...
        $product->delete($productId);
        // Выводим ответ клиенту
        $product_arr=array(
            'method' => 'DELETE',
            'id' => $orderId
        );
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($product_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($product_arr);
         
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