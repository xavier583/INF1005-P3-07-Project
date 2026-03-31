<?php
session_start();
include "php/functions.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$rootPath = ".";
include "includes/header.php";
include "includes/nav.php";

$user_id = 0;
$username = "";
$email = "";
$pwd = "";
$pwd_confirm = "";
$errorMsg = "";
$role = "user";
$success = true;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $success = false;
    $errorMsg = "Invalid request.";
}

if (empty($_POST["username"])) {
    $errorMsg .= "Username is required.<br>";
    $success = false;
} else {
    $username = sanitize_input($_POST["username"]);
    if (!preg_match("/^[A-Za-z0-9_]{4,20}$/", $username)) {
        $errorMsg .= "Username must be 4 to 20 characters and can only contain letters, numbers, and underscores.<br>";
        $success = false;
    }
}

if (empty($_POST["email"])) {
    $errorMsg .= "Email is required.<br>";
    $success = false;
} else {
    $email = sanitize_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Invalid email format.<br>";
        $success = false;
    }
}

if (empty($_POST["pwd"])) {
    $errorMsg .= "Password is required.<br>";
    $success = false;
} else {
    $pwd = $_POST["pwd"];
    $pwd_confirm = $_POST["pwd_confirm"] ?? "";

    if ($pwd !== $pwd_confirm) {
        $errorMsg .= "Passwords do not match.<br>";
        $success = false;
    } else {
        $pwd_hashed = password_hash($pwd, PASSWORD_DEFAULT);
    }
}

echo "<div class='response text-center my-5'>";

if ($success) {
    saveMemberToDB();
}

if ($success) {
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
    $_SESSION["logged_in"] = true;
    $_SESSION['role'] = $role;

    header("Location: products.php");
    exit();
} else {
    echo "<h4>The following input errors were detected:</h4>";
    echo "<p>" . $errorMsg . "</p>";
    echo "<a href='register.php' class='btn btn-dark'>Back to Sign-Up</a>";
}

echo "</div>";

function saveMemberToDB()
{
    global $conn, $username, $email, $pwd_hashed,$role, $errorMsg, $success;

    include "php/db_connect.php";

    if (!isset($conn) || $conn->connect_error) {
        $errorMsg = "Database connection failed.";
        $success = false;
        return;
    }

    $checkStmt = $conn->prepare("SELECT member_id FROM maison_reluxe_members WHERE email = ?");

    if (!$checkStmt) {
        $errorMsg = "Failed to prepare email check query.";
        $success = false;
        return;
    }

    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $errorMsg = "This email is already registered.";
        $success = false;
        $checkStmt->close();
        $conn->close();
        return;
    }
    $checkStmt->close();

    $stmt = $conn->prepare("INSERT INTO maison_reluxe_members (username, email, password,role) VALUES (?, ?, ?,?)");

    if (!$stmt) {
        $errorMsg = "Failed to prepare register query.";
        $success = false;
        $conn->close();
        return;
    }

    $stmt->bind_param("ssss", $username, $email, $pwd_hashed,$role);

    if (!$stmt->execute()) {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    }

    $user_id = $conn->insert_id;

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