-- Veritabanını oluştur (Türkçe karakter desteği ile)
CREATE DATABASE IF NOT EXISTS sifre_ureteci_db CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

-- Veritabanını kullan
USE sifre_ureteci_db;

-- Şifreleri saklamak için tablo oluştur
-- UYARI: Şifreleri ham metin olarak saklamak ciddi bir güvenlik açığıdır.
CREATE TABLE IF NOT EXISTS sifre_ozetleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eposta VARCHAR(255) NOT NULL,
    platform VARCHAR(255) NOT NULL,
    kaynak_metin VARCHAR(255) NOT NULL, -- Güvenlik riski taşıyan sütun
    sha256_ozeti CHAR(64) NOT NULL,
    olusturulma_zamani TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);