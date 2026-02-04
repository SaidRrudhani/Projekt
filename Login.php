<?php
    session_start();
    include_once 'Database.php';
    include_once 'User.php';
    
    if (isset($_SESSION['user_id'])) {
        echo '<script>alert("You are already signed in"); window.location.href="Homepage.php";</script>';
        exit();
    }


    $login_result = null;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
        $db = new Database();
        $connection = $db->getConnection();
        $user = new User($connection);

        $Email = $_POST['email'];
        $Password = $_POST['password'];

        $login_result = $user->login($Email, $Password);
        if($login_result === true){

            if (isset($_POST['remember_me'])) {
                setcookie('remember_email', $Email, time() + (86400 * 30), "/"); 
            } else {
                if (isset($_COOKIE['remember_email'])) {
                    setcookie('remember_email', '', time() - 3600, "/");
                }
            }
            header("Location: Catalog.php");
            exit();

        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Login.css">
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
                <div class="dropdown-menu" id="navbarDropdownMenu">
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
    <div id="loginRoot">
      <div class="signup-container-outer" id="signupContainerOuter">
        <div class="signup-container" id="signupContainer">
            <h2>Login</h2>
            <form id="loginForm" method="POST" autocomplete="off" novalidate>
                <label>Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : ''; ?>" required />
                <div class="error-msg" id="email-error"><?php echo ($login_result === "email_not_found") ? "Your email is incorrect" : ""; ?></div>
                <label>Password:</label><br>
                <input type="password" id="password" name="password" required />
                <div class="error-msg" id="password-error"><?php echo ($login_result === "incorrect_password") ? "Your password is incorrect" : ""; ?></div>
                
                <div style="margin-bottom: 15px; text-align: left; width: 320px; max-width: 100%;">
                    <input type="checkbox" id="remember_me" name="remember_me" style="width: auto; display: inline-block; margin: 0 10px 0 0;">
                    <label for="remember_me" style="display: inline; color: #272750; font-weight: normal;">Remember Me</label>
                </div>

                <button type="submit">Login</button>
            </form>
            <div class="output" id="output"></div>
            <div class="login-link-container">
                                No account? | <a href="Sign up page.php" id="signup-link">Sign Up</a>
            </div>
            <div class="login-link-container" style="margin-top: 10px;">
                <a href="Homepage.php" style="color: #51CAFF; text-decoration: none; font-size: 0.9em;">&larr; Back to Home</a>
            </div>
        </div>
      </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('navbarDropdown');
        var button = document.getElementById('dropdownMenuButton');
        
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
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
    

    (function setUpLoginLogic() {
        const fields = ['email', 'password'];
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
                });
            }
        });

        
        document.getElementById('loginForm').onsubmit = function(e) {
            e.preventDefault();
            var valid = true;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

          if(email === "" || email.trim() === "") { setError('email','Email is required'); valid = false; }
            else if(!validateEmail(email)) { setError('email', 'Email must be of the form user@example.com'); valid = false; }
            else clearError('email');

            if(password === "" || password.trim() === "") { setError('password','Password is required'); valid = false; }
            else if(!validatePassword(password)) { setError('password', 'Password must have an uppercase letter and a number and must be 8 characters or more long'); valid = false; }
            else clearError('password');

            if(!valid) {
                var out = document.getElementById('output');
                out.innerHTML = "";
                out.style.display = "none";
                return false;
            }
            var out = document.getElementById('output');
            out.innerHTML = "";
            out.style.display = "none";

            e.target.submit();
            return true;
        }
    })();
    </script>
</body>
</html>