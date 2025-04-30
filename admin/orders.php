<?php
session_start();
require_once "../config/database.php";

$orders = $query->getData('*','orders','all');

require_once '../head.php';
echo "<title>Orders - E-Shop</title><style>
    section {
            margin-top: 105px;
            margin-bottom: 70px;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 110px;
        }
    }</style>";
require_once 'header.php';
?>
    <section class="shop">
        <div class="container main-heading bg-light">
            <h2>Placed Orders</h2>
            <a href="index.php" class="btn btn-dark"><< Back to Admin</a>
        </div>

        <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order_ID</th>
                        <th>Total</th>
                        <th>Payment Staus</th>
                        <th>Order Staus</th>
                        <th>Created_at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = $orders->fetch_all(MYSQLI_ASSOC); 
                foreach ($result as $key => $row) {
                    // $category_id = (int)$product['category_id'];
                    // $category_row = $query->getDataById('name','categories',$category_id)->fetch_assoc();
                    // $category_name = $category_row['name'];
                ?>
                <tr scop="row">
                    <td data-label="S. No"><?php echo $key + 1 ?></td>
                    <td data-label="ID"><?php echo $row['id'] ?></td>
                    <td data-label="Total"><?php echo '$'.$row['total_amount'] ?></td>
                    <td data-label="Payment Status"><?php echo $row['payment_status'] ?></td>
                    <td data-label="Order Status"><?php echo $row['order_status'] ?></td>
                    <td data-label="Created_at"><?php echo $row['created_at'] ?></td>
                    <td data-label="Actions" width="240px">
                        <a class="btn btn-dark btn-sm" href='order_query.php?id=<?php echo $row["id"] ?>'>Message</a> 
                        <!-- |
                        <a class="btn btn-danger btn-sm" href='cancel_order.php?id=<?php echo $row["id"] ?>' onclick="confirm('Are you sure to cancel this order : <?php echo $row['id'] ?>')">Cancel</a> -->
                    </td>
                </tr>
                <?php 
                }
                ?>
                </tbody>
            </table>
    </section>
    <script src="../assets/js/script.js"></script>
</body>
</html>
<?php include "../footer.php" ?>