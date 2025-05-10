<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] != true ) {
    header("Location: admin_login.php");
    exit;
}
require_once '../config/database.php';

// $categories = $query->getData('*','categories','0');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
        $name = $_POST['name'];
        if (!empty($_POST['parent_id'])){
            $parent_id = (int) $_POST['parent_id'];
            $data = array('name'=>$name,'parent_id'=>$parent_id);
        }else {
            $data = array('name'=>$name);
        } 
        $result = $query->insertData('categories',$data);
        if ($result) {
            session_start();
            $_SESSION['success'] = "<b>{$name}</b> added successfully";
        }else{
            session_start();
            $_SESSION['error'] = "Failed to add category {$name}";
        }
        header("Location: categories.php");
        exit();
    }
    
    // Handle Category Deletion
    if (isset($_GET['delete'])) {
        $id = (int) $_GET['delete'];
        $row = $query->getDataById('name','categories',$id)->fetch_assoc();
        $name = $row['name'];
        // exit;
        echo $name;
        $result = $query->deleteData('categories',$id);
        if ($result) {
            session_start();
            $_SESSION['success'] = "<b>{$name}</b> Deleted successfully";   
        }else{
            session_start();
            $_SESSION['error'] = "Failed to delete category {$name}";
        }
        header("Location: categories.php");
        exit();
    }

    $main_categories = $query->getDataWhere('*','categories','WHERE parent_id IS NULL');
    $categories = $query->getData("*","categories","all")->fetch_all(MYSQLI_ASSOC);
    
require_once '../head.php';
echo "<title>Categories | E-Shop</title><style>
    section {
            margin-top: 120px !important;
        }
    @media (max-width: 768px) {
        section {
            margin-top: 120px;
        }
    }</style>";
require_once 'header.php';
?>   
    <section class="shop">
        <div class="shop">
        <div class="main-heading">
            <h1>Manage Categories</h1>
            <a href="index.php" class="btn btn-secondary fs-5">< Back to Admin</a>
        </div>
        <div id="alerts">
        <?php if (isset($_SESSION['error'])) {
            echo "<span class='d-flex'><p class='alert alert-danger' role='alert'>".$_SESSION['error']."</p><button id='cross' class='btn-cross'> X </button></span>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<span class='d-flex'><p class='alert alert-success' role='alert'>".$_SESSION['success']."</p><button id='cross' class='btn-cross'> X </button></span>";
            unset($_SESSION['success']);
        } ?>
        </div>
            <form class="form w-75 form-border" method="post">
                <legend class="text-center">Add Category</legend>
                <div class="admin-form">
                    Name: <input class="form-control w-25 col-md-5" type="text" name="name" required>
                    <select class="form-select w-25" name="parent_id">
                        <option value="">No Parent (Main Category)</option>
                        <?php foreach ($main_categories as $cat) { ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php } ?>
                    </select>
                    <button type="submit"  name="add_category" class="btn btn-success">Add Category</button>
                </div>
            </form>
        </div>
        <!-- <hr> -->
        <h3 class="mt-3 mb-0">All Categories</h3>
        <div class="table-container d-flex justify-content-center">
            <table class="table table-bordered table-striped table-light">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Actions</th>
                    </tr>  
                </thead>
                <tbody>
                <?php foreach ($categories as $category) { ?>
                    <tr>
                        <td data-label="ID"><?php echo $category['id'] ?></td>
                        <td data-label="Name"><?php echo $category['name'] ?></td>
                        <td data-label="Parent Category"><?php if ($category['parent_id']){
                                $category_id = (int)$category['parent_id'];
                                $category_row = $query->getDataById('name','categories',$category_id)->fetch_assoc();
                                $category_name = $category_row['name']; echo $category_name;
                            }  else {echo 'Main Category';} ?>
                        </td>
                        <td data-label="Actions">
                            <a class="btn btn-dark" href='edit_category.php?id=<?php echo $category["id"] ?>'>Edit</a> |
                            <a class="btn btn-danger" href="?delete=<?= $category['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php 
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="<?= BASE_URL ; ?>assets/js/script.js"></script>
    <?php include(BASE_URL."footer.php") ?>