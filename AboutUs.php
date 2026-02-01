<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: Login.php");
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
    <title>About Us</title>
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
                    <a href="Sign up page.php">Profile</a>
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
        <div class="about-div">
            <div class="about-page-header">
                <h1>About Us</h1>
            </div>
            <div class="about-content">
                <div class="about-section">
                    <h2>Our Mission</h2>
                    <p>We aim to make technology clear and approachable. By breaking down the essentials of computer components, we help everyone — from first‑time builders to seasoned enthusiasts — understand how their systems work and how to get the most out of them.</p>
                </div>
                
                <div class="about-section">
                    <h2>What We Do</h2>
                        <p>Our platform is designed to help you understand the core components that power modern computers. We break down complex ideas into clear, practical explanations so you can make confident decisions whether you’re building, upgrading, or simply learning.</p>
                    <ul>
                        <li><strong>GPUs (Graphics Processing Units):</strong> Explore how graphics cards work, compare models, and see how performance impacts gaming, design, and everyday use.</li>
                        <li><strong>CPUs (Central Processing Units):</strong> Learn how processors handle tasks, discover the differences between models, and understand how core counts and threads affect speed.</li>
                        <li><strong>RAM (Random Access Memory):</strong> Find out why memory matters, how different versions like DDR4 and DDR5 improve performance, and what’s best for your system.</li>
                    </ul>
                        <p>We combine technical detail with easy‑to‑follow guides, giving you both the big picture and the specifics you need to choose the right components.</p>
                </div>
                
                <div class="about-section">
                    <h2>Our Values</h2>
                    <div class="values-list">
                        <div class="value">
                            <h3>Education First</h3>
                                <p>We believe learning should be simple, engaging, and accessible to all.</p>
                        </div>
                        <div class="value">
                            <h3>Accuracy</h3>
                                <p>We provide reliable, up‑to‑date information so you can trust what you read.</p>
                        </div>
                        <div class="value">
                            <h3>User Focus</h3>
                                <p>Your curiosity and needs guide our work. We’re here to support your journey.</p>
                        </div>
                    </div>
                </div>
                
                <div class="about-section">
                    <h2>Why Choose Us</h2>
                    <p>Whether you’re building your first PC, upgrading your setup, or simply curious about how computers work, we provide resources tailored to your level of experience. Our guides are written to be practical, clear, and easy to follow.</p>
                    <p>We know technology can feel overwhelming. That’s why we focus on simplifying complex ideas, giving you both the big picture and the details you need to make confident choices.</p>
                </div>
                
                <div class="about-section">
                    <h2>Get in Touch</h2>
                    <p>Have questions or ideas? We’d love to hear from you. Visit our Help section for answers, or explore our resources to learn more about computer components.</p>
                    <p>Thank you for being part of our community — we’re excited to support your journey into technology.</p>
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
    </script>
</body>
</html>

