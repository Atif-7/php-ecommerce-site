<?php
    session_start();
    require_once '../config/config.php';
    if (array_key_exists('loggedin',$_SESSION)) {
		header('Location: account.php');
	}

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        require('../config/database.php');

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $error = "";
        if (!$name) {
            $error .= "<p>Name field is requied</p>";
        }
        if (!$email) {
            $error .= "<p>an email address is requied</p>";
        }
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $error .= "Enter a valid email address";
        }
        if (!$password) {
            $error .= "<p>please enter a password<P/>";
        }
        if ($error != "") {
            $error	= "<div class='alert alert-danger' role='alert'><strong><p>There were some errors</p></strong>".$error."</div>";
            
        }else{
            // $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($conn,$_POST['email'])."' LIMIT 1";
                $result = $query->getDataWhere('id','users','WHERE email = "'.$email.'" LIMIT 1');

            if (mysqli_num_rows($result) > 0) {
                $error = "<div class='alert alert-danger' role='alert'><strong><p>That email address is already taken.</p></strong></div>";
            }else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                // $query = "INSERT INTO `users` (username, email, password) VALUES('$name','$email','$hashed')";
                $data = array('name'=>$name, 'email'=>$email, 'password'=>$hashed);
                $result = $query->insertData('users',$data);
                if($result){
                    session_start();
                    $_SESSION['user_id'] = mysqli_insert_id($db->getConn());
                    $_SESSION['user_name'] = $name;
                    $_SESSION['loggedin']=true;
                    header("location: account.php");
                }else{
                    $error = "could not sign up at the moment, please try later!";
                }                                 
            }    
        }

    }
  
    require_once('../head.php'); 
    echo "<title>E-Shop | Signup</title>";
    require_once('../header.php'); 

?>
<section class="shop">
    <div class="row container justify-content-center">
        <div class="col-md-6">
        <!-- signup form -->
        <form action="signup.php" method="post" id="signup">
            <legend class="mb-4 text-center">
            <h2>Create E-shop account now!</h2>
            <i class="fa fa-sign-in" aria-hidden="true"></i>
            </legend>
            <div id="errorMsg">
                <?php	
                if (array_key_exists("signup",$_POST)) {
                echo $error;
                }
                ?>	
            </div>
            
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Full Name">
            
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Provide email">
        
            <label for="Password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Your Password">
            
            <div class="my-4">
            <button type="submit" name="signup" id="submit" class="btn btn-success me-4">Sign up</button>
            <small>Already have an account?</small>
            <a href="login.php" class="fs-5 mx-2 text-decoration-none"> Login </a>
            </div>
        </form>
    </div>
</div>
</section>
<?php include('../footer.php'); ?>