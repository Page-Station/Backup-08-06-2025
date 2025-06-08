<?php
session_start();
include('../database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'] ?? null;
    $jenjang = $_POST['jenjang'] ?? null;
    $kelas = $_POST['kelas'] ?? null;
    $stock = intval($_POST['stock']);

    $pdf_path = null;
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $pdf_filename = time() . '-' . basename($_FILES['pdf_file']['name']);
        $pdf_path = $pdf_filename;
       $protected_dir = __DIR__ . '/uploads/protected_pdfs/';
if (!is_dir($protected_dir)) mkdir($protected_dir, 0777, true);
move_uploaded_file($_FILES['pdf_file']['tmp_name'], $protected_dir . $pdf_filename);
    }

    $cover_path = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $cover_filename = time() . '-' . basename($_FILES['cover_image']['name']);
        if (!is_dir('uploads/covers')) mkdir('uploads/covers', 0777, true);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], 'uploads/covers/' . $cover_filename);
        $cover_path = $cover_filename;
    }

    $stmt = $conn->prepare("INSERT INTO books (title, author, description, pdf_path, category, sub_category, cover_image, jenjang, kelas, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssssi', $title, $author, $description, $pdf_path, $category, $sub_category, $cover_path, $jenjang, $kelas, $stock);
    $stmt->execute();

    header("Location: data-buku.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../../css/kelola-buku.css">
</head>
<body>

<form action="tambah-buku.php" method="POST" enctype="multipart/form-data">
    <h2>Tambah Buku</h2>
    <input type="text" name="title" placeholder="Judul Buku" required>
    <input type="text" name="author" placeholder="Penulis">
    <textarea name="description" placeholder="Deskripsi" required></textarea>

    <select name="category" id="category" required>
        <option value="">Pilih Kategori</option>
        <option value="Pelajaran">Pelajaran</option>
        <option value="Novel">Novel</option>
        <option value="Filsafat">Filsafat</option>
        <option value="Psikologi">Psikologi</option>
        <option value="Dongeng">Dongeng</option>
        <option value="Islami">Islami</option>
        <option value="Self Development">Self Development</option>
    </select>

    <div id="subkategori-fields" style="display:none;">
        <label>Sub-Kategori</label>
        <select name="sub_category" id="sub_category">
            <option value="">Pilih Sub-Kategori</option>
        </select>
    </div>

    <div id="pelajaran-fields" style="display:none;">
        <select name="jenjang" id="jenjang">
            <option value="">Pilih Jenjang</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA/SMK">SMA/SMK</option>
        </select>

        <select name="kelas" id="kelas">
            <option value="">Pilih Kelas</option>
        </select>
    </div>

    <input type="number" name="stock" placeholder="Stok Buku" min="0" required>

    <p>Upload PDF</p>
    <input type="file" name="pdf_file" accept=".pdf">

    <p>Upload Cover</p>
    <input type="file" name="cover_image" accept="image/*">

    <button type="submit">Tambah</button>
</form>

<script>
const subCategoryMap = {
    'Novel': ['Romance', 'Horror', 'Comedy', 'Fantasy', 'Mystery'],
    'Filsafat': ['Filsafat Agama', 'Filsafat Ilmu'],
    'Psikologi': ['Psikologi Sosial', 'Psikologi Agama'],
    'Islami': ['Kisah Nabi', 'Fiqih']
};

const categorySelect = document.getElementById('category');
const subCategoryDiv = document.getElementById('subkategori-fields');
const subCategorySelect = document.getElementById('sub_category');
const pelajaranFields = document.getElementById('pelajaran-fields');

categorySelect.addEventListener('change', function () {
    const selected = this.value;

    const options = subCategoryMap[selected] || [];
    subCategorySelect.innerHTML = '<option value="">Pilih Sub-Kategori</option>';
    options.forEach(sub => {
        const opt = document.createElement('option');
        opt.value = sub;
        opt.textContent = sub;
        subCategorySelect.appendChild(opt);
    });
    subCategoryDiv.style.display = options.length > 0 ? 'block' : 'none';

    pelajaranFields.style.display = selected === 'Pelajaran' ? 'block' : 'none';
});

document.getElementById('jenjang').addEventListener('change', function () {
    const jenjang = this.value;
    const kelas = document.getElementById('kelas');
    kelas.innerHTML = '<option value="">Pilih Kelas</option>';
    let jumlah = jenjang === 'SD' ? 6 : 3;
    let start = jenjang === 'SD' ? 1 : (jenjang === 'SMP' ? 7 : 10);
    for (let i = 0; i < jumlah; i++) {
        const option = document.createElement('option');
        option.value = start + i;
        option.textContent = 'Kelas ' + (start + i);
        kelas.appendChild(option);
    }
});
</script>

</body>
</html>
