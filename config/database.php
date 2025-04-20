<?php
class database
{
    private $server;
    private $username;
    private $password;
    private $db_name;
    protected $conn;

    public function __construct() {
        $this->server = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db_name = "ecommerce";

        $this->conn = new mysqli($this->server,$this->username,$this->password,$this->db_name);
        if ($this->conn->connect_error) {
            die("database connect error :".$this->conn->connect_error);
        }
    }
}

class Query extends database
{
    public function insertData($table,$data) {
       
        $values = array();
        $fields = array();
        foreach ($data as $field => $value) {
            $values[] = $value;
            $fields[] = $field;
        }
        $fields = implode(",",$fields);
        $values = implode("','",$values);
        $values = "'".$values."'";

        $sql = "INSERT INTO `$table` ($fields) VALUES($values)";
        $result = $this->conn->query($sql);
        return $result;

    }
    function updateData($tablename,$data,$f_value){

        $length = count($data);
        $i = 1;
        $sql = "UPDATE `$tablename` SET ";

        foreach ($data as $key => $value) {
            $sql = ($i == $length) ? $sql .= " $key = '$value'" :
            $sql .= " $key = '$value',"; $i++; 
        }
       
        $sql = $sql." WHERE id = $f_value" ; 
        
        $result = $this->conn->query($sql);
        return $result;
        
    }
    public function deleteData($table,$id){
        $sql = "DELETE FROM $table WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result;
    }
    public function getData($fields,$table,$quantity){

    // Base query
        $sql = "SELECT $fields FROM $table";

        // Append LIMIT only if quantity is a valid number
        if (is_numeric($quantity) && $quantity > 0) {
            $sql .= " LIMIT $quantity";
            $result = $this->conn->query($sql);
            return $result;
        }else{
            $result = $this->conn->query($sql);
            return $result;
        }
    }
    public function getDataWhere($fields,$table,$where){
        $sql = "SELECT $fields FROM $table $where";
        $result = $this->conn->query($sql);
        return $result;
    }
    public function getDataById($fields,$table,$id){
        $sql = "SELECT $fields FROM $table WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result;
    }
    public function searchProducts($keyword) {
        $keyword = mysqli_real_escape_string($this->conn,$keyword); // Secure input
        $query = "SELECT * FROM products 
                  WHERE name LIKE '%$keyword%' 
                  OR description LIKE '%$keyword%' 
                --   OR category LIKE '%$keyword%'";
    
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function searchSuggestions($keyword) {
    $keyword = mysqli_real_escape_string($this->conn,$keyword);
    $query = "SELECT id, name, description FROM products 
              WHERE name LIKE '%$keyword%' 
              OR description LIKE '%$keyword%' ORDER BY name ASC";
            //   LIMIT 5; // Limit results for speed

    $result = $this->conn->query($query);
      // Debugging: Check SQL execution
    if (!$result) {
        die("SQL Error: " . $this->conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
    }
}

$db = new database;
$query = new Query;
// $data = array();
// $data = ['name'=>'atif','email'=>'atif@1gmail.com','password'=>'1111'];
// $obj->getDataById('*','users','1');
