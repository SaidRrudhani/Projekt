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
        <div class="Box-row">
            <div class="Box-wrapper">
                <div class="Box" id="Box1" title="GPU"></div>
                <div class="info-box">
                    <p class="box-description">
                        <strong>GPU</strong> &mdash;The GPU is dedicated to rendering images, video, and animations. By handling visual workloads separately from the CPU, 
                        it delivers smoother graphics performance and faster rendering.High‑end GPUs are essential for gaming, video editing, and other demanding applications where speed and clarity matter.
                    </p>
                </div>
            </div>
            <div class="Box-wrapper">
                <div class="Box" id="Box2" title="CPU"></div>
                <div class="info-box">
                    <p class="box-description">
                        <strong>CPU</strong> &mdash;Often called the “brain” of the computer, the CPU executes instructions and manages system operations. 
                        Modern processors feature multiple cores and advanced multithreading, allowing them to handle complex tasks efficiently. 
                        A powerful CPU ensures responsive performance across everyday computing and professional workloads.
                    </p>
                </div>
            </div>
            <div class="Box-wrapper">
                <div class="Box" id="Box3" title="RAM"></div>
                <div class="info-box">
                    <p class="box-description">
                        <strong>RAM</strong> &mdash;RAM provides fast, temporary storage for data that the CPU is actively using. 
                        More RAM allows a system to multitask smoothly, keeping applications responsive without slowing down. 
                        While it works closely with the CPU, RAM capacity and speed are critical for overall system performance.
                        Such aspects are improved upon by the DDR5 version.
                    </p>
                </div>
            </div>
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

        const box1 = document.getElementById('Box1');
        const box2 = document.getElementById('Box2');
        const box3 = document.getElementById('Box3');

        const box1Images = [
            'Photos/1678052-radeon-gpu-background-1920x1080_3_cropped-rotated.jpg', // Default
            'Photos/HD-wallpaper-amd-radeon-graphic.jpg',
            'Photos/AMD-GPU-history.webp'
        ];
        const box2Images = [
            'Photos/2613900-amd-ryzen-9000-desktop-og.avif', // Default
            'Photos/Arctic-MX7-Options.jpg',
            'Photos/Asus_Motherboard.jpg'
        ];
        const box3Images = [
            'Photos/DDR5-RAM-and-Ryzen-Buyer-Beware-Twitter-1200x675.jpg', // Default
            'Photos/Asus_Motherboard.jpg',
            'Photos/AMD-GPU-history.webp'
        ];

        function setupCycling(boxElement, images) {
            let timeoutId;
            let intervalId;
            let currentIndex = 0;

            if(!boxElement) return;

            
            boxElement.addEventListener('mouseenter', () => {

                timeoutId = setTimeout(() => {

                    currentIndex = 1; 
                    
                    intervalId = setInterval(() => {
                        const nextImage = images[currentIndex];

                        
                        boxElement.style.background = `url('${nextImage}') center center/cover no-repeat, rgba(30, 30, 30, 0.78)`;
                        
                        currentIndex = (currentIndex + 1) % images.length;
                    }, 1500); 

                }, 2000);
            });

            boxElement.addEventListener('mouseleave', () => {
                if(timeoutId) clearTimeout(timeoutId);
                if(intervalId) clearInterval(intervalId);
                // Reset to default (remove inline style so CSS takes over)
                boxElement.style.background = ''; 
            });
        }

        setupCycling(box1, box1Images);
        setupCycling(box2, box2Images);
        setupCycling(box3, box3Images);
        // ---------------------------

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