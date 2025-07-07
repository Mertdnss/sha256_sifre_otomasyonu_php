<?php
/*
 * Veritabanı bağlantı ayarları
 * Lütfen bu bilgileri kendi sunucu yapılandırmanıza göre güncelleyin.
 */

// Veritabanı sunucusu (genellikle 'localhost')
define('DB_SERVER', 'localhost');

// Veritabanı kullanıcı adı
define('DB_USERNAME', 'root');

// Veritabanı şifresi (varsayılan olarak boş olabilir)
define('DB_PASSWORD', '');

// Veritabanı adı
define('DB_NAME', 'sifre_ureteci_db');

// Veritabanı bağlantısını oluştur
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Karakter setini UTF-8 olarak ayarla
$conn->set_charset("utf8mb4");
?>