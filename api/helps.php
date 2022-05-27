<?php
function getFormData($method) {
 
 // GET или POST: данные возвращаем как есть
 if ($method === 'GET') return $_GET;
 if ($method === 'POST') return $_POST;

 // PUT, PATCH или DELETE
 $data = array();
 $exploded = explode('&', file_get_contents('php://input'));

 foreach($exploded as $pair) {
     $item = explode('=', $pair);
     if (count($item) == 2) {
         $data[urldecode($item[0])] = urldecode($item[1]);
     }
 }

 return $data;
}

// XML BUILD RECURSIVE FUNCTION
function array_to_xml($array, &$xml) {        
    foreach($array as $key => $value) {               
        if(is_array($value)) {            
            if(!is_numeric($key)){
                $subnode = $xml->addChild($key);
                array_to_xml($value, $subnode);
            } else {
                $subnode = $xml->addChild("item");
                array_to_xml($value, $subnode);
            }
        } else {
            $xml->addChild($key, $value);
        }
    }        
}
?>