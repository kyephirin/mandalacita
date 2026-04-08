<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['no_pendaftaran'])) {
    header('Location: login.php');
    exit;
}

$no_pendaftaran = $_SESSION['no_pendaftaran'];
$nama = $_SESSION['nama'];

// Ambil data dari camaba
$user = $conn->query("SELECT * FROM camaba WHERE no_pendaftaran='$no_pendaftaran'")->fetch_assoc();

// Ambil data dari daftar_ulang (termasuk NIM, pas_foto, dll)
$daftar_ulang = $conn->query("SELECT * FROM daftar_ulang WHERE no_pendaftaran='$no_pendaftaran'")->fetch_assoc();

if (!$daftar_ulang || empty($daftar_ulang['nim'])) {
    echo "<h3>Data NIM belum tersedia.</h3>";
    exit;
}

// Ambil path foto
$pas_foto = $daftar_ulang['pas_foto'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Mahasiswa - PMB Mandala Cita</title>
    <style>
        /* Reset & dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Aturan cetak landscape */
        @page {
            size: landscape;
            margin: 1.5cm;
        }

        body {
            background: white;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Kartu utama */
        .kartu {
            width: 100%;
            max-width: 1100px;
            border: 2px solid #2c3e50;
            border-radius: 15px;
            padding: 30px;
            background: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Header dengan logo */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo {
            height: 60px;
        }
        .instansi {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .nim {
            background: #ecf0f1;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: bold;
            color: #27ae60;
            font-size: 1.5rem;
            letter-spacing: 2px;
        }

        /* Konten utama: dua kolom */
        .content {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }
        .data {
            flex: 2;
        }
        .foto {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            border-left: 2px dashed #bdc3c7;
            padding-left: 30px;
        }
        .foto img {
            width: 180px;
            height: 220px;
            object-fit: cover;
            border: 2px solid #2c3e50;
            border-radius: 10px;
            background: #f5f5f5;
        }
        .foto-placeholder {
            width: 180px;
            height: 220px;
            border: 2px dashed #999;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-style: italic;
        }
        .caption-foto {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        /* Tabel data */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table tr {
            border-bottom: 1px solid #ecf0f1;
        }
        table td {
            padding: 12px 8px;
            vertical-align: top;
        }
        table td.label {
            font-weight: 600;
            color: #2c3e50;
            width: 35%;
        }
        table td.value {
            color: #000;
            font-weight: 500;
        }

        /* Tanda tangan */
        .signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #95a5a6;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-box .nama {
            margin-top: 50px;
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-box .kota-tanggal {
            margin-bottom: 10px;
            color: #555;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 12px;
        }

        /* Sembunyikan tombol saat print */
        @media print {
            .no-print {
                display: none;
            }
        }

        /* Tombol aksi */
        .btn-print {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #2c3e50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="kartu">
        <!-- Header dengan logo dan NIM -->
        <div class="header">
            <div class="logo-area">
                <img src="images/logo.png" alt="Logo" class="logo">
                <span class="instansi">Universitas Mandala Cita</span>
            </div>
            <div class="nim">
                NIM: <?= htmlspecialchars($daftar_ulang['nim']) ?>
            </div>
        </div>

        <!-- Konten utama: data dan foto -->
        <div class="content">
            <!-- Kolom kiri: data diri -->
            <div class="data">
                <table>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="value">: <?= htmlspecialchars($user['nama']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Jurusan</td>
                        <td class="value">: <?= htmlspecialchars($user['jurusan']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Program Studi</td>
                        <td class="value">: <?= htmlspecialchars($daftar_ulang['program_studi'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">No. Pendaftaran</td>
                        <td class="value">: <?= htmlspecialchars($no_pendaftaran) ?></td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="value">: <?= htmlspecialchars($daftar_ulang['nik'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Lahir</td>
                        <td class="value">: <?= htmlspecialchars($daftar_ulang['tanggal_lahir'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="value">: <?= htmlspecialchars($daftar_ulang['alamat'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>

            <!-- Kolom kanan: foto -->
            <div class="foto">
                <?php if (!empty($pas_foto) && file_exists($pas_foto)): ?>
                    <img src="<?= htmlspecialchars($pas_foto) ?>" alt="Foto Mahasiswa">
                <?php else: ?>
                    <div class="foto-placeholder">Foto Tidak Tersedia</div>
                <?php endif; ?>
                <div class="caption-foto">Pas Foto</div>
            </div>
        </div>

        <!-- Tanda tangan mahasiswa -->
        <div class="signature">
            <div class="signature-box">
                <div class="kota-tanggal">Jakarta, <?= date('d F Y') ?></div>
                <div>Mahasiswa,</div>
                <div class="nama"><?= htmlspecialchars($user['nama']) ?></div>
            </div>
        </div>

        <!-- Footer kecil -->
        <div class="footer">
            Dokumen ini dicetak pada <?= date('d-m-Y H:i:s') ?> melalui sistem PMB Mandala Cita.
        </div>

        <!-- Tombol cetak (hanya tampil di layar) -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <a href="#" onclick="window.print();" class="btn-print">🖨️ Cetak / Print</a>
            <a href="dashboard-mahasiswa.php" class="btn-print" style="background: #7f8c8d;">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>