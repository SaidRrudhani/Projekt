<?php
    session_start();
    include_once 'Database.php';
    include_once 'User.php';
    include_once 'Product.php';


    if (!isset($_SESSION['user_id'])) {
        header("Location: Login.php");
        exit();
    }

    if (!isset($_SESSION['Role']) || $_SESSION['Role'] != 1) {
        header("Location: Catalog.php");
        exit();
    }

    if (!isset($_SERVER['HTTP_REFERER']) || stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
        header("Location: Homepage.php");
        exit();
    }

    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);
    $productModel = new Product($conn);
    $message = '';

    // --- ACTIONS ---

    // Delete User
    if (isset($_POST['delete_user'])) {
        if ($userModel->deleteUser($_POST['user_id'])) {
            $message = "User deleted successfully.";
        } else {
            $message = "Error deleting user.";
        }
    }

    // Kick User (Force Logout)
    if (isset($_POST['kick_user'])) {
        if ($userModel->kickUser($_POST['user_id'])) {
            $message = "User has been signed out (will take effect on their next page load).";
        } else {
            $message = "Error kicking user.";
        }
    }

    // Delete Product
    if (isset($_POST['delete_product'])) {
        if ($productModel->delete($_POST['product_id'])) {
            $message = "Product deleted successfully.";
        } else {
            $message = "Error deleting product.";
        }
    }

    // Add Product
    if (isset($_POST['add_product'])) {
        try {
            $title = $_POST['item_title'];
            $description = $_POST['item_description'];
            $quantity = isset($_POST['item_quantity']) ? (int)$_POST['item_quantity'] : 1;
            
            if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
                $uploadDir = 'Photos/';
                $fileName = basename($_FILES['item_image']['name']);
                $targetPath = $uploadDir . $fileName; 
                
                if (move_uploaded_file($_FILES['item_image']['tmp_name'], $targetPath)) {
                    if ($productModel->add($title, $description, $targetPath, $quantity)) {
                        $message = "Product added successfully!";
                    } else {
                        $message = "Database error adding product.";
                    }
                } else {
                    $message = "Error uploading image.";
                }
            } else {
                $message = "Please list an image.";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }


    // Stats
    $totalUsers = $conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
    $totalProducts = $productModel->getCount();
    
    // Users List
    $users = $userModel->getAllUsers();

    // Products List for Management
    $allProducts = $productModel->getAll();

    // Edit Product Handling
    $editProduct = null;
    if (isset($_GET['edit_product_id'])) {
        $editProduct = $productModel->getById($_GET['edit_product_id']);
    }

    // Update Product Logic
    if (isset($_POST['update_product'])) {
        try {
            $targetPath = null;
            if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
                $uploadDir = 'Photos/';
                $fileName = basename($_FILES['item_image']['name']);
                $targetPath = $uploadDir . $fileName; 
                if (!move_uploaded_file($_FILES['item_image']['tmp_name'], $targetPath)) {
                    $targetPath = null;
                }
            }

            if ($productModel->update($_POST['product_id'], $_POST['item_title'], $_POST['item_description'], $_POST['item_quantity'], $targetPath)) {
                header("Location: Dashboard.php?msg=updated");
                exit();
            }
        } catch (Exception $e) {
            $message = "Error updating product: " . $e->getMessage();
        }
    }
    
    if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
        $message = "Product updated successfully!";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="Project.css">
    <style>
        /* Dashboard specific overrides since logic is complex */
        .user-table {
            width: 100%;
            border-collapse: collapse;
            color: #F0FFFF;
            margin-top: 15px;
        }
        .user-table th, .user-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid rgba(81, 202, 255, 0.2);
        }
        .user-table th {
            background: rgba(81, 202, 255, 0.1);
            color: #51CAFF;
        }
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            margin-right: 5px;
        }
        .btn-kick {
            background: #f39c12;
            color: white;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        .alert-msg {
            background: rgba(81, 202, 255, 0.2);
            color: #51CAFF;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
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
        <div class="dashboard-page-container">
            <div class="dashboard-page-header">
                <h1>Admin Dashboard</h1>
            </div>
            <div class="dashboard-content">

                <?php if ($message): ?>
                    <div class="alert-msg"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <!-- SECTION 1: OVERVIEW STATS -->
                <div class="dashboard-section">
                    <h2>System Overview</h2>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <h3>Total Users</h3>
                            <p class="stat-number"><?php echo $totalUsers; ?></p>
                        </div>
                        <div class="stat-box">
                            <h3>Total Products</h3>
                            <p class="stat-number"><?php echo $totalProducts; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- SECTION 2: USER MANAGEMENT -->
                <div class="dashboard-section">
                    <h2>User Management</h2>
                    <div style="overflow-x: auto;">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo $u['id']; ?></td>
                                    <td><?php echo htmlspecialchars($u['Fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($u['Email']); ?></td>
                                    <td><?php echo $u['Role'] == 1 ? 'Admin' : 'User'; ?></td>
                                    <td>
                                        <?php if ($u['Role'] != 1): ?>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Sign out (Kick) this user?');">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" name="kick_user" class="action-btn btn-kick">Sign Out</button>
                                            </form>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete this user?');">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" name="delete_user" class="action-btn btn-delete">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color:#aaa;">(Admin)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 3: PRODUCT MANAGEMENT -->
                <div class="dashboard-section">
                    <h2><?php echo $editProduct ? 'Edit Product' : 'Add Catalog Product'; ?></h2>
                    <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                        <?php if ($editProduct): ?>
                            <input type="hidden" name="product_id" value="<?php echo $editProduct['ProductID']; ?>">
                        <?php endif; ?>
                        <div>
                            <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Product Name:</label>
                            <input type="text" name="item_title" required 
                                   style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                          background: rgba(30, 30, 30, 0.6); color: #f4f8fa;"
                                   placeholder="Enter product name" 
                                   value="<?php echo $editProduct ? htmlspecialchars($editProduct['ProductName']) : ''; ?>">
                        </div>
                        <div>
                            <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Description:</label>
                            <textarea name="item_description" required rows="4"
                                      style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                             background: rgba(30, 30, 30, 0.6); color: #f4f8fa;"
                                      placeholder="Enter item description"><?php echo $editProduct ? htmlspecialchars($editProduct['description']) : ''; ?></textarea>
                        </div>
                        <div style="display: flex; gap: 20px;">
                            <div style="flex:1;">
                                <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Quantity:</label>
                                <input type="number" name="item_quantity" value="<?php echo $editProduct ? $editProduct['Quantity'] : '1'; ?>" min="0" required 
                                       style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                              background: rgba(30, 30, 30, 0.6); color: #f4f8fa;">
                            </div>
                        </div>
                        <div>
                            <label style="color: #F0FFFF; display: block; margin-bottom: 5px;">Image: <?php echo $editProduct ? '(Leave blank to keep current)' : ''; ?></label>
                            <input type="file" name="item_image" accept="image/*" <?php echo $editProduct ? '' : 'required'; ?>
                                   style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid rgba(81, 202, 255, 0.3); 
                                          background: rgba(30, 30, 30, 0.6); color: #f4f8fa;">
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" name="<?php echo $editProduct ? 'update_product' : 'add_product'; ?>" 
                                    style="padding: 10px 20px; background: rgba(81, 202, 255, 0.3); color: #F0FFFF; 
                                           border: 1px solid rgba(81, 202, 255, 0.5); border-radius: 6px; cursor: pointer; 
                                           font-weight: bold; transition: background 0.2s;">
                                <?php echo $editProduct ? 'Update Product' : 'Add to Catalog'; ?>
                            </button>
                            <?php if ($editProduct): ?>
                                <a href="Dashboard.php" style="padding: 10px 20px; background: rgba(255,255,255,0.1); color: #eee; 
                                           border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; cursor: pointer; 
                                           text-decoration: none; font-weight: bold; display: flex; align-items: center;">
                                    Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- SECTION 4: MANAGE PRODUCTS -->
                <div class="dashboard-section">
                    <h2>Manage Products</h2>
                    <div style="overflow-x: auto;">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allProducts as $p): ?>
                                <tr>
                                    <td><?php echo $p['ProductID']; ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="<?php echo htmlspecialchars($p['image_path']); ?>" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                            <span><?php echo htmlspecialchars($p['ProductName']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $p['Quantity']; ?></td>
                                    <td>
                                        <a href="?edit_product_id=<?php echo $p['ProductID']; ?>" class="action-btn" style="background:#3498db; color:white; text-decoration:none;">Edit</a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Immediately delete this product?');">
                                            <input type="hidden" name="product_id" value="<?php echo $p['ProductID']; ?>">
                                            <button type="submit" name="delete_product" class="action-btn btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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
            dropdown.classList.toggle('open'); // Corrected for Project.css
        });
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
                if (dropdown.classList.contains('open')) {
                    dropdown.classList.remove('open');
                }
            }
        }
    </script>
</body>
</html>
