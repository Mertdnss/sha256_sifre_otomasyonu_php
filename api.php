<?php
// Veritabanı bağlantısını ve ayarları dahil et
require_once 'config.php';

// Hata raporlamayı geliştirme aşamasında aç
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Yanıt başlığını JSON olarak ayarla
header('Content-Type: application/json');

// Hangi işlemin istendiğini belirle
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Gelen isteğin yöntemini al
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
    case 'list':
        listRecords($conn);
        break;
    case 'create':
        if ($method == 'POST') {
            createRecord($conn);
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek yöntemi.']);
        }
        break;
    case 'update':
        if ($method == 'POST') { // HTML formları sadece GET ve POST desteklediği için
            updateRecord($conn);
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek yöntemi.']);
        }
        break;
    case 'delete':
        if ($method == 'POST') { // HTML formları sadece GET ve POST desteklediği için
            deleteRecord($conn);
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek yöntemi.']);
        }
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Geçersiz işlem.']);
        break;
}

// Tüm kayıtları listele (XSS korumalı)
function listRecords($conn) {
    $result = $conn->query("SELECT id, eposta, platform, kullanici_adi, kaynak_metin, sha256_ozeti, DATE_FORMAT(olusturulma_zamani, '%d.%m.%Y %H:%i') as olusturulma_zamani FROM sifre_ozetleri ORDER BY id DESC");
    $records = [];
    while ($row = $result->fetch_assoc()) {
        error_log("List Records - Fetched Row: " . json_encode($row));
        // XSS saldırılarını önlemek için kullanıcı girdilerini HTML olarak işle
        $row['eposta'] = htmlspecialchars($row['eposta'], ENT_QUOTES, 'UTF-8');
        $row['platform'] = htmlspecialchars($row['platform'], ENT_QUOTES, 'UTF-8');
        $row['kullanici_adi'] = htmlspecialchars($row['kullanici_adi'], ENT_QUOTES, 'UTF-8');
        $row['kaynak_metin'] = htmlspecialchars($row['kaynak_metin'], ENT_QUOTES, 'UTF-8');
        $records[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $records]);
}

// Yeni kayıt oluştur
function createRecord($conn) {
    $eposta = isset($_POST['eposta']) ? trim($_POST['eposta']) : '';
    $platform = isset($_POST['platform']) ? trim($_POST['platform']) : '';
    $kullanici_adi = isset($_POST['kullanici_adi']) ? trim($_POST['kullanici_adi']) : '';
    $kaynak_metin = isset($_POST['kaynak_metin']) ? $_POST['kaynak_metin'] : '';

    error_log("Create Record - Received Data: eposta=" . $eposta . ", platform=" . $platform . ", kullanici_adi=" . $kullanici_adi . ", kaynak_metin=" . $kaynak_metin);

    if (empty($eposta) || empty($platform) || empty($kaynak_metin)) {
        echo json_encode(['success' => false, 'message' => 'E-posta, platform ve kaynak metin alanları zorunludur.']);
        return;
    }

    $sha256_ozeti = hash('sha256', $kaynak_metin);

    $stmt = $conn->prepare("INSERT INTO sifre_ozetleri (eposta, platform, kullanici_adi, kaynak_metin, sha256_ozeti) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $eposta, $platform, $kullanici_adi, $kaynak_metin, $sha256_ozeti);

    if ($stmt->execute()) {
        error_log("Create Record - Insert Successful.");
        echo json_encode(['success' => true, 'message' => 'Kayıt başarıyla oluşturuldu.']);
    } else {
        error_log("Create Record - Insert Failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Kayıt oluşturulurken bir hata oluştu: ' . $stmt->error]);
    }
    $stmt->close();
}

// Mevcut bir kaydı güncelle
function updateRecord($conn) {
    $input_data = [];
    parse_str(file_get_contents("php://input"), $input_data);

    $id = isset($input_data['id']) ? $input_data['id'] : 0;
    $eposta = isset($input_data['eposta']) ? trim($input_data['eposta']) : '';
    $platform = isset($input_data['platform']) ? trim($input_data['platform']) : '';
    $kullanici_adi = isset($input_data['kullanici_adi']) ? trim($input_data['kullanici_adi']) : '';
    $kaynak_metin = isset($input_data['kaynak_metin']) ? $input_data['kaynak_metin'] : '';

    if (empty($id) || empty($eposta) || empty($platform) || empty($kaynak_metin)) {
        echo json_encode(['success' => false, 'message' => 'ID, e-posta, platform ve kaynak metin alanları zorunludur.']);
        return;
    }

    $sha256_ozeti = hash('sha256', $kaynak_metin);

    $stmt = $conn->prepare("UPDATE sifre_ozetleri SET eposta = ?, platform = ?, kullanici_adi = ?, kaynak_metin = ?, sha256_ozeti = ? WHERE id = ?");
    $stmt->bind_param('sssssi', $eposta, $platform, $kullanici_adi, $kaynak_metin, $sha256_ozeti, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Kayıt başarıyla güncellendi.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Güncellenecek kayıt bulunamadı veya veriler aynı.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Kayıt güncellenirken bir hata oluştu: ' . $stmt->error]);
    }
    $stmt->close();
}

// Bir kaydı sil
function deleteRecord($conn) {
    $input_data = [];
    parse_str(file_get_contents("php://input"), $input_data);
    
    $id = isset($input_data['id']) ? $input_data['id'] : 0;

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Silinecek kaydın ID\'si belirtilmelidir.']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM sifre_ozetleri WHERE id = ?");
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Kayıt başarıyla silindi.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Kayıt silinirken bir hata oluştu: ' . $stmt->error]);
    }
    $stmt->close();
}

// Veritabanı bağlantısını kapat
$conn->close();
?>