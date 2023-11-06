<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Check if user is logged in (You might have a session variable set upon successful login)
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$dbusername = "foodlxyc_errandboy";
$dbpassword = "yrayveeboi";
$dbname = "foodlxyc_errand";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user-specific data from the database
$userID = $_SESSION["user_id"];
$sql = "SELECT id, errand_type,  order_status, destination_country, created_at FROM errands WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_errand'])) {
    $errandIDToDelete = $_POST['errand_id_to_delete'];
    
    // Perform the deletion in the database
    $deleteErrandSql = "DELETE FROM errands WHERE id = ?";
    $deleteErrandStmt = $conn->prepare($deleteErrandSql);
    $deleteErrandStmt->bind_param("i", $errandIDToDelete);
    $deleteErrandStmt->execute();
    
    // After deletion, redirect the user to the dashboard page
    header("Location: dashboard.php");
    exit(); // Ensure that no other code is executed after the redirection
}

// Check if errand data is retrieved successfully
if ($result->num_rows > 0) {
    $errands = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Handle the case where no errands are found
    $errands = array();
}

// Fetch user's name and email from the users table
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Check if user's data is retrieved successfully
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION["username"] = $user["name"];
    $userEmail = $user["email"];
} else {
    // Handle the case where user's data is not found
    $_SESSION["username"] = "Unknown User";
    $userEmail = "unknown@example.com";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errandTitle = $_POST["errandTitle"];
    $errandDescription = $_POST["errandDescription"];
    $errandType = $_POST["errandType"];
    $destinationCountry = $_POST["destinationCountry"];

    // Validate and sanitize input (you can use additional validation/sanitization methods)
    $errandTitle = mysqli_real_escape_string($conn, $errandTitle);
    $errandDescription = mysqli_real_escape_string($conn, $errandDescription);


    // Compose email message
    $subject = "Errand Request from " . $_SESSION["username"];
    $message = "Hi,\n\nI am making a request for: \nErrand Type: $errandType\nDestination Country: $destinationCountry <p> Email address: $userEmail</p>";

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->setFrom('errand@foodie-export.com', 'Errandulous'); // Your email address and name
        $mail->addAddress('yrayveeboi2@gmail.com'); // Recipient's email address

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        
        $saveErrandSql = "INSERT INTO errands (user_id, errand_type, destination_country, errand_title, errand_description) VALUES (?, ?, ?, ?, ?)";
    $saveErrandStmt = $conn->prepare($saveErrandSql);
    $saveErrandStmt->bind_param("issss", $userID, $errandType, $destinationCountry, $errandTitle, $errandDescription);
    $saveErrandStmt->execute();
    $saveErrandStmt->close();

        header("Location: dashboard.php?success=1");
        exit();
    } catch (Exception $e) {
        // Log the error or handle it accordingly
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Close database connection
$stmt->close();
$conn->close();
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
        .floating-chat {
      position: fixed;
      bottom: 20px;
      left: 20px;
      display: flex;
      align-items: center;
    }
    
    .chat-icon {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      background-color: rgb(13,66,255);
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
      overflow: hidden;
    }
    
    .chat-icon img {
      width: 100px;
      height: 100px;
    }
    
    .chat-card {
      width: 180px;
      height: 70px;
      background-color:rgb(13,66,255);
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-left: 10px;
      border-radius: 10px;
      animation: fadeInOut 3s infinite;
      opacity: 0;
    }
    
    @keyframes fadeInOut {
      0% {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
    
    .chat-card p {
      margin: 0;
      font-size: 14px;
      color: #333;
    }
    
    .chat-card:before {
      content: "";
      position: absolute;
      bottom: 100%;
      left: 50%;
      border-width: 8px;
      border-style: solid;
      border-color: transparent transparent #ffffff transparent;
    }
    
    .dashboard-section{
        padding-top: 10%;
    }
    
    .label-type{
            font-size: 1.3rem;
    }

    .optionf{
        padding: 5px;
        width:30%;
    }
      .optionf1{
        width: 30%;
    }
    .form-sec{
        background-color: #132848;
        background-image: url("./assets/img/hero-bg.png");
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .name2{
        background-image: url('./assets/img/peakpx.jpg');
        background-size: cover;
        background-position: center;
    }

    .logout{
        font-size: 2.0rem;
        color:white;
    }
    @media (max-width: 768px) {
      .floating-chat {
        bottom: 10px;
        left: 10px;
    }
    
    .chat-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: rgb(16,14,90);
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
      overflow:hidden;
    }
    
    .chat-icon img {
      width: 60px;
      height: 60px;
    }

    .dashboard-section{
        padding-top: 30%;
    }
    .optionf{
        width: 50%;
    }
     .optionf1{
        width: 80%;
    }
    }
       </style>
</head>
<body>
    <header id="header" class=" header d-flex align-items-center fixed-top" style="background-color: #132848;">
        <div
          class="container-fluid container-xl d-flex align-items-center justify-content-between"
        >
          <a href="index.html" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>Errandulous</h1>
          </a>
          
          <a href="logout.php"><i class="bi bi-box-arrow-right logout"></i></a>
          
          <!-- .navbar -->
        </div>
      </header>
      <div  class="px-lg-5 px-2 dashboard-section">
      <div class="shadow p-3 rounded mb-3 name2">
    <h2 class="mb-5" style="text-transform:capitalize;">Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
   <div class="container-fluid px-lg-5 px-2 mt-5">
    <h2  class="mb-4" style="cursor:pointer;">Your Errands<i class="bi bi-clock" style="margin-left:10px;"></i></h2>
    <div class="row" id="errand-container">
        <?php if (empty($errands)) : ?>
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    No errands yet.
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($errands as $errand) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Errand ID: <?php echo $errand["id"]; ?></h5>
                            <p class="card-text"><strong>Type:</strong> <?php echo $errand["errand_type"]; ?></p>
                            <p class="card-text"><strong>Destination:</strong> <?php echo $errand["destination_country"]; ?></p>
                             <p class="card-text" style="font-size:1.2rem; color:green;"><strong>Status:</strong> <?php echo $errand["order_status"]; ?></p>
                            <p class="card-text"><strong>Created At:</strong> <?php echo $errand["created_at"]; ?></p>
                             <form method="post">
                    <input type="hidden" name="errand_id_to_delete" value="<?php echo $errand['id']; ?>">
                    <button type="submit" name="delete_errand" class="btn btn-danger">Delete Errand</button>
                </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</div>

      
    </div>
    
    <section style="color: white;" class="px-lg-5 px-2 form-sec">
    <div class=" px-lg-3 mb-5 rounded mb-3" >
        <h2 class="mb-3">Request for an Errand <i class="bi bi-hourglass-split"></i></h2>
        <p>Note: A mail will be sent</p>
        <form method="post">
             <div>
                <label for="errandTitle" class="label-type mb-3">Errand Title:</label> <br>
                <input type="text" id="errandTitle" name="errandTitle" required class="optionf1 mb-5">
            </div>
            <div>
                <label for="errandDescription" class="label-type mb-3">Errand Description:</label> <br>
                <textarea id="errandDescription" name="errandDescription" required class="optionf1 mb-4" rows="5"></textarea>
            </div>

    <div>
        <label for="errandType" class="label-type mb-3">Errand Type:</label> <br>
        <select id="errandType" name="errandType" required class="optionf mb-5">
            <option>Send a Gift</option>
            <option>Select Option 2</option>
            <option>Select Option 3</option>
            <option>Select Option 4</option>
        </select>
    </div>
    <div>
        <label for="destinationCountry" class="label-type mb-3">Destination Country <i class="bi bi-globe-asia-australia"></i></label> <br>
        <select id="destinationCountry" name="destinationCountry" required class="optionf mb-4">
            <option>United States of America</option>
            <option>Select Option 2</option>
            <option>Select Option 3</option>
            <option>Select Option 4</option>
        </select>
    </div>
    <button class="btn btn-primary mt-lg-4 mt-2 py-3 button-hero" data-aos="fade-up" type="submit" style=" white-space: nowrap;">Submit Request</button>
</form>

    </div>
</section>
     <!-- Implement a logout functionality in logout.php -->
     <div class="floating-chat">
        <div class="chat-icon">
          <img src="mail.jpg" alt="Chat Icon">
          
        </div>
        <div class="chat-card" >
          <p style="color:white; font-weight:700;">Click to mail support!</p>
        </div>
      </div>

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
// <script>
//     function toggleErrandContainer() {
//         var container = document.getElementById("errand-container");
//         container.style.display = (container.style.display === "none" || container.style.display === "") ? "block" : "none";
//     }
// </script>

<script>
  const chatCard = document.querySelector('.chat-icon');

chatCard.addEventListener('click', () => {
window.location.href = 'mailto:';
});

</script>
</html>