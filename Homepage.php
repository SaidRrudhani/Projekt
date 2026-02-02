<?php
    session_start();
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include_once 'Database.php';
    include_once 'User.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);

    if (isset($_SESSION['user_id'])) {
        if ($userModel->isKicked($_SESSION['user_id'])) {
            session_destroy();
            header("Location: Login.php?msg=kicked");
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home Page</title>
    <link rel="stylesheet" href="Project.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="Homepage.php">Home</a>
            <a href="AboutUs.php">About Us</a>
            <a href="Catalog.php">Catalog</a>
            <?php if(isset($_SESSION['Role']) && $_SESSION['Role'] == 1): ?>
                <a href="Dashboard.php">Dashboard</a>
            <?php endif; ?>
        </div>
        <div class="navbar-right">
            <div class="dropdown" id="navbarDropdown">
                <button class="dropdown-toggle" id="dropdownMenuButton">
                    Menu &#x25BC;
                </button>
                <div class="dropdown-menu">
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="Sign up page.php">Profile</a>
                    <?php endif; ?>
                    <a href="#">Settings</a>
                    <a href="Help.php">Help</a>
                    <a href="Analytics.php">Analytics</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="Logout.php">Logout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content centered-content">
        <div class="welcome-box">
            <h1 class="welcome-title"><b>Cyber Forge</b></h1>
            <p class="welcome-desc">
                Explore our Catalog and learn more <br> 
                about us using the buttons below.
            </p>
            <div class="welcome-links">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="Sign up page.php" class="welcome-btn Help-btn">Sign up</a>
                <?php endif; ?>
                <a href="AboutUs.php" class="welcome-btn about-btn">About Us</a>
                <a href="Catalog.php" class="welcome-btn catalog-btn">Catalog</a>
            </div>
        </div>
    </div>

    <script>
        const dropdown = document.getElementById('navbarDropdown');
        const dropdownBtn = dropdown.querySelector('.dropdown-toggle');
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');

        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>