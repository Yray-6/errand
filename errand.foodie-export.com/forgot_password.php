<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);

    // Generate a unique reset key
    $resetKey = md5(uniqid(rand(), true));

    // TODO: Establish a database connection and update the reset_key in the users table
    $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the reset_key in the users table
    $updateQuery = "UPDATE users SET reset_key = '$resetKey' WHERE email = '$email'";
    $conn->query($updateQuery);

    // Send email containing the reset password link using PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        

        //Recipients
        $mail->setFrom('errand@foodie-export.com', 'Errandoulous');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body    = "Click the following link to reset your password:<a href='http://errand.foodie-export.com/reset_password.php?key=$resetKey'>Reset</a>" ;

        $mail->send();
        echo "Password reset link has been sent to your email address.";
    } catch (Exception $e) {
        echo "Failed to send reset email. Please try again later. Error: {$mail->ErrorInfo}";
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
      
                   
      <form method="post" action="forgot_password.php">
           <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Reset Password</h5>
    <div class="form-floating mb-3">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required>
        <label for="floatingInput">Email address</label>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
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