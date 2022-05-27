<?php
    class Customer{
        private $conn;
        private $customers_table_name="customers";

        public $id;
        public $company_name;
        public $contact_name;
        public $address;
        public $city;
        public $country;
        public $phone;
        public $fax;

        public function __construct($db){
            $this->conn=$db;
        }
       
        function readAll(){
          $query = "SELECT * 
                    FROM ".$this->customers_table_name;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function read($customerId){
            $query = "SELECT id, company_name, phone 
                      FROM ".$this->customers_table_name."
                      WHERE id=".$customerId;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function readFull($customerId){
            $query = "SELECT * 
                      FROM ".$this->customers_table_name."
                      WHERE id=".$customerId;
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
        function last(){
            $query = "SELECT *
                      FROM ".$this->customers_table_name."  
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
            $query="INSERT INTO ".$this->customers_table_name." (".$columns.") VALUES(".$vals.")";
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
            $query="REPLACE INTO ".$this->customers_table_name." (".$columns.") VALUES(".$vals.")";
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
            $query="UPDATE ".$this->customers_table_name."  
                    SET ".$vals."
                    WHERE id=".$id;
            echo $query." query\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function delete($id){
            $query="DELETE FROM ".$this->customers_table_name." 
                    WHERE id=".$id;
            echo $query."\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
    }
?>