<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<form action="process_register.php" method="post" class="form-box">
    <h1>Member Registration</h1>
     <p>
        Have an account?Sign in <a href="login.php">here</a>
    </p>
    <div class="form-group">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="Enter first name">
    </div>

    <div class="form-group">
        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter last name">
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
        <label>
            <input type="checkbox" name="agree"> Agree to terms and conditions
        </label>
    </div>

    <button type="submit">Register</button>

</form>

<?php include 'includes/footer.php'; ?>