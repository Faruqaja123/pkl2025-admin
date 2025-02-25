<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "tspkl2025";

try {
    // Koneksi menggunakan PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query untuk menggabungkan data alumni dari berbagai tabel
    $query = "
        SELECT 'Bekerja' AS status, COUNT(*) AS count FROM bekerja
        UNION ALL
        SELECT 'Kuliah', COUNT(*) FROM kuliah
        UNION ALL
        SELECT 'Wirausaha', COUNT(*) FROM wirausaha
        UNION ALL
        SELECT 'opsilain', COUNT(*) FROM opsilain
        UNION ALL
        SELECT 'bekerja_kuliah', COUNT(*) FROM bekerja_kuliah
    ";

    // Menjalankan query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Mengambil data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Menyiapkan data untuk Chart.js
    $labels = [];
    $values = [];
    foreach ($data as $row) {
        $labels[] = $row['status'];
        $values[] = (int) $row['count'];
    }

    // Convert labels dan values ke JSON untuk digunakan di JavaScript
    $labels_json = json_encode($labels);
    $values_json = json_encode($values);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donut Chart Statistik Alumni</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    box-sizing: border-box;
    background-color: #fff;
    color: #333;
    }

 /* Styling Navbar */
 .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #007bff;
            padding: 15px 30px;
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
margin-right: 500px;
margin-left: -100px;
}

/* Link Navigation */
.nav-links {
    display: flex;
    list-style: none;
    gap: 30px; /* Jarak antar item menu */
    }
  
    .nav-links a {
    text-decoration: none;
    color: #000;
    margin: 0 10px;
    font-weight: lighter;
    font-size: 16px;
    transition: color 0.3s;
    }

.nav-links a:hover{
color: #007BFF;
}
/* Logout Button */
.logout {
    background-color: #8A8787;
    padding: 10px 15px;
    border-radius: 10px;
    color: #000;
    font-weight: lighter;
    }
  
    .logout:hover {
    background-color: #8A8787;
    padding: 10px 15px; 
    border-radius: 10px;
    }radius: 10px;

        /* Carousel */
        .carousel {
            position: relative;
            width: 100%;
            max-width: 1200px; /* Memperbesar ukuran gambar */
            height: 500px; /* Tambahkan tinggi agar tetap proporsional */
            margin: 30px auto;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }

        .carousel-image {
            width: 100%;
            flex: 0 0 100%;
            object-fit: cover;
            height: 100%;
        }

        /* Tombol Navigasi */
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            font-size: 24px;
        }

        .prev { left: 20px; }
        .next { right: 20px; }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Welcome Section */
        .welcome-section {
            padding: 20px;
            background: white;
            margin: 20px;
        }

        /* Footer */
        footer {
            background:rgb(255, 255, 255);
            color: black;
            padding: 10px;
            margin-top: 20px;
        }
        
         {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        canvas {
            width: 100%;
            height: 300px;
        }
        /* Navbar Styling */
/* Styling Navbar */
.navbar {
  background-color:rgb(255, 255, 255);
  padding: 15px 100px;
  border-bottom: 1px solid #ddd;
  position: sticky;
  top: 0;
  z-index: 1000;
  display: flex;
  justify-content: flex-end;
  gap: 20px;
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
  margin-right: 800px;
  margin-left: -100px;
  }
/* Menu Styling */
.menu {
    display: flex;
    gap: 20px;
}

.menu a {
    color: black;
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
</head>
<body>
 <!-- Header Navigation -->
 <header>
    <header class="navbar">
        <div class="logo-container">
            <img src="IMG/Logo.1.png" alt="Logo" class="logo">
        </div><!-- Tombol Hamburger -->
        <div class="hamburger" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <nav class="menu">
            <a href="index.php">BERANDA</a>
            <a href="panduan.php">PANDUAN</a>
            <a href="data-alumni.php">DATA ALUMNI</a>
            <a href="kuesionerbaru.php">ISI KUESIONER</a> 
            <a href="statistik.php">STATISTIK</a>
            <a href="login.php" class="logout">LOGOUT</a>
        </nav>
    </header>
</header>

<div class="container">
    <h2>Statistik Alumni berdasarkan Status</h2>
    <canvas id="donutChart"></canvas>
</div>

<script>
// Ambil data dari PHP
const labels = <?php echo $labels_json; ?>;
const data = <?php echo $values_json; ?>;

// Membuat Donut Chart menggunakan Chart.js
const ctx = document.getElementById('donutChart').getContext('2d');
const donutChart = new Chart(ctx, {
    type: 'doughnut', // Tipe chart donut
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Alumni',
            data: data,
            backgroundColor: ['#36A2EB', '#FF6384', '#FFCD56', '#4BC0C0','#9580f2'], // Warna segment
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        }
    }
});
</script>
<footer>
    <p>Made With Kelompok 4 | &copy; 2025</p>
</footer>
</body>
</html>
