<?php 
session_start();
require_once '../config/config.php';
require_once "../config/database.php";
if (!isset($_SESSION['loggedin'])) {
    header('location: login.php');
}
$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$items = $query->getDataWhere('*','order_items','WHERE order_id = "'.$order_id.'"');
$g_total = 0;
require_once '../head.php';
echo "<title>Order Details | E-Shop</title>";
echo "<style> 
section{
margin-top: 140px;
}
button {
    background: blue;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
}
@media (max-width: 768px) {
section{
margin-top: 200px;
width: 95% !important;
}
}
</style>";
require_once '../header.php';
?>
  <section class="container-fluid w-100">
    <div class="bg-light d-flex justify-content-between w-100 align-items-center">
        <h2 style="text-transform:uppercase"><a href="account.php" class="nav-link"><?= $_SESSION['user_name'] ?></a></h2>
        <div><a href="logout.php" class="btn btn-danger">Logout</a></div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <div class="container shop mt-5">
        <div class="main-heading">
            <h3 class="text-secondary">Orders Details (order_id: <?= $order_id ?>)</h3>
            <a href="account.php" class="btn btn-secondary">&lt; Back</a>
        </div>
        <?php if ($items->num_rows > 0) { ?>
        <div class="table-container d-flex flex-column align-items-center justify-content-center">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = $items->fetch_all(MYSQLI_ASSOC); 
                foreach ($result as $key => $row) {
                    $total = $row['price'] * $row['quantity'];
                    $g_total += $total; 
                ?>
                <tr scop="row">
                    <td data-label="S. No"><?php echo $key + 1 ?></td>
                    <td data-label="ID"><?php echo $row['product_id'] ?></td>
                    <td data-label="Name"><?php echo $row['name'] ?></td>
                    <td data-label="Price"><?php echo '$'.$row['price'] ?></td>
                    <td data-label="Quantity"><?php echo $row['quantity'] ?></td>
                    <td data-label="Total"><?php echo '$'.$row['price'] * $row['quantity'] ?></td>
                </tr>
                <?php 
                }
                ?>
                </tbody>
            </table>
            <input type="text" value="Grand Total = $<?= $g_total ?>" class="w-25 fs-5 text-center text-success" disabled>
        </div>
        <?php }else{
            echo "<p>you have no order(s) placed yet.";
        } ?>
    </div>
  </section>
</body>
</html>
<?php include "../footer.php" ?>