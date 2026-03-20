<?php
$rootPath = ".";
include 'includes/header.php';
?>
<?php include 'includes/nav.php'; ?>

<main class="container">
    <h1>Member Sign In</h1>
    <p>
        New user? <a href="register.php">Create an account</a>.
    </p>
    <form action="process_login.php" method="post">
        <div class="form-group">
        <label for="username">Username:</label>
        <input type="username" id="username" name="username" placeholder="Enter username" pattern="[A-Za-z0-9_]{4,20}" required>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            if (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $username)) {
                echo "<p style='color: red;'>Username must be between 4 and 20 characters and can only contain letters, numbers, and underscores.</p>";
            }
            $username = htmlspecialchars(trim($_POST['username']));
        }
        ?>
        </div>

        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd" placeholder="Enter password" required>
        </div>

        <div class="form-group checkbox">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </div>
        <button type="submit">Sign In</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>