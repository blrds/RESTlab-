<?php
    class Order{
        private $conn;
        private $orders_table_name="orders";

        public $id;
        public $customer_id;
        public $order_date;
        public $delivery_date;

        public function __construct($db){
            $this->conn=$db;
        }
       
        function readAll(){
          $query = "SELECT * 
                    FROM ".$this->orders_table_name;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function readCustomer($customer_id){
            $query = "SELECT * 
                      FROM ".$this->orders_table_name."
                      WHERE customer_id=".$customer_id;
              $stmt=$this->conn->prepare($query);            
              $stmt->execute();
              return $stmt;
          }

        function read($orderId){
            $query = "SELECT * 
                      FROM ".$this->orders_table_name."
                      WHERE id=".$orderId;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function readDate($from, $to){
            $query = "SELECT * 
                      FROM ".$this->orders_table_name."
                      WHERE order_date>'".$from."' AND order_date<'".$to."'";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function last(){
            $query = "SELECT *
                      FROM ".$this->orders_table_name."  
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
            $query="INSERT INTO ".$this->orders_table_name." (".$columns.") VALUES(".$vals.")";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function replace($id, $data){
            $columns="id";
            $vals="".$id;
            $keys=array_keys($data);
            for($i=0;$i<count($keys);$i++){
                $columns=$columns.",".$keys[$i];
                $vals=$vals.",".$data[$keys[$i]];    
            }
            $query="REPLACE INTO ".$this->orders_table_name." (".$columns.") VALUES(".$vals.")";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function update($id, $data){
            $vals="";
            $keys=array_keys($data);
            for($i=0;$i<count($keys);$i++){
                if($i!=0)$vals=$vals.",";
                $vals=$vals."".$keys[$i]."=".$data[$keys[$i]];    
            }
            $query="UPDATE ".$this->orders_table_name." 
                    SET ".$vals."
                    WHERE id=".$id;
            echo $query." query\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function delete($id){
            $query="DELETE FROM ".$this->orders_table_name." 
                    WHERE id=".$id;
            echo $query."\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
    }
?>