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
    .modal-form-section {
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .modal-form-section h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-form-section h3 i {
        color: #0041c3;
    }

    .form-group-enhanced {
        margin-bottom: 1rem;
    }

    .form-group-enhanced label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-group-enhanced label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control-enhanced {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control-enhanced:focus {
        outline: none;
        border-color: #0041c3;
        box-shadow: 0 0 0 3px rgba(0, 65, 195, 0.1);
    }

    .form-control-enhanced::placeholder {
        color: #94a3b8;
    }

    select.form-control-enhanced {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23475569' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2.5rem;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }

    .input-icon-wrapper .form-control-enhanced {
        padding-left: 2.5rem;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .modal-footer-enhanced {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        margin-top: 1.5rem;
    }

    .btn-cancel {
        padding: 0.625rem 1.5rem;
        background: white;
        color: #64748b;
        border: 1.5px solid #cbd5e1;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #475569;
    }

    .btn-submit {
        padding: 0.625rem 1.5rem;
        background: #0041c3;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: #003399;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 65, 195, 0.3);
    }

    /* WhatsApp Button Style */
    .whatsapp-link {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        background: #25D366;
        color: white !important;
        border-radius: 0.375rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .whatsapp-link:hover {
        background: #20BA5A;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 211, 102, 0.3);
        color: white !important;
    }

    .whatsapp-link i {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="user-check" class="w-10 h-10 inline-block mr-2"></i>
                Interview Kandidat
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Kelola data kandidat interview
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a role="button" class="btn btn-outline" data-toggle="modal" data-target="#modalImport">
                <i data-feather="upload"></i> Import Excel
            </a>
            <a role="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInterview">
                <i data-feather="plus-circle"></i> Tambah Kandidat
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
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label font-weight-bold text-muted small text-uppercase">Pilih Bulan - Tahun</label>
                        <input type="month" name="bulan_tahun" class="form-control" value="<?= $selected_bulan_tahun ?: ''; ?>">
                    </div>
                    <div class="col-md-8 text-md-right">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i data-feather="filter"></i> Filter
                        </button>
                        <a href="<?= site_url('HR/interview'); ?>" class="btn btn-outline">
                            <i data-feather="refresh-cw"></i> Tampilkan Semua
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Data Interview<?= $selected_bulan_tahun ? ' - ' . date('F Y', strtotime($selected_bulan_tahun . '-01')) : ' (Semua Data)'; ?></h3>
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
                    <table id="tableInterview" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Kandidat</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Domisili</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Ket. Domisili</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No HP</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Role / Divisi</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Status</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Keterangan</th>
                                <th class="border-0 text-right" style="font-weight: 600; color: #4b5563;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (!empty($interview_list)):
                                foreach ($interview_list as $row):
                                    $status_class = '';
                                    switch(strtolower($row['status'])) {
                                        case 'sudah dihubungi': $status_class = 'badge-success'; break;
                                        case 'belum dihubungi': $status_class = 'badge-secondary'; break;
                                        case 'menunggu konfirmasi': $status_class = 'badge-warning'; break;
                                        case 'ditolak': $status_class = 'badge-danger'; break;
                                        default: $status_class = 'badge-info';
                                    }
                                    
                                    // Format phone number for WhatsApp
                                    $phone = $row['no_hp'];
                                    $wa_number = preg_replace('/[^0-9]/', '', $phone);
                                    if (substr($wa_number, 0, 1) === '0') {
                                        $wa_number = '62' . substr($wa_number, 1);
                                    }
                                    $wa_link = "https://wa.me/" . $wa_number;
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nama_kandidat']); ?></td>
                                        <td><span class="badge badge-primary px-3 py-1"><?= htmlspecialchars($row['domisili']); ?></span></td>
                                        <td><span class="badge badge-secondary px-3 py-1"><?= htmlspecialchars($row['keterangan_domisili']); ?></span></td>
                                        <td class="text-center">
                                            <a href="<?= $wa_link; ?>" target="_blank" class="whatsapp-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                <?= htmlspecialchars($row['no_hp']); ?>
                                            </a>
                                        </td>
                                        <td><span class="badge badge-info px-3 py-1"><?= htmlspecialchars($row['divisi']); ?></span></td>
                                        <td><span class="badge <?= $status_class ?> px-3 py-1"><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td><?= htmlspecialchars($row['keterangan_konfirmasi']); ?></td>
                                        <td class="text-right">
                                            <a href="<?= site_url('HR/delete_interview/' . $row['interview_id']); ?>"
                                                class="btn btn-sm btn-outline-danger onclick-confirm" title="Hapus">
                                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data interview</p>
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

<!-- Modal Input Interview -->
<div class="modal" id="modalInterview">
    <div class="modal__content modal__content--lg p-5 intro-y box" style="max-height: 90vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-medium" style="font-size: 1.25rem; font-weight: 600; color: #1e293b;">Tambah Kandidat Interview</h2>
                <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Lengkapi informasi kandidat yang akan diinterview</p>
            </div>
            <a role="button" data-dismiss="modal" class="text-gray-500 hover:text-gray-700" style="cursor: pointer;">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        
        <form action="<?= site_url('HR/save_interview'); ?>" method="POST">
            <!-- Personal Information Section -->
            <div class="modal-form-section">
                <h3>
                    <i data-feather="user"></i>
                    Informasi Personal
                </h3>
                
                <div class="form-grid-2">
                    <div class="form-group-enhanced">
                        <label>Nama Kandidat <span class="required">*</span></label>
                        <div class="input-icon-wrapper">
                            <i data-feather="user"></i>
                            <input type="text" name="nama_kandidat" class="form-control-enhanced" required
                                placeholder="Contoh: Fakhrul Khusaeni">
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label>No HP / WhatsApp <span class="required">*</span></label>
                        <div class="input-icon-wrapper">
                            <i data-feather="phone"></i>
                            <input type="text" name="no_hp" class="form-control-enhanced" required
                                placeholder="Contoh: 0895704307742">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information Section -->
            <div class="modal-form-section">
                <h3>
                    <i data-feather="map-pin"></i>
                    Informasi Lokasi
                </h3>
                
                <div class="form-grid-2">
                    <div class="form-group-enhanced">
                        <label>Domisili <span class="required">*</span></label>
                        <div class="input-icon-wrapper">
                            <i data-feather="home"></i>
                            <input type="text" name="domisili" class="form-control-enhanced" required
                                placeholder="Contoh: Tegal">
                        </div>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label>Keterangan Domisili</label>
                        <select name="keterangan_domisili" class="form-control-enhanced">
                            <option value="">-- Pilih Keterangan --</option>
                            <option value="Terjangkau">Terjangkau</option>
                            <option value="Tidak Terjangkau">Tidak Terjangkau</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Job Information Section -->
            <div class="modal-form-section">
                <h3>
                    <i data-feather="briefcase"></i>
                    Informasi Pekerjaan
                </h3>
                
                <div class="form-group-enhanced">
                    <label>Role / Divisi <span class="required">*</span></label>
                    <select name="divisi" class="form-control-enhanced" required>
                        <option value="">-- Pilih Role / Divisi --</option>
                        <option value="Programmer/IT">Programmer/IT</option>
                        <option value="Admin/Customer Service">Admin/Customer Service</option>
                        <option value="Accounting">Accounting</option>
                        <option value="Human Resource">Human Resource</option>
                        <option value="Digital Marketing">Digital Marketing</option>
                        <option value="Social Media Specialist">Social Media Specialist</option>
                        <option value="Content & Marketing Specialist">Content & Marketing Specialist</option>
                    </select>
                </div>
            </div>

            <!-- Status Information Section -->
            <div class="modal-form-section">
                <h3>
                    <i data-feather="check-circle"></i>
                    Status & Keterangan
                </h3>
                
                <div class="form-grid-2">
                    <div class="form-group-enhanced">
                        <label>Status Interview <span class="required">*</span></label>
                        <select name="status" class="form-control-enhanced" required>
                            <option value="Belum dihubungi" selected>Belum dihubungi</option>
                            <option value="Sudah dihubungi">Sudah dihubungi</option>
                            <option value="Menunggu konfirmasi">Menunggu konfirmasi</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label>Keterangan Konfirmasi</label>
                        <input type="text" name="keterangan_konfirmasi" class="form-control-enhanced"
                            placeholder="Contoh: Menunggu balasan kandidat">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer-enhanced">
                <button type="button" data-dismiss="modal" class="btn-cancel">
                    <i data-feather="x" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i data-feather="save" style="width: 16px; height: 16px;"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal" id="modalImport">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Import Data Interview dari Excel</h2>
            <a role="button" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>

        <div class="mb-4">
            <div class="alert alert-info">
                <strong>Format Excel:</strong><br>
                Kolom A: Nama Kandidat<br>
                Kolom B: Domisili<br>
                Kolom C: No HP<br>
                Kolom D: Role / Divisi<br>
                Kolom E: Status<br>
                Kolom F: Keterangan<br>
                Kolom G: Keterangan Domisili
                <br><br>
                <strong>Catatan:</strong> Untuk file XLSX diperlukan ekstensi ZipArchive. Jika tidak tersedia, gunakan format XLS.
            </div>
            <div class="mt-2">
                <a href="<?= site_url('HR/download_template_interview'); ?>" class="text-blue-600 hover:text-blue-800">
                    <i data-feather="download"></i> Download Template Excel (.xls)
                </a>
            </div>
        </div>

        <form action="<?= site_url('HR/import_interview'); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="font-medium text-sm">Pilih File Excel <span class="text-red-500">*</span></label>
                <input type="file" name="file_excel" class="form-control w-full mt-1" accept=".xls,.xlsx" required>
                <small class="text-muted">Format: .xls (recommended) atau .xlsx (jika ZipArchive tersedia)</small>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <a role="button" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    <i data-feather="upload"></i> Import Data
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    $(document).ready(function () {
        // Initialize Feather Icons
        feather.replace();

        // Initialize DataTable
        $('#tableInterview').DataTable({
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
            order: [[0, 'asc']]
        });

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

        // Fix: Ensure body scroll is restored when modal is closed
        $('[data-dismiss="modal"]').on('click', function() {
            setTimeout(function() {
                if (!$('.modal.show').length) {
                    $('body').removeClass('overflow-y-hidden').css('padding-right', '');
                }
                feather.replace();
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

        // Re-initialize Feather icons when modal opens
        $('[data-toggle="modal"]').on('click', function() {
            setTimeout(function() {
                feather.replace();
            }, 100);
        });
    });
</script>