<?php
// Konfigurasi database
$host = "localhost";
$db_user = "root";
$db_pass = "root"; // Sesuaikan dengan konfigurasi MySQL-mu
$dbname = "tspkl2025";

$conn = new mysqli($host, $db_user, $db_pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah Data Alumni (Username dan tanggal_lahir)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["tanggal_lahir"])) {
    $username_input = trim($_POST["username"]);
    $tanggal_lahir = trim($_POST["tanggal_lahir"]);
    
    if (!empty($username_input) && !empty($tanggal_lahir)) {
        $stmt = $conn->prepare("INSERT INTO login (username, tanggal_lahir) VALUES (?, ?)");
        $stmt->bind_param("ss", $username_input, $tanggal_lahir);
        $stmt->execute();
        $stmt->close();
    }
}

// Edit Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_username"]) && isset($_POST["edit_tanggal_lahir"])) {
    $old_tanggal_lahir = $_POST["old_tanggal_lahir"];
    $new_username = trim($_POST["edit_username"]);
    $new_tanggal_lahir = trim($_POST["edit_tanggal_lahir"]);
    
    if (!empty($new_username) && !empty($new_tanggal_lahir)) {
        $stmt = $conn->prepare("UPDATE login SET username=?, tanggal_lahir=? WHERE tanggal_lahir=?");
        $stmt->bind_param("sss", $new_username, $new_tanggal_lahir, $old_tanggal_lahir);
        $stmt->execute();
        $stmt->close();
    }
}

// Hapus Data
if (isset($_GET['delete_tanggal_lahir'])) {
    $delete_tanggal_lahir = $_GET['delete_tanggal_lahir'];
    $stmt = $conn->prepare("DELETE FROM login WHERE tanggal_lahir=?");
    $stmt->bind_param("s", $delete_tanggal_lahir);
    $stmt->execute();
    $stmt->close();
}

// Ambil data untuk ditampilkan
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT username, tanggal_lahir FROM login WHERE username LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%" . $search . "%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Data Alumni</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    header {
        width: 100%;
        background: white;
        padding: ;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    /* Navbar */
    .navbar {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #007bff;
        padding: 15px 30px;
    }

    .menu {
        display: flex;
        gap: 20px; /* Memberikan jarak antar menu */
    }

    .menu a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        padding: 10px;
        transition: 0.3s;
    }

    .menu a:hover {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
    }

    .container {
        width: 100%;
        max-width: 1200px;
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 20px;
    }

    .form-container input, 
    .search-bar input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .form-container button, 
    .search-bar button {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
        text-align: center;
    }

    .search-bar {
        width: 100%;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .logo {
        max-width: 240px;
        height: auto;
    }

    @media (max-width: 768px) {
        .menu {
            flex-direction: column;
            align-items: center;
        }

        .menu a {
            margin: 5px 0;
        }

        .logo {
            max-width: 150px;
        }
    }
</style>

</head>
<body>
    <!-- Header Navigation -->
    <header>
        <div class="navbar">
            <div class="logo-container">
                <img src="IMG/Logo.1.png" alt="Logo" class="logo">
            </div>
            <nav class="menu">
                <a href="index.php">BERANDA</a>
                <a href="panduan.php">PANDUAN</a>
                <a href="data-alumni.php">DATA ALUMNI</a>
                <a href="kuesionerbaru.php">ISI KUESIONER</a> 
                <a href="statistik.php">STATISTIK</a>
                <a href="login.php" class="login">LOGOUT</a>
            </nav>
        </div>
    </header>

    <header>
        <h1>DATA ALUMNI SMK-BP SUBULUL HUDA</h1>
    </header>
    
    <div class="container">
        <h2>Tambah Data Alumni</h2>
        <form method="POST" class="form-container">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="tanggal_lahir" placeholder="Tanggal lahir" required>
            <button type="submit">Tambah</button>
        </form>
        
        <h2>Edit Data</h2>
        <form method="POST" class="form-container">
            <input type="hidden" name="old_tanggal_lahir" id="old_tanggal_lahir">
            <input type="text" name="edit_username" id="edit_username" placeholder="Username" required>
            <input type="text" name="edit_tanggal_lahir" id="edit_tanggal_lahir" placeholder="Tanggal lahir" required>
            <button type="submit">Simpan Perubahan</button>
        </form>
        
        <h2>Daftar Alumni</h2>
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari username..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Cari</button>
            </form>
        </div>
        <table>
            <tr>
                <th>Username</th>
                <th>Tanggal lahir</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['tanggal_lahir']; ?></td>
                    <td>
                        <a href="?delete_tanggal_lahir=<?php echo $row['tanggal_lahir']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        <button onclick="editData('<?php echo $row['username']; ?>', '<?php echo $row['tanggal_lahir']; ?>')">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    
    <script>
        function editData(username, tanggal_lahir) {
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_tanggal_lahir').value = tanggal_lahir;
            document.getElementById('old_tanggal_lahir').value = tanggal_lahir;
        }
    </script>
</body>
</html>
