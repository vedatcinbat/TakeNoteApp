<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit;
}

include 'includes/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $noteId = $_GET['id'];
    $userId = $_SESSION['userid'];

    $sql = "DELETE FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $noteId, $userId);

    if ($stmt->execute()) {
        header("location: welcome.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>