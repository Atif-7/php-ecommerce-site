<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}

require_once '../config/database.php';
$conn = $db->getConn();

// Add Admin
$name = $email = $password = $nameErr = $emailErr = $passwordErr = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
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
                $emailErr = "This Email Address already exists, try different Email";
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
        $stmt = $conn->prepare("INSERT INTO admins (name,email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if($stmt->execute()){
            $_SESSION['success'] = "{$name} added successfully";
            header("location: admins.php");
            exit;
        }else{
            $_SESSION['error'] = "failed to add admin";
        }
    }
}

// Delete Admin
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id == 1) {
        $cheater = $query->getDataWhere("id","admins","WHERE name = '".$_SESSION['admin_name']."'")->fetch_assoc();
        if ($cheater['id'] != 1) {
            $_SESSION['msg'] = "don't try to delete your father :)";
            echo"<script>alert('". htmlspecialchars("don`t try to delete your father :) .. now good bye")."')</script>";
            $query->deleteData("admins",$cheater['id']);
        }else{
            session_destroy();
            $_SESSION['success'] = "what are you doing man";
        }
        header("Location: admins.php");
    }else{
        if($query->deleteData("admins",$id)){
            $_SESSION['success'] = "user{$id} has been deleted"; 
            header("Location: admins.php");
            exit;
        };
    }
}
// Fetch Admins
$result = $query->getData("*","admins","all")->fetch_all(MYSQLI_ASSOC);

require_once '../head.php';
echo "<title>Admins | E-Shop</title><style>
    section {
            margin-top: 105px;
            margin-bottom: 70px;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 110px;
        }
        .w-25{
        width:80% !important;
        }
    }</style>";
require_once 'header.php';
?>
<section class="shop">
    <div class="container main-heading bg-light">
        <h2>Admins Users</h2>
        <a href="index.php" class="btn btn-dark"><< Back to Admin</a>
    </div>
    <div id="alerts">
        <?php if (isset($_SESSION['error'])) {
            echo "<span class='d-flex'><p class='alert alert-danger' role='alert'>".$_SESSION['msg']."</p><button id='cross' class='btn-cross'> X </button></span>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<span class='d-flex'><p class='alert alert-success' role='alert'>".$_SESSION['success']."</p><button id='cross' class='btn-cross'> X </button></span>";
            unset($_SESSION['success']);
        } ?>
    </div>
    <form class="form w-75 my-2 form-border" method="post">
        <legend class="text-center">Add Admin</legend>
        <div class="admin-form">
            <div>
                <input class="form-control" value="<?php if(!empty($name)) { echo $name;} ?>" placeholder="Name:" type="text" name="name" required>
                <?php if(!empty($nameErr)) { echo "<small class='text-danger'>{$nameErr}</small>";} ?>
            </div>
            <div>
                <input class="form-control" value="<?php if(!empty($email)) { echo $email;} ?>" placeholder="Email: " type="email" name="email" required>
                <?php if(!empty($emailErr)) { echo "<small class='text-danger'>{$emailErr}</small>";} ?>
            </div>
            <div>
                <input class="form-control" type="password" placeholder="Password:" name="password" required>
                <?php if(!empty($passwordErr)) { echo "<small class='text-danger'>{$passwordErr}</small>";} ?>
            </div>
            <button type="submit"  name="add_admin" class="btn btn-success">Add Admin</button>
        </div>
    </form>

    <table class="table table-sm table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Admin_ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $key => $row): if ($row['id'] != $_SESSION['admin_id']):
            ?>
            <tr scop="row">
                <td data-label="S. No"><?php echo $key + 1 ?></td>
                <td data-label="ID"><?php echo $row['id'] ?></td>
                <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                <td data-label="Actions" width="240px">
                    <a class="btn btn-dark btn-sm" href='mailto:<?= htmlspecialchars($row['email']) ?>'>Message</a> 
                    <a class="btn btn-sm btn-danger" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete admin?')">Delete</a>
                </td>
            </tr>
            <?php endif; endforeach; ?>
        </tbody>
        </table>
    </section>
    <script src="../assets/js/script.js"></script>
</body>
</html>
<?php include "../footer.php" ?>