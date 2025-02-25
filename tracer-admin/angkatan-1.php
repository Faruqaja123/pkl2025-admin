<?php
// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "root"; // Sesuaikan dengan konfigurasi MySQL-mu
$dbname = "tspkl2025";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah Nama Alumni
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nama"])) {
    $nama = trim($_POST["nama"]);
    if (!empty($nama)) {
        $stmt = $conn->prepare("INSERT INTO angkatan_1 (nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
        if ($stmt->execute()) {
            $success_message = "Nama berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan nama!";
        }
        $stmt->close();
    } else {
        $error_message = "Nama tidak boleh kosong!";
    }
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : "";
$search_query = $search ? "WHERE nama LIKE '%$search%'" : "";

// Hitung total data
$total_query = "SELECT COUNT(*) FROM angkatan_1 $search_query";
$total_result = $conn->query($total_query);
$total_data = $total_result->fetch_row()[0];
$total_pages = ceil($total_data / $limit);

// Ambil data dengan batas limit
$sql = "SELECT nama FROM angkatan_1 $search_query LIMIT $start, $limit";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alumni</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            margin: 0;
            padding: 0;
        }



        
        /* Navbar */
        .navbar {
            background-color: #f5f5f5;
            padding: 15px 100px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
  justify-content: flex-end;
  gap: 20px
        }
        /* Logo Container */
  .logo-container {
  display: flex;
  align-items: center;
  }
  
  .logo {
  width: -10px;
  height: 60px;
  object-fit: cover;
  object-position: center ;
  margin-top: 0%;
  margin-right: 200px;
  margin-left: -100px;
  }

        .menu a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        .menu a:hover {
            text-decoration: underline;
        }


        .container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .search-bar {
            text-align: right;
            margin-bottom: 10px;
        }

        .search-bar input {
            padding: 10px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-container {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-container input {
            padding: 10px;
            width: 60%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px;
            background:rgb(255, 255, 255);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            padding: 8px 12px;
            margin: 5px;
            border: 1px solid #ddd;
            color: #333;
            border-radius: 5px;
        }

        .pagination a.active {
            background: #4CAF50;
            color: white;
        }

        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
        }

    </style>
    
</head>
<body>
     <!-- Header Navigation -->
 <header>
    <header class="navbar">
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
    </header>
</header>

<style>
    .floating-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #007bff;
    color: white;
    padding: 15px 20px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: 0.3s;
}

.floating-button:hover {
    background: #0056b3;
}

/* Navbar Styling */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #007bff;
    padding: 10px 120px;
}

/* Logo Styling */
.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    width: 250px; /* Sesuaikan ukuran logo */
    height: auto;
}

/* Menu Styling */
.menu {
    display: flex;
    gap: 20px;
}

.menu a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 8px 12px;
}

.menu a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: center;
    }
    
    .menu {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .logo {
        width: 40px; /* Kecilkan logo untuk layar kecil */
    }
}
</style>

<header>
    <h1>DATA ALUMNI ANGKATAN 1 SMK-BP SUBULUL HUDA</h1>
</header>
<?php
// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "root";
$dbname = "tspkl2025";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query mengambil data dari semua tabel menggunakan UNION ALL,
// dengan tambahan kolom status sesuai nama tabel asal data
$sql = "
    SELECT 'Bekerja' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian FROM bekerja
    UNION ALL
    SELECT 'Kuliah' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian FROM kuliah
    UNION ALL
    SELECT 'Bekerja & Kuliah' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian FROM bekerja_kuliah
    UNION ALL
    SELECT 'Wirausaha' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian FROM wirausaha
    UNION ALL
    SELECT 'opsilain' AS status, nama, alamat, email, angkatan, jenis_kelamin, nomor_hp, kompetensi_keahlian FROM opsilain
    ORDER BY angkatan DESC, nama ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alumni</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; }
        .container { width: 80%; margin: auto; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>DATA ALUMNI</h1>
        <table>
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
            </tr>
            <?php
if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        if ($row['angkatan'] == 1) { // Hanya tampilkan angkatan 1
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['alamat'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['angkatan'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['jenis_kelamin'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['nomor_hp'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['kompetensi_keahlian'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['status'] ?? '') . "</td>";
            echo "</tr>";
        }
    }
} else {
    echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
}
?>


        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<footer>
    <p>Made With Kelompok 4 | © 2025</p>
</footer>
<a href="status-alumni.php" class="floating-button">Isi Kuesioner</a>

</body>
</html>


            