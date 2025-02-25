<?php
session_start(); // Memulai sesi

// Konfigurasi koneksi database
$servername = "localhost";   // Biasanya localhost
$username   = "root";        // Ganti dengan username database Anda
$password   = "root";        // Ganti dengan password database Anda (jika ada)
$dbname     = "tspkl2025";   // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error_message = ""; // Variabel untuk pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $tanggal_lahir = $_POST['tanggal_lahir'];

    // Query untuk memeriksa username dan tanggal_lahir di database
    $sql = "SELECT * FROM login WHERE username = ? AND tanggal_lahir = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $tanggal_lahir);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ditemukan, login berhasil
        $_SESSION['username'] = $user;
        $_SESSION['tanggal_lahir'] = $tanggal_lahir;

        header("Location: index.php"); // Redirect ke halaman utama
        exit;
    } else {
        // Jika tidak ditemukan, tampilkan pesan error
        $error_message = "Login gagal! Username atau tanggal lahir tidak terdaftar.";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Menampilkan pesan error jika ada -->


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* CSS untuk pesan error */
        .error-message {
            color: red;
            font-weight: bold;
            font-size: 16px;
            padding: 10px;
            background-color: #fce4e4;
            border: 1px solid red;
            margin-top: 20px;
            text-align: center;
        }

        .login-container {
            text-align: center;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .login-box {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #45a049;
        }

        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Sign In</h2>
        
        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="login-box">
            <form method="POST" action="">
                <label for="username">Nama Anda</label>
                <input type="text" id="username" name="username" placeholder="Nama lengkap Besar guna spasi" required>
                
                <label for="tanggal_lahir">Masukkan TTL Anda</label>
<input type="date" id="tanggal_lahir" name="tanggal_lahir" placeholder="Masukkan Tanggal lahir" required>

                
                <button type="submit" class="btn login-btn">Login</button>
            </form>
        </div>
    </div>

    <footer>
        <p>Made With Kelompok 4 | © 2025</p>
    </footer>

</body>
</html>
