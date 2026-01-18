<?php
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
            <a href="Homepage.html">Home</a>
            <a href="AboutUs.html">About Us</a>
            <a href="Catalog.html">Catalog</a>
        </div>
        <div class="navbar-right">
            <div class="dropdown" id="navbarDropdown">
                <button class="dropdown-toggle" id="dropdownMenuButton">
                    Menu &#x25BC;
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="Sign up page.html">Profile</a>
                    <a href="#">Settings</a>
                    <a href="Help.html">Help</a>
                    <a href="Analytics.html">Analytics</a>
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
        let dropdownOpen = false;

        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownOpen = !dropdownOpen;
            dropdown.classList.toggle('open', dropdownOpen);
        });

        document.addEventListener('click', function() {
            if (dropdownOpen) {
                dropdownOpen = false;
                dropdown.classList.remove('open');
            }
        });
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>

