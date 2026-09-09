<?php
include dirname(__DIR__) . '/koneksi.php';
include dirname(__DIR__) . '/auth.php';

if (!isset($judul)) {
    $judul = 'PERPUS ALMA';
}

if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user = getCurrentUser();
$sidebarMenu = getSidebarMenu();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul); ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #2c3e50;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
            transition: width 0.3s ease, transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar.collapsed h3 {
            font-size: 0;
            padding: 15px 10px;
        }

        .sidebar.collapsed h3::after {
            content: 'AS';
            font-size: 14px;
        }

        .sidebar.collapsed .sidebar-menu-text {
            display: none;
        }

        .sidebar.collapsed .has-sub>a::after {
            display: none;
        }

        .sidebar.collapsed .sub {
            position: absolute;
            left: 60px;
            top: 0;
            background: #2c3e50;
            min-width: 200px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 1001;
        }

        .sidebar.collapsed .has-sub:hover .sub,
        .sidebar.collapsed .has-sub:focus-within .sub {
            display: block !important;
        }

        .sidebar.collapsed .sub a {
            padding: 10px 20px;
            white-space: nowrap;
        }

        .sidebar.collapsed .sub a .sidebar-menu-text {
            display: inline !important;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 10px;
        }

        .sidebar-version {
            font-size: 12px;
            color: #95a5a6;
            font-weight: 600;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-version {
            display: none;
        }

        .sidebar-toggle {
            background: transparent;
            color: #ecf0f1;
            border: none;
            border-radius: 4px;
            width: 36px;
            height: 36px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            line-height: 1;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            background: #34495e;
        }

        .sidebar.collapsed .sidebar-toggle {
            margin-left: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 10px 0;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 11px 20px;
            color: #ecf0f1;
            text-decoration: none;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar ul li a .icon {
            margin-right: 10px;
            font-size: 16px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #34495e;
        }

        .sidebar ul li.sub {
            display: none;
        }

        .sidebar ul li.sub.show {
            display: block;
        }

        .sidebar ul li.sub a {
            padding: 8px 20px 8px 50px;
            font-size: 13px;
            color: #bdc3c7;
        }

        .sidebar ul li.has-sub>a::after {
            content: ' ▼';
            margin-left: auto;
            font-size: 10px;
            transition: transform 0.2s;
        }

        .sidebar ul li.has-sub.open>a::after {
            transform: rotate(180deg);
        }

        .sidebar-header-divider {
            height: 1px;
            background: #34495e;
            margin: 5px 10px;
        }

        .sidebar-menu-header {
            padding: 10px 20px;
            font-size: 11px;
            text-transform: uppercase;
            color: #95a5a6;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .user-info {
            padding: 15px 10px;
            border-top: 1px solid #34495e;
            margin-top: 10px;
        }

        .user-info .user-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .user-info .user-role {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 3px;
            display: inline-block;
            text-transform: uppercase;
        }

        .user-info .user-role.admin {
            background: #e74c3c;
        }

        .user-info .user-role.kepala {
            background: #f39c12;
        }

        .user-info .user-role.user {
            background: #27ae60;
        }

        .sidebar.collapsed .user-info {
            text-align: center;
        }

        .sidebar.collapsed .user-info .user-name {
            font-size: 0;
        }

        .sidebar.collapsed .user-info .user-name::after {
            content: attr(data-initial);
            font-size: 12px;
        }

        .sidebar.collapsed .user-info .user-role {
            font-size: 10px;
            padding: 1px 6px;
        }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: transparent;
            border: 1px solid #34495e;
            color: #ecf0f1;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            text-align: center;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: #34495e;
        }

        .sidebar.collapsed .logout-btn span {
            display: none;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: #f4f4f4;
            border-bottom: 1px solid #ddd;
            margin: -25px -25px 20px -25px;
        }

        .top-bar-left {
            flex: 1;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .top-user-name {
            font-weight: 500;
            color: #2c3e50;
            font-size: 14px;
        }

        .top-user-role {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 3px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .top-user-role.admin {
            background: #fadbd8;
            color: #c0392b;
        }

        .top-user-role.kepala {
            background: #fef9e7;
            color: #f39c12;
        }

        .top-user-role.user {
            background: #d5f5e3;
            color: #27ae60;
        }

        .top-app-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .sidebar.collapsed~.content {
            margin-left: 60px;
        }

        .content {
            margin-left: 230px;
            padding: 30px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        /* .card removed */
        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 9px 12px;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background: #34495e;
            color: #fff;
        }

        label {
            font-weight: bold;
            font-size: 14px;
        }

        input[type=text],
        input[type=date],
        input[type=number],
        input[type=file],
        select {
            padding: 8px;
            width: 100%;
            max-width: 400px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: #fff;
            border: 0;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-success {
            background: #27ae60;
        }

        .btn-success:hover {
            background: #219653;
        }

        .btn-warning {
            background: #f39c12;
        }

        .btn-warning:hover {
            background: #e67e22;
        }
    </style>
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-version"> AS V.1.7</span>
            <button class="sidebar-toggle" id="sidebarToggle" title="Tutup/Buka Menu">☰</button>
        </div>
        <ul>
            <?php foreach ($sidebarMenu as $item): ?>
                <?php if (isset($item['header'])): ?>
                    <li class="sidebar-menu-header"><?= htmlspecialchars($item['header']) ?></li>
                    <li>
                        <div class="sidebar-header-divider"></div>
                    </li>
                <?php else: ?>
                    <?php
                    $cssClass = '';
                    if (isset($item['has_sub'])) $cssClass .= ' has-sub';
                    if (isset($item['sub'])) $cssClass .= ' sub';
                    $active = '';
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    $currentDir = basename(dirname($_SERVER['PHP_SELF']));
                    $itemPage = basename($item['url']);
                    if (
                        $currentPage === $itemPage ||
                        ($currentDir === 'siswa' && strpos($item['url'], 'siswa') !== false) ||
                        ($currentDir === 'guru' && strpos($item['url'], 'guru') !== false) ||
                        ($currentDir === 'kelas' && strpos($item['url'], 'kelas') !== false) ||
                        ($currentDir === 'ruang' && strpos($item['url'], 'ruang') !== false)||
                        ($currentDir === 'buku' && strpos($item['url'], 'buku') !== false) 
                    ) {
                        $active = ' active';
                    }
                    ?>
                    <li class="<?= $cssClass . $active ?>">
                        <a href="<?= $item['url'] ?>">
                            <span class="icon"><?= $item['icon'] ?></span>
                            <span class="sidebar-menu-text"><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </aside>
    <main class="content" id="content">
        <?php if ($user): ?>
            <div class="top-bar">
                <div class="top-bar-left">
                    <h1 class="top-app-title">PERPUS ALMA</h1>
                </div>
                <div class="top-bar-right">
                    <span class="top-user-name"><?= htmlspecialchars($user['nama_lengkap']) ?></span>
                    <span class="top-user-role <?= $user['role'] ?>">
                        <?php
                        $roleLabels = ['admin' => 'Admin', 'kepala' => 'Kepala Sekolah', 'user' => 'User'];
                        echo $roleLabels[$user['role']] ?? ucfirst($user['role']);
                        ?>
                    </span>
                    <form method="POST" action="<?= BASE_URL; ?>/logout.php" style="margin: 0;">
                        <button type="submit" class="btn btn-danger btn-sm" title="Logout">⏻</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <script>
            document.querySelectorAll('.sidebar .has-sub > a').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    var sidebar = document.getElementById('sidebar');
                    if (sidebar.classList.contains('collapsed')) {
                        return;
                    }
                    e.preventDefault();
                    var parent = this.parentElement;
                    parent.classList.toggle('open');
                    var subs = [];
                    var next = parent.nextElementSibling;
                    while (next && next.classList.contains('sub')) {
                        subs.push(next);
                        next = next.nextElementSibling;
                    }
                    subs.forEach(function(sub) {
                        sub.classList.toggle('show');
                    });
                });
            });
            var sidebar = document.getElementById('sidebar');
            var toggleBtn = document.getElementById('sidebarToggle');
            var content = document.getElementById('content');
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                toggleBtn.innerHTML = '☰';
                toggleBtn.title = 'Buka Menu';
            }
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                var isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                if (isCollapsed) {
                    toggleBtn.innerHTML = '☰';
                    toggleBtn.title = 'Buka Menu';
                } else {
                    toggleBtn.innerHTML = '☰';
                    toggleBtn.title = 'Tutup Menu';
                }
            });
        </script>