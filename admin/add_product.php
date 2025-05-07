<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}

require_once '../config/database.php';

$categories = $query->getData('*','categories','all');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = (int)$_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    // Image upload handling
    $target_dir = "../uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $data = ['name'=>$name, 'category_id'=> $category_id, 'price'=>$price,'description'=>$description,'image'=>$image_name];
    $result = $query->insertData('products',$data);
    if ($result) {
        session_start();
        $_SESSION['success'] = "<b>{$name}</b> added successfully";
        header("Location: products.php");

    }else{
        session_start();
        $_SESSION['error'] = "Failed to add product {$name}";
    }
}
require_once '../head.php';
echo "<title>Add Product - E-Shop</title><style>
    section {
            margin-top: 105px;
            margin-bottom: 70px;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 120px;
        }
    }</style>";
require_once 'header.php';
?>   
    <section class="shop">
        
            <div class="main-heading">
                <a href="products.php" class="btn link-primary fs-5">< Manage Products</a>
                <a href="index.php" class="btn link-dark fs-5"><< Back to Admin</a>
            </div>
            <?php if (isset($_SESSION['error'])) {
                echo "<p class='alert alert-danger' role='alert'>".$_SESSION['error']."</p>";
                session_unset();
                session_destroy();
            } ?>
            <form class="form form-border mt-2" method="post" enctype="multipart/form-data">
                <legend><h1 class="text-center my-2">Add Product</h1></legend>
                <div class="admin-form">
                    Name: <input class="form-control w-50" type="text" name="name" required>
                    <select class="form-select w-50" name="category_id" required>
                        <option selected>Select Category</option>
                        <?php foreach ($categories as $cat) { ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="admin-form">
                    Price: <input class="form-control w-25" type="number" name="price" step="0.1" required>
                    Description: <textarea class="form-control my-2 w-50" name="description"></textarea>
                </div>
                <div class="admin-form justify-content-between">
                    Image: <input type="file" class="form-control w-50" name="image" accept="image/*" required>
                  <button type="submit" class="btn btn-success btn-lg">Add Product</button>
                </div>
            </form>
        
    </section>
    <script src="<?= BASE_URL ?>.assets/js/script.js"></script>
    <?php include '../footer.php'?>
