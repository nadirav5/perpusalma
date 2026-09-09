<?php
include 'koneksi.php';
include 'auth.php';
requireLogin();

$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';
    
    if ($currentPass && $newPass && $confirmPass) {
        $stmt = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $dbUser = $result->fetch_assoc();
        
        if (password_verify($currentPass, $dbUser['password'])) {
            if ($newPass === $confirmPass) {
                $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hashedPass, $user['id']);
                if ($stmt->execute()) {
                    $success = 'Password berhasil diubah!';
                } else {
                    $error = 'Gagal mengubah password.';
                }
            } else {
                $error = 'Konfirmasi password tidak cocok.';
            }
        } else {
            $error = 'Password saat ini salah.';
        }
    }
}

$profileData = null;
if ($user['role'] === 'user') {
    if ($user['id_guru']) {
        $stmt = $koneksi->prepare("SELECT * FROM dataguru WHERE id = ?");
        $stmt->bind_param("i", $user['id_guru']);
        $stmt->execute();
        $profileData = $stmt->get_result()->fetch_assoc();
        $profileData['type'] = 'guru';
    } elseif ($user['id_siswa']) {
        $stmt = $koneksi->prepare("SELECT s.*, k.namakelas FROM datasiswa s LEFT JOIN datakelas k ON s.id_kelas = k.id_kelas WHERE s.id = ?");
        $stmt->bind_param("i", $user['id_siswa']);
        $stmt->execute();
        $profileData = $stmt->get_result()->fetch_assoc();
        $profileData['type'] = 'siswa';
    }
}

include __DIR__ . '/layout/header.php';
?>

<h2>Profil Saya</h2>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 20px;">
    <div class="card">
        <h3>Informasi Akun</h3>
        <table style="width: 100%;">
            <tr><td><strong>Username</strong></td><td><?= htmlspecialchars($user['username']) ?></td></tr>
            <tr><td><strong>Nama Lengkap</strong></td><td><?= htmlspecialchars($user['nama_lengkap']) ?></td></tr>
            <tr><td><strong>Role</strong></td><td>
                <span style="padding: 3px 10px; border-radius: 3px; background: 
                    <?= $user['role'] === 'admin' ? '#e74c3c' : ($user['role'] === 'kepala' ? '#f39c12' : '#27ae60') ?>; 
                    color: white; font-size: 12px;">
                    <?= ucfirst($user['role']) ?>
                </span>
            </td></tr>
        </table>
    </div>
    
    <div class="card">
        <h3>Ubah Password</h3>
        <?php if ($error): ?>
            <div style="background: #fadbd8; color: #c0392b; padding: 10px; border-radius: 4px; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div style="background: #d5f5e3; color: #27ae60; padding: 10px; border-radius: 4px; margin-bottom: 15px;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div style="margin-bottom: 15px;">
                <label>Password Saat Ini</label><br>
                <input type="password" name="current_password" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Password Baru</label><br>
                <input type="password" name="new_password" required minlength="6" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Konfirmasi Password Baru</label><br>
                <input type="password" name="confirm_password" required minlength="6" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" class="btn">Ubah Password</button>
        </form>
    </div>
</div>

<?php if ($profileData): ?>
<div class="card" style="margin-top: 30px;">
    <h3>Data <?= $profileData['type'] === 'guru' ? 'Guru' : 'Siswa' ?></h3>
    <table style="width: 100%;">
        <?php if ($profileData['type'] === 'guru'): ?>
            <tr><td><strong>NIP</strong></td><td><?= htmlspecialchars($profileData['nip']) ?></td></tr>
            <tr><td><strong>Mata Pelajaran</strong></td><td><?= htmlspecialchars($profileData['mapel']) ?></td></tr>
            <tr><td><strong>Alamat</strong></td><td><?= htmlspecialchars($profileData['alamat']) ?></td></tr>
        <?php else: ?>
            <tr><td><strong>Kelas</strong></td><td><?= htmlspecialchars($profileData['namakelas'] ?? '-') ?></td></tr>
            <tr><td><strong>Status</strong></td><td>
                <span style="padding: 3px 10px; border-radius: 3px; background: 
                    <?= $profileData['status'] === 'aktif' ? '#27ae60' : '#e74c3c' ?>; 
                    color: white; font-size: 12px;">
                    <?= ucfirst($profileData['status']) ?>
                </span>
            </td></tr>
            <tr><td><strong>Tanggal Lahir</strong></td><td><?= htmlspecialchars($profileData['tanggal_lahir']) ?></td></tr>
            <tr><td><strong>Alamat</strong></td><td><?= htmlspecialchars($profileData['alamat']) ?></td></tr>
        <?php endif; ?>
    </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>