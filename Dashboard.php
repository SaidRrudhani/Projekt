<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_catalog_item'])) {
    $title = $_POST['item_title'] ?? '';
    $description = $_POST['item_description'] ?? '';
    $message = '';
    
    if (!empty($title) && !empty($description) && isset($_FILES['item_image'])) {
        $uploadDir = 'Photos/';
        $fileName = $_FILES['item_image']['name'];
        $fileTmp = $_FILES['item_image']['tmp_name'];
        $fileError = $_FILES['item_image']['error'];
        
        if ($fileError === UPLOAD_ERR_OK) {
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
            
            if (in_array($fileExt, $allowedExts)) {
                $newFileName = uniqid('catalog_', true) . '.' . $fileExt;
                $uploadPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    $catalogFile = 'catalog_items.json';
                    $items = [];
                    
                    if (file_exists($catalogFile)) {
                        $items = json_decode(file_get_contents($catalogFile), true) ?: [];
                    }
                    
                    $items[] = [
                        'id' => uniqid(),
                        'title' => $title,
                        'description' => $description,
                        'image' => $uploadPath,
                        'date' => date('Y-m-d H:i:s')
                    ];
                    
                    file_put_contents($catalogFile, json_encode($items, JSON_PRETTY_PRINT));
                    $message = 'Item added successfully!';
                } else {
                    $message = 'Error uploading file.';
                }
            } else {
                $message = 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp, avif';
            }
        } else {
            $message = 'Error uploading file.';
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="Project.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="Homepage.php">Home</a>
            <a href="AboutUs.php">About Us</a>
            <a href="Catalog.php">Catalog</a>
            <a href="Dashboard.php">Dashboard</a>
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
                </div>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <div class="dashboard-page-container">
            <div class="dashboard-page-header">
                <h1>Dashboard</h1>
            </div>
            <div class="dashboard-content">
                <div class="dashboard-section">
                    <h2>Overview</h2>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <h3>Total Products</h3>
                            <p class="stat-number">24</p>
                        </div>
                        <div class="stat-box">
                            <h3>Users</h3>
                            <p class="stat-number">156</p>
                        </div>
                        <div class="stat-box">
                            <h3>Orders</h3>
                            <p class="stat-number">89</p>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2>Recent Activity</h2>
                    <div class="activity-list">
                        <div class="activity-item">
                            <p>New user registered - 2 hours ago</p>
                        </div>
                        <div class="activity-item">
                            <p>Order #1234 completed - 5 hours ago</p>
                        </div>
                        <div class="activity-item">
                            <p>Catalog page updated - 1 day ago</p>
                        </div>
                        <div class="activity-item">
                            <p>Help page viewed 45 times - 2 days ago</p>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2>Quick Links</h2>
                    <div class="component-stats">
                        <div class="component-item">
                            <strong><a href="Catalog.php" style="color: #F0FFFF; text-decoration: none;">View Catalog</a></strong>
                        </div>
                        <div class="component-item">
                            <strong><a href="Analytics.php" style="color: #F0FFFF; text-decoration: none;">View Analytics</a></strong>
                        </div>
                        <div class="component-item">
                            <strong><a href="Help.php" style="color: #F0FFFF; text-decoration: none;">Help Center</a></strong>
                        </div>
                        <div class="component-item">
                            <strong><a href="AboutUs.php" style="color: #F0FFFF; text-decoration: none;">About Us</a></strong>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2>Add Catalog Item</h2>
                    <?php if (!empty($message)): ?>
                        <p style="color: #51CAFF; padding: 10px; background: rgba(81, 202, 255, 0.1); border-radius: 6px; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($message); ?>
                        </p>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                        <div>
                            <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Item Title:</label>
                            <input type="text" name="item_title" required 
                                   style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                          background: rgba(30, 30, 30, 0.6); color: #f4f8fa; font-family: 'Times New Roman', Times, serif;"
                                   placeholder="Enter item title">
                        </div>
                        <div>
                            <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Description:</label>
                            <textarea name="item_description" required rows="4"
                                      style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                             background: rgba(30, 30, 30, 0.6); color: #f4f8fa; font-family: 'Times New Roman', Times, serif;"
                                      placeholder="Enter item description"></textarea>
                        </div>
                        <div>
                            <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Image:</label>
                            <input type="file" name="item_image" accept="image/*" required
                                   style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                          background: rgba(30, 30, 30, 0.6); color: #f4f8fa; font-family: 'Times New Roman', Times, serif;">
                        </div>
                        <button type="submit" name="add_catalog_item" 
                                style="padding: 10px 20px; background: rgba(81, 202, 255, 0.3); color: #F0FFFF; 
                                       border: 1px solid rgba(81, 202, 255, 0.5); border-radius: 6px; cursor: pointer; 
                                       font-family: 'Times New Roman', Times, serif; font-weight: bold; font-size: 1rem;
                                       transition: background 0.2s;"
                                onmouseover="this.style.background='rgba(81, 202, 255, 0.5)'"
                                onmouseout="this.style.background='rgba(81, 202, 255, 0.3)'">
                            Add to Catalog
                        </button>
                    </form>
                </div>
                
                <div class="dashboard-section">
                    <h2 style="color: #FFFFFF !important; font-weight: bold;">System Status</h2>
                    <p style="color: #FFFFFF !important;">Everything is running normally. All systems operational.</p>
                    <p style="color: #FFFFFF !important;">Last updated: <strong style="color: #51CAFF !important;"><?php echo date('Y-m-d H:i:s'); ?></strong></p>
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
