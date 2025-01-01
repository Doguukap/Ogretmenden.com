<?php
// Kayıt.php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ogretmenden_com";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullaniciAdi = $_POST['kullanici_adi'];
    $sifre = $_POST['password'];
    $sifreTekrar = $_POST['confirm_password']; // Confirm password field

    // Check if password length is less than 8
    if (strlen($sifre) < 8) {
        echo "<script>window.alert('Şifre uzunluğu en az 8 olmalı!');</script>";
    } 
    // Check if passwords match
    elseif ($sifre !== $sifreTekrar) {
        echo "<script>window.alert('Şifreler uyuşmuyor!');</script>";
    } else {
        // If passwords are valid and match, insert into the database
        $sql = "INSERT INTO Kullanıcılar (kullanıcı_adı, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $kullaniciAdi, $sifre);

        if ($stmt->execute()) {
            header("Location: Giris.php");
            exit(); // Prevent further processing after redirection
        } else {
            echo "Hata: " . $stmt->error;
        }
    }
}
$conn->close();
?>




<!DOCTYPE html>
<html>

<head>
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="Kayıt.css">
</head>

<body>
    <div class="container">
        <div class="logo-cont">
            <div class="img-cont"><a href="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Home.php?"><img src="images\Ogretmenden.com Logo.jpeg" alt=""></a></div>
        </div>
        <div class="form-container">
            <div class="form-item">
                <form method="post" action="">
                    <h1>Kayıt Ol</h1>
                    <input type="Kullanıcı Adı" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
                    <input type="Şifre" placeholder="Şifre" name="password" required>
                    <input type="Şifre Tekrar" placeholder="Şifre Tekrar"name="confirm_password" required>
                    <button>Kayıt Ol</button>
                    <p>Zaten hesabın var mı? <a href="Giris.php">Giriş yap</a></p>
                    <div class="divider"></div>
            </div>
        </div>
        </form>
    </div>
    </div>
</body>

</html>