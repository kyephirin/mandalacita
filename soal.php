<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/koneksi.php';

if (!isset($_SESSION['no_pendaftaran'])) {
    header('Location: login.php');
    exit;
}

$no_pendaftaran = $_SESSION['no_pendaftaran'];

// Ambil semua soal
$soal = $conn->query("SELECT * FROM soal ORDER BY id");
$totalSoal = $soal->num_rows;

if ($totalSoal == 0) {
    die("<h3 style='color:red; text-align:center; margin-top:50px;'>Soal ujian belum tersedia. Silakan hubungi admin.</h3>");
}

// Ambil jawaban yang sudah ada
$jawaban = [];
$res = $conn->query("SELECT soal_id, jawaban FROM ujian WHERE no_pendaftaran='$no_pendaftaran'");
while ($row = $res->fetch_assoc()) {
    $jawaban[$row['soal_id']] = $row['jawaban'];
}

// Jika belum ada jawaban, inisialisasi
if (empty($jawaban)) {
    $soal->data_seek(0);
    while ($row = $soal->fetch_assoc()) {
        $conn->query("INSERT INTO ujian (no_pendaftaran, soal_id, jawaban) VALUES ('$no_pendaftaran', '{$row['id']}', NULL)");
        $jawaban[$row['id']] = NULL;
    }
    $soal->data_seek(0);
}

// Ambil ID soal
$soal_ids = [];
$soal->data_seek(0);
while ($row = $soal->fetch_assoc()) {
    $soal_ids[] = $row['id'];
}

$current_index = 0;
if (isset($_GET['soal'])) {
    $current_index = array_search($_GET['soal'], $soal_ids);
    if ($current_index === false) $current_index = 0;
}
$current_soal_id = $soal_ids[$current_index];
$current_soal_data = $conn->query("SELECT * FROM soal WHERE id=$current_soal_id")->fetch_assoc();

// Simpan jawaban
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['jawaban'])) {
    $jawaban_input = $_POST['jawaban'];
    $soal_id = $_POST['soal_id'];
    $conn->query("UPDATE ujian SET jawaban='$jawaban_input' WHERE no_pendaftaran='$no_pendaftaran' AND soal_id='$soal_id'");
    header("Location: soal.php?soal=$soal_id");
    exit;
}

// Submit ujian
if (isset($_POST['submit_ujian'])) {
    $benar = 0;
    $soal->data_seek(0);
    while ($row = $soal->fetch_assoc()) {
        if (isset($jawaban[$row['id']]) && $jawaban[$row['id']] == $row['jawaban']) {
            $benar++;
        }
    }
    $nilai = round(($benar / $totalSoal) * 100, 2);
    $conn->query("UPDATE camaba SET nilai='$nilai' WHERE no_pendaftaran='$no_pendaftaran'");
    header('Location: hasil.php');
    exit;
}

