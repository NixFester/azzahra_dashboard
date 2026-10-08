<?php $this->load->view('Template/header'); ?>

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
    }

    /* Fix button styles for HR views */
    .btn-primary {
        background: #0041c3 !important;
        color: white !important;
        border: 1px solid #0041c3 !important;
    }

    .btn-primary:hover {
        background: #003399 !important;
        border-color: #003399 !important;
    }

    .btn-outline {
        background: white !important;
        color: #0041c3 !important;
        border: 1px solid #0041c3 !important;
    }

    .btn-outline:hover {
        background: #0041c3 !important;
        color: white !important;
    }

    .btn-outline-danger {
        background: white !important;
        color: #ef4444 !important;
        border: 1px solid #ef4444 !important;
    }

    .btn-outline-danger:hover {
        background: #ef4444 !important;
        color: white !important;
    }

    /* Enhanced Modal Styles */
    .modal-header-custom {
        background: linear-gradient(135deg, #0041c3 0%, #0052e0 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 0.5rem 0.5rem 0 0;
        margin: -1.25rem -1.25rem 1.5rem -1.25rem;
    }

    .modal-header-custom h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header-custom .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modal-header-custom .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .form-group-custom {
        margin-bottom: 1.5rem;
    }

    .form-label-custom {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-label-custom .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control-custom {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #0041c3;
        box-shadow: 0 0 0 3px rgba(0, 65, 195, 0.1);
    }

    .form-control-custom::placeholder {
        color: #9ca3af;
    }

    .item-card {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .item-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .item-number {
        background: #0041c3;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .remove-item-btn-custom {
        background: #fff;
        border: 1.5px solid #ef4444;
        color: #ef4444;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.813rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .remove-item-btn-custom:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
    }

    .add-item-btn-custom {
        background: white;
        border: 2px dashed #0041c3;
        color: #0041c3;
        padding: 0.875rem;
        border-radius: 0.75rem;
        width: 100%;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .add-item-btn-custom:hover {
        background: #f0f7ff;
        border-color: #003399;
        transform: translateY(-2px);
    }

    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        background: #f9fafb;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .image-upload-area:hover {
        border-color: #0041c3;
        background: #f0f7ff;
    }

    .image-upload-area.has-image {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .image-preview-container {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }

    .image-preview-wrapper {
        position: relative;
        display: inline-block;
    }

    .preview-image {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .remove-image-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border: 2px solid white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .remove-image-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .modal-footer-custom {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        margin-top: 1.5rem;
    }

    .btn-custom {
        padding: 0.625rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .btn-submit {
        background: #0041c3;
        color: white;
    }

    .btn-submit:hover {
        background: #003399;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 65, 195, 0.3);
    }

    .alert-info-custom {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .alert-info-custom strong {
        color: #1e40af;
        display: block;
        margin-bottom: 0.5rem;
    }

    .alert-info-custom {
        color: #1e3a8a;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .section-divider {
        background: #f3f4f6;
        height: 1px;
        margin: 1.5rem 0;
    }

    /* Input Icons */
    .input-with-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    .input-with-icon .form-control-custom {
        padding-left: 2.5rem;
    }

    /* Responsive Grid */
    .grid-responsive {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .grid-responsive {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Animation */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .item-card {
        animation: slideIn 0.3s ease;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="edit" class="w-10 h-10 inline-block mr-2"></i>
                Edit Pencatatan
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Edit data pencatatan dan keperluan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="<?= site_url('HR/pencatatan'); ?>" class="btn btn-outline">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Edit Form Card -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Edit Pencatatan Barang - Batch: <?= htmlspecialchars($batch['batch_id']); ?></h3>
            </div>
            <div style="padding: 1.5rem;">
                <?php if ($this->session->flashdata('sukses')): ?>
                    <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                        <i data-feather="check-circle" class="mr-2"></i>
                        <span><?= $this->session->flashdata('sukses'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('gagal')): ?>
                    <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                        <i data-feather="alert-circle" class="mr-2"></i>
                        <span><?= $this->session->flashdata('gagal'); ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('HR/update_pencatatan'); ?>" method="POST" enctype="multipart/form-data" id="formEditPencatatan">
                    <input type="hidden" name="batch_id" value="<?= htmlspecialchars($batch['batch_id']); ?>">
                    <input type="hidden" name="existing_gambar" value="<?= htmlspecialchars($batch['gambar']); ?>">

                    <!-- Tanggal -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            <i data-feather="calendar" style="width: 16px; height: 16px; display: inline; margin-right: 4px;"></i>
                            Tanggal <span class="required">*</span>
                        </label>
                        <input type="date" name="tanggal" class="form-control-custom" value="<?= $batch['tanggal']; ?>" required>
                    </div>

                    <!-- Kategori Global -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            <i data-feather="tag" style="width: 16px; height: 16px; display: inline; margin-right: 4px;"></i>
                            Kategori Global <span class="required">*</span>
                        </label>
                        <select name="kategori_global" id="kategoriGlobal" class="form-control-custom" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Elektronik" <?= $batch['kategori_global'] == 'Elektronik' ? 'selected' : ''; ?>>📱 Elektronik</option>
                            <option value="ATK" <?= $batch['kategori_global'] == 'ATK' ? 'selected' : ''; ?>>✏️ ATK (Alat Tulis Kantor)</option>
                            <option value="Konsumsi" <?= $batch['kategori_global'] == 'Konsumsi' ? 'selected' : ''; ?>>🍽️ Konsumsi</option>
                            <option value="Lainnya" <?= $batch['kategori_global'] == 'Lainnya' ? 'selected' : ''; ?>>📦 Lainnya</option>
                        </select>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Items Section -->
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">
                                <i data-feather="list" style="width: 20px; height: 20px; display: inline; margin-right: 8px;"></i>
                                Daftar Item Barang
                            </h3>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <label style="font-size: 0.875rem; color: #6b7280; margin: 0;">Jumlah Item:</label>
                                    <input type="number" id="itemQuantityInput" class="form-control-custom" style="width: 80px; padding: 0.25rem 0.5rem; font-size: 0.875rem;" min="1" max="50" value="<?= count($batch_items); ?>">
                                </div>
                                <span style="font-size: 0.875rem; color: #6b7280;">Item: <strong id="itemCount"><?= count($batch_items); ?></strong></span>
                            </div>
                        </div>

                        <div id="itemsContainer">
                            <?php foreach ($batch_items as $index => $item): ?>
                            <div class="item-card">
                                <div class="item-card-header">
                                    <div class="item-number"><?= $index + 1; ?></div>
                                    <button type="button" class="remove-item-btn-custom" style="display: <?= count($batch_items) > 1 ? 'flex' : 'none'; ?>;">
                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid-responsive">
                                    <div class="form-group-custom" style="margin-bottom: 0;">
                                        <label class="form-label-custom">Nama Item <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i data-feather="box" class="input-icon" style="width: 16px; height: 16px;"></i>
                                            <input type="text" name="nama_barang[]" class="form-control-custom" required value="<?= htmlspecialchars($item['nama_barang']); ?>" placeholder="Contoh: Keyboard Wireless Logitech">
                                        </div>
                                    </div>

                                    <div class="form-group-custom" style="margin-bottom: 0;">
                                        <label class="form-label-custom">Jumlah (QTY) <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i data-feather="hash" class="input-icon" style="width: 16px; height: 16px;"></i>
                                            <input type="number" name="qty[]" class="form-control-custom" required min="1" value="<?= $item['qty']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group-custom" style="margin-bottom: 0;">
                                        <label class="form-label-custom">Harga Satuan <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i data-feather="dollar-sign" class="input-icon" style="width: 16px; height: 16px;"></i>
                                            <input type="number" name="harga_satuan[]" class="form-control-custom" required min="0" step="0.01" value="<?= $item['harga_satuan']; ?>" placeholder="150000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" id="addItemBtn" class="add-item-btn-custom">
                            <i data-feather="plus-circle" style="width: 20px; height: 20px;"></i>
                            Tambah Item Baru
                        </button>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Image Upload -->
                    <div class="form-group-custom">
                        <label class="form-label-custom">
                            <i data-feather="image" style="width: 16px; height: 16px; display: inline; margin-right: 4px;"></i>
                            Upload Gambar (Opsional)
                        </label>

                        <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('gambar').click()" style="<?= $batch['gambar'] ? 'display: none;' : ''; ?>">
                            <i data-feather="upload-cloud" style="width: 48px; height: 48px; color: #9ca3af; margin-bottom: 0.5rem;"></i>
                            <p style="margin: 0; font-size: 0.875rem; color: #6b7280; font-weight: 500;">
                                Klik untuk upload gambar
                            </p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.75rem; color: #9ca3af;">
                                Format: JPG, PNG, GIF • Maksimal 2MB
                            </p>
                        </div>

                        <input type="file" name="gambar" id="gambar" style="display: none;" accept="image/*" onchange="previewImage(event)">

                        <div id="imagePreview" class="image-preview-container" style="display: <?= $batch['gambar'] ? 'flex' : 'none'; ?>;">
                            <div class="image-preview-wrapper">
                                <img id="previewImg" src="<?= $batch['gambar'] ? base_url($batch['gambar']) : ''; ?>" alt="Preview" class="preview-image">
                                <button type="button" class="remove-image-btn">
                                    <i data-feather="x" style="width: 14px; height: 14px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="modal-footer-custom" style="border-top: 1px solid #e5e7eb; margin-top: 1.5rem; padding-top: 1.5rem;">
                        <a href="<?= site_url('HR/pencatatan'); ?>" class="btn-custom btn-cancel">
                            <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn-custom btn-submit">
                            <i data-feather="save" style="width: 18px; height: 18px;"></i>
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    // Preview Image Function
    function previewImage(event) {
        const file = event.target.files[0];
        const modal = $(event.target).closest('.dashboard-container');
        const preview = modal.find('#imagePreview');
        const previewImg = modal.find('#previewImg');
        const uploadArea = modal.find('#imageUploadArea');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg.length) previewImg[0].src = e.target.result;
                if (preview.length) preview.show();
                if (uploadArea.length) uploadArea.hide();
            };
            reader.readAsDataURL(file);
        }
    }

    $(document).ready(function () {
        // Initialize Feather Icons
        feather.replace();

        // Add Item Functionality
        let itemCount = <?= count($batch_items); ?>;

        function updateItemCount() {
            itemCount = $('.item-card').length;
            $('#itemCount').text(itemCount);
            $('#itemQuantityInput').val(itemCount);

            // Update numbering
            $('.item-card').each(function(index) {
                $(this).find('.item-number').text(index + 1);
            });
        }

        function adjustItems(targetCount) {
            const currentCount = $('.item-card').length;
            const difference = targetCount - currentCount;

            if (difference > 0) {
                // Add items
                for (let i = 0; i < difference; i++) {
                    itemCount++;
                    const itemRow = `
                        <div class="item-card">
                            <div class="item-card-header">
                                <div class="item-number">${itemCount}</div>
                                <button type="button" class="remove-item-btn-custom">
                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                    Hapus
                                </button>
                            </div>

                            <div class="grid-responsive">
                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">Nama Item <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i data-feather="box" class="input-icon" style="width: 16px; height: 16px;"></i>
                                        <input type="text" name="nama_barang[]" class="form-control-custom" required placeholder="Contoh: Keyboard Wireless Logitech">
                                    </div>
                                </div>

                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">Jumlah (QTY) <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i data-feather="hash" class="input-icon" style="width: 16px; height: 16px;"></i>
                                        <input type="number" name="qty[]" class="form-control-custom" required min="1" value="1">
                                    </div>
                                </div>

                                <div class="form-group-custom" style="margin-bottom: 0;">
                                    <label class="form-label-custom">Harga Satuan <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i data-feather="dollar-sign" class="input-icon" style="width: 16px; height: 16px;"></i>
                                        <input type="number" name="harga_satuan[]" class="form-control-custom" required min="0" step="0.01" placeholder="150000">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#itemsContainer').append(itemRow);
                }
                feather.replace();
            } else if (difference < 0) {
                // Remove items from the end
                const itemsToRemove = Math.abs(difference);
                for (let i = 0; i < itemsToRemove; i++) {
                    $('.item-card').last().remove();
                }
            }

            updateRemoveButtons();
            updateItemCount();
        }

        function addItemClickHandler() {
            $('#addItemBtn').on('click', function() {
                itemCount++;
                const itemRow = `
                    <div class="item-card">
                        <div class="item-card-header">
                            <div class="item-number">${itemCount}</div>
                            <button type="button" class="remove-item-btn-custom">
                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                Hapus
                            </button>
                        </div>

                        <div class="grid-responsive">
                            <div class="form-group-custom" style="margin-bottom: 0;">
                                <label class="form-label-custom">Nama Item <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i data-feather="box" class="input-icon" style="width: 16px; height: 16px;"></i>
                                    <input type="text" name="nama_barang[]" class="form-control-custom" required placeholder="Contoh: Keyboard Wireless Logitech">
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0;">
                                <label class="form-label-custom">Jumlah (QTY) <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i data-feather="hash" class="input-icon" style="width: 16px; height: 16px;"></i>
                                    <input type="number" name="qty[]" class="form-control-custom" required min="1" value="1">
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0;">
                                <label class="form-label-custom">Harga Satuan <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i data-feather="dollar-sign" class="input-icon" style="width: 16px; height: 16px;"></i>
                                    <input type="number" name="harga_satuan[]" class="form-control-custom" required min="0" step="0.01" placeholder="150000">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#itemsContainer').append(itemRow);
                feather.replace();
                updateRemoveButtons();
                updateItemCount();
            });
        }

        function updateRemoveButtons() {
            const rows = $('.item-card');
            if (rows.length > 1) {
                $('.remove-item-btn-custom').show();
            } else {
                $('.remove-item-btn-custom').hide();
            }
        }

        $(document).on('click', '.remove-item-btn-custom', function() {
            $(this).closest('.item-card').fadeOut(300, function() {
                $(this).remove();
                updateRemoveButtons();
                updateItemCount();
            });
        });

        $(document).on('click', '.remove-image-btn', function() {
            const container = $(this).closest('.form-group-custom');
            const preview = container.find('#imagePreview');
            const uploadArea = container.find('#imageUploadArea');
            const fileInput = container.find('#gambar');

            if (preview.length) preview.hide();
            if (uploadArea.length) uploadArea.show();
            if (fileInput.length) fileInput.val('');

            feather.replace();
        });

        // Initialize on load
        updateRemoveButtons();
        updateItemCount();

        // Handle quantity input change
        $('#itemQuantityInput').on('input change', function() {
            const targetCount = parseInt($(this).val()) || 1;
            if (targetCount >= 1 && targetCount <= 50) {
                adjustItems(targetCount);
            } else {
                if (targetCount < 1) $(this).val(1);
                if (targetCount > 50) $(this).val(50);
            }
        });

        // Attach add item handler
        addItemClickHandler();
    });
</script>