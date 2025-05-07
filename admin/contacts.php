<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}

require_once "../config/database.php";

$messages = $query->getData('*','contacts','all');

require_once '../head.php';
echo "<title>Contact messages - E-Shop</title><style>
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
            <h2>Contact Messages</h2>
            <a href="index.php" class="btn btn-dark"><< Back to Admin</a>
        </div>

        <table class="table table-bordered table-light">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </thead>

            <?php while ($row = $messages->fetch_assoc()): ?>
            <tbody>
                <tr>
                    <td data-label="ID"><?php echo $row['id']; ?></td>
                    <td data-label="Name"><?php echo $row['name']; ?></td>
                    <td data-label="Email"><?php echo $row['email']; ?></td>
                    <td data-label="Message"><p><?php echo $row['message']; ?></p></td>
                    <td data-label="Time"><?php echo $row['created_at']; ?></td>
                </tr>
            </tbody>
            <?php endwhile; ?>
        </table>
    </section>
    <script src="../assets/js/script.js"></script>
<?php include "../footer.php" ?>