<?php
session_start();
include('../database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID buku tidak ditemukan.";
    exit();
}

$book_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'] ?? null;
    $jenjang = $_POST['jenjang'] ?? null;
    $kelas = $_POST['kelas'];
    $kelas = ($kelas === '' || !is_numeric($kelas)) ? null : intval($kelas);
    $stock = intval($_POST['stock']);

    // PDF Handling
    $pdf_path = $_POST['existing_pdf'];
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $filename = basename($_FILES['pdf_file']['name']);
        $pdf_dir = 'uploads/protected_pdfs/';
        if (!is_dir($pdf_dir)) mkdir($pdf_dir, 0777, true);
        $new_path = $pdf_dir . $filename;
        if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $new_path)) {
            $pdf_path = $filename; // simpan hanya nama file
        }
    }

    // Cover Handling
    $cover_path = $_POST['existing_cover'];
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $cover_filename = basename($_FILES['cover_image']['name']);
        $cover_dir = 'uploads/covers/';
        if (!is_dir($cover_dir)) mkdir($cover_dir, 0777, true);
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_dir . $cover_filename)) {
            $cover_path = $cover_filename;
        }
    }

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, description=?, pdf_path=?, category=?, sub_category=?, cover_image=?, jenjang=?, kelas=?, stock=? WHERE id=?");
    $stmt->bind_param('ssssssssiii', $title, $author, $description, $pdf_path, $category, $sub_category, $cover_path, $jenjang, $kelas, $stock, $book_id);
    $stmt->execute();

    header("Location: data-buku.php");
    exit();
}
?>

<!-- HTML & form tidak berubah, cukup pastikan <input type="file" name="pdf_file"> tetap ada -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../../css/kelola-buku.css">
</head>
<body>

<h2>Edit Buku</h2>
<form action="edit-buku.php?id=<?php echo $book['id']; ?>" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
    <textarea name="description" required><?php echo htmlspecialchars($book['description']); ?></textarea>

    <select name="category" id="category" required>
        <?php
        $categories = ["Pelajaran", "Novel", "Filsafat", "Psikologi", "Dongeng", "Islami", "Self Development"];
        foreach ($categories as $cat) {
            $selected = ($book['category'] == $cat) ? 'selected' : '';
            echo "<option value='$cat' $selected>$cat</option>";
        }
        ?>
    </select>

    <!-- Subkategori -->
    <div id="subkategori-fields" style="display:none;">
        <label>Sub-Kategori</label>
        <select name="sub_category" id="sub_category">
            <option value="">Pilih Sub-Kategori</option>
        </select>
    </div>

    <!-- Pelajaran -->
    <div id="pelajaran-fields" style="display: <?php echo ($book['category'] == 'Pelajaran') ? 'block' : 'none'; ?>;">
        <select name="jenjang" id="jenjang">
            <option value="">Pilih Jenjang</option>
            <?php
            $jenjangs = ['SD', 'SMP', 'SMA/SMK'];
            foreach ($jenjangs as $j) {
                $selected = ($book['jenjang'] == $j) ? 'selected' : '';
                echo "<option value='$j' $selected>$j</option>";
            }
            ?>
        </select>

        <select name="kelas" id="kelas">
            <option value="">Pilih Kelas</option>
            <?php
            $kelas_options = [];
            if ($book['jenjang'] == 'SD') {
                $kelas_options = range(1, 6);
            } elseif ($book['jenjang'] == 'SMP') {
                $kelas_options = range(7, 9);
            } elseif ($book['jenjang'] == 'SMA/SMK') {
                $kelas_options = range(10, 12);
            }
            foreach ($kelas_options as $k) {
                $selected = ($book['kelas'] == $k) ? 'selected' : '';
                echo "<option value='$k' $selected>Kelas $k</option>";
            }
            ?>
        </select>
    </div>

    <input type="number" name="stock" placeholder="Stok Buku" min="0" value="<?php echo intval($book['stock']); ?>" required>

    <p>PDF Saat Ini: <?php echo $book['pdf_path'] ? "<a href='".$book['pdf_path']."' target='_blank'>Lihat</a>" : "Tidak ada"; ?></p>
    <input type="file" name="pdf_file" accept=".pdf">

    <p>Cover Saat Ini:
        <?php if ($book['cover_image']): ?>
            <img src="uploads/covers/<?php echo $book['cover_image']; ?>" style="width:80px;">
        <?php else: ?>
            Tidak ada
        <?php endif; ?>
    </p>
    <input type="file" name="cover_image" accept="image/*">

    <input type="hidden" name="existing_pdf" value="<?php echo htmlspecialchars($book['pdf_path']); ?>">
    <input type="hidden" name="existing_cover" value="<?php echo htmlspecialchars($book['cover_image']); ?>">

    <button type="submit">Update</button>
</form>

<script>
const categorySelect = document.getElementById('category');
const subCategoryDiv = document.getElementById('subkategori-fields');
const subCategorySelect = document.getElementById('sub_category');

const subCategoryMap = {
    'Novel': ['Romance', 'Horror', 'Comedy', 'Fantasy', 'Mystery'],
    'Filsafat': ['Filsafat Agama', 'Filsafat Ilmu'],
    'Psikologi': ['Psikologi Sosial', 'Psikologi Agama'],
    'Islami': ['Kisah Nabi', 'Fiqih']
};

function updateSubCategoryOptions(selectedCat, currentValue) {
    const options = subCategoryMap[selectedCat] || [];
    subCategorySelect.innerHTML = '<option value="">Pilih Sub-Kategori</option>';
    options.forEach(sub => {
        const opt = document.createElement('option');
        opt.value = sub;
        opt.textContent = sub;
        if (sub === currentValue) opt.selected = true;
        subCategorySelect.appendChild(opt);
    });
    subCategoryDiv.style.display = options.length > 0 ? 'block' : 'none';
}

categorySelect.addEventListener('change', function() {
    const selected = this.value;
    updateSubCategoryOptions(selected, null);

    const pelajaranFields = document.getElementById('pelajaran-fields');
    pelajaranFields.style.display = selected === 'Pelajaran' ? 'block' : 'none';
});

window.addEventListener('DOMContentLoaded', function () {
    updateSubCategoryOptions('<?php echo $book['category']; ?>', '<?php echo $book['sub_category']; ?>');
});

document.getElementById('jenjang').addEventListener('change', function () {
    const jenjang = this.value;
    const kelas = document.getElementById('kelas');
    kelas.innerHTML = '<option value="">Pilih Kelas</option>';
    let jumlah = jenjang === 'SD' ? 6 : (jenjang === 'SMP' ? 3 : 3);
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
