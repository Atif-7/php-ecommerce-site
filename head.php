<?php 
$current_page = basename($_SERVER['PHP_SELF']);

// define('PAGE_PATH',strpos($_SERVER['PHP_SELF'],'user/') or strpos($_SERVER['PHP_SELF'],needle: 'admin/')  !== false ? '../' : '');
require_once __DIR__.'/config/config.php';
require_once __DIR__."/config/database.php";

$query = new Query();

$main_categories = $query->getDataWhere('*','categories','WHERE parent_id IS NULL');
$categories = $query->getData('*','categories','all')->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1bb566">
    <link rel="shortcut icon" href="<?php echo BASE_URL ; ?>fav_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo BASE_URL ; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL ; ?>assets/css/bootstrap.min.css">