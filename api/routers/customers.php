<?php
function route($method, $urlData, $formData) {
     
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once("api/config/database.php");
    include_once ("api/objects/customer.php");
    
    $database = new Database();
    $db=$database->getConnection();
    $customer = new Customer($db);
    $customer_arr=array();
    $len=count($urlData);
    $format=$urlData[$len-1];
    if($format=="xml")array_pop($urlData);
    if ($method === 'GET') {
        // GET /customers/{customerId}
        if(count($urlData) === 1){
            $customerId = $urlData[0];
            $stmt = $customer->read($customerId);
        }elseif(empty($urlData)){// GET /customers
            
            $stmt = $customer->readAll();
        }elseif(count($urlData)===2 && $urlData[1]=="full"){// GET /customers/{customerId}/full
            $stmt=$customer->readFull($urlData[0]);
        }
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if(count($urlData)===1)
            $customer_item=array(
                "id"=>$id,
                "company_name"=>$company_name,
                "phone"=>$phone
            );
            else
            $customer_item=array(
                "id"=>$id,
                "company_name"=>$company_name,
                "contact_name"=>$cintact_name,
                "address"=>$address,
                "city"=>$city,
                "country"=>$coutry,
                "phone"=>$phone,
                "fax"=>$fax
            );
            array_push($customer_arr, $customer_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($customer_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($customer_arr);
        return;
    }
    
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем товар в базу...
        $customer->insert($formData);
        // Выводим ответ клиенту
        $stmt=$customer->last();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $customer_item=array(
                'method' => 'POST',
                "id"=>$id,
                'formData' => $formData
            );
            array_push($customer_arr, $customer_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($customer_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($customer_arr);
        return;
    }
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id товара
        $customerId = $urlData[0];
        // Обновляем все поля товара в базе...
        $customer->replace($customerId, $formData);    
        // Выводим ответ клиенту
        $stmt=$customer->read($customerId);
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $customer_item=array(
                "id"=>$id,
                "company_name"=>$company_name,
                "contact_name"=>$cintact_name,
                "address"=>$address,
                "city"=>$city,
                "country"=>$coutry,
                "phone"=>$phone,
                "fax"=>$fax
            );
            array_push($customer_arr, $customer_item);

        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($customer_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($customer_arr);
     
        return;
    }
    if ($method === 'PATCH' && count($urlData) === 1) {
        // Получаем id товара
        $customerId = $urlData[0];
        
        // Обновляем только указанные поля товара в базе...
        $customer->update($customerId, $formData);
        // Выводим ответ клиенту
        $stmt=$customer->read($customerId);
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $customer_item=array(
                "id"=>$id,
                "company_name"=>$company_name,
                "contact_name"=>$cintact_name,
                "address"=>$address,
                "city"=>$city,
                "country"=>$coutry,
                "phone"=>$phone,
                "fax"=>$fax
            );
            array_push($customer_arr, $customer_item);
        }
        http_response_code(200);
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($customer_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($customer_arr);
     
        return;
    }
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id товара
        $customerId = $urlData[0];
     
        // Удаляем товар из базы...
        $customer->delete($customerId);
        // Выводим ответ клиенту
        $customer_arr=array(
            'method' => 'DELETE',
            'id' => $customerId
        );
        if($format=="xml"){
            include_once("api/helps.php");
            $xml=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
            array_to_xml($customer_arr, $xml);
            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            $domxml->loadXML($xml->asXML());
            echo $domxml->saveXML();
        }
        else echo json_encode($customer_arr);
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