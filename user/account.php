<?php 
session_start();
require_once '../config/config.php';
require_once "../config/database.php";
if (!isset($_SESSION['loggedin'])) {
    header('location: login.php');
}

$user_id = $_SESSION['id'];
$orders = $query->getDataWhere('*','orders','WHERE user_id = "'.$user_id.'"');

require_once '../head.php';
echo "<title>Account - E-Shop</title>";
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
        <h2>User Dashboard</h2>
        <div><a href="logout.php" class="btn btn-danger">Logout</a></div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <div class="container shop mt-5">
        <h3>Orders History</h3>
        <?php if ($orders->num_rows > 0) { ?>
        <div class="table-container d-flex justify-content-center">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S. No</th>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Staus</th>
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
                    <td data-label="Total"><?php echo '$'.$row['total'] ?></td>
                    <td data-label="Status"><?php echo $row['status'] ?></td>
                    <td data-label="Created_at"><?php echo $row['created_at'] ?></td>
                    <td data-label="Actions" width="240px">
                        <a class="btn btn-dark btn-sm" href='order_query.php?id=<?php echo $row["id"] ?>'>Message</a> |
                        <a class="btn btn-danger btn-sm" href='cancel_order.php?id=<?php echo $row["id"] ?>' onclick="confirm('Are you sure to cancel this order : <?php echo $row['id'] ?>')">Cancel</a>
                    </td>
                </tr>
                <?php 
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php }else{
            echo "<p>you have no order(s) placed yet.";
        } ?>
    </div>
  </section>
</body>
</html>
<?php include "../footer.php" ?>