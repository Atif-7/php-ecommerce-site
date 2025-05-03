<?php
require_once 'config/config.php';
require_once "config/database.php";

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = $query->getDataById('*','categories',$category_id);
if (mysqli_num_rows($category) > 0) {
    $result = $category->fetch_assoc();
    $category_name = htmlspecialchars($result['name']);
} else {
    header('location: index.php');
}
if ($result['parent_id'] == null) {
    $sub_categories = $query->getDataWhere('*','categories','WHERE parent_id = "'.$category_id.'"');
}else{
    $sub_categories = $query->getDataWhere('*','categories','WHERE parent_id = "'.$category_id.'"');
}
 
$products = $query->getDataWhere('*','products',' WHERE category_id = "'.$category_id.'"');

require_once 'head.php';
echo "<title> E-Shop | {$category_name} </title><style>section {
        margin-top: 140px !important;
    } 
    @media (max-width: 768px) {
    section {
        margin-top: 180px !important;
    }
}</style>";
require_once 'header.php';

?>
    <section class="shop">
        <div class="categories-container">
            <?php if ($result['parent_id'] == null) {
                foreach ($sub_categories as $cat) { ?>
                <div class="category">
                    <a href='category.php?id=<?= $cat['id'] ?>' class='btn btn-secondary w-100'><?= $cat['name'] ?></a>
                    <!-- <img src="" alt=""> -->
                </div>
            <?php } } ?>
        </div>
        <h1><?= $category_name ?></h1>
        <?php if (mysqli_num_rows($products)>0 or mysqli_num_rows($sub_categories)>0) { ?>
        <div class="prod-container">
            <?php 
            foreach ($products as $product) {
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
        <?php } else { ?>
        <p>No products found in this category.</p>
        <?php } ?>
    </section>

<?php require_once "footer.php" ?>