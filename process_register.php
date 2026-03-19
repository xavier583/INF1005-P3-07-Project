 <?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);
    include "includes/header.php";
    ?>
 <?php
    include "includes/nav.php";
    ?>
<?php 
$fname = $lname = $email = $pwd = $pwd_confirm = $errorMsg = "";
$success = true;

if (empty($_POST["fname"])){
    $errorMsg .= "Name is required.<br>";
    $success = false;   
} else {
    $fname = sanitize_input($_POST["fname"]);
    if (!filter_var($_POST["fname"],FILTER_SANITIZE_SPECIAL_CHARS)){
        $errorMsg .=  "Invalid name format.";
        $success = false;
    }
}
if (!empty($_POST["lname"])){
    $lname = sanitize_input($_POST["lname"]);
     if (!filter_var($_POST["lname"],FILTER_SANITIZE_SPECIAL_CHARS)){
        $errorMsg .=  "Invalid name format.";
        $success = false;
    }
    
}
if (empty($_POST["email"])) {
    $errorMsg .= "Email is required.<br>";
    $success = false;
} else {
    $email = sanitize_input($_POST["email"]);
    // Additional check to make sure e-mail address is well-formed.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Invalid email format.";
        $success = false;
    }
}
if (empty($_POST["pwd"])){
    $errorMsg .= "Password is required.<br>";
    $success = false;
}
else{
    $pwd = $_POST["pwd"];
    $pwd_confirm = $_POST["pwd_confirm"];
    if ($pwd == $pwd_confirm)
        $pwd_hashed = password_hash($pwd,PASSWORD_DEFAULT);
    else{
        $errorMsg .= "Passwords do not match.";
        $success = false;
    }
        
}
echo "<div class='response'>";
if ($success)
    {
    saveMemberToDB();
    }
if ($success) {
    echo "<h4>Registration successful!</h4>";
    echo "<p>Email: " . $email . "</p>";
    echo " <a href = 'login.php' class='btn ms-auto'> Log-In </a>";
} else {
    echo "<h4>The following input errors were detected:</h4>";
    echo "<p>" . $errorMsg . "</p>";
    echo " <a href = 'register.php' class='btn ms-auto'> Back to Sign-Up </a>";
}
echo "</div>";
/*
* Helper function that checks input for malicious or unwanted content.
*/
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
/*
* Helper function to write the member data to the database.
*/
function saveMemberToDB()
{
global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success;
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
$stmt = $conn->prepare("INSERT INTO maison_reluxe_members
(fname, lname, email, password) VALUES (?, ?, ?, ?)");
// Bind & execute the query statement:
$stmt->bind_param("ssss", $fname, $lname, $email, $pwd_hashed);
if (!$stmt->execute())
{
$errorMsg = "Execute failed: (" . $stmt->errno . ") " .
$stmt->error;
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
