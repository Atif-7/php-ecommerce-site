<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}
require_once "../config/database.php";

$users = $query->getData('*','users','all');

require_once '../head.php';
echo "<title>Users | E-Shop</title><style>
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
            <h2>All Users</h2>
            <a href="index.php" class="btn btn-dark"><< Back to Admin</a>
        </div>

        <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>User_ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = $users->fetch_all(MYSQLI_ASSOC); 
                foreach ($result as $key => $row) {
                    // $category_id = (int)$product['category_id'];
                    // $category_row = $query->getDataById('name','categories',$category_id)->fetch_assoc();
                    // $category_name = $category_row['name'];
                ?>
                <tr scop="row">
                    <td data-label="S. No"><?php echo $key + 1 ?></td>
                    <td data-label="ID"><?php echo $row['id'] ?></td>
                    <td data-label="Name"><a class="nav-link text-primary" href="user_details.php?id=<?php echo $row['id'] ?>"><?= $row['name'] ?></a></td>
                    <td data-label="Email"><?php echo $row['email'] ?></td>
                    <td data-label="Actions" width="240px">
                        <a class="btn btn-dark btn-sm" href='mailto:<?php echo $row["email"] ?>'>Message</a> 
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
<?php include "../footer.php" ?>