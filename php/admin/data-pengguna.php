<?php 
session_start();
include('../database.php');


// Ambil semua pengguna
$query_users = "SELECT * FROM users";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link rel="stylesheet" href="../../css/kelola-buku&pengguna.css">
</head>
<body>
<section class="users-section">
    <h2 class="judul">Daftar Pengguna</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>IP Address</th>
            <th>Buku yang Sedang Dibaca</th>
            <th>Buku yang Dipinjam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($user = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td>
                    <?php
                    $ip = $user['ip_address'] ?? '-';
                    echo ($ip === '::1') ? '127.0.0.1' : $ip;
                    ?>
                </td>
                <td>
                    <?php if (isset($user['book_id']) && is_numeric($user['book_id'])): ?>
                        <button class="btn-detail" onclick="openBacaModal(<?= $user['id'] ?>)">Lihat Buku</button>
                        <div id="baca-modal-<?= $user['id'] ?>" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeBacaModal(<?= $user['id'] ?>)">&times;</span>
                                <h4>Buku yang Sedang Dibaca - <?= htmlspecialchars($user['username']) ?></h4>
                                <ul style="list-style: none; padding-left: 0;">
                                <?php
                                $stmt = $conn->prepare("SELECT title FROM books WHERE id = ?");
                                $stmt->bind_param('i', $user['book_id']);
                                $stmt->execute();
                                $book_result = $stmt->get_result();
                                if ($book = $book_result->fetch_assoc()) {
                                    echo "<li>📖- <strong>" . htmlspecialchars($book['title']) . "</strong></li>";
                                } else {
                                    echo "<li><em>Tidak ada buku yang dibaca</em></li>";
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    <?php else: ?>
                        Tidak ada buku yang dibaca
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn-detail" onclick="openModal(<?= $user['id'] ?>)">Lihat Pinjaman</button>
                    <div id="modal-<?= $user['id'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal(<?= $user['id'] ?>)">&times;</span>
                            <h4>Riwayat Pinjaman - <?= htmlspecialchars($user['username']) ?></h4>
                            <ul style="list-style: none; padding-left: 0;">
                            <?php
                            $stmt = $conn->prepare("SELECT books.title, rak_pinjam.tanggal_pinjam, rak_pinjam.kembali_at 
                                                    FROM rak_pinjam 
                                                    JOIN books ON rak_pinjam.book_id = books.id 
                                                    WHERE rak_pinjam.user_id = ? 
                                                    ORDER BY rak_pinjam.tanggal_pinjam DESC");
                            $stmt->bind_param('i', $user['id']);
                            $stmt->execute();
                            $result_pinjam = $stmt->get_result();
                            if ($result_pinjam->num_rows > 0) {
                                while ($row = $result_pinjam->fetch_assoc()) {
                                    echo "<li style='margin-bottom:10px'>";
                                    echo "📚 <strong>" . htmlspecialchars($row['title']) . "</strong><br>";
                                    echo "<small>Dipinjam: " . htmlspecialchars($row['tanggal_pinjam']) . "</small><br>";
                                    if (!empty($row['kembali_at'])) {
                                        echo "<small>Dikembalikan: " . htmlspecialchars($row['kembali_at']) . "</small>";
                                    } else {
                                        echo "<small><em>Belum dikembalikan</em></small>";
                                    }
                                    echo "</li>";
                                }
                            } else {
                                echo "<li><em>Tidak ada buku yang dipinjam.</em></li>";
                            }
                            ?>
                            </ul>
                        </div>
                    </div>
                </td>
                <td><?= ($user['is_blocked'] ?? 0) == 1 ? '<span style="color:red">Diblokir</span>' : 'Aktif'; ?></td>
                <td>
                    <?php if (($user['is_blocked'] ?? 0) == 1): ?>
                        <form method="POST" action="unblock-user.php">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" class="btn-unblock">Buka Blokir</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="block-user.php">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" class="btn-block">Blokir</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<script>
function openModal(userId) {
    document.getElementById('modal-' + userId).style.display = 'block';
}
function closeModal(userId) {
    document.getElementById('modal-' + userId).style.display = 'none';
}
function openBacaModal(userId) {
    document.getElementById('baca-modal-' + userId).style.display = 'block';
}
function closeBacaModal(userId) {
    document.getElementById('baca-modal-' + userId).style.display = 'none';
}
window.onclick = function(event) {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) modal.style.display = 'none';
    });
};
</script>
</body>
</html>
