<?php
  session_start();
  require_once '../config/database.php';

  if (array_key_exists("logout",$_GET)) {

		session_unset();
    session_destroy();
		
	}elseif (array_key_exists('loggedin',$_SESSION)) {
		header('Location: account.php');
	}
  
  if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $hashedToken = hash('sha256', $token);

    $stmt = $db->getConn()->prepare("SELECT id, name FROM users WHERE remember_token = ? AND token_expires > NOW()");
    $stmt->bind_param("s", $hashedToken);
    $stmt->execute();
    $stmt->bind_result($userId, $userName);

    if ($stmt->fetch()) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $userName;
        $_SESSION['loggedin']=true;
        header('Location: account.php');
    } else {
        setcookie('remember_me', '', time() - 3600, "/");
    }
}

  $email = $password = $emailErr = $passwordErr = $error = "";
  if ($_SERVER['REQUEST_METHOD']=="POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($email)) {
      $emailErr = "Email is Required";
    }else {
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
          $emailErr = "Invalid Email Address";
      }
    }    
    if (empty($password)) {
        $passwordErr = "Password is Required";
    }

    if (empty($emailErr) && empty($passwordErr)) {
     
      $result = $query->runQuery("SELECT * FROM `users` WHERE Email='$email'");

      if (mysqli_num_rows($result) == 1) {

        while ($row = mysqli_fetch_assoc($result)) {
          if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            session_regenerate_id(true);
            $_SESSION['user_name'] = $row['name'];
            // $_SESSION['activation'] = $row['activated'];
            $_SESSION['loggedin']=true;

            if (isset($_POST['remember'])) {
              $token = bin2hex(random_bytes(32)); // Generate secure token
              $hashedToken = hash('sha256', $token);
             $expires = date('Y-m-d H:i:s', strtotime('+1 day'));
          
              // Save hashed token and expiry to database
              $stmt = $db->getConn()->prepare("UPDATE users SET remember_token=?, token_expires=? WHERE id=?");
              $stmt->bind_param("ssi", $hashedToken, $expires, $_SESSION['user_id']);
              $stmt->execute();
          
              // Set cookie with token
              setcookie('remember_me', $token, time() + (86400 * 1), "/", "", true, true); // secure & httpOnly
          }
          
            header('Location: account.php');
          }else{
            $error = "<div class='alert alert-danger' role='alert'>This Password is incorrect.</div>";
          }
        }  
      }else{
        $error = "<div class='alert alert-danger' role='alert'>Incorrect email. account could not found.</div>";
      }  
    }else{
      $error	= "<div class='alert alert-danger' role='alert'><strong><p>There were some errors</p></strong></div>";
    }
  }
  require_once('../head.php');
  echo "<title>E-Shop | Login</title>";
  require_once('../header.php');
?>
<section class="shop">
<div class="row container justify-content-center">
  <div class="col-md-6">
      <!-- Login form-->
    <form action="login.php" id="login" method="POST">
      <legend class="mb-4 text-center">
        Login <span class="text-success"> <i class="fa fa-sign-in" aria-hidden="true"></i></span> 
      </legend>
      <div id="errorMsg2">
        <?php	
        if (array_key_exists("login",$_POST)) {
        echo $error;
        }
        ?>	
      </div>

      <label for="email">Email address</label>
      <input type="email" name="email" value="<?php if(!empty($email)) { echo $email;} ?>" class="form-control" ariadescribedby="emailHelp" placeholder="Provide email">
      <?php if(!empty($emailErr)) { echo "<small class='text-danger'>{$emailErr}</small><br>";} ?>

      <label for="Password" class="mt-2">Password</label>
      <input type="password" name="password" id="password2" class="form-control" placeholder="Enter Your Password" required>
      <?php if(!empty($passwordErr)) { echo "<small class='text-danger'>{$passwordErr}</small>";} ?>
      
      <label>
      <input type="checkbox" name="remember" class="mt-3"> Remember me</label>
      <div class="my-3">
        <button type="submit" name="login" class="btn btn-success me-4">Login</button>
        <small>yet not signup?</small>
        <a href="signup.php" class="fs-5 mx-2 text-decoration-none"> Signup Now</a>
      </div>
      <a href="forgot_password.php" class="mx-2 text-primary">Forgot Password?</a>
    </form>  
  </div>  
</div>
</section>

<?php include("../footer.php") ?>