<?php
$rootPath = ".";
include 'includes/header.php';
include 'includes/nav.php';
?>

<main class="container py-5" style="max-width: 700px;">
    <h1 class="mb-3">Member Sign In</h1>
    <p class="mb-4">
        New user? <a href="register.php">Create an account</a>.
    </p>

    <form action="process_login.php" method="post" class="border rounded p-4 bg-light">
        <div class="form-group mb-3">
            <label for="email" class="mb-2">Email:</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>

        <div class="form-group mb-3">
            <label for="pwd" class="mb-2">Password:</label>
            <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-dark">Sign In</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>