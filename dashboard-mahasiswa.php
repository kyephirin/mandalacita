<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['no_pendaftaran'])) {
    header('Location: login.php');
    exit;
}

$no_pendaftaran = $_SESSION['no_pendaftaran'];
$nama = $_SESSION['nama'];

// Ambil data user dari tabel camaba
$user = $conn->query("SELECT * FROM camaba WHERE no_pendaftaran='$no_pendaftaran'")->fetch_assoc();

// Cek status ujian
$sudah_ujian = $conn->query("SELECT COUNT(*) as total FROM ujian WHERE no_pendaftaran='$no_pendaftaran'")->fetch_assoc()['total'] > 0;

// Cek kelulusan (nilai >= 70)
$status_lulus = ($user['nilai'] >= 70);

// Cek apakah sudah daftar ulang
$daftar_ulang = $conn->query("SELECT * FROM daftar_ulang WHERE no_pendaftaran='$no_pendaftaran'");
$sudah_daftar_ulang = $daftar_ulang->num_rows > 0;

// Jika sudah daftar ulang, ambil data NIM dan status verifikasi
$nim = '';
$status_verifikasi = '';
if ($sudah_daftar_ulang) {
    $data_du = $daftar_ulang->fetch_assoc();
    $nim = $data_du['nim'] ?? '';
    $status_verifikasi = $data_du['status_verifikasi'] ?? 'menunggu';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta - PMB Mandala Cita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4f6f9;
        }
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .navbar h2 {
            font-size: 1.5rem;
            font-weight: 500;
        }
        .navbar h2 span {
            font-weight: 700;
            color: #f39c12;
        }
        .logout {
            background: #e74c3c;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .logout:hover {
            background: #c0392b;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .welcome-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .welcome-info p {
            color: #666;
            margin: 5px 0;
        }
        .nim-info {
            background: #f0f7ff;
            border: 2px dashed #2c3e50;
            border-radius: 10px;
            padding: 15px 25px;
            text-align: center;
        }
        .nim-info .label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .nim-info .nim {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 2px;
        }
        .nim-info .status {
            margin-top: 5px;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
        }
        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }
        .status-verifikasi {
            background: #d4edda;
            color: #155724;
        }
        .btn-cetak {
            background: #2c3e50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn-cetak:hover {
            background: #2980b9;
        }
        .status-badge {
            background: #f39c12;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .status-badge.selesai {
            background: #4caf50;
        }
        .status-badge.red {
            background: #e74c3c;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .card p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-primary {
            background: #2c3e50;
            color: white;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .btn-success {
            background: #4caf50;
            color: white;
        }
        .btn-success:hover {
            background: #45a049;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Halo, <span><?= htmlspecialchars($nama) ?></span></h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <div class="welcome-info">
                <h3>Selamat datang di Dashboard Peserta</h3>
                <p><strong>Nomor Tes:</strong> <?= htmlspecialchars($no_pendaftaran) ?></p>
                <p><strong>Jurusan:</strong> <?= htmlspecialchars($user['jurusan']) ?></p>
            </div>
            
            <?php if ($sudah_ujian): ?>
                <?php if ($status_lulus): ?>
                    <div class="status-badge selesai">✅ Lulus</div>
                <?php else: ?>
                    <div class="status-badge red">❌ Belum Lulus</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="status-badge">⚡ Belum Tes</div>
            <?php endif; ?>
        </div>

        <?php if ($sudah_daftar_ulang && $status_verifikasi == 'terverifikasi' && !empty($nim)): ?>
        <!-- Info NIM (hanya muncul jika sudah diverifikasi dan punya NIM) -->
        <div style="margin-bottom: 30px;">
            <div class="nim-info">
                <div class="label">Nomor Induk Mahasiswa (NIM)</div>
                <div class="nim"><?= htmlspecialchars($nim) ?></div>
                <div class="status status-verifikasi">Status: Terverifikasi</div>
                <a href="cetak-data.php" target="_blank" class="btn-cetak">🖨️ Cetak Data Mahasiswa</a>
            </div>
        </div>
        <?php elseif ($sudah_daftar_ulang && $status_verifikasi == 'menunggu'): ?>
        <!-- Info jika masih menunggu verifikasi -->
        <div style="margin-bottom: 30px;">
            <div class="nim-info" style="border-color: #f39c12;">
                <div class="label">Status Daftar Ulang</div>
                <div class="status status-menunggu">Menunggu Verifikasi Admin</div>
                <p style="margin-top: 10px; color: #666;">NIM akan muncul setelah diverifikasi.</p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="grid">
            <div class="card">
                <div class="card-icon">📝</div>
                <h3>Mulai Ujian</h3>
                <p>Ikuti tes seleksi online. Waktu 120 menit untuk 10 soal.</p>
                <?php if (!$sudah_ujian): ?>
                    <a href="soal.php" class="btn btn-primary">Mulai Tes</a>
                <?php else: ?>
                    <button class="btn btn-primary" disabled>Sudah Mengerjakan</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-icon">📊</div>
                <h3>Hasil Tes</h3>
                <p>Lihat nilai dan status kelulusan Anda.</p>
                <?php if ($sudah_ujian): ?>
                    <a href="hasil.php" class="btn btn-secondary">Lihat Hasil</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Belum Tersedia</button>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-icon">📎</div>
                <h3>Daftar Ulang</h3>
                <p>Jika lulus, lakukan daftar ulang dan upload berkas.</p>
                <?php if ($status_lulus && !$sudah_daftar_ulang): ?>
                    <a href="daftar-ulang.php" class="btn btn-success">Daftar Ulang</a>
                <?php elseif ($sudah_daftar_ulang): ?>
                    <button class="btn btn-success" disabled>Sudah Daftar Ulang</button>
                <?php else: ?>
                    <button class="btn btn-success" disabled>Belum Lulus</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="margin-top: 30px; background: #fff3cd; border-left: 4px solid #f39c12; padding: 15px; border-radius: 5px;">
            <p style="color: #856404;"><strong>Informasi:</strong> Pastikan Anda telah membaca tata tertib ujian sebelum memulai tes.</p>
        </div>
    </div>
    
    <footer>
        <p>© 2026 PMB Mandala Cita</p>
    </footer>
</body>
</html>