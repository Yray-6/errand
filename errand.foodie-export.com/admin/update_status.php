<?php
session_start();

// Check if the user is an admin (You might have a session variable set upon successful admin login)
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php"); // Redirect to the login page if not logged in as admin
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errand_id = $_POST["errand_id"];
    $new_status = $_POST["new_status"];

    // TODO: Implement database update logic to update the order status based on $errand_id and $new_status
    // For example, you might use a prepared statement to update the status in your database
    $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the update statement
    $sql = "UPDATE errands SET order_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $errand_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redirect back to the admin dashboard after updating the status
    header("Location: admin_dashboard.php");
    exit();
}
?>
