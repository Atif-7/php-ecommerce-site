    <?php 
    $user_id = $_GET['id'];
    require_once('../config/database.php');
    if (isset($user_id)) {
        $result = $query->getDataWhere('*','users','WHERE id = "'.$user_id.'" LIMIT 1');
        $row = $result->fetch_assoc();
        if ($result->num_rows != 1) {
            header('Location: account.php');
        }
    }
    $orders = $query->getDataWhere('*','orders','WHERE user_id = "'.$user_id.'"');
    require_once('../head.php');
    echo "<title>User Details | E-Shop</title>";
    echo "<style>
        section {
                margin-top: 140px;
            }
        @media (max-width: 768px) {
            section {
                margin-top: 200px;
                width: 95% !important;
            }
        }</style>";
    require_once('../header.php');
    ?>
    <section class="shop">
        <div class="main-heading mt-3 bg-light">
            <h3 class="text-secondary">user's Details (<?= $row['name'] ?>)</h3>
            <a href="javascript:history.back()" class="btn btn-secondary">&lt; Back</a>
        </div>
        </div>
        <div class="container shop mt-3">
            <?php if ($result->num_rows > 0) { ?>
            <div class="table-container d-flex flex-column align-items-center justify-content-center">
                <h3 class="text-primary mb-2">Account Info</h3>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        echo "<tr scop='row'>
                            <td data-label='ID'>".$row['id']."</td>
                            <td data-label='Name'>".$row['name']."</td>
                            <td data-label='Email'>".$row['email']."</td>
                            <td data-label='Actions' width='240px'>
                                <a class='btn btn-dark btn-sm' href='user_query.php?id=".$row["id"]."'>Message</a> 
                            </td>
                        </tr>";
                        }
                    ?>
                </tbody>               
                </table>
                <h3 class="text-success my-2">placed orders</h3>
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
                        <td data-label="ID"><a href="order_details.php?id=<?php echo $row['id'] ?>"><?php echo $row['id'] ?></a></td>
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
            </div>
        </section>
