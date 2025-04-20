<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $categories = $query->getDataWhere('*','categories','WHERE parent_id IS NULL');

    $category = $query->getDataById('*','categories',$id)->fetch_assoc();
    $category_name = htmlspecialchars($category['name']) ;

    $category_parent_id = $category['parent_id']; // null
    // exit;
    if ($category_parent_id != null) {
        $parent_category = $query->getDataWhere('*','categories','WHERE id = "'.$category_parent_id.'"')->fetch_assoc();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_category'])) {
    $name = $_POST['name'];
    if (!empty($_POST['parent_id'])){
        $parent_id = (int) $_POST['parent_id'];
        $data = array('name'=>$name,'parent_id'=>$parent_id);
    }else {
        $data = array('name'=>$name);
    } 
    $result = $query->updateData('categories',$data,$id);
    if ($result) {
        session_start();
        $_SESSION['success'] = "<b>{$name}</b> updated successfully";
    }else{
        session_start();
        $_SESSION['error'] = "Failed to update category {$name}";
    }
    header("Location: categories.php");
    exit();
}

require_once '../head.php';
echo "<title>Edit Product - E-Shop</title><style>
    @media (max-width: 768px) {
        section {
            margin-top: 175px;
        }
    }</style>";
require_once 'header.php';

?>
    <section class="shop">
        <div class="main-heading">
            <h2>Edit category <span class="text-secondary">( <?= $category_name ?> )</span></h2>
            <a href="categories.php" class="btn link-primary">< Manage Categories</a>
            <a href="index.php" class="btn link-dark"><< Back to Admin</a>
        </div>

        <form method="POST" class="form-border mt-2 w-75" enctype="multipart/form-data">
            <div class="admin-form">
                <label for="name">Name:</label> <input class="form-control w-50" type="text" name="name" value="<?= $category['name'] ?>" required>
                <?php if ($category['parent_id'] != null) { ?>
                    <label for="category" class="w-30">Parent Category:</label>
                    <select class="form-select w-50" name="parent_id">
                        <option class="fw-bold" value="<?= $category['parent_id'] ?>" selected><?php if ($category_parent_id == null) {
                            echo "No Parent - Main Category"; 
                        }else { echo $parent_category['name']; } ?></option>
                        <?php foreach ($categories as $cat) { 
                                if($cat['name'] != $category['name'] AND $cat['name'] != $parent_category['name']) {
                                    if ($cat['parent_id'] == null) {
                                        $cat_name = htmlspecialchars($cat['name']);
                                        echo "<option value='{$cat["id"]}'>{$cat_name}</option>'";
                                    }
                                }
                            }
                        ?>
                    </select>
                <?php } ?>    
            </div>
            <div class="admin-form mt-3">
                <button class="btn btn-success btn-lg text-light" name="update_category" type="submit">Save</button>
            </div>
        </form>
    <?php include '../footer.php' ?>