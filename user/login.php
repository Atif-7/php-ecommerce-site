<?php
  session_start();

  if (array_key_exists("logout",$_GET)) {

		session_unset();
    session_destroy();
		
	}elseif (array_key_exists('loggedin',$_SESSION)) {
		header('Location: account.php');
	}

  $email = $password = $emailErr = $passwordErr = $error = "";
  if ($_SERVER['REQUEST_METHOD']=="POST") {
    require_once '../config/database.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

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
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['activation'] = $row['activated'];
            $_SESSION['loggedin']=true;
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

      <label for="Password">Password</label>
      <input type="password" name="password" id="password2" class="form-control" placeholder="Enter Your Password" required>
      <?php if(!empty($passwordErr)) { echo "<small class='text-danger'>{$passwordErr}</small>";} ?>

      <div class="my-4">
        <button type="submit" name="login" class="btn btn-success me-4">Log in</button>
        <small>yet not signup?</small>
        <a href="signup.php" class="fs-5 mx-2 text-decoration-none"> Signup Now</a>
      </div>
      <a href="forgot_password.php" class="mx-2 text-primary">Forgot Password?</a>
    </form>  
  </div>  
</div>
</section>

<?php include("../footer.php") ?>