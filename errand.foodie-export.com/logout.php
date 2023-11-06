<?php
// Start the session to access session variables
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to index.html after logout
header("Location: index.html");
exit();
?>