$terjawab_count = 0;
foreach ($jawaban as $j) if (!is_null($j)) $terjawab_count++;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Seleksi - PMB Mandala Cita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .header h2 {
            font-size: 1.5rem;
            font-weight: 500;
        }
        .timer {
            background-color: #e67e22;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 30px;
        }
        .main {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .sidebar {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        .info {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .question {
            font-size: 1.2rem;
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .options {
            list-style: none;
            margin-bottom: 30px;
        }
        .option {
            margin-bottom: 12px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
        }
        .option:hover {
            background: #f5f5f5;
            border-color: #b0b0b0;
        }
        .option.selected {
            background: #d4e6f1;
            border-color: #2c3e50;
        }
        .option input[type="radio"] {
            margin-right: 15px;
            transform: scale(1.2);
        }
        .option label {
            flex: 1;
            cursor: pointer;
        }
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn {
            background-color: #2c3e50;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition: 0.2s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }
        .btn-submit {
            background-color: #27ae60;
            width: 100%;
            margin-top: 15px;
        }
        .btn-submit:hover {
            background-color: #2ecc71;
        }
        .indicator-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin: 15px 0;
        }
        .indicator {
            display: block;
            padding: 8px;
            text-align: center;
            background: #ecf0f1;
            border-radius: 5px;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: 0.2s;
        }
        .indicator.current {
            background: #2c3e50;
            color: white;
        }
        .indicator.answered {
            background: #27ae60;
            color: white;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background: #2c3e50;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Ujian Seleksi PMB Mandala Cita</h2>
        <div class="timer" id="timer">02:00:00</div>
    </div>

    <div class="container">
        <div class="main">
            <div class="info">
                <p>Nomor Tes: <strong><?= htmlspecialchars($no_pendaftaran) ?></strong> | Nama: <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></p>
                <p>Soal <?= $current_index+1 ?> dari <?= $totalSoal ?> | Terjawab: <?= $terjawab_count ?></p>
            </div>

            <form method="POST" id="soalForm">
                <div class="question">
                    <?= htmlspecialchars($current_soal_data['pertanyaan']) ?>
                </div>

                <div class="options">
                    <?php
                    $opsi = ['A','B','C','D','E'];
                    foreach ($opsi as $huruf):
                        $field = 'opsi_'.strtolower($huruf);
                        $teks = $current_soal_data[$field] ?? '';
                        $checked = (isset($jawaban[$current_soal_id]) && $jawaban[$current_soal_id] == $huruf) ? 'checked' : '';
                    ?>
                    <div class="option <?= $checked ? 'selected' : '' ?>" onclick="this.querySelector('input[type=radio]').click();">
                        <input type="radio" name="jawaban" value="<?= $huruf ?>" <?= $checked ?> required onchange="this.form.submit();">
                        <label><?= $huruf ?>. <?= htmlspecialchars($teks) ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>

                <input type="hidden" name="soal_id" value="<?= $current_soal_id ?>">
            </form>

            <div class="nav-buttons">
                <?php if ($current_index > 0): ?>
                    <a href="soal.php?soal=<?= $soal_ids[$current_index-1] ?>" class="btn">← Sebelumnya</a>
                <?php else: ?>
                    <button class="btn" disabled>← Sebelumnya</button>
                <?php endif; ?>

                <?php if ($current_index < $totalSoal-1): ?>
                    <a href="soal.php?soal=<?= $soal_ids[$current_index+1] ?>" class="btn">Selanjutnya →</a>
                <?php else: ?>
                    <button class="btn" disabled>Selanjutnya →</button>
                <?php endif; ?>
            </div>

            <form method="POST">
                <button type="submit" name="submit_ujian" value="1" class="btn btn-submit">Kumpulkan Jawaban</button>
            </form>
        </div>

        <div class="sidebar">
            <h3 style="margin-bottom: 15px; text-align: center;">Navigasi Soal</h3>
            <div class="indicator-grid">
                <?php for ($i=0; $i<$totalSoal; $i++):
                    $id = $soal_ids[$i];
                    $kelas = 'indicator';
                    if ($id == $current_soal_id) $kelas .= ' current';
                    elseif (!is_null($jawaban[$id])) $kelas .= ' answered';
                ?>
                    <a href="soal.php?soal=<?= $id ?>" class="<?= $kelas ?>"><?= $i+1 ?></a>
                <?php endfor; ?>
            </div>
            <div style="margin-top: 20px;">
                <p><span style="display:inline-block; width:15px; height:15px; background:#2c3e50; border-radius:3px;"></span> Sedang</p>
                <p><span style="display:inline-block; width:15px; height:15px; background:#27ae60; border-radius:3px;"></span> Dijawab</p>
                <p><span style="display:inline-block; width:15px; height:15px; background:#ecf0f1; border:1px solid #ccc;"></span> Belum</p>
            </div>
        </div>
    </div>

    <div class="footer">
        © 2026 PMB Mandala Cita
    </div>

    <script>
        // Timer sederhana (120 menit = 7200 detik)
        let remainingTime = 7200;
        const timerEl = document.getElementById('timer');
        const timerInterval = setInterval(() => {
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                // Submit ujian via form submit (tambahkan input hidden)
                let form = document.createElement('form');
                form.method = 'POST';
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'submit_ujian';
                input.value = '1';
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
                return;
            }
            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;
            timerEl.textContent = `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
            remainingTime--;
        }, 1000);
    </script>
</body>
</html>