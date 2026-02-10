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

    .btn-outline-primary:hover {
        background-color: #f0f7ff !important;
        color: #0041c3 !important;
    }

    .btn-outline-warning:hover {
        background-color: #fefce8 !important;
        color: #d97706 !important;
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

<?php
// Array nama bulan Indonesia
$bulan_indonesia = array(
1 => 'Januari',
2 => 'Februari',
3 => 'Maret',
4 => 'April',
5 => 'Mei',
6 => 'Juni',
7 => 'Juli',
8 => 'Agustus',
9 => 'September',
10 => 'Oktober',
11 => 'November',
12 => 'Desember'
);

// Fungsi untuk format tanggal Indonesia
function format_tanggal_indonesia($tanggal, $bulan_array) {
$timestamp = strtotime($tanggal);
$hari = date('d', $timestamp);
$bulan = (int)date('m', $timestamp);
$tahun = date('Y', $timestamp);
return $hari . ' ' . $bulan_array[$bulan] . ' ' . $tahun;
}
?>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="package" class="w-10 h-10 inline-block mr-2"></i>
                Pencatatan 
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Kelola pencatatan dan keperluan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="javascript:;" class="btn btn-outline" data-toggle="modal" data-target="#modalImport">
                <i data-feather="upload"></i> Import Excel
            </a>
            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modalPencatatan">
                <i data-feather="plus-circle"></i> Tambah Pencatatan
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter & Export Card -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Filter Data</h3>
            </div>
            <div style="padding: 1.5rem;">
                <form action="" method="GET" class="row align-items-end">
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label class="form-label font-weight-bold text-muted small text-uppercase">Tipe Filter</label>
                        <div class="d-flex flex-column">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="filter_type" id="filter_month" value="month" <?= ($filter_type ?? 'month') == 'month' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="filter_month">Bulan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="filter_type" id="filter_date" value="date" <?= ($filter_type ?? 'month') == 'date' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="filter_date">Tanggal</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0" id="month_filter" style="<?= ($filter_type ?? 'month') == 'date' ? 'display: none;' : ''; ?>">
                        <label class="form-label font-weight-bold text-muted small text-uppercase">Pilih Bulan</label>
                        <div class="input-group">
                            <input type="month" name="bulan_tahun" class="form-control" value="<?= isset($selected_bulan_tahun) && ($filter_type ?? 'month') == 'month' ? $selected_bulan_tahun : date('Y-m'); ?>" style="border-radius: 0.375rem 0 0 0.375rem;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border-radius: 0 0.375rem 0.375rem 0; background-color: #f8f9fa; border-left: none;">
                                    <i data-feather="calendar" class="text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0" id="date_filter" style="<?= ($filter_type ?? 'month') == 'month' ? 'display: none;' : ''; ?>">
                        <label class="form-label font-weight-bold text-muted small text-uppercase">Pilih Tanggal</label>
                        <div class="input-group">
                            <input type="date" name="tanggal" class="form-control" value="<?= isset($selected_tanggal) ? $selected_tanggal : date('Y-m-d'); ?>" style="border-radius: 0.375rem 0 0 0.375rem;">
                            <div class="input-group-append">
                                <span class="input-group-text" style="border-radius: 0 0.375rem 0.375rem 0; background-color: #f8f9fa; border-left: none;">
                                    <i data-feather="calendar" class="text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i data-feather="filter"></i> Filter
                        </button>
                        <a href="<?= site_url('HR/pencatatan?filter_type=month&bulan_tahun=' . date('Y-m')); ?>" class="btn btn-outline mr-2">
                            <i data-feather="refresh-cw"></i> Bulan Ini
                        </a>
                        <a href="<?= site_url('HR/pencatatan?filter_type=month&bulan_tahun=all'); ?>" class="btn btn-outline">
                            <i data-feather="list"></i> Semua Data
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Data Pencatatan<?= isset($filter_title) ? $filter_title : ' (Bulan Ini)'; ?></h3>
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

                <div class="table-responsive">
                    <table id="tablePencatatan" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563; width:90px;">Nama Barang / Keperluan</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Total QTY</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Kategori</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Total Batch</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Tanggal</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Gambar</th>
                                <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (!empty($pencatatan_list)):
                                foreach ($pencatatan_list as $batch):
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="font-weight-bold text-dark">
                                            <?php
                                            $items = $batch['items'];
                                            $count = count($items);
                                            for ($i = 0; $i < min(2, $count); $i++) {
                                                echo '<div>• ' . htmlspecialchars($items[$i]['nama_barang']) . ' (' . $items[$i]['qty'] . 'x)</div>';
                                            }
                                            if ($count > 2) {
                                                echo '<div>...</div>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $total_qty = 0;
                                            foreach ($batch['items'] as $item) {
                                                $total_qty += $item['qty'];
                                            }
                                            echo $total_qty;
                                            ?>
                                        </td>
                                        <td><span class="badge badge-info px-3 py-1"><?= htmlspecialchars($batch['kategori_global']); ?></span></td>
                                        <td class="font-weight-bold">Rp <?= number_format($batch['total_batch'], 0, ',', '.'); ?></td>
                                        <td><?= format_tanggal_indonesia($batch['tanggal'], $bulan_indonesia); ?></td>
                                        <td>
                                            <?php if ($batch['gambar']): ?>
                                                <img src="<?= base_url($batch['gambar']); ?>" alt="Gambar" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; cursor: pointer;" onclick="showImageModal('<?= base_url($batch['gambar']); ?>')">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:;" class="btn btn-sm btn-outline-primary mr-1" title="Lihat Detail" onclick="showBatchDetail('<?= $batch['batch_id']; ?>')" style="padding: 0.2rem 0.3rem; font-size: 0.75rem;">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="javascript:;" class="btn btn-sm btn-outline-warning mr-1" title="Edit Batch" onclick="editBatch('<?= $batch['batch_id']; ?>')" style="padding: 0.2rem 0.3rem; font-size: 0.75rem;">
                                                <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="<?= site_url('HR/delete_batch_pencatatan/' . $batch['batch_id']); ?>"
                                                class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus Batch" style="padding: 0.2rem 0.3rem; font-size: 0.75rem;">
                                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data pencatatan untuk tanggal ini</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Input Pencatatan - REDESIGNED -->
<div class="modal" id="modalPencatatan">
    <div class="modal__content modal__content--lg p-5 intro-y box" style="max-height: 90vh; overflow-y: auto;">
        
        <!-- Modal Header -->
        <div class="modal-header-custom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>
                    <i data-feather="package" style="width: 24px; height: 24px;"></i>
                    Tambah Pencatatan Barang
                </h2>
                <button type="button" class="close-btn" data-dismiss="modal">
                    <i data-feather="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
        </div>

        <form action="<?= site_url('HR/save_pencatatan'); ?>" method="POST" enctype="multipart/form-data" id="formPencatatan">
            <input type="hidden" name="tanggal" value="<?= $selected_date; ?>">

            <!-- Kategori Global -->
            <div class="form-group-custom">
                <label class="form-label-custom">
                    <i data-feather="tag" style="width: 16px; height: 16px; display: inline; margin-right: 4px;"></i>
                    Kategori Global <span class="required">*</span>
                </label>
                <select name="kategori_global" id="kategoriGlobal" class="form-control-custom" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Elektronik">📱 Elektronik</option>
                    <option value="ATK">✏️ ATK (Alat Tulis Kantor)</option>
                    <option value="Konsumsi">🍽️ Konsumsi</option>
                    <option value="Lainnya">📦 Lainnya</option>
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
                            <input type="number" id="itemQuantityInput" class="form-control-custom" style="width: 80px; padding: 0.25rem 0.5rem; font-size: 0.875rem;" min="1" max="50" value="1">
                        </div>
                        <span style="font-size: 0.875rem; color: #6b7280;">Item: <strong id="itemCount">1</strong></span>
                    </div>
                </div>

                <div id="itemsContainer">
                    <!-- Item Row 1 -->
                    <div class="item-card">
                        <div class="item-card-header">
                            <div class="item-number">1</div>
                            <button type="button" class="remove-item-btn-custom" style="display: none;">
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
                
                <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('gambar').click()">
                    <i data-feather="upload-cloud" style="width: 48px; height: 48px; color: #9ca3af; margin-bottom: 0.5rem;"></i>
                    <p style="margin: 0; font-size: 0.875rem; color: #6b7280; font-weight: 500;">
                        Klik untuk upload gambar
                    </p>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.75rem; color: #9ca3af;">
                        Format: JPG, PNG, GIF • Maksimal 2MB
                    </p>
                </div>
                
                <input type="file" name="gambar" id="gambar" style="display: none;" accept="image/*" onchange="previewImage(event)">
                
                <div id="imagePreview" class="image-preview-container" style="display: none;">
                    <div class="image-preview-wrapper">
                        <img id="previewImg" src="" alt="Preview" class="preview-image">
                        <button type="button" class="remove-image-btn">
                            <i data-feather="x" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button type="button" class="btn-custom btn-cancel" data-dismiss="modal">
                    <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                    Batal
                </button>
                <button type="submit" class="btn-custom btn-submit">
                    <i data-feather="save" style="width: 18px; height: 18px;"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel - REDESIGNED -->
<div class="modal" id="modalImport">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        
        <!-- Modal Header -->
        <div class="modal-header-custom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>
                    <i data-feather="upload" style="width: 24px; height: 24px;"></i>
                    Import Data dari Excel
                </h2>
                <button type="button" class="close-btn" data-dismiss="modal">
                    <i data-feather="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="alert-info-custom">
            <strong>📋 Format Excel yang Diperlukan:</strong>
            <div style="margin-top: 0.5rem;">
                <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; margin-top: 0.5rem;">
                    <span>• Kolom A:</span> <span>Tanggal (DD/MM/YYYY)</span>
                    <span>• Kolom B:</span> <span>Nama Barang / Keperluan</span>
                    <span>• Kolom C:</span> <span>QTY (Jumlah)</span>
                    <span>• Kolom D:</span> <span>Kategori</span>
                    <span>• Kolom E:</span> <span>Harga Satuan</span>
                </div>
                <div style="margin-top: 1rem; font-size: 0.875rem; color: #1e3a8a;">
                    <strong>📝 Catatan:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        <li>Data dengan tanggal <strong>DAN kategori</strong> yang sama akan dikelompokkan dalam satu batch</li>
                        <li>Data dengan tanggal sama tapi kategori berbeda akan dibuat batch terpisah</li>
                        <li>Total harga akan dihitung otomatis dari QTY × Harga Satuan</li>
                        <li>Untuk beberapa item pada tanggal yang sama, bisa menggunakan cell merge pada kolom Tanggal (opsional, untuk tampilan lebih rapi)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Download Template -->
        <div style="background: #f0f7ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
            <a href="<?= site_url('HR/download_template_pencatatan'); ?>" style="color: #0041c3; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="download" style="width: 20px; height: 20px;"></i>
                Download Template Excel (.xls)
            </a>
        </div>

        <form action="<?= site_url('HR/import_pencatatan'); ?>" method="POST" enctype="multipart/form-data">
            
            <!-- File Upload -->
            <div class="form-group-custom">
                <label class="form-label-custom">
                    <i data-feather="file" style="width: 16px; height: 16px; display: inline; margin-right: 4px;"></i>
                    Pilih File Excel <span class="required">*</span>
                </label>
                <input type="file" name="file_excel" class="form-control-custom" accept=".xls,.xlsx" required>
                <small style="color: #6b7280; font-size: 0.813rem; display: block; margin-top: 0.5rem;">
                    💡 Format: .xls (recommended) atau .xlsx
                </small>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-custom">
                <button type="button" class="btn-custom btn-cancel" data-dismiss="modal">
                    <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                    Batal
                </button>
                <button type="submit" class="btn-custom btn-submit">
                    <i data-feather="upload" style="width: 18px; height: 18px;"></i>
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Batch -->
<div class="modal" id="modalBatchDetail">
    <div class="modal__content modal__content--lg p-5 intro-y box" style="max-height: 90vh; overflow-y: auto;">

        <!-- Modal Header -->
        <div class="modal-header-custom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>
                    <i data-feather="eye" style="width: 24px; height: 24px;"></i>
                    Detail Pencatatan Batch
                </h2>
                <button type="button" class="close-btn" data-dismiss="modal">
                    <i data-feather="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
        </div>

        <div id="batchDetailContent">
            <!-- Content will be loaded here -->
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer-custom">
            <button type="button" class="btn-custom btn-cancel" data-dismiss="modal">
                <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Image Viewer -->
<div class="modal" id="modalImageViewer">
    <div class="modal__content modal__content--lg p-3 intro-y box" style="max-height: 95vh; max-width: 95vw;">

        <!-- Modal Header -->
        <div class="modal-header-custom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>
                    <i data-feather="image" style="width: 24px; height: 24px;"></i>
                    Gambar Bukti
                </h2>
                <button type="button" class="close-btn" data-dismiss="modal">
                    <i data-feather="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
        </div>

        <div style="text-align: center; padding: 1rem;">
            <img id="viewerImage" src="" alt="Gambar Bukti" style="max-width: 100%; max-height: 80vh; object-fit: contain; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer-custom">
            <button type="button" class="btn-custom btn-cancel" data-dismiss="modal">
                <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                Tutup
            </button>
            <a id="downloadImageBtn" href="" download class="btn-custom btn-submit">
                <i data-feather="download" style="width: 18px; height: 18px;"></i>
                Download
            </a>
        </div>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    // Preview Image Function
    function previewImage(event) {
        const file = event.target.files[0];
        const modal = $(event.target).closest('.modal');
        const preview = modal.find('#imagePreview');
        const previewImg = modal.find('#previewImg');
        const uploadArea = modal.find('#imageUploadArea');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg.length) previewImg[0].src = e.target.result;
                if (preview.length) preview.show();
                if (uploadArea.length) uploadArea.addClass('has-image');
            };
            reader.readAsDataURL(file);
        }
    }


    $(document).ready(function () {
        // Initialize Feather Icons
        feather.replace();

        // Initialize DataTable
        try {
            $('#tablePencatatan').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data",
                    zeroRecords: "Data tidak ditemukan"
                },
                order: [[6, 'desc']]
            });
        } catch (e) {
            console.log('DataTable initialization failed:', e);
        }

        // Delete Confirmation
        $('.onclick-confirm').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Hapus data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Add Item Functionality
        let itemCount = 1;

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
            console.log('addItemClickHandler called');
            $('#addItemBtn').on('click', function() {
                console.log('Add item button clicked');
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
            const modal = $(this).closest('.modal');
            const preview = modal.find('#imagePreview');
            const uploadArea = modal.find('#imageUploadArea');
            const fileInput = modal.find('#gambar');

            if (preview.length) preview.hide();
            if (uploadArea.length) uploadArea.removeClass('has-image');
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
                // Reset to valid range
                if (targetCount < 1) $(this).val(1);
                if (targetCount > 50) $(this).val(50);
            }
        });

        // Attach add item handler directly on document ready
        addItemClickHandler();

        // Filter type toggle
        $('input[name="filter_type"]').on('change', function() {
            if ($(this).val() == 'month') {
                $('#month_filter').show();
                $('#date_filter').hide();
            } else {
                $('#month_filter').hide();
                $('#date_filter').show();
            }
        });

        // Modal event handlers
        $('#modalPencatatan, #modalImport').on('shown.bs.modal', function() {
            console.log('Modal shown:', $(this).attr('id'));
            feather.replace();
        });

        // Fix: Ensure body scroll is restored when modal is closed
        $('[data-dismiss="modal"]').on('click', function() {
            setTimeout(function() {
                if (!$('.modal.show').length) {
                    $('body').removeClass('overflow-y-hidden').css('padding-right', '');
                }
            }, 300);
        });

        $('.modal').on('click', function(e) {
            if (e.target === this) {
                setTimeout(function() {
                    if (!$('.modal.show').length) {
                        $('body').removeClass('overflow-y-hidden').css('padding-right', '');
                    }
                }, 300);
            }
        });
    });

    // Show batch detail modal
    function showBatchDetail(batchId) {
        // Show loading
        $('#batchDetailContent').html(`
            <div style="text-align: center; padding: 2rem;">
                <i data-feather="loader" style="width: 48px; height: 48px; color: #6b7280;"></i>
                <p style="margin-top: 1rem; color: #6b7280;">Memuat detail...</p>
            </div>
        `);
        feather.replace();

        // Show modal
        $('#modalBatchDetail').modal('show');

        // Load batch detail via AJAX
        $.ajax({
            url: '<?= site_url("HR/get_batch_detail"); ?>',
            type: 'GET',
            data: { batch_id: batchId },
            success: function(response) {
                $('#batchDetailContent').html(response);
                feather.replace();
            },
            error: function() {
                $('#batchDetailContent').html(`
                    <div style="text-align: center; padding: 2rem; color: #ef4444;">
                        <i data-feather="alert-circle" style="width: 48px; height: 48px;"></i>
                        <p style="margin-top: 1rem;">Gagal memuat detail batch</p>
                    </div>
                `);
                feather.replace();
            }
        });
    }

    // Edit batch function
    function editBatch(batchId) {
        window.location.href = '<?= site_url("HR/edit_batch_pencatatan"); ?>/' + batchId;
    }

    // Show image viewer modal
    function showImageModal(imageSrc) {
        $('#viewerImage').attr('src', imageSrc);
        $('#downloadImageBtn').attr('href', imageSrc);
        $('#modalImageViewer').modal('show');
    }
</script>