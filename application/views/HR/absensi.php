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

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-buttons .btn {
        background: white;
        color: #0041c3;
        border: 1px solid #0041c3;
        backdrop-filter: none;
        box-shadow: none;
    }

    .filter-buttons .btn-primary {
        background: #0041c3;
        color: white;
        border-color: #0041c3;
    }

    .filter-buttons .btn:hover {
        background: #003399;
        color: white;
        border-color: #003399;
    }

    .filter-buttons .btn-primary:hover {
        background: #003399;
        border-color: #003399;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="clock" class="w-10 h-10 inline-block mr-2"></i>
                Absensi Karyawan
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                Kelola kehadiran harian karyawan
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modalAbsen">
                <i data-feather="plus-circle"></i> Input Absensi
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter Card -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Filter Periode</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div class="filter-buttons" style="margin-bottom: 1rem;">
                    <a href="<?= site_url('HR/absensi?periode=hari_ini'); ?>" class="btn <?= $selected_periode == 'hari_ini' ? 'btn-primary' : 'btn-outline'; ?>">Hari Ini</a>
                    <a href="<?= site_url('HR/absensi?periode=minggu_ini'); ?>" class="btn <?= $selected_periode == 'minggu_ini' ? 'btn-primary' : 'btn-outline'; ?>">Minggu Ini</a>
                    <a href="<?= site_url('HR/absensi?periode=bulan_ini'); ?>" class="btn <?= $selected_periode == 'bulan_ini' ? 'btn-primary' : 'btn-outline'; ?>">Bulan Ini</a>
                </div>
                <form action="" method="GET" class="row align-items-end">
                    <input type="hidden" name="periode" value="tanggal">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label font-weight-bold text-muted small text-uppercase">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= isset($selected_tanggal) ? $selected_tanggal : $selected_date; ?>"
                            onchange="this.form.submit()">
                    </div>
                    <div class="col-md-6 text-md-right">
                        <a href="<?= site_url('HR/export_absensi_csv?periode=' . $selected_date . '&tipe=harian'); ?>"
                            class="btn btn-outline">
                            <i data-feather="download"></i> Download CSV
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Absensi Offline -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Data Absensi Offline -
                    <?php
                    if ($selected_periode == 'hari_ini') echo 'Hari Ini';
                    elseif ($selected_periode == 'minggu_ini') echo 'Minggu Ini';
                    elseif ($selected_periode == 'bulan_ini') echo 'Bulan Ini';
                    elseif ($selected_periode == 'tanggal') echo date('d F Y', strtotime($selected_date));
                    ?>
                </h3>
            </div>
            <div style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table id="tableAbsensiOffline" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No Kartu</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Karyawan</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Alamat</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Masuk</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Istirahat</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Kembali</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Pulang</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Presentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (!empty($absensi_offline)):
                                foreach ($absensi_offline as $row):
                                    // Format phone number for WhatsApp
                                    // $phone = $row['no_hp'];
                                    // $wa_number = preg_replace('/[^0-9]/', '', $phone);
                                    // if (substr($wa_number, 0, 1) === '0') {
                                    //     $wa_number = '62' . substr($wa_number, 1);
                                    // }
                                    // $wa_link = "https://wa.me/" . $wa_number;
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nokartu']); ?>
                                        </td>
                                         <!-- <td class="text-center">
                                            <a href="<?= $wa_link; ?>" target="_blank" class="whatsapp-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                <?= htmlspecialchars($row['no_hp']); ?>
                                            </a>
                                        </td> -->
                                        <td class="text-muted"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                                        <td><?= $row['jam_masuk'] ? date('H:i:s', strtotime($row['jam_masuk'])) : '-'; ?></td>
                                        <td><?= $row['jam_istirahat'] && $row['jam_istirahat'] != '00:00:00' ? date('H:i:s', strtotime($row['jam_istirahat'])) : '-'; ?></td>
                                        <td><?= $row['jam_kembali'] && $row['jam_kembali'] != '00:00:00' ? date('H:i:s', strtotime($row['jam_kembali'])) : '-'; ?></td>
                                        <td><?= $row['jam_pulang'] && $row['jam_pulang'] != '00:00:00' ? date('H:i:s', strtotime($row['jam_pulang'])) : '-'; ?></td>
                                        <td class="text-right">
                                            <?php
                                            $komponen = 0;
                                            if ($row['jam_masuk'] && $row['jam_masuk'] != '00:00:00') $komponen++;
                                            if ($row['jam_istirahat'] && $row['jam_istirahat'] != '00:00:00') $komponen++;
                                            if ($row['jam_kembali'] && $row['jam_kembali'] != '00:00:00') $komponen++;
                                            if ($row['jam_pulang'] && $row['jam_pulang'] != '00:00:00') $komponen++;
                                            $presentase = ($komponen / 4) * 100;
                                            ?>
                                            <span class="badge" style="background-color: <?= $presentase == 100 ? '#10b981' : ($presentase >= 50 ? '#f59e0b' : '#ef4444') ?>; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; margin-right:60px;">
                                                <?= round($presentase) ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data absensi offline untuk tanggal ini</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Data Absensi WFH -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Data Absensi WFH -
                    <?php
                    if ($selected_periode == 'hari_ini') echo 'Hari Ini';
                    elseif ($selected_periode == 'minggu_ini') echo 'Minggu Ini';
                    elseif ($selected_periode == 'bulan_ini') echo 'Bulan Ini';
                    elseif ($selected_periode == 'tanggal') echo date('d F Y', strtotime($selected_date));
                    ?>
                </h3>
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
                    <table id="tableAbsensiWFH" class="table table-hover w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">No Kartu</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Nama Karyawan</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Alamat</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Masuk</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Jam Pulang</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Lokasi</th>
                                <th class="border-0" style="font-weight: 600; color: #4b5563;">Gambar</th>
                                <th class="border-0 text-center" style="font-weight: 600; color: #4b5563;">Presentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (!empty($absensi_wfh)):
                                foreach ($absensi_wfh as $row):
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['nokartu']); ?>
                                        </td>
                                        <td class="text-muted"><?= htmlspecialchars($row['nama_karyawan']); ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                                        <td><?= $row['jam_masuk'] ? date('H:i:s', strtotime($row['jam_masuk'])) : '-'; ?></td>
                                        <td><?= $row['jam_pulang'] && $row['jam_pulang'] != '00:00:00' ? date('H:i:s', strtotime($row['jam_pulang'])) : '-'; ?></td>
                                        <td class="text-muted small"><?= htmlspecialchars($row['lokasi'] ?? '-'); ?></td>
                                        <td>
                                            <?php if (!empty($row['gambar'])): ?>
                                                <a href="https://azzahracomputertegal.com<?= $row['gambar']; ?>" target="_blank">
                                                    <img src="https://azzahracomputertegal.com<?= $row['gambar']; ?>" alt="Gambar WFH" style="max-width: 50px; max-height: 50px; border-radius: 4px;">
                                                </a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $komponen = 0;
                                            if ($row['jam_masuk'] && $row['jam_masuk'] != '00:00:00') $komponen++;
                                            if ($row['jam_pulang'] && $row['jam_pulang'] != '00:00:00') $komponen++;
                                            if (!empty($row['lokasi'])) $komponen++;
                                            if (!empty($row['gambar'])) $komponen++;
                                            $presentase = ($komponen / 4) * 100;
                                            ?>
                                            <span class="badge" style="background-color: <?= $presentase == 100 ? '#10b981' : ($presentase >= 50 ? '#f59e0b' : '#ef4444') ?>; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                                <?= round($presentase) ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="mt-2">Belum ada data absensi WFH untuk tanggal ini</p>
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

