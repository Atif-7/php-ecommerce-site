<?php
session_start();
require_once 'config/config.php';
require_once "config/database.php";
require 'vendor/autoload.php';
require_once 'vendor/stripe/stripe-php/init.php';

if (isset($_POST['stripeToken'])) {
  \Stripe\Stripe::setVerifySslCerts(false);

  $token = $_POST['stripeToken'];

  $data = \Stripe\Charge::create([
    'amount' => $_POST['unit_amount'],
    'currency' => 'usd',
    'description'=> '',
    'source' => $token, 
  ]);
}else {
  echo "<script> alert('the process has not completed, try again later or contact website's email for help');
  window.location.href = 'vew_cart.php'; </script>";
}

if (isset($_GET['session_id'])) {
  // Assume session contains user_id and cart total
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];
  // $total = $_SESSION['total_amount'];
  // $payment_id = $_POST['payment_id']; // Stripe session/payment id
  // Load environment variables
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
  \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

  // Get the session id from URL
  $session_id = $_GET['session_id'];

  // Fetch the session from Stripe
  $session = \Stripe\Checkout\Session::retrieve($session_id);
  // Get payment status, total amount, customer email, etc
  $payment_status = $session->payment_status; // usually 'paid'
  if ($session->payment_status == 'paid') {
    $amount_total = $session->amount_total / 100; // Stripe sends amount in cents
    $payment_id = $session->payment_intent; // this is your real payment ID
    // Insert order
    $check = $query->getDataWhere("id","orders", "WHERE payment_id = '$payment_id'");

    if ($check->num_rows == 0) {
      $data = array("user_id"=>$user_id, "user_name"=>$user_name, "total_amount"=>$amount_total, "payment_status"=>$payment_status,"order_status"=>"Pending","payment_id"=>$payment_id);
      $order_id = $query->insertData('orders',$data);
       // Insert order items
      foreach ($_SESSION['cart'] as $product) {
        $product_id = $product['product_id'];
        $name = $product['product_name'];
        $quantity = $product['product_quantity'];
        $price = $product['product_price'];
        $data = array("order_id"=>$order_id, "product_id"=>$product_id, "name"=>$name, "quantity"=>$quantity,"price"=>$price);
        $query->insertData("order_items",$data);
      }
      // Empty cart after placing order
      unset($_SESSION['cart']);
      // $_SESSION['order_id'] = mysqli_insert_id($conn); // Get the last inserted order ID
      header("Location: order_successful.php");
      exit;
    }
    else {
      $order_placed = "❌ Order already placed!";
    }
  } else {
    echo "❌ Payment not completed."; exit;
  }
} else {
  echo "❌ No session ID provided!"; exit;
}

require_once "head.php";
echo "<title>E-Shop - Thank You</title>";
require_once "header.php";
?>

    <section class="container">
      <h3><?= $order_placed ?></h3>
      <h3 class="mt-5 text-center">Thanks for the Payment of <?php echo $_SESSION['total_amount'].'$'?> you will get your product within 2 days.</h3>
      <div class="d-flex justify-content-center mt-3">
        <a href="user/account.php" class="btn btn-primary">Go to Dashboard </a>
      </div>
    </section>
