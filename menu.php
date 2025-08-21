<a href="master/obat_list.php">Data Obat</a> | 
<a href="master/obat_masuk_list.php">Obat Masuk</a> | 
<a href="master/obat_keluar_list.php">Obat Keluar</a>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menu Navigasi</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .navbar {
            background-color: #2c3e50;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .navbar a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 14px 20px;
            display: block;
            transition: background 0.3s;
        }
        .navbar a:hover { background-color: #34495e; }
        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #34495e;
            min-width: 180px;
            top: 100%;
            left: 0;
            z-index: 1;
        }
        .dropdown-content a {
            padding: 10px 20px;
        }
        .dropdown:hover .dropdown-content { display: block; }
        .navbar-right { margin-left: auto; display: flex; align-items: center; }
        .logout-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 10px;
        }
        .logout-btn:hover { background-color: #c0392b; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="../index.php">🏠 Dashboard</a>

        <div class="dropdown">
            <a href="#">📦 Master</a>
            <div class="dropdown-content">
                <a href="../master/obat.php">Data Obat</a>
                <a href="../master/sumber.php">Data Sumber</a>
                <a href="../master/kelompok.php">Data Kelompok</a>
                <a href="../master/mapping_obat.php">Mapping Obat</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#">📝 Transaksi</a>
            <div class="dropdown-content">
                <a href="../transaksi/obat_masuk.php">Obat Masuk</a>
                <a href="../transaksi/obat_keluar.php">Obat Keluar</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="../laporan/laporan_menu.php">📊 Laporan</a>
        </div>

        <div class="navbar-right">
            <span style="color:#ecf0f1;">Hi, <?php echo $_SESSION['username']; ?></span>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
