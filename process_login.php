<?php
session_start();
require_once 'php/db_connect.php';

include 'includes/header.php';
include 'includes/nav.php';

$success  = false;
$errorMsg = '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

if (empty($username) || empty($_POST['pwd'])) {
    $errorMsg = 'Please enter both username and password.';
} else {
    // Query by username 
    $stmt = $conn->prepare(
        "SELECT username, password, role FROM maison_reluxe_members WHERE username = ?"
    );
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row        = $result->fetch_assoc();
        $pwd_hashed = $row['password'];
        $role       = $row['role'];

        if (password_verify($_POST['pwd'], $pwd_hashed)) {
            // ── Save login info to session ────────────────────────────
            $_SESSION['logged_in'] = true;
            $_SESSION['username']  = $row['username'];
            $_SESSION['role']      = $role; // 'admin' or 'user'
            // ─────────────────────────────────────────────────────────
            $success = true;
        } else {
            $errorMsg = 'Username or password does not match.';
        }
    } else {
        $errorMsg = 'Username or password does not match.';
    }
    $stmt->close();
}
?>

<main class="container my-5">
    <div class="response text-center">
        <?php if ($success): ?>
            <h4>Login successful!</h4>
            <h5>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</h5>
            <a href="index.php" class="btn btn-dark mt-3">Return to Home</a>
        <?php else: ?>
            <h4>The following input errors were detected:</h4>
            <p class="text-danger"><?= htmlspecialchars($errorMsg) ?></p>
            <a href="login.php" class="btn btn-outline-dark mt-3">Return to Login</a>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>