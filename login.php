<?php
session_start();
require_once 'config/koneksi.php';

$error = '';
$success = '';

// Ambil pesan sukses dari session (misal dari registrasi)
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_tes = $_POST['nomor_tes'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM camaba WHERE no_pendaftaran='$nomor_tes' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['no_pendaftaran'] = $user['no_pendaftaran'];
        $_SESSION['nama'] = $user['nama'];
        header('Location: dashboard-mahasiswa.php');
        exit;
    } else {
        $error = "Nomor Tes atau Password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PMB Mandala Cita</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
        }
        .sub {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .error {
            background-color: #f8d7da;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #721c24;
        }
        .success {
            background-color: #d4edda;
            border-left: 4px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #155724;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }
        button {
            width: 100%;
            padding: 14px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
        .link {
            text-align: center;
            margin-top: 25px;
            color: #666;
        }
        .link a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 600;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .forgot {
            text-align: right;
            margin-top: 10px;
        }
        .forgot a {
            color: #666;
            font-size: 13px;
            text-decoration: none;
        }
        .forgot a:hover {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Login PMB</h2>
        <div class="sub">Masuk dengan Nomor Tes dan Password</div>
        
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nomor_tes">Nomor Tes</label>
                <input type="text" id="nomor_tes" name="nomor_tes" placeholder="Contoh: PMB2026001" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="link">
            Belum punya akun? <a href="regist.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>