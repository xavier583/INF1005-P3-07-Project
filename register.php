<?php
$rootPath = ".";
include 'includes/header.php';
?>
<?php include 'includes/nav.php'; ?>

<main>
<form action="process_register.php" method="post" class="form-box" aria-label="Member Registration Form">
    <h1 id="register-heading">Member Registration</h1>
    <p>
        Have an account? <a href="login.php">Sign in</a>
    </p>
    <fieldset>
        <legend class="sr-only">Account Details</legend>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" pattern="[A-Za-z0-9_]{4,20}" required aria-describedby="username-error">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            if (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $username)) {
                echo "<p id='username-error' style='color: red;'>Username must be between 4 and 20 characters and can only contain letters, numbers, and underscores.</p>";
            }
            $username = htmlspecialchars(trim($_POST['username']));
        }
        ?>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>
    </div>

    <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" id="pwd" name="pwd" placeholder="Enter password" required>
    </div>

    <div class="form-group">
        <label for="pwd_confirm">Confirm Password:</label>
        <input type="password" id="pwd_confirm" name="pwd_confirm" placeholder="Confirm password" required>
    </div>

    <div class="form-group checkbox">
        <input type="checkbox" name="agree" id="agree" required>
        <label for="agree">Agree to terms and conditions</label>
    </div>
    </fieldset>

    <button type="submit">Register</button>

</form>
</main>

<?php include 'includes/footer.php'; ?>