<?php
session_start();
require_once 'config/config.php';
require_once "config/database.php";
require_once "head.php";
echo "<title>Order Success | E-Shop</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .success-card {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        .success-card h1 {
            color: #27ae60;
            font-size: 48px;
            margin-bottom: 10px;
        }
        .success-card p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }
        .success-card a {
            display: inline-block;
            padding: 12px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }
    </style>";
require_once "header.php";
?>
<section>
<div class="success-card">
    <h1>Thank You!</h1>
    <p>Your order has been placed successfully.<br>We’ll send you an update when it's shipped within 2 days.</p>
    <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a><br>
    <a href="user/account.php" class="btn btn-success mt-2">View My Orders</a>
</div>
</section>
<?php include "footer.php" ?>