<?php
session_start();
require_once 'config/config.php';
require_once "config/database.php";

$searchResults = [];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $keyword = trim($_GET['query']);
    if (!empty($keyword)) {
        $searchResults = $query->searchProducts($keyword);
    }
}
require_once 'head.php';
echo "<title>Search Result- E-Shop</title>";
require_once 'header.php';
?>

<section class="shop">
    <h2 class="text-dark">Search Results for "<?php echo htmlspecialchars($keyword); ?>"</h2>
    <div class="prod-container">
    <?php 
        if (!empty($searchResults)){ 
        foreach ($searchResults as $product) {
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
        }}else{
    ?>
    </div>
    <h3 class="text-danger text-center">No results found.</h3>
    <?php } ?>
</section>

<?php require_once "footer.php" ?>   