<?php
session_start();
require_once "../config/database.php";

$orders = $query->getData('*','orders','all');

require_once '../head.php';
echo "<title>Orders | E-Shop</title><style>
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
                        <th>User Name</th>
                        <th>Total</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
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
                    <td data-label="ID"><a href="order_details.php?id=<?php echo $row['id'] ?>"><?php echo $row['id'] ?></a></td>
                    <td data-label="Name"><a class="text-primary nav-link" href="user_details.php?id=<?php echo $row['user_id'] ?>"><?php echo $row['user_name'] ?></a></td>
                    <td data-label="Total"><?php echo '$'.$row['total_amount'] ?></td>
                    <td data-label="Payment Status"><form method="post" action="update_status.php">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option class="fw-bold" value="<?= $row['payment_status'] ?>" selected><?= $row['payment_status'] ?></option>
                            <?php
                            $statuses = ['Processing', 'Paid'];
                            $key = array_search($row['payment_status'],$statuses);
                            if ($key !== false) {
                                unset($statuses[$key]);
                            }
                            print_r($statuses);
                            $rem_statuses = array_values($statuses);
                            foreach ($rem_statuses as $status) {
                                echo "<option value='$status'>$status</option>";
                                // $selected = $row['payment_status'] === $status ? "selected" : "";
                            }
                            ?>
                        </select>
                    </form></td>
                    <td data-label="Order Status"><form method="post" action="update_status.php">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <select name="order_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <?php
                            $statuses = ['Pending', 'Processing', 'Shipped', 'Completed'];
                            foreach ($statuses as $status) {
                                $selected = $row['order_status'] === $status ? "selected" : "";
                                echo "<option value='$status' $selected>$status</option>";
                            }
                            ?>
                        </select>
                    </form></td>
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