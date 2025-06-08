<?php
session_start();
include('../database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    $stmt = $conn->prepare("UPDATE users SET is_blocked = 1 WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    header('Location: data-pengguna.php');
    exit();
} else {
    echo "Akses tidak sah.";
    exit();
}
?>
