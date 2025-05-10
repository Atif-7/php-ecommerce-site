<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $categories = $query->getData('*','categories','all');
    $product = $query->getDataById('*','products',$id)->fetch_assoc();

    $category_id = (int)$product['category_id'];
    $category_row = $query->getDataById('name','categories',$category_id)->fetch_assoc();
    $category_name = $category_row['name'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = (int)$_POST['category_id'];
    $price = $_POST['price'];
    $description = htmlspecialchars($_POST['description']);
    // Image upload handling
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $data = ['name'=>$name,'category_id'=> $category_id,'price'=>$price,'description'=>$description,'image'=>$image_name];
    } else {
        // Update without changing the image
        $image_name = $product['image'];
        $data = ['name'=>$name,'category_id'=> $category_id,'price'=>$price,'description'=>$description,'image'=>$image_name];
    }
    $res = $query->insertData('products',$data);   
    if ($res) {
        session_start();
        $_SESSION['success'] = "<b>{$name}</b> added successfully";
        header("Location: products.php");
    }else{
        session_start();
        $_SESSION['error'] = "Failed to add product {$name}";
    }
}

require_once '../head.php';
echo "<title>Add Product - E-Shop</title>";
require_once 'header.php';

?>  
    <section class="shop">
        <div class="main-heading">
            <h1>Add Product</h1>
            <a href="products.php" class="btn link-primary"><< Manage Products</a>
        </div>
        <?php if (isset($_SESSION['error'])) {
            echo "<p class='alert alert-danger' role='alert'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        } ?>
         <form method="POST" class="form-border mt-2" enctype="multipart/form-data">
            <div class="admin-form">
                <label for="name">Name:</label> <input class="form-control w-50" type="text" name="name" value="<?= $product['name'] ?>" required>
                <label for="category">Category:</label>
                <select class="form-select w-50" name="category_id" required>
                    <option class="fw-bold" value="<?= $product['category_id'] ?>" selected><?= $category_name ?></option>
                    <?php foreach ($categories as $cat) { 
                        if($cat['id'] != $product['category_id']) {
                            $cat_name = htmlspecialchars($cat['name']);
                            echo "<option value='{$cat["id"]}'>{$cat_name}</option>'";
                        }
                    }
                    ?>
                </select>
                <label for="price">Price:</label> <input class="form-control w-50" type="number" name="price" step="0.1" value="<?= $product['price'] ?>" required>
            </div>
            <div class="admin-form mt-2">
                <label for="description">Description:</label> <textarea class="form-control w-50" name="description"><?= $product['description'] ?></textarea>
                <label for="current_image" class="w-20">Current Image:</label> 
                <img src="../uploads/<?= $product['image'] ?>" width="100" class="">
                <label for="image" class="w-20"> Change Image:</label><input class="form-control w-50" type="file" name="image" accept="image/*">
            </div>
            <div class="admin-form mt-4">
                <button class="btn btn-success btn-lg text-light" type="submit">Add Product</button>
            </div>
        </form>
        </div>
    </section>

    <script src="<?= BASE_URL ; ?>assets/js/script.js"></script>
<?php include '../footer.php' ?>