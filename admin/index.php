<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}

require_once '../config/config.php';
require_once '../config/database.php';

$products = $query->getData('*','products','all');

require_once '../head.php';
echo "<title>Admin panel | E-Shop</title>
    <style>
    section {
            margin-top: 80px;
            margin-bottom: 70px;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 110px;
        }
    }</style>";

require_once 'header.php';

?>
    
    <section class="shop w-100">
        <div class="mt-5 text-center w-75">
            <h1 class="text-secondary mb-4">Admin Dashboard </h1>
            <div class="link-container justify-content-around w-100">
                <a href="products.php" class="btn btn-success">🛒 Products</a>
                <a href="categories.php" class="btn btn-warning text-white">Categories</a>
                <a href="orders.php" class="btn btn-danger">Manage orders</a>
                <a href="contacts.php" class="btn btn-primary">📩 Contact Messages</a>
                <a href="users.php" class="btn btn-secondary text-white">Users</a>
                <a href="admins.php" class="btn btn-dark"> Admins</a>
            </div>
        </div>
            <div id="alerts"><?php if (isset($_SESSION['success'])) {
                echo "<span class='d-flex'><p class='alert alert-success' role='alert'>{$_SESSION['success']}</p><button id='cross' class='btn-cross'> X </button></span>";
                unset($_SESSION['success']);
                }
                if (isset($_SESSION['delete'])) {
                echo "<span class='d-flex'><p class='alert alert-warning' role='alert'>{$_SESSION['delete']}</p><button id='cross' class='btn-cross'> X </button></span>";
                unset($_SESSION['delete']);
                }
                ?>
            </div>
        <hr>
       
        <div class="table-container" >
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                while ($product = $products->fetch_assoc()) {
                    $category_id = (int)$product['category_id'];
                    $category_result = $query->getDataById('name','categories',$category_id);
                    $category_row = mysqli_fetch_assoc($category_result);
                    $category_name = $category_row['name'];
                ?>
                <tr scop="row">
                    <td data-label="ID"><?php echo $product['id'] ?></td>
                    <td data-label="Name"><?php echo $product['name'] ?></td>
                    <td data-label="Category"><?php echo $category_name ?></td>
                    <td data-label="Price"><?php echo '$'.$product['price'] ?></td>
                    <td data-label="Description"><?php echo "<p>".$product['description']."</p>" ?></td>
                    <td data-label="Image"><img src='../uploads/<?php echo $product['image'] ?>' width='100'></td>
                    <td data-label="Actions" width="240px">
                        <a class="btn btn-dark btn-sm" href='edit_product.php?id=<?php echo $product["id"] ?>'>Edit</a> |
                        <a class="btn btn-primary my-1" href='duplicate_product.php?id=<?php echo $product["id"] ?>'>Duplicate</a> |
                        <a class="btn btn-danger btn-sm" href='delete_product.php?id=<?php echo $product["id"] ?>' onclick="confirm('Are you sure to delete this product : <?php echo $product['name'] ?>')">Delete</a>
                    </td>
                </tr>
                <?php 
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include("../footer.php") ?>