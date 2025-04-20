<?php
session_start();
require_once '../config/database.php';

$products = $query->getData('*','products','0');
$categories = $query->getData('*','categories','all');

require_once '../head.php';
echo "<title>Manage Product - E-Shop</title><style>
    section {
            margin-top: 120px !important;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 120px;
        }
    }</style>";
require_once 'header.php';
?>   
    <section class="shop">
        <div class="shop">
            <div class="d-flex gap-3">
                <a href="add_product.php" class="btn btn-success">Add Product</a>
                <a href="index.php" class="btn btn-secondary">< Back to Admin</a>
            </div>
            <?php if (isset($_SESSION['success']) or isset($_SESSION['delete']) or isset($_SESSION['error'])) {
                echo "<div id='alerts'>";
                if (isset($_SESSION['success'])){
                echo "<span class='d-flex'><p class='alert alert-success' role='alert'>{$_SESSION['success']}</p><button id='cross' class='btn-cross'> X </button></span>";
                session_unset();
                session_destroy();
                }
                if (isset($_SESSION['delete'])) {
                echo "<span class='d-flex'><p class='alert alert-warning' role='alert'>{$_SESSION['delete']}</p><button id='cross' class='btn-cross'> X </button></span>";
                session_unset();
                session_destroy();
                }
                if (isset($_SESSION['error'])) {
                    echo "<p class='alert alert-danger' role='alert'>".$_SESSION['error']."</p>";
                    session_unset();
                    session_destroy();
                }
                echo "</div>";
            }
            ?>
            <!-- <hr> -->
            <h2 class="container text-center py-2 bg-secondary text-white">Manage All Products</h2>
            <div class="table-container d-flex justify-content-center" >
                <table class="table table-sm table-bordered table-striped">
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
                        $category_row = $query->getDataById('name','categories',$category_id)->fetch_assoc();
                        $category_name = $category_row['name'];
                    ?>
                    <tr scop="row">
                        <td data-label="ID"><?php echo $product['id'] ?></td>
                        <td data-label="Name"><?php echo $product['name'] ?></td>
                        <td data-label="Category"><?php echo $category_name ?></td>
                        <td data-label="Price"><?php echo '$'.$product['price'] ?></td>
                        <td data-label="Description"><?php echo "<p>".$product['description']."</p>" ?></td>
                        <td data-label="Image"><img src='../uploads/<?php echo $product['image'] ?>' width='70'></td>
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
        </div>
    </section>

    <script src="<?= BASE_URL ; ?>/assets/js/script.js"></script>
    <?php include "../footer.php"; ?>
</body>
</html>