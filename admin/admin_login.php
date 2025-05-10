<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
    header("location: admins.php");
}
require_once '../config/database.php';
require_once '../config/config.php';

$email = $password = $emailErr = $passwordErr = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($email)) {
        $emailErr = "Email is Required";
    } else {
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $emailErr = "Invalid Email Address";
        }
    }    
    if (empty($password)) {
        $passwordErr = "Password is Required";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $conn = $db->getConn();

        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: index.php");
            exit;
        } else {
            $error = "<p class='alert alert-danger mb-2 text-center'>Invalid login credentials</p>";
        }
    }else{
        $error = "<p class='alert alert-danger mb-2 text-center'> there were some errors</p>";
    }
}

require_once '../head.php';
echo "<title>Admin Login | E-Shop</title><style>
    section {
            margin-top: 105px;
            margin-bottom: 70px;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 110px;
        }
    }</style>";
require_once 'header.php';
?>

<section class="shop">
<form method="POST" class="mt-5">
      <legend class="mb-2 text-center">
        Admin Login <span class="text-success"> <i class="fa fa-sign-in" aria-hidden="true"></i></span> 
      </legend>
      <div>
        <?php	
        if (array_key_exists("login",$_POST)) {
        echo $error;
        }
        ?>	
      </div>
        <label for="email">Email address</label>
        <input class="form-control mb-2" value="<?php if(!empty($email)) { echo $email;} ?>" placeholder="Email: " type="email" name="email" required>
        <?php if(!empty($emailErr)) { echo "<small class='text-danger'>{$emailErr}</small><br>";} ?>
        <label for="Password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter Your Password" required>
        <?php if(!empty($passwordErr)) { echo "<small class='text-danger'>{$passwordErr}</small>";} ?>

      <div class="my-3">
        <button type="submit" name="login" class="btn btn-success me-3">Log in</button>
      </div>
      <a href="forgot_password.php" class="mx-2 text-primary">Forgot Password?</a>
    </form> 
</section>
<!-- HTML form -->
<?php include "../footer.php" ?>