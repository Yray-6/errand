<?php
session_start();

// Check if the user is an admin (You might have a session variable set upon successful admin login)
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php"); // Redirect to the login page if not logged in as admin
    exit();
}

// TODO: Implement your database connection logic here
$servername = "localhost";
$dbusername = "foodlxyc_errandboy";
$dbpassword = "yrayveeboi";
$dbname = "foodlxyc_errand";

// Establish database connection with error handling
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users and their orders from the database with error handling
$sql = "SELECT users.id AS user_id, users.name AS user_name, errands.id AS errand_id, errands.errand_type, errands.errand_title, errands.errand_description, errands.destination_country, errands.order_status 
        FROM users 
        LEFT JOIN errands ON users.id = errands.user_id";

$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error executing the query: " . $conn->error);
}

// Check if user data is retrieved successfully
$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[$row["user_id"]]["name"] = $row["user_name"];
        $users[$row["user_id"]]["errands"][] = array(
            "errand_id" => $row["errand_id"],
            "errand_type" => $row["errand_type"],
            "destination_country" => $row["destination_country"],
            "order_status" => $row["order_status"],
            "title" => $row["errand_title"],
            "description" => $row["errand_description"]
        );
    }
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom CSS file -->
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Welcome, Admin!</h1>

        <?php foreach ($users as $user_id => $user) : ?>
            <div class="mt-4">
                <h2>User: <?php echo $user["name"]; ?></h2>
                <?php if (!empty($user["errands"])) : ?>
                    <table class="table table-bordered mt-3">
                        <thead>
    <tr>
        <th>Errand ID</th>
        <th>Errand Type</th>
        <th>Title</th>
        <th>Description</th>
        <th>Destination Country</th>
        <th>Order Status</th>
        <th>Update Status</th>
    </tr>
</thead>

                        <tbody>
                            <?php foreach ($user["errands"] as $errand) : ?>
    <tr>
        <td><?php echo $errand["errand_id"]; ?></td>
        <td><?php echo $errand["errand_type"]; ?></td>
        <td><?php echo $errand["title"]; ?></td>
        <td><?php echo $errand["description"]; ?></td>
        <td><?php echo $errand["destination_country"]; ?></td>
        <td><?php echo $errand["order_status"]; ?></td>
        <td>
            <form method="post" action="update_status.php">
                <input type="hidden" name="errand_id" value="<?php echo $errand["errand_id"]; ?>">
                <select name="new_status" class="form-select">
                    <option value="confirming">Confirming</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                </select>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <p>No errands for this user.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
