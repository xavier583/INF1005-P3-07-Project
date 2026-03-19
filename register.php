<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container">
    <h1>Member Registration</h1>

    <p>
        For existing members, please go to the
        <a href="login.php">Sign In page</a>.
    </p>

    <form action="process_register.php" method="post">

        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="Enter first name" required>
        <br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter last name">
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>
        <br>

        <label for="pwd">Password:</label>
        <input type="password" id="pwd" name="pwd" placeholder="Enter password" required>
        <br>

        <label for="pwd_confirm">Confirm Password:</label>
        <input type="password" id="pwd_confirm" name="pwd_confirm" placeholder="Confirm password" required>
        <br>

        <label>
            <input type="checkbox" name="agree">
            Agree to terms and conditions.
        </label>
        <br>

        <button type="submit">Register</button>

    </form>
</main>

<?php include 'includes/footer.php'; ?>