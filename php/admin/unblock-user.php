<?php
include '../database.php';
session_start();

// Hanya admin yang boleh
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Akses ditolak");
}

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("UPDATE users SET is_blocked = 0 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: data-pengguna.php");
    exit();
} else {
    echo "Permintaan tidak valid.";
}
