<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['no_pendaftaran'])) {
    header('Location: login.php');
    exit;
}

$no_pendaftaran = $_SESSION['no_pendaftaran'];
$nama = $_SESSION['nama'];

// Ambil nilai dari tabel camaba
$user = $conn->query("SELECT * FROM camaba WHERE no_pendaftaran='$no_pendaftaran'")->fetch_assoc();
$nilai = $user['nilai'] ?? 0;

// Tentukan kelulusan (misal >= 70)
$lulus = $nilai >= 70;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - PMB Mandala Cita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 500px;
            padding: 30px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .info p {
            margin: 8px 0;
            color: #555;
        }
        .status {
            padding: 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 24px;
            margin: 20px 0;
        }
        .lulus {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .tidak-lulus {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hasil Ujian Penerimaan Mahasiswa Baru</h2>
        <div class="info">
            <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
            <p><strong>Nomor Pendaftaran:</strong> <?= htmlspecialchars($no_pendaftaran) ?></p>
            <p><strong>Nilai:</strong> <?= round($nilai, 2) ?></p>
        </div>
        
        <?php if ($lulus): ?>
            <div class="status lulus">
                SELAMAT! ANDA DINYATAKAN LULUS
            </div>
            <p>Silakan lanjutkan ke tahap daftar ulang.</p>
            <a href="daftar-ulang.php" class="btn">Daftar Ulang Sekarang</a>
        <?php else: ?>
            <div class="status tidak-lulus">
                MOHON MAAF, ANDA BELUM LULUS
            </div>
            <p>Terima kasih telah mengikuti ujian.</p>
        <?php endif; ?>
        
        <a href="dashboard-mahasiswa.php" class="btn" style="background: #6c757d;">Kembali ke Dashboard</a>
    </div>
</body>
</html>