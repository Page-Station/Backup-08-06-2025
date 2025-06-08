<?php
session_start();
include('../database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit();
}

$query_books = "SELECT * FROM books";
$result_books = $conn->query($query_books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link rel="stylesheet" type="text/css" href="../../css/kelola-buku&pengguna.css">
</head>
<body>

<section class="books-section">
    <h2 class="judul">Daftar Buku</h2>
    <a href="tambah-buku.php" class="btn-tambah">+ Tambah Buku</a>
    <table>
        <tr>
            <th>Cover</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Deskripsi</th>
            <th>Kategori</th>
            <th>Sub Kategori</th>
            <th>File PDF</th>
            <th>stock</th>
            <th>Aksi</th>
        </tr>
        <?php while ($book = $result_books->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($book['cover_image']): ?>
                    <img src="../admin/uploads/covers/<?php echo $book['cover_image']; ?>" class="cover-preview">
                <?php else: ?>
                    Tidak ada
                <?php endif; ?>
            </td>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo substr($book['description'], 0, 100) . '...'; ?></td>
            <td><?php echo $book['category']; ?></td>
            <td><?php echo $book['sub_category']; ?></td>
            <td>
                <?php if ($book['pdf_path']): ?>
                    <a href="<?php echo $book['pdf_path']; ?>" target="_blank" class="a">Baca PDF</a>
                <?php else: ?>
                    Tidak ada
                <?php endif; ?>
            </td>
            <td><?php echo $book['stock']; ?></td>
            <td>
                <a class="a" href="edit-buku.php?id=<?php echo $book['id']; ?>">Edit</a> |
                <a class="a" href="hapus-buku.php?id=<?php echo $book['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</section>

</body>
</html>
