# SHA256 Şifre Oluşturucu ve Yönetim Paneli

Bu proje, PHP ve MySQL kullanılarak geliştirilmiş, modern bir web arayüzüne sahip basit bir şifre otomasyonu uygulamasıdır. Kullanıcıların metin tabanlı şifrelerini SHA256 algoritması ile hash'lemesine, bu kayıtları saklamasına, görüntülemesine, güncellemesine ve silmesine olanak tanır.

Uygulama, dinamik ve kullanıcı dostu bir deneyim sunmak için AJAX teknolojisinden faydalanır, böylece sayfa yeniden yüklenmeden tüm işlemler gerçekleştirilebilir.

## ✨ Özellikler

- **SHA256 Hash Oluşturma:** Girdiğiniz metinlerin anında SHA256 özetini oluşturur.
- **CRUD İşlemleri:**
    - **Kaydetme (Create):** Oluşturulan hash'leri ilişkili e-posta ve platform bilgisiyle veritabanına kaydeder.
    - **Listeleme (Read):** Tüm kayıtları modern bir tabloda görüntüler.
    - **Güncelleme (Update):** Mevcut kayıtları anında düzenler.
    - **Silme (Delete):** İstenmeyen kayıtları tek tıkla kaldırır.
- **Modern Arayüz:** Bootstrap 5 ile geliştirilmiş temiz, duyarlı ve mobil uyumlu tasarım.
- **Dinamik İşlemler:** jQuery ve AJAX sayesinde sayfa yenilemeden akıcı bir kullanıcı deneyimi.
- **Güvenlik:** SQL Injection ve XSS (Siteler Arası Komut Dosyası Çalıştırma) gibi temel web zafiyetlerine karşı önlemler alınmıştır.

## 🛠️ Kullanılan Teknolojiler

- **Arka Uç (Backend):** PHP
- **Veritabanı (Database):** MySQL
- **Ön Yüz (Frontend):** HTML5, CSS3, JavaScript
- **Kütüphaneler:**
    - [Bootstrap 5](https://getbootstrap.com/) - Duyarlı ve modern arayüz için.
    - [jQuery](https://jquery.com/) - DOM manipülasyonu ve AJAX işlemleri için.
    - [Font Awesome](https://fontawesome.com/) - İkonlar için.

## 📂 Proje Yapısı

```
/
├── db/
│   └── schema.sql         # Veritabanı ve tablo oluşturma sorguları
├── api.php                # Arka uç mantığı ve veritabanı işlemleri (CRUD API)
├── config.php             # Veritabanı bağlantı ayarları
├── index.php              # Ana uygulama arayüzü (HTML ve JavaScript)
├── style.css              # Özel CSS stilleri
└── README.md              # Bu dosya
```

## 🚀 Kurulum ve Çalıştırma

Bu uygulamayı yerel sunucunuzda çalıştırmak için aşağıdaki adımları izleyin.

### Gereksinimler

- [WAMP](https://www.wampserver.com/en/), [XAMPP](https://www.apachefriends.org/index.html) veya benzeri bir yerel sunucu ortamı (Apache, MySQL, PHP içeren).

### Adımlar

1.  **Projeyi İndirin veya Klonlayın:**
    Bu projeyi web sunucunuzun ana dizinine (`www` veya `htdocs`) klonlayın veya dosyaları doğrudan kopyalayın.

2.  **Veritabanını Oluşturun:**
    - Tarayıcınızdan `phpMyAdmin` arayüzünü açın.
    - Yeni bir veritabanı oluşturmak için `db/schema.sql` dosyasının içeriğini kopyalayıp **SQL** sekmesine yapıştırın ve çalıştırın.
    - Bu işlem, `sifre_ureteci_db` adında bir veritabanı ve `sifre_ozetleri` adında bir tablo oluşturacaktır.

3.  **Veritabanı Bağlantısını Yapılandırın:**
    - Proje dizinindeki `config.php` dosyasını bir metin düzenleyici ile açın.
    - `DB_USERNAME` ve `DB_PASSWORD` sabitlerini kendi MySQL kullanıcı adı ve şifrenizle eşleşecek şekilde güncelleyin.
      ```php
      define('DB_SERVER', 'localhost');
      define('DB_USERNAME', 'root'); // Kendi kullanıcı adınız
      define('DB_PASSWORD', '');      // Kendi şifreniz
      define('DB_NAME', 'sifre_ureteci_db');
      ```

4.  **Uygulamayı Başlatın:**
    - Web tarayıcınızı açın ve projenin bulunduğu dizine gidin.
    - Örnek Adres: `http://localhost/sha256_generator_php/`

Artık uygulamayı kullanmaya başlayabilirsiniz!

## 🛡️ Güvenlik Notları

Bu uygulama, temel güvenlik zafiyetlerine karşı aşağıdaki önlemleri içerir:

- **SQL Injection:** Veritabanı sorgularında **Prepared Statements (Hazırlanmış İfadeler)** kullanılarak kullanıcı girdilerinin doğrudan sorguya enjekte edilmesi engellenmiştir.
- **XSS (Cross-Site Scripting):** Kullanıcıdan alınan ve ekranda gösterilen tüm veriler, `htmlspecialchars()` fonksiyonu ile filtrelenerek zararlı scriptlerin çalıştırılması önlenmiştir.
