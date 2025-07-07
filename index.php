<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHA256 Şifre Yöneticisi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (İkonlar için) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Özel Stil Dosyası -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-4"><i class="fas fa-shield-alt"></i> Yeni Şifre Oluştur</h2>
                        <form id="create-form">
                            <div class="mb-3">
                                <label for="eposta" class="form-label">E-posta Adresi</label>
                                <input type="email" class="form-control" id="eposta" placeholder="ornek@eposta.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="platform" class="form-label">Platform</label>
                                <input type="text" class="form-control" id="platform" placeholder="Örn: Google, Facebook" required>
                            </div>
                            <div class="mb-3">
                                <label for="kullanici_adi" class="form-label">Kullanıcı Adı (Opsiyonel)</label>
                                <input type="text" class="form-control" id="kullanici_adi" placeholder="Kullanıcı Adı">
                            </div>
                            <div class="mb-3">
                                <label for="kaynak_metin" class="form-label">Şifre Metni</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="kaynak_metin" placeholder="Güçlü bir şifre girin" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-password"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Oluştur ve Kaydet</button>
                            </div>
                        </form>
                        <div id="generated-hash" class="mt-4 p-3 bg-light rounded" style="display:none;">
                            <strong>Oluşturulan SHA256 Özeti:</strong>
                            <code class="d-block mt-2"></code>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                 <div id="notification" class="alert alert-success" style="display:none;"></div>
            </div>
        </div>

        <hr class="my-5">

        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="mb-0"><i class="fas fa-list-ul"></i> Kayıtlı Şifreler</h2>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="search-input" placeholder="Arama...">
                    <button class="btn btn-outline-secondary" type="button" id="search-button"><i class="fas fa-search"></i> Ara</button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>E-posta</th>
                        <th>Platform</th>
                        <th>Kullanıcı Adı</th>
                        <th>Kaynak Şifre</th>
                        <th>SHA256 Şifre</th>
                        <th>Oluşturulma</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody id="records-tbody">
                    <!-- Kayıtlar buraya dinamik olarak yüklenecek -->
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center" id="pagination-controls">
                <!-- Sayfalama kontrolleri buraya dinamik olarak yüklenecek -->
            </ul>
        </nav>
    </div>

    <!-- Düzenleme Modalı -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kaydı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-eposta" class="form-label">E-posta</label>
                            <input type="email" class="form-control" id="edit-eposta" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-platform" class="form-label">Platform</label>
                            <input type="text" class="form-control" id="edit-platform" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-kullanici_adi" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="edit-kullanici_adi">
                        </div>
                        <div class="mb-3">
                            <label for="edit-kaynak_metin" class="form-label">Yeni Şifre Metni</label>
                            <input type="text" class="form-control" id="edit-kaynak_metin" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary" id="save-changes-btn">Değişiklikleri Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Silme Onay Modalı -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Kaydı Silmeyi Onayla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bu kaydı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">Sil</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {

            // Bildirim gösterme fonksiyonu
            function showNotification(message, isSuccess) {
                const notification = $('#notification');
                notification.text(message);
                notification.removeClass('alert-success alert-danger').addClass(isSuccess ? 'alert-success' : 'alert-danger');
                notification.fadeIn().delay(3000).fadeOut();
            }

            // Kayıtları listele
            let currentPage = 1;
            const recordsPerPage = 10; // Her sayfada 10 kayıt

            function loadRecords(page = 1, searchQuery = '') {
                currentPage = page;
                $.get(`api.php?action=list&page=${page}&limit=${recordsPerPage}&search=${encodeURIComponent(searchQuery)}`, function(response) {
                    console.log("API Response:", response); // Hata ayıklama için eklendi
                    if (response.success) {
                        const tbody = $('#records-tbody');
                        tbody.empty();
                        if (response.data.length === 0) {
                            tbody.append('<tr><td colspan="8" class="text-center">Kayıt bulunamadı.</td></tr>');
                        }
                        response.data.forEach(rec => {
                            tbody.append(`
                                <tr>
                                    <td>${rec.id}</td>
                                    <td>${rec.eposta}</td>
                                    <td>${rec.platform}</td>
                                    <td>${rec.kullanici_adi}</td>
                                    <td>${rec.kaynak_metin}</td>
                                    <td class="text-truncate" style="max-width: 150px;">${rec.sha256_ozeti}</td>
                                    <td>${rec.olusturulma_zamani}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="${rec.id}" data-eposta="${rec.eposta}" data-platform="${rec.platform}" data-kullanici_adi="${rec.kullanici_adi}" data-kaynak="${rec.kaynak_metin}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="${rec.id}"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            `);
                        });
                        renderPagination(response.total_records, page, recordsPerPage, searchQuery);
                    }
                });
            }

            // Sayfalama kontrollerini oluştur
            function renderPagination(totalRecords, currentPage, recordsPerPage, searchQuery) {
                const totalPages = Math.ceil(totalRecords / recordsPerPage);
                const paginationControls = $('#pagination-controls');
                paginationControls.empty();

                if (totalPages <= 1) {
                    return; // Tek sayfa varsa sayfalama gösterme
                }

                // Önceki sayfa butonu
                paginationControls.append(`
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Önceki</a>
                    </li>
                `);

                // Sayfa numaraları
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, currentPage + 2);

                if (startPage > 1) {
                    paginationControls.append(`<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`);
                    if (startPage > 2) {
                        paginationControls.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationControls.append(`
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        paginationControls.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                    paginationControls.append(`<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`);
                }

                // Sonraki sayfa butonu
                paginationControls.append(`
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Sonraki</a>
                    </li>
                `);

                // Sayfa linklerine tıklama olayı
                paginationControls.find('.page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page && page !== currentPage) {
                        loadRecords(page, searchQuery);
                    }
                });
            }

            // Sayfa yüklendiğinde kayıtları getir
            loadRecords();

            // Arama butonu tıklama olayı
            $('#search-button').on('click', function() {
                const searchQuery = $('#search-input').val();
                loadRecords(1, searchQuery); // Arama yapıldığında ilk sayfaya dön
            });

            // Arama inputunda Enter tuşuna basma olayı
            $('#search-input').on('keypress', function(e) {
                if (e.which === 13) { // Enter tuşu
                    $('#search-button').click();
                }
            });

            // Şifre görünürlüğünü değiştir
            $('#toggle-password').click(function() {
                const input = $('#kaynak_metin');
                const icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Yeni kayıt oluştur
            $('#create-form').submit(function(e) {
                e.preventDefault();
                const eposta = $('#eposta').val();
                const platform = $('#platform').val();
                const kullanici_adi = $('#kullanici_adi').val();
                const kaynak_metin = $('#kaynak_metin').val();

                $.post('api.php?action=create', { eposta, platform, kullanici_adi, kaynak_metin }, function(response) {
                    if (response.success) {
                        showNotification(response.message, true);
                        loadRecords(currentPage, $('#search-input').val());
                        $('#create-form')[0].reset();
                        // Oluşturulan hash'i göster
                        $.get('api.php?action=list', function(listResponse) {
                            if(listResponse.success && listResponse.data.length > 0) {
                                const newHash = listResponse.data[0].sha256_ozeti;
                                $('#generated-hash code').text(newHash);
                                $('#generated-hash').slideDown();
                            }
                        });
                    } else {
                        showNotification(response.message, false);
                    }
                }).fail(function() {
                    showNotification('Sunucu ile iletişim kurulamadı.', false);
                });
            });

            // Kayıt silme
            let deleteId = null; // Silinecek kaydın ID'sini tutmak için
            let deleteModal; // Modal instance'ı tutmak için

            // Silme modalını açma
            $(document).on('click', '.delete-btn', function() {
                deleteId = $(this).data('id');
                if (!deleteModal) {
                    deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                }
                deleteModal.show();
            });

            // Silme işlemini onayla
            $('#confirm-delete-btn').click(function() {
                if (deleteId) {
                    $.ajax({
                        url: 'api.php?action=delete',
                        type: 'POST',
                        data: `id=${deleteId}`,
                        success: function(response) {
                            showNotification(response.message, response.success);
                            if (response.success) {
                                loadRecords(currentPage, $('#search-input').val());
                            }
                            deleteModal.hide(); // Modalı kapat
                        },
                        error: function() {
                            showNotification('İstek işlenirken bir hata oluştu.', false);
                            deleteModal.hide(); // Modalı kapat
                        }
                    });
                }
            });

            // Düzenleme modalını açma
            let editModal;
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const eposta = $(this).data('eposta');
                const platform = $(this).data('platform');
                const kullanici_adi = $(this).data('kullanici_adi');
                const kaynak = $(this).data('kaynak');

                $('#edit-id').val(id);
                $('#edit-eposta').val(eposta);
                $('#edit-platform').val(platform);
                $('#edit-kullanici_adi').val(kullanici_adi);
                $('#edit-kaynak_metin').val(kaynak);

                if (!editModal) {
                    editModal = new bootstrap.Modal(document.getElementById('editModal'));
                }
                editModal.show();
            });

            // Değişiklikleri kaydet
            $('#save-changes-btn').click(function() {
                const id = $('#edit-id').val();
                const eposta = $('#edit-eposta').val();
                const platform = $('#edit-platform').val();
                const kullanici_adi = $('#edit-kullanici_adi').val();
                const kaynak_metin = $('#edit-kaynak_metin').val();

                $.ajax({
                    url: 'api.php?action=update',
                    type: 'POST', // Güncelleme için POST kullanıyoruz ama PUT metodunu simüle ediyoruz
                    data: `id=${id}&eposta=${eposta}&platform=${platform}&kullanici_adi=${kullanici_adi}&kaynak_metin=${kaynak_metin}`,
                    success: function(response) {
                        showNotification(response.message, response.success);
                        if (response.success) {
                            loadRecords(currentPage, $('#search-input').val());
                            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                            modalInstance.hide();
                        }
                    },
                     error: function() {
                        showNotification('İstek işlenirken bir hata oluştu.', false);
                    }
                });
            });

        });
    </script>

</body>
</html>