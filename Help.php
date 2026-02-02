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
    <title>Help & Support</title>
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
        <div class="help-page-container">
            <div class="help-page-header">
                <h1>Help & Support</h1>
            </div>
            <div class="help-content">
                <div class="help-section">
                    <h2>Getting Started</h2>
                    <p>Welcome to our platform! Here you can explore information about computer components including GPUs, CPUs, and RAM. This guide will help you navigate and make the most of our website.</p>
                </div>
                
                <div class="help-section">
                    <h2>Navigation</h2>
                    <p>Use the navigation bar at the top of the page to access different sections:</p>
                    <ul>
                        <li><strong>Home:</strong> Return to the main page where you can view information about computer components</li>
                        <li><strong>About Us:</strong> Learn more about our company and mission</li>
                        <li><strong>Catalog:</strong> Browse our product catalog and available items</li>
                    </ul>
                </div>
                
                <div class="help-section">
                    <h2>Menu Options</h2>
                    <p>Click on the "Menu" button in the top right corner to access additional options:</p>
                    <ul>
                        <li><strong>Profile:</strong> View and manage your account settings and personal information</li>
                        <li><strong>Settings:</strong> Customize your preferences and adjust application settings</li>
                        <li><strong>Help:</strong> Access this help page for support and guidance</li>
                        <li><strong>Analytics:</strong> View your usage statistics and activity data</li>
                    </ul>
                </div>
                
                <div class="help-section">
                    <h2>Component Information</h2>
                    <p>On the home page, you'll find three main component boxes:</p>
                    <ul>
                        <li><strong>GPU (Graphics Processing Unit):</strong> Learn about graphics cards and their role in rendering images and videos</li>
                        <li><strong>CPU (Central Processing Unit):</strong> Understand how processors work as the brain of your computer</li>
                        <li><strong>RAM (Random Access Memory):</strong> Discover how memory affects your system's performance</li>
                    </ul>
                    <p>Hover over each component box to see detailed information about its function and importance in your computer system.</p>
                </div>
                
                <div class="help-section">
                    <h2>Frequently Asked Questions</h2>
                    <div class="faq-item">
                        <h3>How do I navigate back to the home page?</h3>
                        <p>Click on "Home" in the navigation bar at the top of the page, or use the browser's back button.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Can I customize the information displayed?</h3>
                        <p>Currently, the component information is displayed automatically. Check the Settings menu for future customization options.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Where can I find more technical details?</h3>
                        <p>Hover over the component boxes on the home page for detailed descriptions. For more in-depth information, visit our Catalog section.</p>
                    </div>
                </div>
                
                <div class="help-section">
                    <h2>Need More Help?</h2>
                    <p>If you have additional questions or need further assistance, please don't hesitate to contact our support team. We're here to help you make the most of our platform.</p>
                    <p>You can also explore the other sections of the website to discover more features and information.</p>
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

