<?php
session_start();
require_once 'config/koneksi.php';

if (!isset($_SESSION['no_pendaftaran'])) {
    header('Location: login.php');
    exit;
}

$no_pendaftaran = $_SESSION['no_pendaftaran'];
$nama = $_SESSION['nama'];

// Cek apakah user sudah lulus (nilai >= 70)
$user = $conn->query("SELECT * FROM camaba WHERE no_pendaftaran='$no_pendaftaran'")->fetch_assoc();
if ($user['nilai'] < 70) {
    header('Location: dashboard-mahasiswa.php');
    exit;
}

// Cek apakah sudah pernah daftar ulang
$cek = $conn->query("SELECT id FROM daftar_ulang WHERE no_pendaftaran='$no_pendaftaran'");
if ($cek->num_rows > 0) {
    $sudah_daftar = true;
    $data_du = $cek->fetch_assoc();
} else {
    $sudah_daftar = false;
}

$error = '';
$success = '';

// ... setelah deklarasi variabel
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$sudah_daftar) {
    // Ambil data form
    $nik = trim($_POST['nik'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $program_studi = trim($_POST['program_studi'] ?? '');
    $jalur_masuk = trim($_POST['jalur_masuk'] ?? '');
    $asal_sekolah = trim($_POST['asal_sekolah'] ?? '');
    $nominal_bayar = trim($_POST['nominal_bayar'] ?? '');
    $tanggal_bayar = trim($_POST['tanggal_bayar'] ?? '');

    // Validasi
    if (empty($nik) || empty($tanggal_lahir) || empty($alamat) || empty($program_studi) || empty($jalur_masuk) || empty($asal_sekolah) || empty($nominal_bayar) || empty($tanggal_bayar)) {
        $error = "Semua field harus diisi.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_lahir)) {
        $error = "Format tanggal lahir tidak valid. Gunakan YYYY-MM-DD.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_bayar)) {
        $error = "Format tanggal bayar tidak valid. Gunakan YYYY-MM-DD.";
    } elseif (!is_numeric($nominal_bayar) || $nominal_bayar <= 0) {
        $error = "Nominal bayar harus angka positif.";
    } else {
        // Buat folder upload jika belum ada
        $folders = ['uploads/bukti', 'uploads/ktp', 'uploads/ijazah', 'uploads/foto'];
        foreach ($folders as $folder) {
            if (!is_dir($folder)) mkdir($folder, 0777, true);
        }

       
        function upload_file($file, $target_dir) {
            if ($file['error'] != 0 || $file['size'] == 0) return '';
            $target_file = $target_dir . time() . '_' . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                return $target_file;
            }
            return '';
        }

        $bukti = upload_file($_FILES['bukti_transfer'] ?? ['error'=>4], 'uploads/bukti/');
        $ktp = upload_file($_FILES['ktp'] ?? ['error'=>4], 'uploads/ktp/');
        $ijazah = upload_file($_FILES['ijazah'] ?? ['error'=>4], 'uploads/ijazah/');
        $pas_foto = upload_file($_FILES['pas_foto'] ?? ['error'=>4], 'uploads/foto/');

        // Simpan ke database
        $sql = "INSERT INTO daftar_ulang 
                (no_pendaftaran, nik, tanggal_lahir, alamat, program_studi, jalur_masuk, asal_sekolah, nominal_bayar, tanggal_bayar, bukti_transfer, ktp, ijazah, pas_foto) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssisssss", 
            $no_pendaftaran, $nik, $tanggal_lahir, $alamat, $program_studi, $jalur_masuk, $asal_sekolah, 
            $nominal_bayar, $tanggal_bayar, $bukti, $ktp, $ijazah, $pas_foto
        );
        if ($stmt->execute()) {
            $success = "Daftar ulang berhasil dikirim. Menunggu verifikasi admin.";
            $sudah_daftar = true;
        } else {
            $error = "Gagal menyimpan data: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang - PMB Mandala Cita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #2c3e50, #2c3e50);
            min-height: 100vh;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .info {
            background-color: #e3f2fd;
            border-left: 4px solid #2c3e50;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .error {
            background-color: #f8d7da;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            color: #721c24;
        }
        .success {
            background-color: #d4edda;
            border-left: 4px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            color: #155724;
        }
        .section {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #4caf50;
        }
        .section h3 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1 1 calc(50% - 20px);
            min-width: 250px;
        }
        .form-group.full-width {
            flex: 1 1 100%;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
        }
        .file-input {
            padding: 5px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .checkbox-group input {
            width: auto;
            margin-right: 10px;
        }
        .btn-submit {
            background: #4caf50;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #45a049;
        }
        .btn-kembali {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
        }
        .btn-kembali:hover {
            color: #2c3e50;
        }
        .status-info {
            text-align: center;
            padding: 30px;
        }
        .status-info p {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Daftar Ulang Mahasiswa Baru</h2>
        <div class="subtitle">Universitas Mandala Cita</div>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($sudah_daftar): ?>
            <div class="status-info">
                <p>Anda sudah melakukan daftar ulang.</p>
                <p>Status: <strong><?= $data_du['status_verifikasi'] ?? 'Menunggu' ?></strong></p>
                <p>Silakan tunggu verifikasi dari admin.</p>
                <a href="dashboard-mahasiswa.php" class="btn-submit" style="width: auto; display: inline-block; padding: 10px 30px;">Kembali ke Dashboard</a>
            </div>
        <?php else: ?>
            <div class="info">
                <strong>Info:</strong> Isi data dengan lengkap dan benar.
            </div>

            <form method="POST" enctype="multipart/form-data">
                <!-- Data Diri -->
                <div class="section">
                    <h3>📋 Data Diri</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="no_pendaftaran">Nomor Pendaftaran</label>
                            <input type="text" id="no_pendaftaran" value="<?= htmlspecialchars($no_pendaftaran) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" value="<?= htmlspecialchars($nama) ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nik">NIK <span style="color:red;">*</span></label>
                            <input type="text" id="nik" name="nik" placeholder="Nomor Induk Kependudukan" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir <span style="color:red;">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="alamat">Alamat Lengkap <span style="color:red;">*</span></label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap" required></textarea>
                    </div>
                </div>

                <!-- Data Akademik -->
                <div class="section">
                    <h3>🎓 Data Akademik</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="program_studi">Program Studi <span style="color:red;">*</span></label>
                            <select id="program_studi" name="program_studi" required>
                                <option value="">Pilih Program Studi</option>
                                <option value="Informatika">Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Manajemen">Manajemen</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jalur_masuk">Jalur Masuk <span style="color:red;">*</span></label>
                            <select id="jalur_masuk" name="jalur_masuk" required>
                                <option value="">Pilih Jalur Masuk</option>
                                <option value="Prestasi">Jalur Prestasi</option>
                                <option value="Ujian">Jalur Ujian</option>
                                <option value="Mandiri">Jalur Mandiri</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="asal_sekolah">Asal Sekolah <span style="color:red;">*</span></label>
                        <input type="text" id="asal_sekolah" name="asal_sekolah" placeholder="Nama SMA/SMK/MA" required>
                    </div>
                </div>

                <!-- Data Pembayaran -->
                <div class="section">
                    <h3>💰 Data Pembayaran</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nominal_bayar">Nominal Bayar (Rp) <span style="color:red;">*</span></label>
                            <input type="number" id="nominal_bayar" name="nominal_bayar" placeholder="Contoh: 5000000" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_bayar">Tanggal Bayar <span style="color:red;">*</span></label>
                            <input type="date" id="tanggal_bayar" name="tanggal_bayar" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="bukti_transfer">Upload Bukti Transfer</label>
                        <input type="file" id="bukti_transfer" name="bukti_transfer" accept=".jpg,.jpeg,.png,.pdf" class="file-input">
                    </div>
                </div>

             
                <div class="section">
                    <h3>📎 Upload Dokumen </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ktp">KTP</label>
                            <input type="file" id="ktp" name="ktp" accept=".jpg,.jpeg,.png,.pdf" class="file-input">
                        </div>
                        <div class="form-group">
                            <label for="ijazah">Ijazah / SKL </label>
                            <input type="file" id="ijazah" name="ijazah" accept=".jpg,.jpeg,.png,.pdf" class="file-input">
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="pas_foto">Pas Foto </label>
                        <input type="file" id="pas_foto" name="pas_foto" accept=".jpg,.jpeg,.png" class="file-input">
                    </div>
                </div>

                <!-- Checklist Persetujuan -->
                <div class="checkbox-group">
                    <input type="checkbox" id="data_benar" required>
                    <label for="data_benar">☑️ Data yang saya isi sudah benar dan dapat dipertanggungjawabkan.</label>
                </div>

                <button type="submit" class="btn-submit">Kirim Daftar Ulang</button>
            </form>

            <a href="dashboard-mahasiswa.php" class="btn-kembali">← Kembali ke Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>