<?php
// Kode PHP untuk menyambungkan ke database
$servername = "localhost";   // Biasanya localhost
$username   = "root";        // Ganti dengan username database Anda
$password   = "root";        // Ganti dengan password database Anda (jika ada)
$dbname     = "tspkl2025";   // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menyimpan data ke dalam database setelah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $alamat = $_POST['alamat'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $nomor_hp = $_POST['nomor_hp'];
    $angkatan = $_POST['angkatan'];
    $kompetensi_keahlian = $_POST['kompetensi_keahlian'];
    $keterangan = $_POST['keterangan'];

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO opsilain (nama, nisn, alamat, jenis_kelamin, email, nomor_hp, angkatan, kompetensi_keahlian, keterangan)
            VALUES ('$nama', '$nisn', '$alamat', '$jenis_kelamin', '$email', '$nomor_hp', '$angkatan', '$kompetensi_keahlian', '$keterangan')";

if ($conn->query($sql) === TRUE) {
    // Setelah menyimpan data, arahkan ke halaman data-alumni.php
    header("Location: kuesionerbaru.php");
    exit(); // Pastikan script berhenti setelah redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
?>
}

// Tutup koneksi database
<?$conn->close();?>

