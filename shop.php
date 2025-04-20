<?php 
session_start();
require_once 'config/database.php';
$products = $query->getData('*','products','all');
require_once 'head.php';
echo "<title>Shop - E-Shop</title>";
require_once 'header.php';

?>
    <section class="shop">
        <h2>Top Categories</h2>
        <div class="categories-container">
            <?php foreach ($categories as $category) { ?>
            <div class="category">
                <a href='category.php?id=<?= $category['id'] ?>' class='btn btn-secondary w-100'><?= $category['name'] ?></a>
                <img src="" alt="">
            </div>
            <?php } ?>
        </div>
        <h1>Our products</h1>
        <div class="prod-container">
            <?php 
            while ($product = $products->fetch_assoc()) {
            ?>
            <div class="product">
                <img src="uploads/<?php echo $product['image'] ?>" alt="Product">
                <h2><?php echo $product['name'] ?></h2>
                <p><?php echo $product['price'] ?></p>
                <p><?php echo $product['description'] ?></p>
                <form action="cart.php" method="POST">
                      <input type="hidden" value="<?php echo $product['id'] ?>" name="product_id">
                      <input type="hidden" value="<?php echo $product['name'] ?>" name="product_name">
                      <input type="hidden" value="<?php echo $product['price'] ?>" name="product_price">
                      <!-- <input type="number" name="quantity" value="1" min="1" max="10"> -->
                      <button class="btn btn-success" type="submit" name="addtocart">Add to Cart</button>
                </form>
            </div>
            <?php 
            }
            ?>
        </div>
    </section>

<?php require_once "footer.php" ?>