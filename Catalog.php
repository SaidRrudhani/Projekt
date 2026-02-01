<?php
    session_start();
    include_once 'Database.php';
    include_once 'User.php';
    include_once 'Product.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    $userModel = new User($conn);
    $productModel = new Product($conn);
    
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

    // Fetch Products
    $products = $productModel->getAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Catalog</title>
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
    <div class="main-content">
        <div class="Box-row" style="flex-wrap: wrap; justify-content: center;">
            <?php 
            if (count($products) > 0) {
                foreach ($products as $product): 
                    // Map new column names to UI
                    $pID = $product['ProductID'];
                    $pName = $product['ProductName'];
                    $pDesc = $product['description'] ?? 'No description available.';
                    $pImg = $product['image_path'] ?? '';
                    
                    // Use ID Box1, Box2 etc for legacy cycling support if IDs match
                    $boxId = "Box" . $pID; 
            ?>
            <div class="Box-wrapper">
                <!-- Apply background inline for dynamic images -->
                <div class="Box" id="<?php echo $boxId; ?>" title="<?php echo htmlspecialchars($pName); ?>"
                     style="background-image: url('<?php echo htmlspecialchars($pImg); ?>'); background-size: cover; background-position: center;">
                </div>
                <div class="info-box">
                    <p class="box-description">
                        <strong><?php echo htmlspecialchars($pName); ?></strong> &mdash; 
                        <?php echo htmlspecialchars($pDesc); ?>
                    </p>
                </div>
            </div>
            <?php endforeach; 
            } else {
                echo "<div style='color:white; text-align:center; width:100%; margin-top:20px;'>
                        <p>No products found.</p>
                        <p style='font-size:0.9em; color:#aaa;'>Please run <strong>update_schema.php</strong> to sync the database.</p>
                      </div>";
            }
            ?>
        </div>
        
        <div class="more-coming-soon-container">
            <div class="more-coming-soon" id="moreComingSoon">MORE COMING SOON</div>
        </div>
    </div>
    <script>
        const boxes = document.querySelectorAll('.Box');

        const moreComingSoon = document.getElementById('moreComingSoon');
        let hue = 0;
        function animateBorder() {
            hue = (hue + 1) % 360;
            const borderColor = `conic-gradient(
                hsl(${hue},90%,58%),
                hsl(${(hue+90)%360},90%,58%),
                hsl(${(hue+180)%360},90%,58%),
                hsl(${(hue+270)%360},90%,58%),
                hsl(${hue},90%,58%)
            )`;
            moreComingSoon.style.borderImage = `${borderColor} 1`;
            requestAnimationFrame(animateBorder);
        }
        function animateSimpleBorder() {
            hue = (hue + 2) % 360;
            const color = `hsl(${hue}, 90%, 58%)`;
            moreComingSoon.style.borderColor = color;
            requestAnimationFrame(animateSimpleBorder);
        }
        const test = document.createElement('div');
        test.style.borderImage = 'conic-gradient(red, yellow) 1';
        if (test.style.borderImage && CSS.supports('border-image', 'conic-gradient(red, yellow) 1')) {
            animateBorder();
        } else {
            animateSimpleBorder();
        }

        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            boxes.forEach(box => {
                box.style.boxShadow = `0 ${4 + (scrollY/30)}px ${18 + (scrollY/10)}px rgba(0,0,0,0.22)`;
            });
        });

        // Legacy Cycling Logic for Box1, Box2, Box3
        const box1 = document.getElementById('Box1');
        const box2 = document.getElementById('Box2');
        const box3 = document.getElementById('Box3');

        // Only setup cycling if elements exist
        const box1Images = [
            'Photos/1678052-radeon-gpu-background-1920x1080_3_cropped-rotated.jpg', 
            'Photos/HD-wallpaper-amd-radeon-graphic.jpg',
            'Photos/AMD-GPU-history.webp'
        ];
        const box2Images = [
            'Photos/2613900-amd-ryzen-9000-desktop-og.avif', 
            'Photos/Arctic-MX7-Options.jpg',
            'Photos/Asus_Motherboard.jpg'
        ];
        const box3Images = [
            'Photos/DDR5-RAM-and-Ryzen-Buyer-Beware-Twitter-1200x675.jpg', 
            'Photos/Asus_Motherboard.jpg',
            'Photos/AMD-GPU-history.webp'
        ];

        function setupCycling(boxElement, images) {
            let timeoutId;
            let intervalId;
            let currentIndex = 0;

            if(!boxElement) return;

            const originalBg = boxElement.style.backgroundImage;

            boxElement.addEventListener('mouseenter', () => {
                timeoutId = setTimeout(() => {
                    currentIndex = 1; 
                    intervalId = setInterval(() => {
                        const nextImage = images[currentIndex];
                        boxElement.style.backgroundImage = `url('${nextImage}')`;
                        currentIndex = (currentIndex + 1) % images.length;
                    }, 1500); 
                }, 2000);
            });

            boxElement.addEventListener('mouseleave', () => {
                if(timeoutId) clearTimeout(timeoutId);
                if(intervalId) clearInterval(intervalId);
                // Reset to default
                boxElement.style.backgroundImage = originalBg; 
            });
        }

        if(box1) setupCycling(box1, box1Images);
        if(box2) setupCycling(box2, box2Images);
        if(box3) setupCycling(box3, box3Images);
        // ---------------------------
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('navbarDropdown');
            if (dropdown) {
                const dropdownBtn = dropdown.querySelector('.dropdown-toggle');
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');

                if (dropdownBtn) {
                    dropdownBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        dropdown.classList.toggle('open');
                    });
                }

                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('open');
                    }
                });

                if (dropdownMenu) {
                    dropdownMenu.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }
            }
        });
    </script>
    
</body>
</html>