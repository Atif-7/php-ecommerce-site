</head>
<body>
    
<script src="<?php echo BASE_URL; ?>assets/js/jquery-3.7.1.js"></script>
<script>
    $(document).ready(function(){
//   console.log("jQuery is working!");
        $("#search-box").keyup(function(){
            let query = $(this).val();
            if (query.length > 1) {
                $("#suggestions").css("display","block");
                $.ajax({
                url: "<?php echo BASE_URL; ?>search_suggestions.php",
                method: "GET",
                data: { query: query },
                dataType: "json", // Ensure response is JSON
                    success: function(data) {
                        //   console.log("AJAX Response:", data);  Debugging output

                        let suggestionBox = $("#suggestions");
                        suggestionBox.empty();

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(product => {
                                suggestionBox.append(`<p class="suggestion-item"><a href="<?php echo BASE_URL; ?>search.php?query=${product.name}">${product.name}</a></p>`);
                            });
                        } else {
                            suggestionBox.html("<p>No results found</p>");
                        }
                    },
                    error: function(xhr, status, error) {
                    console.log("AJAX Error:", status, error); // Debugging
                    console.log("Response Text:", xhr.responseText);
                    }
                });
            } else {
            $("#suggestions").empty();
            $("#suggestions").css("display","none");
            }
        });
    });
    window.onclick = function(event) {
        if (!event.target.matches('#suggestions p') && !event.target.matches('#search-box')) {
            $("#suggestions").css("display","none");
        }
    }
</script>
<header class="header">
<!-- Top Navbar -->
    <div class="top-bar container-fluid d-flex justify-content-between">
        <p>webdevatif@gmail.com</p>
        <p>Personal full-stack project (Complete E-Commerce website)</p>
        <p>+923193955313</p>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="logo d-flex justify-content-center">
            <a href="<?php echo BASE_URL; ?>index.php" class="nav-link"><h1> E-Shop </h1></a>
        </div>
        
        <form method="GET" action="<?php echo BASE_URL; ?>search.php" class="search-box">               
            <input type="text" name="query" id="search-box" placeholder="Search for products..." required>
            <button type="submit">🔍</button>
        </form>
        <div id="suggestions"></div>

        <div class="user-options">
            <?php 
            if (isset($_SESSION["loggedin"])) {
                echo '<span class="dropdown"><a href="#" class="dd-btn';

                if($current_page == "account.php") { echo ' active-link"';}else{echo '"' ;}

                echo ' onclick="myFunction(this)">👤 '.$_SESSION["name"].' ⌵</a>
                    <ul class="dd-menu pb-1" style="width:95px !important; left:15px;">
                        <li><a href="'.BASE_URL.'user/account.php"';
                        if($current_page == "account.php") { echo ' class="active-link"';}
                        echo '>Account</a></li>
                        <li><div><a href="'.BASE_URL.'user/logout.php" class="text-light bg-danger btn-sm">Logout</a></div></li>
                    </ul>
                </span>';
            }else{
                echo '<a class="nav-link';
                if($current_page == "account.php") { echo ' active-link"'; }else { echo '"'; }
                echo 'href="'.BASE_URL.'user/account.php">👤 Account</a>';
            }
            ?>
            <a class="nav-link <?= ($current_page == 'view_cart.php') ? 'active-link' : ''; ?>" href="<?php echo BASE_URL; ?>view_cart.php">🛒 Cart</a>
            <div id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div> <!-- Hamburger Icon -->
        </div>
    </div>

    <!-- Navigation Menu with Dropdown -->
    <nav class="nav-menu">
        <ul>
            <li><a class="nav-link <?= ($current_page == 'index.php') ? 'active-link' : ''; ?>" href="<?php echo BASE_URL; ?>index.php">Home</a></li>
            <li><a class="nav-link <?= ($current_page == 'shop.php') ? 'active-link' : ''; ?>" href="<?php echo BASE_URL; ?>shop.php">Shop</a></li>
            <li class="dropdown">
                <a href="#"class="dd-btn" onclick="myFunction(this)">Categories ⌵</a>
                <ul class="dd-menu">
                    <?php foreach ($main_categories as $cat) { ?>
                    <li><a href="<?php echo BASE_URL; ?>category.php?id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dd-btn" onclick="myFunction(this)">Sub Categories ⌵</a>
                <ul class="dd-menu">
                    <?php foreach ($categories as $cat) { 
                        if ($cat['parent_id'] != NULL) {
                        ?>
                    <li><a href="<?php echo BASE_URL; ?>category.php?id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                    <?php } } ?>
                </ul>
            </li>
            <li><a class="nav-link <?= ($current_page == 'contact.php') ? 'active-link' : ''; ?>" href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
        </ul>
    </nav>
</header>