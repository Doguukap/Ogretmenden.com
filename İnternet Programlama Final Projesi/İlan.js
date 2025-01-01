const ilanContainer = document.getElementById("ilan-cont");

if (ilanContainer) {
  // Mevcut içeriği temizle
  ilanContainer.innerHTML = "";

  // Yeni bir paragraf oluştur
  const p = document.createElement("p");
  const button = document.createElement("button");
  button.textContent = "Ana Sayfa";
  // Buton oluşturma ve stillerini ayarlama
  button.style.padding = "10px 20px";
  button.style.border = "none";
  button.style.backgroundColor = "var(--koyumavi)";
  button.style.color = "var(--beyaz)";
  button.style.borderRadius = "5px";
  button.style.marginLeft = "410px";
  button.style.cursor = "pointer";
  button.id = "anasayfa-button";
  

  // Paragraf oluşturma ve içeriğini ayarlama

  p.textContent = "İlan Başarıyla Oluşturuldu.";
  p.style.fontFamily ="Ariel"
  p.style.fontSize = "larger"
  p.style.textAlign = "center"

  ilanContainer.appendChild(p);
  ilanContainer.appendChild(button);
} else {
  console.error("ilan-cont öğesi bulunamadı.");
}

const anasayfaButton = document.getElementById("anasayfa-button");

if (anasayfaButton) {
  anasayfaButton.addEventListener("click", function () {
    // Ana sayfaya yönlendirme
    window.location.href =
      "http://localhost/%C4%B0nternet%20Programlama%20Final%20Projesi/Uye.php"; // Ana sayfanın dosya yolu
  });
} else {
  console.error("anasayfa-button öğesi bulunamadı.");
}

