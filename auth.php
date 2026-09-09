<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('getCurrentUser')) {
    function getCurrentUser()
    {
        if (!isLoggedIn()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'nama_lengkap' => $_SESSION['nama_lengkap'],
            'role' => $_SESSION['role'],
            'id_guru' => $_SESSION['id_guru'] ?? null,
            'id_siswa' => $_SESSION['id_siswa'] ?? null
        ];
    }
}

if (!function_exists('hasRole')) {
    function hasRole($roles)
    {
        if (!isLoggedIn()) return false;
        $userRole = $_SESSION['role'];
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        return $userRole === $roles;
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin()
    {
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login.php');
            exit;
        }
    }
}

if (!function_exists('requireRole')) {
    function requireRole($roles)
    {
        requireLogin();
        if (!hasRole($roles)) {
            header('Location: ' . BASE_URL . '/unauthorized.php');
            exit;
        }
    }
}

if (!function_exists('login')) {
    function login($username, $password)
    {
        include dirname(__FILE__) . '/koneksi.php';

       $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id_guru'] = $user['id_guru'];
                $_SESSION['id_siswa'] = $user['id_siswa'];
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('logout')) {
    function logout()
    {
        include dirname(__FILE__) . '/koneksi.php';
        session_destroy();
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

if (!function_exists('getUserAccessiblePages')) {
    function getUserAccessiblePages()
    {
        $role = $_SESSION['role'] ?? 'user';
        $pages = [
            'dashboard' => ['admin', 'kepala', 'user'],
            'siswa/tampil' => ['admin', 'kepala'],
            'siswa/index' => ['admin'],
            'siswa/detail' => ['admin', 'kepala', 'user'],
            'guru/tampil' => ['admin', 'kepala'],
            'guru/index' => ['admin'],
            'guru/detail' => ['admin', 'kepala', 'user'],
            'kelas/tampil' => ['admin', 'kepala'],
            'kelas/index' => ['admin'],
            'kelas/detail' => ['admin', 'kepala', 'user'],
            'ruang/tampil' => ['admin', 'kepala'],
            'ruang/index' => ['admin'],
            'ruang/detail' => ['admin', 'kepala', 'user'],
            'user/tampil' => ['admin'],
            'user/index' => ['admin'],
            'user/detail' => ['admin'],
            'user/edit' => ['admin'],
            'profile' => ['admin', 'kepala', 'user']
        ];

        $accessible = [];
        foreach ($pages as $page => $allowedRoles) {
            if (in_array($role, $allowedRoles)) {
                $accessible[] = $page;
            }
        }
        return $accessible;
    }
}

if (!function_exists('canAccess')) {
    function canAccess($page)
    {
        $accessible = getUserAccessiblePages();
        return in_array($page, $accessible);
    }
}

if (!function_exists('getSidebarMenu')) {
    function getSidebarMenu()
    {
        $role = $_SESSION['role'] ?? 'user';
        $menu = [];

        $menu[] = ['url' => BASE_URL . '/index.php', 'icon' => '🏠', 'label' => 'Dashboard', 'roles' => ['admin', 'kepala', 'user']];

        if (in_array($role, ['admin', 'kepala'])) {
            $menu[] = ['url' => BASE_URL . '/siswa/tampil.php', 'icon' => '👨‍🎓', 'label' => 'Data Siswa', 'roles' => ['admin', 'kepala'], 'has_sub' => true];
            $menu[] = ['url' => BASE_URL . '/siswa/tampil.php', 'icon' => '📋', 'label' => 'Lihat Data Siswa', 'roles' => ['admin', 'kepala'], 'sub' => true];
            if ($role === 'admin') {
                $menu[] = ['url' => BASE_URL . '/siswa/index.php', 'icon' => '➕', 'label' => 'Tambah Data Siswa', 'roles' => ['admin'], 'sub' => true];
            }

            $menu[] = ['url' => BASE_URL . '/guru/tampil.php', 'icon' => '👨‍🏫', 'label' => 'Data Guru', 'roles' => ['admin', 'kepala'], 'has_sub' => true];
            $menu[] = ['url' => BASE_URL . '/guru/tampil.php', 'icon' => '📋', 'label' => 'Lihat Data Guru', 'roles' => ['admin', 'kepala'], 'sub' => true];
            if ($role === 'admin') {
                $menu[] = ['url' => BASE_URL . '/guru/index.php', 'icon' => '➕', 'label' => 'Tambah Data Guru', 'roles' => ['admin'], 'sub' => true];
            }

            $menu[] = ['url' => BASE_URL . '/kelas/tampil.php', 'icon' => '🏫', 'label' => 'Data Kelas', 'roles' => ['admin', 'kepala'], 'has_sub' => true];
            $menu[] = ['url' => BASE_URL . '/kelas/tampil.php', 'icon' => '📋', 'label' => 'Lihat Data Kelas', 'roles' => ['admin', 'kepala'], 'sub' => true];
            if ($role === 'admin') {
                $menu[] = ['url' => BASE_URL . '/kelas/index.php', 'icon' => '➕', 'label' => 'Tambah Data Kelas', 'roles' => ['admin'], 'sub' => true];
            }

            $menu[] = ['url' => BASE_URL . '/ruang/tampil.php', 'icon' => '🚪', 'label' => 'Data Ruangan', 'roles' => ['admin', 'kepala'], 'has_sub' => true];
            $menu[] = ['url' => BASE_URL . '/ruang/tampil.php', 'icon' => '📋', 'label' => 'Lihat Data Ruangan', 'roles' => ['admin', 'kepala'], 'sub' => true];
            if ($role === 'admin') {
                $menu[] = ['url' => BASE_URL . '/ruang/index.php', 'icon' => '➕', 'label' => 'Tambah Data Ruangan', 'roles' => ['admin'], 'sub' => true];
            }
 $menu[] = ['url' => BASE_URL . '/buku/tampil.php', 'icon' => '🚪', 'label' => 'Data buku', 'roles' => ['admin', 'kepala'], 'has_sub' => true];
            if ($role === 'admin') {
                $menu[] = ['url' => BASE_URL . '/buku/index.php', 'icon' => '➕', 'label' => 'Tambah Data buku', 'roles' => ['admin'], 'sub' => true];
            }
            

            if ($role === 'admin') {
                $menu[] = ['url' => BASE_URL . '/user/tampil.php', 'icon' => '👥', 'label' => 'Kelola User', 'roles' => ['admin'], 'has_sub' => true];
                $menu[] = ['url' => BASE_URL . '/user/tampil.php', 'icon' => '📋', 'label' => 'Lihat User', 'roles' => ['admin'], 'sub' => true];
                $menu[] = ['url' => BASE_URL . '/user/index.php', 'icon' => '➕', 'label' => 'Tambah User', 'roles' => ['admin'], 'sub' => true];
            }
        } else {
            $menu[] = ['url' => BASE_URL . '/profile.php', 'icon' => '👤', 'label' => 'Profil Saya', 'roles' => ['user']];
        }

        return array_filter($menu, function ($item) use ($role) {
            if (isset($item['header'])) return true;
            return in_array($role, $item['roles'] ?? []);
        });
    }
}
