<?php
// Giris.php
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

    // Prepare SQL query to check for the user
    $sql = "SELECT * FROM Kullanıcılar WHERE kullanıcı_adı=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $kullaniciAdi, $sifre);
    $stmt->execute();
    $result = $stmt->get_result();

    session_start(); // Oturum başlat
    if ($result->num_rows > 0) {
        $_SESSION['kullanici_adi'] = $kullaniciAdi; // Kullanıcı adını oturuma kaydet
        echo "Giriş başarılı!";
        header("Location: Uye.php"); // Giriş başarılıysa Uye.php'ye yönlendir
        exit();
    } else {
        // If no user found, show an error message
        echo "<script>window.alert('Kullanıcı adı veya şifre hatalı!');</script>";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="Giris.css">
</head>

<body>
    <div class="container">
        <div class="logo-cont">
            <div class="img-cont"><a href="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Home.php?"><img src="images\Ogretmenden.com Logo.jpeg" alt=""></a></div>
        </div>
        <div class="form-container">
            <div class="form-item">
                <form method="post" action="">
                    <h1>Giris Yap</h1>
                    <input type="Kullanıcı Adı" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
                    <input type="Şifre" placeholder="Şifre" name="password" required>

                    <button>Giris Yap</button>
                    <p>Henüz hesabın yok mu? <a href="Kayıt.php">Kayıt Ol</a></p>
                    <div class="divider"></div>
            </div>
        </div>
        </form>
    </div>
    </div>
</body>

</html>