<?php
session_start(); // Oturumu başlat

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ogretmenden_com";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$uploadOk = 1;
$success = false; // Başarılı ilan ekleme kontrolü

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = $_POST['model'];
    $yil = $_POST['yil'];
    $km = $_POST['km'];
    $renk = $_POST['renk'];
    $fiyat = $_POST['fiyat'];
    $ilan_tarihi = $_POST['ilan_tarihi'];
    $il = $_POST['il'];
    $ilce = $_POST['ilce'];

    // Fotoğraf yükleme işlemi
    if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        $targetDir = "İlanimages/";
        $fotoAdi = uniqid() . "-" . basename($foto['name']);
        $targetFile = $targetDir . $fotoAdi;

        // Dosya türü kontrolü
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Sadece JPG, JPEG, PNG & GIF dosyalarına izin verilir.";
            $uploadOk = 0;
        }

        // Dosya boyutu kontrolü
        if ($foto['size'] > 5000000) {
            echo "Dosya boyutu çok büyük.";
            $uploadOk = 0;
        }

        if ($uploadOk === 1) {
            if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
                $sql = "INSERT INTO ogretmenden_ilan (model, yıl, km, renk, fiyat, ilan_tarihi, il, ilce, foto) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("siisdssss", $model, $yil, $km, $renk, $fiyat, $ilan_tarihi, $il, $ilce, $fotoAdi);

                if ($stmt->execute()) {
                    $scriptSrc = "İlan.js"; 
                    $success = true; // İlan başarıyla eklendi
                } else {
                    echo "Hata: " . $stmt->error;
                }
            } else {
                echo "Dosya yüklenirken bir hata oluştu.";
            }
        }
    } else {
        echo "Fotoğraf yüklenmedi veya geçersiz bir dosya.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="İlan.css">
    <title>Yeni İlan Ekle</title>
</head>

<body>
    <div class="header-cont">
        <div class="logo-cont"><a href="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Uye.php"><img
                    src="images/Ogretmenden.com Logo.jpeg" alt="" width='100%' height="100%"></a></div>
        <div class="search-cont">
            <input type="text" id="search-input">
            <button id="search-button">Ara</button>
        </div>
        <div class="profil-cont">
            <div class="profil-name">
                <h1><?php echo htmlspecialchars($_SESSION['kullanici_adi']); ?></h1>
            </div>
            <div class="profil-foto"><img src="images/blank-profile-picture-973460_1280.webp" alt=""></div>
        </div>
        <div class="button-cont">
            <form action="http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Home.php"">
                <button id="giris">Çıkış Yap</button>
            </form>
        </div>
    </div>

    <div class="ilan-cont" id="ilan-cont">
        <h1>Yeni İlan Ekle</h1>
        <form method="post" action="İlan.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>

            <div class="form-group">
                <label for="yil">Yıl:</label>
                <input type="number" id="yil" name="yil" required>
            </div>

            <div class="form-group">
                <label for="km">Km:</label>
                <input type="number" id="km" name="km" required>
            </div>

            <div class="form-group">
                <label for="renk">Renk:</label>
                <input type="text" id="renk" name="renk" required>
            </div>

            <div class="form-group">
                <label for="fiyat">Fiyat:</label>
                <input type="number" id="fiyat" name="fiyat" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="ilan_tarihi">İlan Tarihi:</label>
                <input type="date" id="ilan_tarihi" name="ilan_tarihi" required>
            </div>

            <div class="form-group">
                <label for="il">İl:</label>
                <select id="adres-il" name="il" onchange="updateDistricts()">
                    <option value="" id="iller">İl</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ilce">İlçe:</label>
                <select id="adres-ilce" name="ilce" disabled>
                    <option value="" id="ilceler">İlçe</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file-upload">Fotoğraf Yükle:</label>
                <input type="file" name="foto" id="file-upload" accept="image/*" required>
            </div>


            <button type="submit" class="submit-button" id="ilan-button">İlan Ekle</button>
        </form>
    </div>
    <!-- Script kaynağı PHP tarafından belirleniyor -->
    <script src="<?php echo $scriptSrc; ?>"></script>
    <script>
    const illerVeIlceler = {
        Adana: [
            "Aladağ",
            "Ceyhan",
            "Çukurova",
            "Feke",
            "İmamoğlu",
            "Karaisalı",
            "Karataş",
            "Kozan",
            "Pozantı",
            "Saimbeyli",
            "Sarıçam",
            "Seyhan",
            "Tufanbeyli",
            "Yumurtalık",
            "Yüreğir",
        ],
        Adıyaman: [
            "Besni",
            "Çelikhan",
            "Gerger",
            "Gölbaşı",
            "Kahta",
            "Merkez",
            "Samsat",
            "Sincik",
            "Tut",
        ],
        Afyonkarahisar: [
            "Başmakçı",
            "Bayat",
            "Bolvadin",
            "Çay",
            "Çobanlar",
            "Dazkırı",
            "Dinar",
            "Emirdağ",
            "Evciler",
            "Hocalar",
            "İhsaniye",
            "İscehisar",
            "Kızılören",
            "Sandıklı",
            "Sinanpaşa",
            "Sultandağı",
            "Şuhut",
            "Merkez",
        ],
        Ağrı: [
            "Diyadin",
            "Doğubayazıt",
            "Eleşkirt",
            "Hamur",
            "Patnos",
            "Taşlıçay",
            "Tutak",
            "Merkez",
        ],
        Amasya: [
            "Göynücek",
            "Gümüşhacıköy",
            "Hamamözü",
            "Merzifon",
            "Suluova",
            "Taşova",
            "Merkez",
        ],
        Ankara: [
            "Altındağ",
            "Ayaş",
            "Balâ",
            "Beypazarı",
            "Çamlıdere",
            "Çankaya",
            "Çubuk",
            "Elmadağ",
            "Güdül",
            "Haymana",
            "Kalecik",
            "Kızılcahamam",
            "Nallıhan",
            "Polatlı",
            "Şereflikoçhisar",
            "Yenimahalle",
            "Gölbaşı",
            "Keçiören",
            "Mamak",
            "Sincan",
            "Kazan",
            "Akyurt",
            "Etimesgut",
            "Evren",
            "Pursaklar",
        ],
        Antalya: [
            "Akseki",
            "Alanya",
            "Demre",
            "Döşemealtı",
            "Finike",
            "Gazipaşa",
            "Gündoğmuş",
            "İbradı",
            "Kaş",
            "Kemer",
            "Kepez",
            "Konyaaltı",
            "Korkuteli",
            "Kumluca",
            "Manavgat",
            "Muratpaşa",
            "Serik",
        ],
        Artvin: [
            "Ardanuç",
            "Arhavi",
            "Borçka",
            "Hopa",
            "Murgul",
            "Şavşat",
            "Yusufeli",
            "Merkez",
        ],
        Aydın: [
            "Bozdoğan",
            "Buharkent",
            "Çine",
            "Didim",
            "Efeler",
            "Germencik",
            "İncirliova",
            "Karacasu",
            "Karpuzlu",
            "Koçarlı",
            "Köşk",
            "Kuşadası",
            "Kuyucak",
            "Nazilli",
            "Söke",
            "Sultanhisar",
            "Yenipazar",
        ],
        Balıkesir: [
            "Altıeylül",
            "Ayvalık",
            "Balya",
            "Bandırma",
            "Bigadiç",
            "Burhaniye",
            "Dursunbey",
            "Edremit",
            "Erdek",
            "Gömeç",
            "Gönen",
            "Havran",
            "İvrindi",
            "Karesi",
            "Kepsut",
            "Manyas",
            "Marmara",
            "Savaştepe",
            "Sındırgı",
            "Susurluk",
        ],
        Bilecik: [
            "Bozüyük",
            "Gölpazarı",
            "İnhisar",
            "Osmaneli",
            "Pazaryeri",
            "Söğüt",
            "Yenipazar",
            "Merkez",
        ],
        Bingöl: [
            "Adaklı",
            "Genç",
            "Karlıova",
            "Kiğı",
            "Solhan",
            "Yayladere",
            "Yedisu",
            "Merkez",
        ],
        Bitlis: [
            "Adilcevaz",
            "Ahlat",
            "Güroymak",
            "Hizan",
            "Mutki",
            "Tatvan",
            "Merkez",
        ],
        Bolu: [
            "Dörtdivan",
            "Gerede",
            "Göynük",
            "Kıbrıscık",
            "Mengen",
            "Mudurnu",
            "Seben",
            "Yeniçağa",
            "Merkez",
        ],
        Burdur: [
            "Ağlasun",
            "Altınyayla",
            "Bucak",
            "Çavdır",
            "Çeltikçi",
            "Gölhisar",
            "Karamanlı",
            "Kemer",
            "Tefenni",
            "Yeşilova",
            "Merkez",
        ],
        Bursa: [
            "Büyükorhan",
            "Gemlik",
            "Gürsu",
            "Harmancık",
            "İnegöl",
            "İznik",
            "Karacabey",
            "Keles",
            "Kestel",
            "Mudanya",
            "Mustafakemalpaşa",
            "Nilüfer",
            "Orhaneli",
            "Orhangazi",
            "Osmangazi",
            "Yenişehir",
            "Yıldırım",
        ],
        Çanakkale: [
            "Ayvacık",
            "Bayramiç",
            "Biga",
            "Bozcaada",
            "Çan",
            "Eceabat",
            "Ezine",
            "Gelibolu",
            "Gökçeada",
            "Lapseki",
            "Yenice",
            "Merkez",
        ],
        Çankırı: [
            "Atkaracalar",
            "Bayramören",
            "Çerkeş",
            "Eldivan",
            "Ilgaz",
            "Kızılırmak",
            "Korgun",
            "Kurşunlu",
            "Orta",
            "Şabanözü",
            "Yapraklı",
            "Merkez",
        ],
        Çorum: [
            "Alaca",
            "Bayat",
            "Boğazkale",
            "Dodurga",
            "İskilip",
            "Kargı",
            "Laçin",
            "Mecitözü",
            "Oğuzlar",
            "Ortaköy",
            "Osmancık",
            "Sungurlu",
            "Uğurludağ",
            "Merkez",
        ],
        Denizli: [
            "Acıpayam",
            "Babadağ",
            "Baklan",
            "Bekilli",
            "Beyağaç",
            "Bozkurt",
            "Buldan",
            "Çal",
            "Çameli",
            "Çardak",
            "Çivril",
            "Güney",
            "Honaz",
            "Kale",
            "Merkezefendi",
            "Pamukkale",
            "Sarayköy",
            "Serinhisar",
            "Tavas",
        ],
        Diyarbakır: [
            "Bağlar",
            "Bismil",
            "Çermik",
            "Çınar",
            "Çüngüş",
            "Dicle",
            "Eğil",
            "Ergani",
            "Hani",
            "Hazro",
            "Kayapınar",
            "Kocaköy",
            "Kulp",
            "Lice",
            "Silvan",
            "Sur",
            "Yenişehir",
        ],
        Edirne: [
            "Enez",
            "Havsa",
            "İpsala",
            "Keşan",
            "Lalapaşa",
            "Meriç",
            "Süloğlu",
            "Uzunköprü",
            "Merkez",
        ],
        Elazığ: [
            "Ağın",
            "Alacakaya",
            "Arıcak",
            "Baskil",
            "Karakoçan",
            "Keban",
            "Kovancılar",
            "Maden",
            "Palu",
            "Sivrice",
            "Merkez",
        ],
        Erzincan: [
            "Çayırlı",
            "İliç",
            "Kemah",
            "Kemaliye",
            "Otlukbeli",
            "Refahiye",
            "Tercan",
            "Üzümlü",
            "Merkez",
        ],
        Erzurum: [
            "Aşkale",
            "Aziziye",
            "Çat",
            "Hınıs",
            "Horasan",
            "İspir",
            "Karaçoban",
            "Karayazı",
            "Köprüköy",
            "Narman",
            "Oltu",
            "Olur",
            "Palandöken",
            "Pasinler",
            "Pazaryolu",
            "Şenkaya",
            "Tekman",
            "Tortum",
            "Uzundere",
            "Yakutiye",
        ],
        Eskişehir: [
            "Alpu",
            "Beylikova",
            "Çifteler",
            "Günyüzü",
            "Han",
            "İnönü",
            "Mahmudiye",
            "Mihalgazi",
            "Mihalıççık",
            "Odunpazarı",
            "Sarıcakaya",
            "Seyitgazi",
            "Sivrihisar",
            "Tepebaşı",
        ],
        Gaziantep: [
            "Araban",
            "İslahiye",
            "Karkamış",
            "Nizip",
            "Nurdağı",
            "Oğuzeli",
            "Şahinbey",
            "Şehitkamil",
            "Yavuzeli",
        ],
        Giresun: [
            "Alucra",
            "Bulancak",
            "Çamoluk",
            "Çanakçı",
            "Dereli",
            "Doğankent",
            "Espiye",
            "Eynesil",
            "Görele",
            "Güce",
            "Keşap",
            "Piraziz",
            "Şebinkarahisar",
            "Tirebolu",
            "Yağlıdere",
            "Merkez",
        ],
        Gümüşhane: ["Kelkit", "Köse", "Kürtün", "Şiran", "Torul", "Merkez"],
        Hakkari: ["Çukurca", "Derecik", "Şemdinli", "Yüksekova", "Merkez"],
        Hatay: [
            "Altınözü",
            "Antakya",
            "Arsuz",
            "Belen",
            "Defne",
            "Dörtyol",
            "Erzin",
            "Hassa",
            "İskenderun",
            "Kırıkhan",
            "Kumlu",
            "Payas",
            "Reyhanlı",
            "Samandağ",
            "Yayladağı",
        ],
        Iğdır: ["Aralık", "Karakoyunlu", "Tuzluca", "Merkez"],
        Isparta: [
            "Aksu",
            "Atabey",
            "Eğirdir",
            "Gelendost",
            "Gönen",
            "Keçiborlu",
            "Senirkent",
            "Sütçüler",
            "Şarkikaraağaç",
            "Uluborlu",
            "Yalvaç",
            "Yenişarbademli",
            "Merkez",
        ],
        İstanbul: [
            "Adalar",
            "Arnavutköy",
            "Ataşehir",
            "Avcılar",
            "Bağcılar",
            "Bahçelievler",
            "Bakırköy",
            "Başakşehir",
            "Bayrampaşa",
            "Beşiktaş",
            "Beykoz",
            "Beylikdüzü",
            "Beyoğlu",
            "Büyükçekmece",
            "Çatalca",
            "Çekmeköy",
            "Esenler",
            "Esenyurt",
            "Eyüpsultan",
            "Fatih",
            "Gaziosmanpaşa",
            "Güngören",
            "Kadıköy",
            "Kağıthane",
            "Kartal",
            "Küçükçekmece",
            "Maltepe",
            "Pendik",
            "Sancaktepe",
            "Sarıyer",
            "Silivri",
            "Sultanbeyli",
            "Sultangazi",
            "Şile",
            "Şişli",
            "Tuzla",
            "Ümraniye",
            "Üsküdar",
            "Zeytinburnu",
        ],
        İzmir: [
            "Aliağa",
            "Balçova",
            "Bayındır",
            "Bayraklı",
            "Bergama",
            "Beydağ",
            "Bornova",
            "Buca",
            "Çeşme",
            "Çiğli",
            "Dikili",
            "Foça",
            "Gaziemir",
            "Güzelbahçe",
            "Karabağlar",
            "Karaburun",
            "Karşıyaka",
            "Kemalpaşa",
            "Kınık",
            "Kiraz",
            "Konak",
            "Menderes",
            "Menemen",
            "Narlıdere",
            "Ödemiş",
            "Seferihisar",
            "Selçuk",
            "Tire",
            "Torbalı",
            "Urla",
        ],
        Kahramanmaraş: [
            "Afşin",
            "Andırın",
            "Çağlayancerit",
            "Dulkadiroğlu",
            "Ekinözü",
            "Elbistan",
            "Göksun",
            "Nurhak",
            "Onikişubat",
            "Pazarcık",
            "Türkoğlu",
        ],
        Karabük: ["Eflani", "Eskipazar", "Ovacık", "Safranbolu", "Yenice", "Merkez"],
        Karaman: [
            "Ayrancı",
            "Başyayla",
            "Ermenek",
            "Kazımkarabekir",
            "Sarıveliler",
            "Merkez",
        ],
        Kars: [
            "Akyaka",
            "Arpaçay",
            "Digor",
            "Kağızman",
            "Sarıkamış",
            "Selim",
            "Susuz",
            "Merkez",
        ],
        Kastamonu: [
            "Abana",
            "Ağlı",
            "Araç",
            "Azdavay",
            "Bozkurt",
            "Cide",
            "Çatalzeytin",
            "Daday",
            "Devrekani",
            "Doğanyurt",
            "Hanönü",
            "İhsangazi",
            "İnebolu",
            "Küre",
            "Pınarbaşı",
            "Şenpazar",
            "Seydiler",
            "Taşköprü",
            "Tosya",
            "Merkez",
        ],
        Kayseri: [
            "Akkışla",
            "Bünyan",
            "Develi",
            "Felahiye",
            "Hacılar",
            "İncesu",
            "Kocasinan",
            "Melikgazi",
            "Özvatan",
            "Pınarbaşı",
            "Sarıoğlan",
            "Sarız",
            "Talas",
            "Tomarza",
            "Yahyalı",
            "Yeşilhisar",
        ],
        Kırıkkale: [
            "Bahşılı",
            "Balışeyh",
            "Çelebi",
            "Delice",
            "Karakeçili",
            "Keskin",
            "Sulakyurt",
            "Yahşihan",
            "Merkez",
        ],
        Kırklareli: [
            "Babaeski",
            "Demirköy",
            "Kofçaz",
            "Lüleburgaz",
            "Pehlivanköy",
            "Pınarhisar",
            "Vize",
            "Merkez",
        ],
        Kırşehir: [
            "Akçakent",
            "Akpınar",
            "Boztepe",
            "Çiçekdağı",
            "Kaman",
            "Mucur",
            "Merkez",
        ],
        Kilis: ["Elbeyli", "Musabeyli", "Polateli", "Merkez"],
        Kocaeli: [
            "Başiskele",
            "Çayırova",
            "Darıca",
            "Derince",
            "Dilovası",
            "Gebze",
            "Gölcük",
            "İzmit",
            "Kandıra",
            "Karamürsel",
            "Kartepe",
            "Körfez",
        ],
        Konya: [
            "Ahırlı",
            "Akören",
            "Akşehir",
            "Altınekin",
            "Beyşehir",
            "Bozkır",
            "Çeltik",
            "Cihanbeyli",
            "Çumra",
            "Derbent",
            "Derebucak",
            "Doğanhisar",
            "Emirgazi",
            "Ereğli",
            "Güneysınır",
            "Hadim",
            "Halkapınar",
            "Hüyük",
            "Ilgın",
            "Kadınhanı",
            "Karapınar",
            "Karatay",
            "Kulu",
            "Meram",
            "Sarayönü",
            "Selçuklu",
            "Seydişehir",
            "Taşkent",
            "Tuzlukçu",
            "Yalıhüyük",
            "Yunak",
        ],
        Kütahya: [
            "Altıntaş",
            "Aslanapa",
            "Çavdarhisar",
            "Domaniç",
            "Dumlupınar",
            "Emet",
            "Gediz",
            "Hisarcık",
            "Pazarlar",
            "Şaphane",
            "Simav",
            "Tavşanlı",
            "Merkez",
        ],
        Malatya: [
            "Akçadağ",
            "Arapgir",
            "Arguvan",
            "Battalgazi",
            "Darende",
            "Doğanşehir",
            "Doğanyol",
            "Hekimhan",
            "Kale",
            "Kuluncak",
            "Pütürge",
            "Yazıhan",
            "Yeşilyurt",
        ],
        Manisa: [
            "Ahmetli",
            "Akhisar",
            "Alaşehir",
            "Demirci",
            "Gölmarmara",
            "Gördes",
            "Kırkağaç",
            "Köprübaşı",
            "Kula",
            "Salihli",
            "Sarıgöl",
            "Saruhanlı",
            "Selendi",
            "Soma",
            "Şehzadeler",
            "Turgutlu",
            "Yunusemre",
        ],
        Mardin: [
            "Artuklu",
            "Dargeçit",
            "Derik",
            "Kızıltepe",
            "Mazıdağı",
            "Midyat",
            "Nusaybin",
            "Ömerli",
            "Savur",
            "Yeşilli",
        ],
        Mersin: [
            "Akdeniz",
            "Anamur",
            "Aydıncık",
            "Bozyazı",
            "Çamlıyayla",
            "Erdemli",
            "Gülnar",
            "Mezitli",
            "Mut",
            "Silifke",
            "Tarsus",
            "Toroslar",
            "Yenişehir",
        ],
        Muğla: [
            "Bodrum",
            "Dalaman",
            "Datça",
            "Fethiye",
            "Kavaklıdere",
            "Köyceğiz",
            "Marmaris",
            "Menteşe",
            "Milas",
            "Ortaca",
            "Seydikemer",
            "Ula",
            "Yatağan",
        ],
        Muş: ["Bulanık", "Hasköy", "Korkut", "Malazgirt", "Varto", "Merkez"],
        Nevşehir: [
            "Acıgöl",
            "Avanos",
            "Derinkuyu",
            "Gülşehir",
            "Hacıbektaş",
            "Kozaklı",
            "Ürgüp",
            "Merkez",
        ],
        Niğde: ["Altunhisar", "Bor", "Çamardı", "Çiftlik", "Ulukışla", "Merkez"],
        Ordu: [
            "Akkuş",
            "Altınordu",
            "Aybastı",
            "Çamaş",
            "Çatalpınar",
            "Çaybaşı",
            "Fatsa",
            "Gölköy",
            "Gülyalı",
            "Gürgentepe",
            "İkizce",
            "Kabadüz",
            "Kabataş",
            "Korgan",
            "Kumru",
            "Mesudiye",
            "Perşembe",
            "Ulubey",
            "Ünye",
        ],
        Osmaniye: [
            "Bahçe",
            "Düziçi",
            "Hasanbeyli",
            "Kadirli",
            "Sumbas",
            "Toprakkale",
            "Merkez",
        ],
        Rize: [
            "Ardeşen",
            "Çamlıhemşin",
            "Çayeli",
            "Derepazarı",
            "Fındıklı",
            "Güneysu",
            "Hemşin",
            "İkizdere",
            "İyidere",
            "Kalkandere",
            "Pazar",
            "Merkez",
        ],
        Sakarya: [
            "Adapazarı",
            "Akyazı",
            "Arifiye",
            "Erenler",
            "Ferizli",
            "Geyve",
            "Hendek",
            "Karapürçek",
            "Karasu",
            "Kaynarca",
            "Kocaali",
            "Pamukova",
            "Sapanca",
            "Serdivan",
            "Söğütlü",
            "Taraklı",
        ],
        Samsun: [
            "Alaçam",
            "Asarcık",
            "Atakum",
            "Ayvacık",
            "Bafra",
            "Canik",
            "Çarşamba",
            "Havza",
            "İlkadım",
            "Kavak",
            "Ladik",
            "Salıpazarı",
            "Tekkeköy",
            "Terme",
            "Vezirköprü",
            "Yakakent",
        ],
        Siirt: ["Baykan", "Eruh", "Kurtalan", "Pervari", "Şirvan", "Tillo", "Merkez"],
        Sinop: [
            "Ayancık",
            "Boyabat",
            "Dikmen",
            "Durağan",
            "Erfelek",
            "Gerze",
            "Saraydüzü",
            "Türkeli",
            "Merkez",
        ],
        Sivas: [
            "Akıncılar",
            "Altınyayla",
            "Divriği",
            "Doğanşar",
            "Gemerek",
            "Gölova",
            "Gürün",
            "Hafik",
            "İmranlı",
            "Kangal",
            "Koyulhisar",
            "Merkez",
            "Suşehri",
            "Şarkışla",
            "Ulaş",
            "Yıldızeli",
            "Zara",
        ],
        Şanlıurfa: [
            "Akçakale",
            "Birecik",
            "Bozova",
            "Ceylanpınar",
            "Eyyübiye",
            "Halfeti",
            "Haliliye",
            "Harran",
            "Hilvan",
            "Karaköprü",
            "Siverek",
            "Suruç",
            "Viranşehir",
        ],
        Şırnak: [
            "Beytüşşebap",
            "Cizre",
            "Güçlükonak",
            "İdil",
            "Silopi",
            "Uludere",
            "Merkez",
        ],
        Tekirdağ: [
            "Çerkezköy",
            "Çorlu",
            "Ergene",
            "Hayrabolu",
            "Kapaklı",
            "Malkara",
            "Marmaraereğlisi",
            "Muratlı",
            "Saray",
            "Süleymanpaşa",
            "Şarköy",
        ],
        Tokat: [
            "Almus",
            "Artova",
            "Başçiftlik",
            "Erbaa",
            "Niksar",
            "Pazar",
            "Reşadiye",
            "Sulusaray",
            "Turhal",
            "Yeşilyurt",
            "Zile",
            "Merkez",
        ],
        Trabzon: [
            "Akçaabat",
            "Araklı",
            "Arsin",
            "Beşikdüzü",
            "Çarşıbaşı",
            "Çaykara",
            "Dernekpazarı",
            "Düzköy",
            "Hayrat",
            "Köprübaşı",
            "Maçka",
            "Of",
            "Ortahisar",
            "Sürmene",
            "Şalpazarı",
            "Tonya",
            "Vakfıkebir",
            "Yomra",
        ],
        Tunceli: [
            "Çemişgezek",
            "Hozat",
            "Mazgirt",
            "Nazımiye",
            "Ovacık",
            "Pertek",
            "Pülümür",
            "Merkez",
        ],
        Uşak: ["Banaz", "Eşme", "Karahallı", "Sivaslı", "Ulubey", "Merkez"],
        Van: [
            "Bahçesaray",
            "Başkale",
            "Çaldıran",
            "Çatak",
            "Edremit",
            "Erciş",
            "Gevaş",
            "Gürpınar",
            "İpekyolu",
            "Muradiye",
            "Özalp",
            "Saray",
            "Tuşba",
        ],
        Yalova: ["Altınova", "Armutlu", "Çınarcık", "Çiftlikköy", "Termal", "Merkez"],
        Yozgat: [
            "Akdağmadeni",
            "Aydıncık",
            "Boğazlıyan",
            "Çandır",
            "Çayıralan",
            "Çekerek",
            "Kadışehri",
            "Saraykent",
            "Sarıkaya",
            "Sorgun",
            "Şefaatli",
            "Yenifakılı",
            "Yerköy",
            "Merkez",
        ],
        Zonguldak: [
            "Alaplı",
            "Çaycuma",
            "Devrek",
            "Gökçebey",
            "Kilimli",
            "Kozlu",
            "Merkez",
        ],
    };

    window.onload = function() {
        // İller dropdown'ı doldur
        const illerSelect = document.getElementById("adres-il"); // Doğru ID'yi kullanın
        for (const il in illerVeIlceler) {
            let option = document.createElement("option");
            option.value = il;
            option.textContent = il;
            illerSelect.appendChild(option);
        }
    };

    // İl seçildiğinde ilçeleri güncelleyen fonksiyon
    function updateDistricts() {
        const ilcelerSelect = document.getElementById("adres-ilce"); // Doğru ID'yi kullanın
        const selectedIl = document.getElementById("adres-il").value; // Doğru ID'yi kullanın

        // Önce mevcut ilçeleri temizleyin
        ilcelerSelect.innerHTML = '<option value="">İlçe seçiniz</option>';

        if (selectedIl) {
            const ilceler = illerVeIlceler[selectedIl];
            ilceler.forEach((ilce) => {
                let option = document.createElement("option");
                option.value = ilce;
                option.textContent = ilce;
                ilcelerSelect.appendChild(option);
            });
            ilcelerSelect.disabled = false; // İlçeler dropdown'ını etkinleştir
        } else {
            ilcelerSelect.disabled = true; // İlçeler dropdown'ını devre dışı bırak
        }
    };
    </script>
</body>

</html>