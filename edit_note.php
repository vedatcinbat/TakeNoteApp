<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit;
}

include 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noteId = $_POST['note_id'];
    $newTitle = $conn->real_escape_string($_POST['new_title']);
    $newContent = $conn->real_escape_string($_POST['new_content']);
    $userId = $_SESSION['userid'];

    if (empty($newTitle) || empty($newContent)) {
        $error = 'Please fill in all fields.';
    } else {
        $checkSql = "SELECT id FROM notes WHERE id = ? AND user_id = ?";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bind_param("ii", $noteId, $userId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            $updateSql = "UPDATE notes SET title = ?, content = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->bind_param("ssi", $newTitle, $newContent, $noteId);

            if ($stmtUpdate->execute()) {
                $success = 'Note updated successfully!';
            } else {
                $error = 'Error updating note: ' . $conn->error;
            }

            $stmtUpdate->close();
        } else {
            $error = 'Invalid note ID or you do not have permission to edit this note.';
        }

        $stmtCheck->close();
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $noteId = $_GET['id'];
    $userId = $_SESSION['userid'];

    $sql = "SELECT id, title, content FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $noteId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        $error = 'Invalid note ID or you do not have permission to edit this note.';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Note</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="container">
        <h1>Edit Note</h1>

        <?php if($error != ''): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if($success != ''): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if (isset($row)): ?>
            <form method="post" action="edit_note.php">
                <input type="hidden" name="note_id" value="<?php echo $row['id']; ?>">
                <label for="new_title">Title:</label>
                <input type="text" name="new_title" value="<?php echo htmlspecialchars($row['title']); ?>"><br>
                <label for="new_content">Content:</label><br>
                <textarea name="new_content" rows="5" cols="40"><?php echo htmlspecialchars($row['content']); ?></textarea><br>
                <button type="submit">Update Note</button>
            </form>
        <?php endif; ?>

        <a href="welcome.php">Back to your notes</a>
    </div>
</body>
</html>