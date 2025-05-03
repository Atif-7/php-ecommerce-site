<?php
session_start();
require_once "config/database.php";

$name = $email = $message = "";
$error = "";
// session_destroy();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = $_POST['message'];
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    if (empty($name)) {
        $error .= "name is empty <br>";
    }
    if (strlen($name) > 20) {
        $error .= "name should contains less than 20 characters";
    }
    if (empty($email)) {
        $error .= "email is empty <br>";
    }
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error .= "invalid email <br>";
    }
    if (empty($message)) {
        $error .= "message is empty <br>";
    }
    if ( $error == ""){
        $data = ['name'=>$name,'email'=>$email,'message'=>$message];
        $result = $query->insertData('contacts',$data);

        if ($result) {
            $to = "webdevatif@gmail.com"; 
            $subject = "e-shop Contact Message";
            $body = "Name: $name\nEmail: $email\nMessage:\n$message";
            $headers = "From: $email";

            // mail($to, $subject, $body, $headers);

            $_SESSION['success'] = "Message sent successfully!";
        } else {
            $_SESSION['error'] = "Error: Could not send message.";
        }
    } else {
        $_SESSION['error'] = "<b>There are some errors</b> : <br>".$error ;
    }
}

require_once 'head.php';
echo "<title>Contact Us | E-Shop</title>
    <style>
        button {
            background: #333;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        button:hover {
        background: #222
        }
    </style>";
require_once 'header.php';

?>  
<section class="shop">
    <h2>Contact Us</h2>

<?php if (isset($_SESSION['success'])): ?>
    <p style="color: #222;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form method="post" class="form-border">
    <div class="admin-form">
        <label>Name:</label>
        <input type="text" class="form-control" name="name" required><br>
    
        <label>Email:</label>
        <input type="email" class="form-control" name="email" required><br>
    </div>
    <div class="admin-form">
        <label>Message:</label>
        <textarea name="message" class="w-100 form-control" required></textarea><br>
    </div>
    <div class="admin-form">
        <button type="submit">Send Message</button>
    </div>
</form>
    </section>
<?php require_once "footer.php" ?>