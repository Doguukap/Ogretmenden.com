<?php
session_start(); // Oturumu başlat
?>
<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ogretmenden_com";

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Veritabanından araçları çek
$sql = "SELECT * FROM ogretmenden_ilan ORDER BY id DESC"; 
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Uye.css">
    <title>Document</title>
</head>

<body>
    <div class="header-cont">
        <div class="logo-cont"><a href="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Uye.php"><img src="images/Ogretmenden.com Logo.jpeg" alt="" width='100%' height="100%"></a></div>
        <div class="search-cont">
            <input type="text" id="search-input">
            <button id="search-button">Ara</button>
        </div>
        <div class="profil-cont">
            <div class="profil-name">
                <h1><?php echo htmlspecialchars($_SESSION['kullanici_adi']); ?></h1>
            </div>
            <div class="profil-foto"><img src="images\blank-profile-picture-973460_1280.webp" alt=""></div>
        </div>
        <div class="button-cont">
            <form action="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Home.php"><button id="giris">Çıkış Yap</button></form>
        </div>
    </div>
    <div class="content-div">
    <div class="ilan-arama">
                <div class="filter-group">
                    <label for="adres-il">Adres</label>
                    <select id="adres-il" name="il" onchange="updateDistricts()">
                        <option value="" id="iller">İl</option>
                    </select>
                    <select id="adres-ilce" name="ilce" disabled>
                        <option value="" id="ilceler">İlçe</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Fiyat</label>
                    <div class="currency-tabs">
                        <button class="active">TL</button>
                        <button>USD</button>
                        <button>EUR</button>
                        <button>GBP</button>
                    </div>
                    <div class="price-inputs">
                        <input type="number" placeholder="min TL">
                        <input type="number" placeholder="max TL">
                    </div>
                </div>
                <div class="filter-group">
                    <label>Yıl</label>
                    <div class="year-inputs">
                        <input type="text" placeholder="min">
                        <input type="text" placeholder="max">
                    </div>
                </div>
                <div class="filter-group">
                    <label>Km</label>
                    <div class="km-inputs">
                        <input type="text" placeholder="min">
                        <input type="text" placeholder="max">
                    </div>
                </div>
                <div id="filter-button"><button>Ara</button></div>
            </div>

        <div class="ilan-sonuc">
        <table class="listing-table">
                <thead class="listing-header">
                    <tr>
                        <th>Görsel</th>
                        <th>Model</th>
                        <th>Yıl</th>
                        <th>Km</th>
                        <th>Renk</th>
                        <th>Fiyat</th>
                        <th>İlan Tarihi</th>
                        <th>İl/İlçe</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody class="listing-body">
                    <?php
                   if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fotoYolu = !empty($row['foto']) ? 'İlanimages/' . htmlspecialchars($row['foto']) : 'images/placeholder.png';
                        echo "<tr>";
                        echo "<td><img src='" . $fotoYolu . "' alt='Araç Fotoğrafı' width='100' height='100'></td>";
                        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['yıl']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['km']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['renk']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fiyat']) . " TL</td>";
                        echo "<td>" . htmlspecialchars($row['ilan_tarihi']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['il']) . "/" . htmlspecialchars($row['ilce']) . "</td>";
                        echo "<td><button>Seç</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Henüz ilan yok.</td></tr>";
                }
                    ?>
                </tbody>
            </table>
            <div class="ilan-button-cont">
                <form action="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/İlan.php"><button>İlan
                        Ver</button></form>
            </div>
        </div>
    </div>
    <script src="Home.js"></script>
</body>

</html>