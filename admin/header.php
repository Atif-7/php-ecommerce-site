</head>
<body>
<script src="../assets/js/script.js"></script>
<header class="header">
    <!-- Top Navbar -->
        <div class="top-bar">
            <p>Find out how we're responding to COVID-19</p>
        </div>

        <!-- Main Header -->
        <div class="main-header">
            <div class="logo d-flex justify-content-center">
                <a href="../index.php" class="nav-link"><h1> E-Shop </h1></a>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li class="nav-item"><a class="nav-link active-link" href="index.php">Admin Panel</a></li>                
                    <li class="nav-item"><a class="nav-link" href="../shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item"><?php if (isset($_SESSION['admin_logged_in'])): ?>
                    <a class="btn btn-danger btn-sm" href="logout.php">Logout (<?= $_SESSION['admin_name'] ?>)🚪</a>
                    <?php endif; ?>
                    </li>
                </ul>
            </nav>
            <div id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div> 
        </div>

    <!-- Navigation Menu with Dropdown -->
   
</header>