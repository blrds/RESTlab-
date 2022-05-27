<?php
    class Product{
        private $conn;
        private $products_table_name="products";

        public $id;
        public $product_name;
        public $price;

        public function __construct($db){
            $this->conn=$db;
        }
       
        function readAll(){
          $query = "SELECT * 
                    FROM ".$this->products_table_name."
                    ORDER BY product_name";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function read($productId){
            $query = "SELECT * 
                      FROM ".$this->products_table_name."
                      WHERE id=".$productId."
                      ORDER BY product_name";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
        function last(){
            $query = "SELECT *
                      FROM ".$this->products_table_name."  
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
            $query="INSERT INTO ".$this->products_table_name."(".$columns.") VALUES(".$vals.")";
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
            $query="REPLACE INTO ".$this->products_table_name." (".$columns.") VALUES(".$vals.")";
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
            $query="UPDATE ".$this->products_table_name." 
                    SET ".$vals."
                    WHERE id=".$id;
            echo $query." query\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }

        function delete($id){
            $query="DELETE FROM ".$this->products_table_name." 
                    WHERE id=".$id;
            echo $query."\n";
            $stmt=$this->conn->prepare($query);            
            $stmt->execute();
            return $stmt;
        }
    }
?>