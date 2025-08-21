<?php
session_start();
include 'config.php';

$error = '';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_user, nama, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        // Cek password plain text dulu, jika gagal cek hash
        if($password === $row['password'] || password_verify($password, $row['password'])){
            session_regenerate_id(true);
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['id_user'] = $row['id_user'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas Farmasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Roboto', sans-serif; background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: rgba(255,255,255,0.95); padding: 40px; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); width: 400px; text-align: center; }
        .login-box h2 { margin-bottom: 30px; color: #333; font-weight: 700; }
        input[type=text], input[type=password] { width: 100%; padding: 15px; margin: 15px 0; border-radius: 8px; border: 1px solid #ccc; font-size: 16px; transition: 0.3s; }
        input[type=text]:focus, input[type=password]:focus { border-color: #2575fc; box-shadow: 0 0 8px rgba(37,117,252,0.3); outline: none; }
        button { width: 100%; padding: 15px; background: #2575fc; color: white; border: none; border-radius: 8px; font-size: 18px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #6a11cb; }
        .error { color: #ff4d4d; margin-bottom: 15px; }
        .login-footer { margin-top: 20px; font-size: 14px; color: #555; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Petugas Farmasi</h2>
        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <div class="login-footer">© 2025 Instalasi Farmasi Kab. Lamongan</div>
    </div>
</body>
</html>
