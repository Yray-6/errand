<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php'; // Include PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["email"])) {
    $email = urldecode($_GET["email"]);

    // Establish a database connection
    $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to update user's email verification status
    $sql = "UPDATE users SET email_verified = 1 WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the statement
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        // Email verified successfully
        echo "<h1>Email Verified Successfully</h1>";
        echo "<p>Your email has been verified. You can now <a href='login.php'>log in</a>.</p>";
    } else {
        // Email verification failed
        echo "<h1>Email Verification Failed</h1>";
        echo "<p>Sorry, we could not verify your email. Please contact support for assistance.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    echo "<h1>Invalid Request</h1>";
    echo "<p>Invalid confirmation link.</p>";
}
?>



