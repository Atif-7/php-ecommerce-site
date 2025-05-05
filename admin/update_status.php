<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_status'])) {
    $order_id = (int) $_POST['order_id'];
    $status = mysqli_real_escape_string($db->getConn(), $_POST['order_status']);
    $data = array('order_status' => $status);
    $result = $query->updateData("orders", $data, $order_id);
    if ($result) {
        header("Location: orders.php");
        exit;
    }else{
        die();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_status'])) {
    $order_id = (int) $_POST['order_id'];
    $status = mysqli_real_escape_string($db->getConn(), $_POST['payment_status']);
    $data = array('payment_status' => $status);
    $result = $query->updateData("orders", $data, $order_id);
    if ($result) {
        header("Location: orders.php");
        exit;
    }else{
        die();
    }
}
?>
