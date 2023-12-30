<?php
session_start();

// Check if the user is logged in, if not redirect login.php page
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit;
}

// Connect db
include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Catch the values of title, content, userId if this file got POST request
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $userId = $_SESSION['userid'];

    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields.';
    } else {
        $sql = "INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $title, $content);

        if ($stmt->execute()) {
            $success = 'Note created successfully!';
        } else {
            $error = 'Error: ' . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Note</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Create a New Note</h1>

        <?php if($error != ''): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if($success != ''): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="post" action="create_note.php">
            <label for="title">Title:</label>
            <input type="text" name="title" required><br>
            <label for="content">Content:</label><br>
            <textarea name="content" rows="5" cols="40" required></textarea><br>
            <button type="submit">Create Note</button>
        </form>

        <a href="welcome.php">Back to your notes</a>
    </div>
</body>
</html>