<!-- Modal Input Absensi -->
<div class="modal" id="modalAbsen">
    <div class="modal__content modal__content--md p-5 intro-y box" style="max-height: 85vh; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Input Absensi Harian</h2>
            <a href="javascript:;" data-dismiss="modal" class="text-gray-500 hover:text-gray-700">
                <i data-feather="x" class="w-5 h-5"></i>
            </a>
        </div>
        <form action="<?= site_url('HR/save_absensi'); ?>" method="POST">
            <input type="hidden" name="tanggal" value="<?= $selected_date; ?>">

            <div class="mb-3">
                <label class="font-medium text-sm">Karyawan <span class="text-red-500">*</span></label>
                <select name="id_karyawan" class="form-control w-full mt-1" required>
                    <option value="">-- Pilih Karyawan --</option>
                    <?php if (!empty($karyawan_list)):
                        foreach ($karyawan_list as $k): ?>
                            <option value="<?= $k->kry_kode; ?>"><?= $k->kry_nama; ?> - <?= $k->kry_level; ?></option>
                        <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="font-medium text-sm">Status Kehadiran <span class="text-red-500">*</span></label>
                <select name="status" id="statusSelect" class="form-control w-full mt-1" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Telat">Telat</option>
                    <option value="Alpa">Alpa</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3" id="jamRow">
                <div>
                    <label class="font-medium text-sm">Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="form-control w-full mt-1" value="08:00">
                </div>
                <div>
                    <label class="font-medium text-sm">Jam Pulang</label>
                    <input type="time" name="jam_pulang" class="form-control w-full mt-1" value="17:00">
                </div>
            </div>

            <div class="mb-4">
                <label class="font-medium text-sm">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control w-full mt-1" rows="2"
                    placeholder="Contoh: Izin sakit perut..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <a href="javascript:;" data-dismiss="modal" class="btn btn-secondary py-2 px-4">Batal</a>
                <button type="submit" class="btn btn-primary py-2 px-4" style="background-color: #3b82f6; color: white; border: none;">
                    Simpan Data
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

        // Initialize DataTable for Offline
        $('#tableAbsensiOffline').DataTable({
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
            order: [[1, 'asc']]
        });

        // Initialize DataTable for WFH
        $('#tableAbsensiWFH').DataTable({
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
            order: [[1, 'asc']]
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

        // Toggle Jam Input based on Status
        $('#statusSelect').change(function () {
            const val = $(this).val();
            if (val === 'Hadir' || val === 'Telat') {
                $('#jamRow').show();
                $('input[name="jam_masuk"]').prop('required', true);
            } else {
                $('#jamRow').hide();
                $('input[name="jam_masuk"]').prop('required', false);
            }
        });

        // Trigger on page load
        $('#statusSelect').trigger('change');

        // Custom modal handling
        $('[data-toggle="modal"][data-target="#modalAbsen"]').on('click', function() {
            $('#modalAbsen').show();
            $('body').addClass('overflow-y-hidden').css('padding-right', '17px');
        });

        $('#modalAbsen [data-dismiss="modal"]').on('click', function() {
            $('#modalAbsen').hide();
            $('body').removeClass('overflow-y-hidden').css('padding-right', '');
        });

        $('#modalAbsen').on('click', function(e) {
            if (e.target === this) {
                $(this).hide();
                $('body').removeClass('overflow-y-hidden').css('padding-right', '');
            }
        });
    });
</script>