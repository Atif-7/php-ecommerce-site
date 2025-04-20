<?php
session_start();
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
unset($_SESSION['cart']);

require_once 'config/config.php';
require_once "config/database.php";
require_once "head.php";
echo "<title>E-Shop - Thank You</title>";
require_once "header.php";
?>

    <section class="container">

      <h3 class="mt-5 text-center">Thanks for the Payment of <?php echo $_SESSION['total_amount'].'$'?> you will get your product within 2 days.</h3>
      <div class="d-flex justify-content-center mt-3">
        <a href="index.php" class="btn btn-primary">Back to Home </a>
      </div>
    </section>
<?php include "footer.php" ?>