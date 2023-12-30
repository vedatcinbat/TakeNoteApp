<?php
include 'includes/db.php';

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $age = intval($_POST['age']);
    $country = $conn->real_escape_string($_POST['country']);

    $sql = "INSERT INTO users (username, password, age, country) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $username, $password, $age, $country);

    if ($stmt->execute()) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
        <?php if (!empty($message)): ?>
            showAlert("<?php echo $message; ?>");
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        <form method="post" action="register.php">
            <label for="username">Username</label>
            <input type="text" name="username" required><br>
            <label for="password">Password</label>
            <input type="password" name="password" required><br>
            <label for="age">Age</label>
            <input type="number" name="age" required><br>
            <label for="country">Country</label>
            <input type="text" name="country" required><br>
            <input type="submit" value="Register">      
        </form>
        <a href="login.php">Login</a>
    </div>
</body>
</html>

