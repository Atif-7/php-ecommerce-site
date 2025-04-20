<?php
    session_start();
    if (array_key_exists('loggedin',$_SESSION)) {
        header('Location: account.php');
    }
    $name = $_GET['name'];
    if (isset($_POST['reset_now'])) {
        require_once('../config/database.php');
        $reset_token = $_GET['token'];
        $email = $_GET['email'];
        $new_pass = $_POST['new_password'];
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
        $error = '';
        $result = $query->getDataWhere('*','users','WHERE email = "'.$email.'" AND reset_token = "'.$reset_token.'"');

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $data = array('password' => $hashed_password);
            $result = $query->updateData('users',$data,$id);
            
            if ($result) {                
                $error = "<p class='text-success alert alert-success' role='alert'>Your Password has been changed Successfully!</p>";

                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['loggedin']=true;

                header("refresh:4,url='account.php'");

            }else{
                echo "<script>alert('invalid token or email');</script>";
            }
        }

    }
    require_once('../head.php');
    echo "<title>E-Shop - Reset Password</title>";
    require_once('../header.php'); 
?>

<section class="shop">
<div class="container row justify-content-center">
    <div class="col-md-6">

        <form method="post" id="signup">
            <legend class="my-4 text-center">
            Set New Password - <span class="text-primary"><?php echo $name; ?></span> 
            </legend>
            <div id="errorMsg">
                <?php	
                if (array_key_exists("reset_now",$_POST)) {
                echo $error;
                }
                ?>	
            </div>
        
            <label for="Password">New Password</label>
            <input type="password" name="new_password" id="password" class="form-control my-3" placeholder="Enter Your Password">
            
            <div class="my-3">
            <button type="submit" name="reset_now" id="submit" class="btn btn-success me-4">Reset</button>
            </div>
        </form>
    </div>
</div>
</section>
<?php include('../footer.php'); ?>