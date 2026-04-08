<?php
session_start();
require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $password = md5($_POST['password']); // sementara
    $jurusan = $_POST['jurusan'];

    // Cek email atau no hp sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM camaba WHERE email = ? OR no_hp = ?");
    $stmt->bind_param("ss", $email, $no_hp);
    $stmt->execute();
    $cek = $stmt->get_result();

    if ($cek->num_rows > 0) {
        $_SESSION['error'] = "Email atau No. HP sudah terdaftar.";
        header('Location: regist.php');
        exit;
    } else {
        // Generate nomor pendaftaran unik
        $no_pendaftaran = 'PMB' . date('Y') . rand(1000, 9999);

        $stmt = $conn->prepare("INSERT INTO camaba (no_pendaftaran, nama, email, no_hp, password, jurusan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $no_pendaftaran, $nama, $email, $no_hp, $password, $jurusan);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registrasi berhasil! Nomor Tes Anda: <strong>$no_pendaftaran</strong>. Silakan login.";
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['error'] = "Gagal registrasi: " . $stmt->error;
            header('Location: regist.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - PMB Mandala Cita</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:linear-gradient(135deg,#2c3e50,#2c3e50); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px; }
        .regist-card { background:white; border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,0.2); width:100%; max-width:500px; padding:40px; }
        h2 { color:#2c3e50; margin-bottom:10px; text-align:center; font-size:28px; }
        .sub { text-align:center; color:#666; margin-bottom:25px; font-size:14px; }
        .info { background-color:#e3f2fd; border-left:4px solid #2c3e50; padding:15px; border-radius:5px; margin-bottom:25px; font-size:14px; color:#2c3e50; }
        .error { background-color:#f8d7da; border-left:4px solid #e74c3c; padding:15px; border-radius:5px; margin-bottom:25px; font-size:14px; color:#721c24; }
        .success { background-color:#d4edda; border-left:4px solid #4caf50; padding:15px; border-radius:5px; margin-bottom:25px; font-size:14px; color:#155724; }
        .form-group { margin-bottom:20px; }
        label { display:block; margin-bottom:5px; font-weight:600; color:#555; font-size:14px; }
        input, select { width:100%; padding:12px 15px; border:1px solid #ddd; border-radius:5px; font-size:15px; transition:border-color 0.3s; }
        input:focus, select:focus { outline:none; border-color:#2c3e50; box-shadow:0 0 0 3px rgba(52,152,219,0.1); }
        button { width:100%; padding:14px; background:#4caf50; color:white; border:none; border-radius:5px; font-size:16px; font-weight:600; cursor:pointer; transition:background-color 0.3s; margin-top:10px; }
        button:hover { background:#45a049; }
        .link { text-align:center; margin-top:25px; color:#666; }
        .link a { color:#2c3e50; text-decoration:none; font-weight:600; }
        .link a:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="regist-card">
        <h2>Registrasi PMB</h2>
        <div class="sub">Universitas Mandala Cita</div>
        
        <div class="info">
            <strong>ℹ️ Info:</strong> Setelah registrasi, Anda akan mendapatkan Nomor Tes.
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="no_hp">No. WhatsApp</label>
                <input type="text" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            
            <div class="form-group">
                <label for="jurusan">Pilihan Jurusan</label>
                <select id="jurusan" name="jurusan" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <option value="Informatika">Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Manajemen">Manajemen</option>
                </select>
            </div>
            
            <button type="submit">Daftar Sekarang</button>
        </form>
        
        <div class="link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>