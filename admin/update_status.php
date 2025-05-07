<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_status'])) {
    $order_id = (int) $_POST['order_id'];
    $status = mysqli_real_escape_string($db->getConn(), $_POST['order_status']);
    $data = array('order_status' => $status);
    $result = $query->updateData("orders", $data, $order_id);
    if ($result) {
        $_SESSION['success'] = "order status updated successfully";
    }else{
        $_SESSION['error'] = "order status failed to update";
    }
    header("Location: orders.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_status'])) {
    $order_id = (int) $_POST['order_id'];
    $status = mysqli_real_escape_string($db->getConn(), $_POST['payment_status']);
    $data = array('payment_status' => $status);
    $result = $query->updateData("orders", $data, $order_id);
    if ($result) {
        $_SESSION['success'] = "payment status updated successfully";
    }else{
        $_SESSION['error'] = "payment status failed to update";
    }
    header("Location: orders.php");
    exit;
}
?>
