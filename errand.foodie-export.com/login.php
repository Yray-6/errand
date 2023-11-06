<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Database connection
    $servername = "localhost";
    $dbusername = "foodlxyc_errandboy";
    $dbpassword = "yrayveeboi";
    $dbname = "foodlxyc_errand";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a SQL query to check if the email exists in the database
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $db_email, $db_password);
    $stmt->fetch();

    // Verify the password and log the user in if it's correct
    if ($db_email && password_verify($password, $db_password)) {
        // Start a session and store user information
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $db_email;

        // Redirect to the dashboard or any other authenticated page
        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid email or password, redirect back to the login page with an error message
        header("Location: login.php?error=invalidlogin");
        exit();
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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>Errandulous</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    

    <!-- Google Fonts -->
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
    <link href="assets/css/main.css" rel="stylesheet">
    <style>
      .body{
    background-color: var(--mainred);
    height: 100%;
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
                  <li><a class="cta-btn" href="register.php" style="font-size: 1.2rem;">Sign up</a></li>
                  
              <li>
                <a class="" href="login.php" style="font-size: 1.2rem;">Login</a>
              </li>
            </ul>
          </nav>
          <!-- .navbar -->
        </div>
      </header>
    <section class="" style="background-color: #132848;">
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
      
                      <form method="post" action="login.php">
      
                        <div class="d-flex align-items-center mb-3 pb-1">
                          
                        </div>
      
                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>
      
                        <!-- input Fields for the Form -->

                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                            <label for="exampleInputEmail1">Email address</label>
                          </div>
                          <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                            <label for="floatingPassword">Password</label>
                          </div>
                        <div class="pt-4 mb-4">
                          <button class="btn btn-dark w-100 button1 btn-lg btn-block" type="submit">Login</button>
                        </div>

                        <!-- Footer -->
      
                        <a class="small forgot text-muted" href="forgot_password.php">Forgot password?</a>
                        <p class="mb-5 account pb-lg-2" style="color: #393f81;">Don't have an account? <a href="./register.php" class="account"
                            style="color: #393f81; font-weight: 700;">Register here</a></p>
                        
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