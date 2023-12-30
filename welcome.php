<?php
session_start();

// Check if the user is logged in else go login.php
if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit;
}

include 'includes/db.php';

// Get the user notes from db
$userId = $_SESSION['userid'];
$sql = "SELECT id, title, content, created_at FROM notes WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$numNotes = $result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo $numNotes; ?> note)</h1>
        <a href="logout.php">Logout</a>

        <h2>My Notes</h2>

        <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['title']); ?></strong> 
                    (<?php 
                        $timestamp = strtotime($row['created_at']);
                        $formattedDateTime = date('l, F j, Y g:i A', $timestamp);    
                        echo $formattedDateTime
                    ?>)
                    <p><?php echo htmlspecialchars($row['content']); ?></p>
                    <?php if (isset($row['id'])): ?>
                        <?php $noteId = $row['id']; ?>
                        <a href="edit_note.php?id=<?php echo $noteId; ?>">Edit</a>
                        <a href="delete_note.php?id=<?php echo $noteId; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
        <?php else: ?>
            <p>You have no notes.</p>
        <?php endif; ?>

        <a href="create_note.php">Create a New Note</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>