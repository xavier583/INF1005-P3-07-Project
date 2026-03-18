<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<form action="process_register.php" method="post" class="form-box">
    <h1>Member Registration</h1>
    <p>
        Have an account? <a href="login.php">Sign in</a>
    </p>
    <div class="form-group">
        <label for="fname">Username:</label>
        <input type="username" id="username" name="username" placeholder="Enter username" pattern="[A-Za-z0-9_]{4,20}" required>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["fname"];
            if (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $username)) {
                echo "<p style='color: red;'>Username must be between 4 and 20 characters and can only contain letters, numbers, and underscores.</p>";
            }
            $username = htmlspecialchars(trim($_POST['username']));
        }
        ?>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email">
    </div>

    <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" id="pwd" name="pwd" placeholder="Enter password">
    </div>

    <div class="form-group">
        <label for="pwd_confirm">Confirm Password:</label>
        <input type="password" id="pwd_confirm" name="pwd_confirm" placeholder="Confirm password">
    </div>

    <div class="form-group checkbox">
        <input type="checkbox" name="agree" id="agree">
        <label for="agree">Agree to terms and conditions</label>
    </div>

    <button type="submit">Register</button>

</form>

<?php include 'includes/footer.php'; ?>