<?php
    class OrderDetails{
        private $conn;
        private $order_details_table_name="order_details";

        public $order_id;
        public $product_id;
        public $quantity;
        public $unit_price;
        public $discount;

        public function __construct($db){
            $this->conn=$db;
        }
       
        function readAll(){
          $query = "SELECT * 
                    FROM ".$this->order_details_table_name;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
        function read($orderId, $product_id){
            $query = "SELECT * 
                      FROM ".$this->order_details_table_name."
                      WHERE order_id=".$orderId." AND product_id=".$product_id;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
        function readOrder($orderId){
            $query = "SELECT * 
                      FROM ".$this->order_details_table_name."
                      WHERE order_id=".$orderId;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        

        function readProduct($productId){
            $query = "SELECT * 
                      FROM ".$this->order_details_table_name."
                      WHERE product_id=".$productId;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
        function last(){
            $query = "SELECT *
                      FROM ".$this->order_details_table_name."  
                      ORDER BY id 
                      DESC LIMIT 1";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function insert($data){
            $columns="";
            $vals="";
            $keys=array_keys($data);
            for($i=0;$i<count($keys);$i++){
                if($i!=0){
                    $vals=$vals.",";
                    $columns=$columns.",";
                }
                $columns=$columns."".$keys[$i];
                $vals=$vals."".$data[$keys[$i]];    
            }
            $query="INSERT INTO ".$this->order_details_table_name." (".$columns.") VALUES(".$vals.")";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function replace($orderid, $productid, $data){
            $columns="order_id, product_id";
            $vals="".$orderid.",".$productid;
            $keys=array_keys($data);
            for($i=0;$i<count($keys);$i++){
                $columns=$columns.",".$keys[$i];
                $vals=$vals.",".$data[$keys[$i]];    
            }
            $query="REPLACE INTO ".$this->order_details_table_name." (".$columns.") VALUES(".$vals.")";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function update($orderid, $productid, $data){
            $vals="";
            $keys=array_keys($data);
            for($i=0;$i<count($keys);$i++){
                if($i!=0)$vals=$vals.",";
                $vals=$vals."".$keys[$i]."=".$data[$keys[$i]];    
            }
            $query="UPDATE ".$this->order_details_table_name." 
                    SET ".$vals."
                    WHERE order_id=".$orderid." AND product_id=".$productid;
            echo $query." query\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function delete($orderid, $productid){
            $query="DELETE FROM ".$this->order_details_table_name." 
                    WHERE order_id=".$orderid." AND product_id=".$productid;
            echo $query."\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
    }
?>