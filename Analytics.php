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

    if (!isset($_SESSION['user_id'])) {
        header("Location: Login.php");
        exit();
    }

    if ($userModel->isKicked($_SESSION['user_id'])) {
        session_destroy();
        header("Location: Login.php?msg=kicked");
        exit();
    }
    
    if (!isset($_SERVER['HTTP_REFERER']) || stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
        header("Location: Homepage.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Analytics</title>
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
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
    <div class="main-content">
        <div class="analytics-page-container">
            <div class="analytics-page-header">
                <h1>Analytics Dashboard</h1>
            </div>
            <div class="analytics-content">
                <div class="analytics-section">
                    <h2>Overview</h2>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <h3>Total Visits</h3>
                            <p class="stat-number">1,247</p>
                        </div>
                        <div class="stat-box">
                            <h3>Page Views</h3>
                            <p class="stat-number">3,891</p>
                        </div>
                        <div class="stat-box">
                            <h3>Unique Visitors</h3>
                            <p class="stat-number">892</p>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-section">
                    <h2>Popular Components</h2>
                    <div class="component-stats">
                        <div class="component-item">
                            <strong>GPU</strong>
                            <span class="stat-value">45%</span>
                        </div>
                        <div class="component-item">
                            <strong>CPU</strong>
                            <span class="stat-value">32%</span>
                        </div>
                        <div class="component-item">
                            <strong>RAM</strong>
                            <span class="stat-value">23%</span>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-section">
                    <h2>Recent Activity</h2>
                    <div class="activity-list">
                        <div class="activity-item">
                            <p><strong>Home page</strong> viewed 156 times today</p>
                        </div>
                        <div class="activity-item">
                            <p><strong>Help page</strong> accessed 42 times this week</p>
                        </div>
                        <div class="activity-item">
                            <p><strong>Catalog</strong> browsed 89 times this month</p>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-section">
                    <h2>Time Statistics</h2>
                    <p>Average session duration: <strong>4 minutes 32 seconds</strong></p>
                    <p>Most active time: <strong>2:00 PM - 4:00 PM</strong></p>
                    <p>Peak day: <strong>Wednesday</strong></p>
                </div>
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

        (function() {
            history.pushState(null, null, location.href);
            window.addEventListener('popstate', function() {
                window.location.href = 'Homepage.php';
            });
        })();
    </script>
     
</body>
</html>

