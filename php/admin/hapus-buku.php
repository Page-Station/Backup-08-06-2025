<?php
session_start();
include('../database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: data-buku.php");
exit();
