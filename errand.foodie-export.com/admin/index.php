<?php
$servername = "localhost";
$dbusername = "foodlxyc_errandboy";
$dbpassword = "yrayveeboi";
$dbname = "foodlxyc_errand";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Admin login successful, set session variables and redirect to admin dashboard
            $_SESSION["admin_id"] = $row["id"];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Incorrect password, show error message
            $error = "Incorrect password. Please try again.";
        }
    } else {
        // Admin not found, show error message
        $error = "Admin not found. Please check your username.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
    <title>Admin Login</title>
</head>

<body>
    <div class="container mt-5 p-5">
        <h2>Admin Login</h2>
    <?php if (isset($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>
    <form action="index.php" method="post">
        <!--<label for="username">Username:</label>-->
        <!--<input type="text" id="username" name="username" required><br><br>-->

        <!--<label for="password">Password:</label>-->
        <!--<input type="password" id="password" name="password" required><br><br>-->
                                <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>
      
                        <!-- input Fields for the Form -->

                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                            <label for="username">Username</label>
                          </div>
                          <div class="form-floating">
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                            <label for="password">Password</label>
                          </div>

        <button class="btn btn-dark w-100 button1 btn-lg btn-block mt-5" type="submit">Login</button>
    </form>
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</html>

