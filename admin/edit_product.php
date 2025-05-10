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
    
    // Check if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $data = ['name'=>$name, 'category_id'=> $category_id, 'price'=>$price,'description'=>$description,'image'=>$image_name];
        $result = $query->updateData("products",$data,$id);
    } else {
        // Update without changing the image
        $data = ['name'=>$name,'category_id'=> $category_id,'price'=>$price,'description'=>$description];
        $result = $query->updateData("products",$data,$id);
    }
    if($result){
        session_start();
        $_SESSION['success'] = "<b>{$product['name']}</b> updated successfully";
        header("Location: products.php");
    }
}

require_once '../head.php';
echo "<title>Edit Product | E-Shop</title><style>
    section {
            margin-top: 140px;
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
            <h2>Edit Product <span class="text-secondary">( <?= $product['name'] ?> )</span></h2>
            <a href="products.php" class="btn link-primary">< Manage Products</a>
            <a href="index.php" class="btn link-dark"><< Back to Admin</a>
        </div>

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
            <div class="admin-form mt-3">
                <button class="btn btn-success btn-lg text-light" type="submit">Save</button>
            </div>
        </form>
    </section>
  <?php
require_once '../footer.php'; ?>