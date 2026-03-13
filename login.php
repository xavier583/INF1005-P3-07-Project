<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container">
    <h1>Member Sign In</h1>
    <p>
        New user? <a href="register.php">Create an account</a>.
    </p>
    <form action="process_login.php" method="post">
        <div class="form-group">  
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd" placeholder="Enter password" required>
        </div>

        <div class="form-group checkbox">
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>
        <button type="submit">Sign In</button>

    </form>
</main>

<?php include 'includes/footer.php'; ?>