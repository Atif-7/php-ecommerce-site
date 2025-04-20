<?php 
session_start();
require_once 'config/config.php';
require_once 'config/database.php';
$products = $query->getData('*','products','4');

require_once 'head.php';
echo "<title>E-Shop</title>";
require_once 'header.php';

?>
<section class="shop">
    <div class="hero-container">
        <h1>Discover the Latest Trends in Fashion</h1>
        <h3>Shop the newest arrivals and elevate your style with our exclusive collection.</h3>
        <a class="btn btn-primary" href="shop.php">Shop Now</a>
    </div>
        <h2>Featured products</h2>
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