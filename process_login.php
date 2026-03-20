 <?php
    include "includes/header.php";
    ?>
 <?php
    include "includes/nav.php";
    ?>
<?php 
$success = true;
$email = $_POST["email"];


echo "<div class='response'>";
authenticateUser();
if ($success) {
    echo "<h4>Login successful!</h4>";
    echo "<h5>Welcome back, "  . $username . "</h5>";
    echo " <a href = 'index.php' class='btn ms-auto'> Return to home </a>";
} else {
    echo "<h4>The following input errors were detected:</h4>";
    echo "<p>" . $errorMsg . "</p>";
    echo " <a href = 'login.php' class='btn ms-auto'> Return to login </a>";
}
echo "</div>";

function authenticateUser()
{
global $username, $email, $pwd_hashed, $errorMsg, $success;
// Create database connection.
$config = parse_ini_file('/var/www/private/db_config.ini');
if (!$config)
{
$errorMsg = "Failed to read database config file.";
$success = false;
}
else
{
$conn = new mysqli(
$config['servername'],
$config['username'],
$config['password'],
$config['dbname']
);
// Check connection
if ($conn->connect_error)
{
$errorMsg = "Connection failed: " . $conn->connect_error;
$success = false;
}
else
{
// Prepare the statement:
$stmt = $conn->prepare("SELECT * FROM maison_reluxe_members WHERE email=?");
// Bind & execute the query statement:
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0)
{
// Note that email field is unique, so should only have one row.
$row = $result->fetch_assoc();
$username = $row["username"];
$pwd_hashed = $row["password"];
// Check if the password matches:
if (!password_verify($_POST["pwd"], $pwd_hashed))
{
// Don’t tell hackers which one was wrong, keep them guessing...
$errorMsg = "Email not found or password doesn't match...";
$success = false;
}
}
else
{
$errorMsg = "Email not found or password doesn't match...";
$success = false;
}
$stmt->close();
}
$conn->close();
}
}


?>
 <?php
    include "includes/footer.php";
    ?>
