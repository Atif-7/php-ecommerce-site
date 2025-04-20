<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $query->getDataById('*','products',$id);
    if ($data->num_rows > 0) {
        $row = mysqli_fetch_assoc($data);
        session_start();
        $_SESSION['name'] = $row['name'];
    }
    $result = $query->deleteData('products',$id);
    if($result){
        $_SESSION['delete'] = "<b>{$_SESSION['name']}</b> has <b>deleted</b> successfully";
        header("Location: products.php");
    }
}

?>
