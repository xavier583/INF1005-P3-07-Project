<?php
session_start();
$rootPath = ".";
include "includes/header.php";
include "includes/nav.php";

$success = true;
$errorMsg = "";
$username = "";
$email = trim($_POST["email"] ?? "");
$password = $_POST["pwd"] ?? "";
$user_id = 0;

echo "<div class='container py-5'>";
echo "<div class='response text-center'>";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $success = false;
    $errorMsg = "Invalid request.";
} elseif (empty($email) || empty($password)) {
    $success = false;
    $errorMsg = "Email and password are required.";
} else {
    authenticateUser();
}

if ($success) {
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
    $_SESSION["logged_in"] = true;

    echo "<h4>Login successful!</h4>";
    echo "<h5 class='mb-4'>Welcome back, " . htmlspecialchars($username) . "</h5>";
    echo "<a href='index.php' class='btn btn-dark'>Go to Home</a>";
} else {
    echo "<h4>The following input errors were detected:</h4>";
    echo "<p>" . htmlspecialchars($errorMsg) . "</p>";
    echo "<a href='login.php' class='btn btn-dark'>Return to login</a>";
}

echo "</div>";
echo "</div>";

function authenticateUser()
{
    global $conn, $user_id, $username, $email, $password, $errorMsg, $success;

    include "php/db_connect.php";

    if (!isset($conn) || $conn->connect_error) {
        $errorMsg = "Database connection failed.";
        $success = false;
        return;
    }

    $stmt = $conn->prepare("SELECT member_id, username, email, password FROM maison_reluxe_members WHERE email = ?");

    if (!$stmt) {
        $errorMsg = "Failed to prepare login query.";
        $success = false;
        return;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row["member_id"];
        $username = $row["username"];
        $hashed_password = $row["password"];

        if (!password_verify($password, $hashed_password)) {
            $errorMsg = "Email not found or password doesn't match.";
            $success = false;
        }
    } else {
        $errorMsg = "Email not found or password doesn't match.";
        $success = false;
    }

    $stmt->close();
    $conn->close();
}
?>

<style>
    .response {
        max-width: 680px;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<?php include "includes/footer.php"; ?>