<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["key"])) {
    // Get the reset key from the URL
    $resetKey = htmlspecialchars($_GET["key"]);

    // Establish a database connection
   $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";


    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the reset key is valid
    $checkKeyQuery = "SELECT * FROM users WHERE reset_key = '$resetKey'";
    $result = $conn->query($checkKeyQuery);

    if ($result->num_rows == 1) {
        // Valid reset key, allow the user to reset their password
        // Display the reset password form
    } else {
        // Invalid reset key, show an error message
        echo "Invalid reset key. Please try again.";
        exit(); // Exit the script if the key is invalid
    }

    // Close the database connection
    $conn->close();
}

// Reset password form handling logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_password"])) {
    // Get new password from the form
    $newPassword = htmlspecialchars($_POST["new_password"]);

    // Establish a database connection and update the user's password
   $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";


    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use the reset key to identify the user
    $resetKey = $_GET["key"];

    // Hash the new password for security
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE reset_key = '$resetKey'";
    if ($conn->query($updatePasswordQuery) === TRUE) {
        // Password updated successfully
        echo "Password has been successfully reset!";
    } else {
        // Error updating password
        echo "Error updating password: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link
      href="assets/vendor/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link
      href="assets/vendor/fontawesome-free/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="assets/vendor/glightbox/css/glightbox.min.css"
      rel="stylesheet"
    />
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />
    <link href="assets/vendor/aos/aos.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet" />
    <title>Dashboard</title>
     <style>
      .body{
    background-color: var(--mainred);
    height: 1000px;
}

:root{
    --mainred:#132848;
}

.forgot{
    text-decoration: none;

}

.account{
    text-decoration: none;
}

.button1{
    transition: all 0.5s linear;
    border: none;
}

.button1:hover{
    background-color: var(--mainred);
}

.translate{
    position: absolute;
    top: 0;
}
    </style>
    </head>
    <body>

<!-- Add this form in your login.php file -->

<section class="" style="background-color: #132848; height:100%;">
      <div>
        <div class="container py-5 h-100 " data-aos="fade-right" data-aos-delay="400">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
              <div class="card" style="border-radius: 1rem; border: none;">
                <div class="row g-0">
                  <div class="col-md-6 col-lg-5 d-none d-md-block">
                    <img src="./assets/img/about.jpg"
                      alt="login form" class="img-fluid h-100" style="border-radius: 1rem 0 0 1rem;object-fit: cover;" />
                  </div>
                  <div class="col-md-6 col-lg-7 d-flex align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">
      
    
 <form method="post" action="reset_password.php?key=<?php echo $resetKey; ?>">
        <div>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> 
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="assets/js/main.js"></script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<script>
  AOS.init();
</script>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <!-- Reset password form -->
   
</body>

</html>
