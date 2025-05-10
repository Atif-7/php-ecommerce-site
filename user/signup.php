<?php
    session_start();
    require_once '../config/config.php';
    if (array_key_exists('loggedin',$_SESSION)) {
		header('Location: account.php');
	}

    $name = $email = $password = $nameErr = $emailErr = $passwordErr = $error = "";
    if ($_SERVER['REQUEST_METHOD']=="POST") {
        require('../config/database.php');

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($name)) {
        $nameErr = "Name is Required";
        }else {
            if(!preg_match("/^[a-zA-Z ]*$/", $name)){
                $nameErr = "only letters and white space allowed";
            }
        }

        if (empty($email)) {
            $emailErr = "Email is Required";
        } else {
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $emailErr = "Invalid Email Address";
            }else {
            $check_email = $query->getDataWhere("email","admins","WHERE email = '".$email."'");
                if ($check_email->num_rows > 0) {
                    $emailErr = "This Email is already taken, try different Email";
                }
            }
        }

        if (empty($password)) {
            $passwordErr = "Password is Required";
        } else {
            if(strlen($password) < 6){
                $passwordErr = "Password must contain at least 6 characters";
            }elseif(!preg_match("#[0-9]+#",$password)){
                $passwordErr = "Password must contain atleast one number";
            }else{
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
        }

        if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {         
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
        }else{
            $error	= "<div class='alert alert-danger' role='alert'><strong><p>There were some errors</p></strong></div>";
            
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
            <input type="text" value="<?php if (!empty($name)){ echo $name; } ?>" name="name" id="name" class="form-control" placeholder="Enter Your Full Name">
            <?php if(!empty($nameErr)) { echo "<small class='text-danger'>{$nameErr}</small><br>";} ?>
            
            <label for="email">Email address</label>
            <input type="email" value="<?php if (!empty($email)){ echo $email; } ?>" name="email" id="email" class="form-control" placeholder="Provide email">
            <?php if(!empty($emailErr)) { echo "<small class='text-danger'>{$emailErr}</small><br>";} ?>
        
            <label for="Password">Password</label>
            <input type="password" value="<?php if (!empty($password)){ echo $password; } ?>" name="password" id="password" class="form-control" placeholder="Enter Your Password">
            <?php if(!empty($passwordErr)) { echo "<small class='text-danger'>{$passwordErr}</small><br>";} ?>
            
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