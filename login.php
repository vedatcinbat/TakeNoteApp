
<?php
session_start();

include 'includes/db.php';

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("location: welcome.php");
            exit();
        } else {
            $loginError = 'Invalid password.';
        }
    } else {
        $loginError = 'Invalid username.';
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            Username: <input type="text" name="username" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
        <a href="register.php">Sign Up</a>
        <?php if($loginError != ''): ?>
            <p><?php echo $loginError; ?></p>
        <?php endif; ?>
    </div>

</body>
</html>