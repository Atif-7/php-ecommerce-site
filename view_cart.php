<?php

session_start();
require_once 'config/config.php';
require_once 'config/database.php';

$g_total = 0;

require_once 'head.php';
echo "<title>Cart- E-Shop</title>
    <style>td {align-content:center } @media (max-width: 768px) { td { height:50px !important; padding: 5px 9px 0 !important; align-content: center !important; } }</style>";
require_once 'header.php';
?>

<section>
    <div class="shop">
        <?php
            if (isset($_SESSION['deleted'])) {
            echo "<div id='alerts'><span class='d-flex'><p class='alert alert-warning' role='alert'>{$_SESSION['deleted']}</p><button id='cross' class='btn-cross'> X </button></span></div>";
            unset($_SESSION['deleted']);
            }
        ?>
        
        <h1 class="text-center"> My Cart <span style="opacity:0.85">🛒</span></h1>
        
        <!-- <div class="shop"> -->
            <table class="table table-bordered table-light text-center">
                <thead>
                    <th>S. No </th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </thead>
                <?php 
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $key => $item) { 
                    $total = $item['product_price'] * $item["product_quantity"];  
                    $g_total += $total;
                    $_SESSION['total_amount'] = $g_total;
                ?>
                <tbody>
                    <form action="cart.php" method="POST">
                    <tr>
                    <td data-label="S. No"><h3 style="color: var(--dark); font-size: 18px;"><?php echo $key + 1 ?></h3></td>
                    <td data-label="Id">
                    <input type="hidden" value="<?php echo $item["product_id"] ?>" name="product_id">
                    <h3 style="color: var(--dark); font-size: 18px;"><?php echo $item["product_id"] ?></h3>
                    </td>
                    <td data-label="Name">
                    <input type="hidden" value="<?php echo $item["product_name"] ?>" name="product_name"><h3 style="color: var(--dark); font-size: 18px;"><?php echo $item["product_name"] ?></h3></td>
                    <td data-label="Price">
                    <input type="hidden" value="<?php echo $item["product_price"] ?>" name="product_price">
                    <h3 style="color: var(--dark); font-size: 18px;"><?php echo $item["product_price"] ?>$</h3>
                    </td>
                    <td data-label="Quantity">
                    <h3 style="color: var(--dark); font-size: 18px;"><input type="number" value="<?php echo $item["product_quantity"] ?>" class="text-center" max="10" min="1" name="quantity"></h3>
                    </td>
                    <td data-label="Total">
                    <h3 style="color: var(--dark); font-size: 18px;"><?php echo $total ?></h3>
                    </td>
                    <input type="hidden" name="item" value="<?php echo $item["product_id"]; ?>">
                    <td data-label="Actions"><button class="btn btn-primary" type="submit" name="update_cart">Update</button>
                    <button class="btn btn-danger" type="submit" name="remove_item">Delete</button>
                    </td>
                    </tr>
                    </form>
                </tbody>
                <?php } } ?>
            </table>
            <div class="shop">
                
                <form method="POST" action="checkout.php" class="d-flex align-items-center">
                    <input type="text" disabled class="g-total fw-bold fs-5 text-success text-center" value="Grand Total = <?php echo $g_total ?>">
                    <input type="submit" class="btn btn-success mx-2 fw-bold" value="Checkout">
                </form>
                
                <div>
                    <a href="shop.php" class="text-decoration-none mx-2 text-primary">click here to continue Shopping</a>
                </div>
            </div>
        <!-- </div>              -->
    </div>
</section>
</body>
</html>
<?php include("footer.php") ?>