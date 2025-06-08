<?php
session_start();
include('php/database.php');

if (isset($_SESSION['user'])) {
   $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
    $check_block = $conn->prepare("SELECT is_blocked FROM users WHERE id = ?");
    $check_block->bind_param("i", $user_id);
    $check_block->execute();
    $result = $check_block->get_result();
    if ($result && $result->num_rows === 1) {
        $status = $result->fetch_assoc();
        if ($status['is_blocked']) {
            session_destroy();
            header("Location: php/login.php?blocked=1");
            exit();
        }
    }
}

$hasilPencarian = [];
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $keyword = '%' . trim($_GET['q']) . '%';
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR description LIKE ?");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $hasilPencarian = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Station</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/script.js" defer>
        const toggleButton = document.querySelector('.mode-toggle');
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</head>
<body>
    <!-- Icons di Pojok Kanan Atas -->
    <div class="top-icons">
        <i class="fas fa-moon mode-toggle"></i>
        <a href="php/profile.php"><i class="fas fa-user-circle profile-icon"></i></a>
    </div>

    <!-- Sidebar -->
    <input type="checkbox" id="check">
    <label for="check">
        <i class="fas fa-bars" id="btn"></i>
        <i class="fas fa-times" id="cancel"></i>
    </label>
    <div class="sidebar">
        <img src="image/pagestationblue.png" alt="Logo">
        <ul>
            <li><a href="index.php"><i class="fas fa-qrcode"></i>Home</a></li>
            <li><a href="html/learn-more.html"><i class="far fa-question-circle"></i>About us</a></li>
            <li><a href="html/learn-more.html"><i class="far fa-envelope"></i>Contact us</a></li>
            <li><a href="php/pinjam/rak-pinjam.php"><i class="fas fa-calendar-week"></i>Rak pinjam</a></li>
    </div>

    <!-- Main Content -->
    <section class="content">
        <!-- Search Bar -->
        <div class="search-bar" style="position: relative;">
    <form id="searchForm" action="index.php" method="GET" autocomplete="off">
        <input type="text" id="searchInput" name="q" placeholder="Cari sesuatu yang keren" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
    <div id="suggestionBox" class="suggestion-box" style="display:none;"></div>
</div>

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h2>Hi, <?php echo isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Pengguna'; ?></h2>
            <p>Selamat Datang di Page Station, Website penyedia E-Book Terlengkap</p>
            <img src="image/book-stack.png" alt="Books" class="banner-image">
            <a href="html/learn-more.html">
                <button>Learn More</button>
            </a>
        </div>

        <!-- White Boxes -->
        <div class="white-boxes">
            <div class="kategori">
                <h3>Kategori</h3>
                <div class="category-container">
                    <div class="category-box">
                        <a href="php/kategori/pelajaran.php" class="a">
                            <img src="image/pelajaran.png" alt="Pelajaran" class="category-image">
                            Pelajaran
                        </a>
                    </div>
                    <div class="category-box">
                        <a href="php/kategori/novel.php" class="a">
                            <img src="image/Novel.png" alt="Novel" class="category-image">
                            Novel
                        </a>
                    </div>
                    <div class="category-box">
                        <a href="php/kategori/filsafat.php" class="a">
                            <img src="image/filsafat.png" alt="Filsafat" class="category-image">
                            Filsafat
                        </a>
                    </div>
                    <div class="category-box">
                        <a href="php/kategori/psikologi.php" class="a">
                            <img src="image/Psikologi.png" alt="Psikologi" class="category-image">
                            Psikologi
                        </a>
                    </div>
                    <div class="category-box">
                        <a href="php/kategori/dongeng.php" class="a">
                            <img src="image/Dongeng.png" alt="Dongeng" class="category-image">
                            Dongeng
                        </a>
                    </div>
                    <div class="category-box">
                        <a href="php/kategori/islami.php" class="a">
                            <img src="image/Islamic.png" alt="Islami" class="category-image">
                            Islami
                        </a>
                    </div>
                    <div class="category-box">
                        <a href="php/kategori/self-defelopment.php" class="a">
                            <img src="image/self-development.pn" alt="Self Development" class="category-image">
                            Self Development
                        </a>
                    </div>
                </div>
            </div>
            <div class="right-boxes">
                <div class="golongan">
                    <h3>Box 2</h3>
                    <p>deskripsi</p>
                </div>
                <div class="putih">
                    <h3>Box 3</h3>
                    <p>desk</p>
                </div>
            </div>
        </div>
    </section>
    <script src="js/screen-protection.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarToggle = document.getElementById('check');
        const sidebarLinks = document.querySelectorAll('.sidebar ul a');

        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebarToggle.checked = false;
                }
            });
        });
    });
</script>
    <script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert('Klik kanan dinonaktifkan.');
    });

    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert('Klik kanan dinonaktifkan.');
    });
    </script>
</body>
</html>
