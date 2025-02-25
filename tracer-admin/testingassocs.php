<?php
// Konfigurasi database
$host     = "localhost";
$username = "root";
$password = "root"; // Sesuaikan dengan konfigurasi MySQL-mu
$dbname   = "tspkl2025";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Daftar tabel yang diizinkan untuk operasi CRUD
$allowed_tables = ['bekerja', 'kuliah', 'bekerja_kuliah', 'wirausaha', 'opsilain'];

// Cek parameter action dari URL (edit, delete, atau tampilkan halaman utama)
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ---------- FUNGSI DELETE ----------
if ($action === 'delete') {
    $table = $_GET['table'] ?? '';
    $id    = $_GET['id'] ?? '';
    
    if (!$table || !$id) {
        die("Data tidak valid.");
    }
    if (!in_array($table, $allowed_tables)) {
        die("Tabel tidak valid.");
    }
    
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Gagal menghapus data.";
    }
    $stmt->close();
}

// ---------- FUNGSI EDIT ----------
if ($action === 'edit') {
    $table = $_GET['table'] ?? '';
    $id    = $_GET['id'] ?? '';
    
    if (!$table || !$id) {
        die("Data tidak valid.");
    }
    if (!in_array($table, $allowed_tables)) {
        die("Tabel tidak valid.");
    }
    
    // Jika form edit sudah disubmit, proses update data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama                = $_POST['nama'];
        $alamat              = $_POST['alamat'];
        $email               = $_POST['email'];
        $angkatan            = $_POST['angkatan'];
        $jenis_kelamin       = $_POST['jenis_kelamin'];
        $nomor_hp            = $_POST['nomor_hp'];
        $kompetensi_keahlian = $_POST['kompetensi_keahlian'];
        
        $stmt = $conn->prepare("UPDATE $table SET nama = ?, alamat = ?, email = ?, angkatan = ?, jenis_kelamin = ?, nomor_hp = ?, kompetensi_keahlian = ? WHERE id = ?");
        $stmt->bind_param("sssisssi", $nama, $alamat, $email, $angkatan, $jenis_kelamin, $nomor_hp, $kompetensi_keahlian, $id);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Gagal memperbarui data.";
        }
        $stmt->close();
    } else {
        // Ambil data yang akan diedit
        $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data   = $result->fetch_assoc();
        $stmt->close();
        if (!$data) {
            die("Data tidak ditemukan.");
        }
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Edit Data Alumni</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
                .container {
                    width: 50%; margin: 50px auto; background: #fff;
                    padding: 20px; border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                label { display: block; margin-top: 10px; }
                input[type="text"],
                input[type="email"],
                input[type="number"] {
                    width: 100%; padding: 8px; margin-top: 5px;
                }
                button {
                    margin-top: 15px; padding: 10px 15px;
                    background: #4CAF50; color: white;
                    border: none; cursor: pointer;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Edit Data Alumni</h1>
                <form method="post">
                    <label>Nama:</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required>
    
                    <label>Alamat:</label>
                    <input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat'] ?? '') ?>" required>
    
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
    
                    <label>Angkatan:</label>
                    <input type="number" name="angkatan" value="<?= htmlspecialchars($data['angkatan'] ?? '') ?>" required>
    
                    <label>Jenis Kelamin:</label>
                    <input type="text" name="jenis_kelamin" value="<?= htmlspecialchars($data['jenis_kelamin'] ?? '') ?>" required>
    
                    <label>Nomor HP:</label>
                    <input type="text" name="nomor_hp" value="<?= htmlspecialchars($data['nomor_hp'] ?? '') ?>" required>
    
                    <label>Kompetensi Keahlian:</label>
                    <input type="text" name="kompetensi_keahlian" value="<?= htmlspecialchars($data['kompetensi_keahlian'] ?? '') ?>" required>
    
                    <button type="submit">Update</button>
                </form>
                <p><a href="<?= $_SERVER['PHP_SELF'] ?>">Kembali ke Data Alumni</a></p>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// ---------- HALAMAN UTAMA ----------
// Proses penambahan data alumni (form tambah)
$add_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_alumni'])) {
    // Ambil data dari form tambah alumni
    $table               = $_POST['status'] ?? '';
    $nama                = trim($_POST['nama'] ?? '');
    $alamat              = trim($_POST['alamat'] ?? '');
    $email               = trim($_POST['email'] ?? '');
    $angkatan            = $_POST['angkatan'] ?? '';
    $jenis_kelamin       = trim($_POST['jenis_kelamin'] ?? '');
    $nomor_hp            = trim($_POST['nomor_hp'] ?? '');
    $kompetensi_keahlian = trim($_POST['kompetensi_keahlian'] ?? '');
    
    if (!in_array($table, $allowed_tables)) {
        $add_message = "Status tidak valid.";
    } elseif (empty($nama)) {
        $add_message = "Nama tidak boleh kosong.";
    } else {
        $stmt = $conn->prepare("INSERT INTO $table (nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $nama, $alamat, $email, $angkatan, $jenis_kelamin, $nomor_hp, $kompetensi_keahlian);
        if ($stmt->execute()) {
            $add_message = "Data alumni berhasil ditambahkan!";
        } else {
            $add_message = "Gagal menambahkan data alumni!";
        }
        $stmt->close();
    }
}

// Ambil data alumni (hanya angkatan 1) dengan query UNION dari beberapa tabel
$sql = "
    SELECT id, 'Bekerja' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian, 'bekerja' AS table_name 
      FROM bekerja WHERE angkatan = 1
    UNION ALL
    SELECT id, 'Kuliah' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian, 'kuliah' AS table_name 
      FROM kuliah WHERE angkatan = 1
    UNION ALL
    SELECT id, 'Bekerja & Kuliah' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian, 'bekerja_kuliah' AS table_name 
      FROM bekerja_kuliah WHERE angkatan = 1
    UNION ALL
    SELECT id, 'Wirausaha' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian, 'wirausaha' AS table_name 
      FROM wirausaha WHERE angkatan = 1
    UNION ALL
    SELECT id, 'Opsilain' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian, 'opsilain' AS table_name 
      FROM opsilain WHERE angkatan = 1
    ORDER BY angkatan DESC, nama ASC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Alumni Angkatan 1</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; }
    .container {
      width: 90%; margin: 30px auto; background: white;
      padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .message { margin: 10px; padding: 10px; }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }
    form.add-form { margin-bottom: 30px; text-align: left; }
    form.add-form label { display: block; margin-top: 10px; }
    form.add-form input, form.add-form select {
      width: 100%; padding: 8px; margin-top: 5px;
    }
    form.add-form button {
      margin-top: 15px; padding: 10px 15px;
      background: #4CAF50; color: white; border: none; cursor: pointer;
    }
    a { text-decoration: none; color: #007bff; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Data Alumni Angkatan 1</h1>
    <?php if (!empty($add_message)): ?>
      <div class="message <?= strpos($add_message, 'berhasil') !== false ? 'success' : 'error' ?>">
        <?= $add_message ?>
      </div>
    <?php endif; ?>
    <h2>Tambah Data Alumni</h2>
    <form method="post" class="add-form">
      <label>Status (Pilih Tabel):</label>
      <select name="status" required>
        <option value="">--Pilih Status--</option>
        <option value="bekerja">Bekerja</option>
        <option value="kuliah">Kuliah</option>
        <option value="bekerja_kuliah">Bekerja &amp; Kuliah</option>
        <option value="wirausaha">Wirausaha</option>
        <option value="opsilain">Opsilain</option>
      </select>
      <label>Nama:</label>
      <input type="text" name="nama" required>
      <label>Alamat:</label>
      <input type="text" name="alamat" required>
      <label>Email:</label>
      <input type="email" name="email" required>
      <label>Angkatan:</label>
      <input type="number" name="angkatan" required>
      <label>Jenis Kelamin:</label>
      <input type="text" name="jenis_kelamin" required>
      <label>Nomor HP:</label>
      <input type="text" name="nomor_hp" required>
      <label>Kompetensi Keahlian:</label>
      <input type="text" name="kompetensi_keahlian" required>
      <button type="submit" name="add_alumni">Tambah Alumni</button>
    </form>

    <h2>Daftar Alumni</h2>
    <table border="1">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>Email</th>
          <th>Angkatan</th>
          <th>Jenis Kelamin</th>
          <th>Nomor HP</th>
          <th>Kompetensi Keahlian</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['angkatan'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nomor_hp'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['kompetensi_keahlian'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                    <td>
                        <a href="?action=edit&table=<?= urlencode($row['table_name']) ?>&id=<?= $row['id'] ?>">Edit</a> |
                        <a href="?action=delete&table=<?= urlencode($row['table_name']) ?>&id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="10">Tidak ada data</td>
            </tr>
            <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php
$conn->close();
?>
