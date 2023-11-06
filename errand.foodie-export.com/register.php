<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$registrationSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $name = htmlspecialchars($_POST["name"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    $country = htmlspecialchars($_POST["country"]);

    // Validate inputs
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($country)) {
        header("Location: signup.php?error=emptyfields");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=invalidemail");
        exit();
    } elseif ($password !== $confirmPassword) {
        header("Location: signup.php?error=passwordmismatch");
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to insert data into the users table
    $sql = "INSERT INTO users (name, username, email, password, country) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the statement
    $stmt->bind_param("sssss", $name, $username, $email, $hashedPassword, $country);
    if ($stmt->execute()) {
        // Send confirmation email
        $mail = new PHPMailer();
        $mail->setFrom('errand@foodie-export.com', 'Errandoulous');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Confirm Your Email';
        $mail->isHTML(true);
        $mail->Body = 'Click the following link to confirm your email: <a href="http://errand.foodie-export.com/verify.php?email=' . urlencode($email) . '">Confirm Email</a>';

        if ($mail->send()) {
            $registrationSuccess = true;
        } else {
            // Handle email sending error
        }
    } else {
        // Handle SQL error
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
    <title>Errandulous</title>
</head>
<body>
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div
          class="container-fluid container-xl d-flex align-items-center justify-content-between"
        >
          <a href="index.html" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>Errandulous</h1>
          </a>
  
          <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
          <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
          <nav id="navbar" class="navbar">
            <ul>
              
                <ul class="mt-lg-0 mt-5 pt-5 pt-lg-0">
                  <li><a class="cta-btn" href="register.html" style="font-size: 1.5rem;">Sign up</a></li>
                  
              <li>
                <a class="" href="login.php" style="font-size: 1.5rem;">Login</a>
              </li>
            </ul>
          </nav>
          <!-- .navbar -->
        </div>
      </header>
      
    <section class="" style="background-color: #132848;">
        <?php if ($registrationSuccess): ?>
        <div class="alert alert-success" role="alert">
            Registration successful! Please check your email to confirm your account.
        </div>
    <?php endif; ?>
        <div class="container py-5 h-100 " data-aos="fade-right" data-aos-delay="400">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
              <div class="card" style="border-radius: 1rem; border: none;">
                <div class="row g-0">
                  <div class="col-md-6 col-lg-5 d-none d-md-block">
                    <img src="./assets/img/packaging-service.jpg"
                      alt="login form" class="img-fluid h-100" style="border-radius: 1rem 0 0 1rem; object-fit:cover;" />
                  </div>
                  <div class="col-md-6 col-lg-7 d-flex align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">
      
                      <form method="post" action="register.php">
      
                        <div class="d-flex align-items-center mb-3 pb-1">
                          
                         
                        </div>
      
                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register below: Fill in your Credentials</h5>
      
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="name">
                          <label for="floatingInput">Name</label>
                        </div>

                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="floatingPassword" placeholder="Password" name="username">
                          <label for="floatingPassword">Username</label>
                        </div>
                        <div class="form-floating mb-3">
        <select class="form-select" name="country" id="country" name="country">
            <option value="" selected disabled>Select your country</option>
            <option value="Country1">Country 1</option>
            <option value="Country2">Country 2</option>
            <!-- Add more country options here -->
        </select>
        <label for="country">Country</label>
    </div>
                        <div class="form-floating mb-3">
                          <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                          <label for="floatingInput">Email address</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                          <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                          <label for="floatingPassword">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                          <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="confirmPassword">
                          <label for="floatingPassword">Confirm Password</label>
                        </div>
      
                        <div class="pt-4 mb-4">
                          <button class="btn button1 w-100 btn-dark btn-lg btn-block" type="submit" name="register">Register</button>
                        </div>
      
                        
                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Already Have an Account?<a href="./login.php" class="account"
                            style="color: #393f81; font-weight: 700; ">Login</a></p>
                        
                      </form>
      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> 
      <div id="google_translate_element" class="translate"></div>
</body>
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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
  function googleTranslateElementInit(){
      new google.translate.TranslateElement(
          {pagelanguage:'en'},
          'google_translate_element'
      );
  }
</script>
</html>