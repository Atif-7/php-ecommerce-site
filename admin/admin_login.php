<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
    header("location: admins.php");
}
require_once '../config/database.php';
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = $db->getConn();

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: index.php");
        exit;
    } else {
        $error = "<p class='alert alert-danger mb-2 text-center'>Invalid login credentials";
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
<form method="POST">
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
      <input type="email" name="email" class="form-control" ariadescribedby="emailHelp" placeholder="Provide email" required>
      <label for="Password">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Enter Your Password" required>

      <div class="my-4">
        <button type="submit" name="login" class="btn btn-success me-4">Log in</button>
      </div>
      <a href="forgot_password.php" class="mx-2 text-primary">Forgot Password?</a>
    </form> 
</section>
<!-- HTML form -->
<?php include "../footer.php" ?>