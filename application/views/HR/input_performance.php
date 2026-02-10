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
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="plus-circle" class="w-10 h-10 inline-block mr-2"></i>
                Input Poin Performa Mingguan
            </h1>
            <p class="page-subtitle">
                <i data-feather="activity"></i>
                Input poin performa karyawan untuk periode mingguan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="<?= site_url('HR/karyawan'); ?>" class="btn btn-outline">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">
        <!-- Flash Messages -->
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

        <div class="chart-card">
            <div class="chart-header">
                <h4 class="mb-0">Form Input Poin Performa</h4>
            </div>

            <div style="padding: 1.5rem;">
                <form action="<?= site_url('HR/save_performance_points'); ?>" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-medium text-sm">Karyawan <span class="text-red-500">*</span></label>
                            <select name="kry_kode" id="kry_kode" class="form-control w-full mt-1" required>
                                <option value="">-- Pilih Karyawan --</option>
                                <?php if (!empty($karyawan_list)):
                                    foreach ($karyawan_list as $k): ?>
                                        <option value="<?= $k->kry_kode; ?>" data-level="<?= $k->kry_level; ?>"><?= $k->kry_nama; ?> - <?= $k->kry_level; ?></option>
                                    <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-medium text-sm">Periode Minggu <span class="text-red-500">*</span></label>
                            <input type="week" name="periode" class="form-control w-full mt-1" required
                                   value="<?= date('Y-\WW'); ?>">
                        </div>
                    </div>

                    <div class="criteria-section" id="criteria-section" style="display: none;">
                        <h4 class="mb-3">Kriteria Penilaian</h4>
                        <div id="criteria-container">
                            <!-- Criteria will be loaded here via AJAX -->
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <a href="<?= site_url('HR/karyawan'); ?>" class="btn btn-secondary py-2 px-4">Batal</a>
                        <button type="submit" class="btn btn-primary py-2 px-4">
                            <i data-feather="save"></i> Simpan Poin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    $(document).ready(function () {
        // Initialize Feather Icons
        feather.replace();

        // Handle employee selection change
        $('#kry_kode').change(function() {
            var selectedOption = $(this).find('option:selected');
            var kryLevel = selectedOption.data('level');

            if (kryLevel) {
                // Determine type based on level
                var type = (kryLevel.toLowerCase().includes('magang') || kryLevel.toLowerCase().includes('intern')) ? 'magang' : 'karyawan';

                // Load criteria for this type
                loadCriteria(type);
            } else {
                $('#criteria-section').hide();
            }
        });

        function loadCriteria(type) {
            $.ajax({
                url: '<?= site_url("HR/get_performance_criteria"); ?>',
                type: 'GET',
                data: { type: type },
                success: function(response) {
                    var criteria = JSON.parse(response);
                    var html = '';

                    if (criteria.length > 0) {
                        criteria.forEach(function(crit) {
                            html += `
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-4">
                                        <label class="font-medium text-sm">${crit.criteria_name}</label>
                                        <small class="text-muted d-block">Max: ${crit.max_points} poin</small>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="criteria_points[${crit.criteria_id}]"
                                               class="form-control" min="0" max="${crit.max_points}" placeholder="0">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="notes_${crit.criteria_id}"
                                               class="form-control" placeholder="Catatan (opsional)">
                                    </div>
                                </div>
                            `;
                        });
                        $('#criteria-container').html(html);
                        $('#criteria-section').show();
                    } else {
                        $('#criteria-container').html('<p class="text-muted">Tidak ada kriteria untuk tipe ini.</p>');
                        $('#criteria-section').show();
                    }
                },
                error: function() {
                    alert('Error loading criteria');
                }
            });
        }
    });
</script>