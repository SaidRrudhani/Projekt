<?php
    include_once 'Database.php';
    include_once 'User.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
        $db = new Database();
        $connection = $db->getConnection();
        $user = new User($connection);

        $Fullname = $_POST['name'];
        $Email = $_POST['email'];
        $Password = $_POST['password'];


        if($user->register($Fullname, $Email, $Password)){
            header("Location: Login.php");
            exit();
        } else {
            echo "Registration failed. Please try again.";
        }    
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up page</title>
    <link rel="stylesheet" href="Sign up.css">
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
                <div class="dropdown-menu" id="navbarDropdownMenu">
                    <a href="Sign up page.php">Profile</a>
                    <a href="#">Settings</a>
                    <a href="Help.php">Help</a>
                    <a href="Analytics.php">Analytics</a>
                </div>
            </div>
        </div>
    </nav>

        <div id="signupRoot">
      <div class="signup-container-outer" id="signupContainerOuter">
        <div class="signup-container" id="signupContainer">
            <h2>Sign Up</h2>
            <form id="signupForm" method="POST" autocomplete="off" novalidate>

                <label>Email:</label><br>
                <input type="email" id="email" name="email" required />
                <div class="error-msg" id="email-error"></div>

                <label>Password:</label><br>
                <input type="password" id="password" name="password" required />
                <div class="error-msg" id="password-error"></div>

                <label>Fullname:</label><br>
                <input type="text" id="name" name="name" required />
                <div class="error-msg" id="name-error"></div>
                <button type="submit">Sign Up</button>
            </form>
    <div class="output" id="output"></div>
        <div class="login-link-container">
        Already signed up? | <a href="Login.php" id="login-link">Login</a>
            </div>
        </div>
      </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('navbarDropdown');
        var button = document.getElementById('dropdownMenuButton');
        var menu = document.getElementById('navbarDropdownMenu');

            document.addEventListener('click', function(e) {
            if (button.contains(e.target)) {
                menu.classList.toggle('show');

                var rect = menu.getBoundingClientRect();
                var vw = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
                if(rect.right > vw) {
                    menu.style.left = 'auto';
                    menu.style.right = '0';
                    menu.style.maxWidth = '96vw';
                } else {
                    menu.style.left = '';
                    menu.style.right = '';
                    menu.style.maxWidth = '';
                }
            } else if (!menu.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
            window.addEventListener('resize', function() {
            if(menu.classList.contains('show')) {
                var rect = menu.getBoundingClientRect();
                var vw = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
                if(rect.right > vw) {
                    menu.style.left = 'auto';
                    menu.style.right = '0';
                    menu.style.maxWidth = '96vw';
                } else {
                    menu.style.left = '';
                    menu.style.right = '';
                    menu.style.maxWidth = '';
                }
            }
        });
        var borderDiv = document.getElementById('signupContainerOuter');
        var deg = 0;
        function doSpin() {
            deg = (deg + 1) % 360;
            if (borderDiv) borderDiv.style.setProperty('--rotate', deg + 'deg');
            requestAnimationFrame(doSpin);
        }
        if (borderDiv) { requestAnimationFrame(doSpin); }
    });
        
    function validateEmail(val) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    }
    function validatePassword(val) {
    return val.length >= 8 && /[A-Z]/.test(val) && /\d/.test(val);
    }
    function validateFullname(val) {
        return val.length >= 3;
    }
    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

       (function setUpSignupLogic() {
        const fields = [
            'email', 'password', 'name',
        ];
        function setError(id, msg) {
            document.getElementById(id+'-error').textContent = msg;
        }
        function clearError(id) {
            document.getElementById(id+'-error').textContent = "";
        }
        fields.forEach(function(field) {
            var el = document.getElementById(field);
            if(el) {
                el.addEventListener('input', function() {
                    clearError(field);
                    if(field === 'password' || field === 'confirmpassword') {
                        clearError('confirmpassword');
                    }
                });
            }
        });
        
        document.getElementById('signupForm').onsubmit = function(e) {
            e.preventDefault();
            var valid = true;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var name = document.getElementById('name').value;

            if(email === "" || email.trim() === "") { setError('email','Email is required'); valid = false; }
            else if(!validateEmail(email)) { setError('email', 'Email must be of the form user@example.com'); valid = false; }
            else clearError('email');

            if(password === "" || password.trim() === "") { setError('password','Password is required'); valid = false; }
            else if(!validatePassword(password)) { setError('password', 'Password must have an uppercase letter and a number and must be 8 or more characters long'); valid = false; }
            else clearError('password');

            if(name === "" || name.trim() === "") { setError('name','Name is required'); valid = false; }
            else if(!validateFullname(name)) { setError('name', 'Name must be at least 3 characters long'); valid = false; }
            else clearError('name');

            if(!valid) {
                var out = document.getElementById('output');
                out.innerHTML = "";
                out.style.display = "none";
                return false;
            }
            var out = document.getElementById('output');
            out.innerHTML = "";
            out.style.display = "none";

            // Allow form submission
            e.target.submit();
            return true;
        }
    })();
    </script>
</body>
</html>