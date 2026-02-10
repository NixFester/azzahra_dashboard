<?php $this->load->view('Template/header'); ?>

<style>
    .content-area {
        margin-top: 4rem;
        padding: 2rem;
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

    .btn-outline {
        background: white !important;
        color: #0041c3 !important;
        border: 1px solid #0041c3 !important;
    }

    .btn-outline:hover {
        background: #0041c3 !important;
        color: white !important;
    }
</style>

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title-section">
            <h1 class="page-title">
                <i data-feather="home" class="w-10 h-10 inline-block mr-2"></i>
                HR Overview
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar"></i>
                <?= date('l, d F Y'); ?>
            </p>
        </div>
    </div>
    <div class="page-header-right">
        <div class="header-actions">
            <a href="<?= site_url('HR/absensi'); ?>" class="btn btn-outline">
                <i data-feather="clock"></i> Absensi
            </a>
            <a href="<?= site_url('HR/kpi'); ?>" class="btn btn-outline">
                <i data-feather="bar-chart-2"></i> KPI
            </a>
            <a href="<?= site_url('HR/pencatatan'); ?>" class="btn btn-outline">
                <i data-feather="package"></i> Pencatatan
            </a>
        </div>
    </div>
</div>

<div class="content-area">
    <div class="dashboard-container">

        <!-- Filter Section -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="chart-header">
                <h3 class="chart-title">Filter Periode</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div class="filter-buttons" style="margin-bottom: 1rem;">
                    <a href="<?= site_url('HR?periode=hari_ini'); ?>" class="btn <?= $selected_periode == 'hari_ini' ? 'btn-primary' : 'btn-outline'; ?>">Hari Ini</a>
                    <a href="<?= site_url('HR?periode=minggu_ini'); ?>" class="btn <?= $selected_periode == 'minggu_ini' ? 'btn-primary' : 'btn-outline'; ?>">Minggu Ini</a>
                    <a href="<?= site_url('HR?periode=bulan_ini'); ?>" class="btn <?= $selected_periode == 'bulan_ini' ? 'btn-primary' : 'btn-outline'; ?>">Bulan Ini</a>
                </div>
                <form method="get" style="display: inline;">
                    <input type="hidden" name="periode" value="tanggal">
                    <input type="date" name="tanggal" value="<?= isset($selected_tanggal) ? $selected_tanggal : date('Y-m-d'); ?>" class="form-control" style="display: inline-block; width: auto; margin-right: 0.5rem;">
                    <button type="submit" class="btn btn-outline">Filter Tanggal</button>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Kehadiran Card -->
            <div class="stat-card blue">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="users"></i>
                    </div>
                    <div class="stat-status">
                        <?php
                        if ($selected_periode == 'hari_ini') echo 'Hari Ini';
                        elseif ($selected_periode == 'minggu_ini') echo 'Minggu Ini';
                        elseif ($selected_periode == 'bulan_ini') echo 'Bulan Ini';
                        elseif ($selected_periode == 'tanggal') echo 'Tanggal';
                        ?>
                    </div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Kehadiran</div>
                    <div class="stat-value"><?= $stats['hadir']; ?></div>
                    <div class="stat-description">
                        <?php
                        if ($selected_periode == 'hari_ini') echo 'Karyawan hadir tepat waktu';
                        elseif ($selected_periode == 'minggu_ini') echo 'Total hadir minggu ini';
                        elseif ($selected_periode == 'bulan_ini') echo 'Total hadir bulan ini';
                        elseif ($selected_periode == 'tanggal') echo 'Total hadir pada tanggal tersebut';
                        ?>
                    </div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change positive">
                        <i data-feather="trending-up" style="width: 14px; height: 14px;"></i>
                        <span>Active</span>
                    </div>
                    <a href="<?= site_url('HR/absensi?periode=' . $selected_periode . (isset($selected_tanggal) ? '&tanggal=' . $selected_tanggal : '')); ?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>

            <!-- Terlambat/Izin Card -->
            <div class="stat-card orange">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="clock"></i>
                    </div>
                    <div class="stat-status">Warning</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Izin</div>
                    <div class="stat-value"><?= $stats['izin']; ?></div>
                    <div class="stat-description">Perlu review dan tindak lanjut</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change warning">
                        <i data-feather="alert-circle" style="width: 14px; height: 14px;"></i>
                        <span>Review</span>
                    </div>
                    <a href="<?= site_url('HR/absensi?periode=' . $selected_periode . (isset($selected_tanggal) ? '&tanggal=' . $selected_tanggal : '')); ?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>

            <!-- Alpha Card -->
            <div class="stat-card warning" style="border-left-color: #ef4444;">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper" style="color: #ef4444; background: rgba(239, 68, 68, 0.1);">
                        <i data-feather="user-x"></i>
                    </div>
                    <div class="stat-status" style="color: #ef4444; background: rgba(239, 68, 68, 0.1);">Alpha</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Tidak Hadir</div>
                    <div class="stat-value"><?= $stats['alpa']; ?></div>
                    <div class="stat-description">Tanpa keterangan</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change negative">
                        <i data-feather="alert-triangle" style="width: 14px; height: 14px;"></i>
                        <span>Critical</span>
                    </div>
                    <a href="<?= site_url('HR/absensi?periode=' . $selected_periode . (isset($selected_tanggal) ? '&tanggal=' . $selected_tanggal : '')); ?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>

            <!-- KPI Card -->
            <div class="stat-card purple">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="bar-chart-2"></i>
                    </div>
                    <div class="stat-status">Bulanan</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Rata-rata KPI</div>
                    <div class="stat-value"><?= $stats['avg_kpi']; ?></div>
                    <div class="stat-description">Performa tim bulan ini</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change positive">
                        <i data-feather="trending-up" style="width: 14px; height: 14px;"></i>
                        <span>Good</span>
                    </div>
                    <a href="<?= site_url('HR/kpi'); ?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <!-- Attendance Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        Komposisi Kehadiran
                        <?php
                        if ($selected_periode == 'hari_ini') echo 'Hari Ini';
                        elseif ($selected_periode == 'minggu_ini') echo 'Minggu Ini';
                        elseif ($selected_periode == 'bulan_ini') echo 'Bulan Ini';
                        elseif ($selected_periode == 'tanggal') echo 'Tanggal';
                        ?>
                    </h3>
                </div>
                <div class="chart-container">
                    <canvas id="attendanceChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-card">
                <div class="chart-header">
                    <h3 class="chart-title">Detail Absensi</h3>
                </div>
                <div class="activity-list">                   

                    <?php if (!empty($detail_absensi['IZIN']) || !empty($detail_absensi['CUTI'])): ?>
                        <div class="activity-item">
                            <div class="activity-icon info">
                                <i data-feather="info"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Izin / Cuti</div>
                                <div class="activity-description">
                                    <?= count($detail_absensi['IZIN']) + count($detail_absensi['CUTI']); ?> Karyawan
                                    izin/cuti</div>
                            </div>
                            <div class="activity-time">
                                <?php
                                if ($selected_periode == 'hari_ini') echo 'Today';
                                elseif ($selected_periode == 'minggu_ini') echo 'This Week';
                                elseif ($selected_periode == 'bulan_ini') echo 'This Month';
                                elseif ($selected_periode == 'tanggal') echo 'Selected Date';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($detail_absensi['ALPA'])): ?>
                        <div class="activity-item">
                            <div class="activity-icon danger">
                                <i data-feather="x-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Alpha</div>
                                <div class="activity-description"><?= count($detail_absensi['ALPA']); ?> Karyawan alpha
                                    tanpa keterangan</div>
                            </div>
                            <div class="activity-time">
                                <?php
                                if ($selected_periode == 'hari_ini') echo 'Today';
                                elseif ($selected_periode == 'minggu_ini') echo 'This Week';
                                elseif ($selected_periode == 'bulan_ini') echo 'This Month';
                                elseif ($selected_periode == 'tanggal') echo 'Selected Date';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($detail_absensi['TELAT']) && empty($detail_absensi['IZIN']) && empty($detail_absensi['CUTI']) && empty($detail_absensi['ALPA'])): ?>
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i data-feather="check-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Semua Hadir!</div>
                                <div class="activity-description">Tidak ada masalah kehadiran dalam periode ini</div>
                            </div>
                            <div class="activity-time">
                                <?php
                                if ($selected_periode == 'hari_ini') echo 'Today';
                                elseif ($selected_periode == 'minggu_ini') echo 'This Week';
                                elseif ($selected_periode == 'bulan_ini') echo 'This Month';
                                elseif ($selected_periode == 'tanggal') echo 'Selected Date';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Performers Section -->
        <div class="section-header">
            <h2 class="section-title">Top Performance KPI Bulan Ini</h2>
        </div>

        <div class="technician-productivity-card">
            <div class="technician-list">
                <h4>Top Performers</h4>
                <div class="technician-items">
                    <?php
                    if (isset($kpi_data) && is_array($kpi_data) && !empty($kpi_data)) {
                        usort($kpi_data, function ($a, $b) {
                            return $b['rata_rata'] <=> $a['rata_rata']; });
                        $top_kpi = array_slice($kpi_data, 0, 5);
                        $rank = 1;
                        foreach ($top_kpi as $k):
                            $score = floatval($k['rata_rata']);
                            ?>
                            <div class="technician-item">
                                <div class="technician-rank"><?= $rank; ?></div>
                                <div class="technician-name"><?= $k['nama_karyawan']; ?></div>
                                <div class="technician-services"><?= number_format($k['rata_rata'], 2); ?> / 5.0</div>
                            </div>
                            <?php
                            $rank++;
                        endforeach;
                    } else {
                        echo '<div class="technician-item">Belum ada data KPI bulan ini</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize Feather Icons
        feather.replace();

        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const chartData = JSON.parse('<?= $chart_absensi; ?>');
        const total = chartData.reduce((a, b) => a + b, 0);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin/Cuti', 'Terlambat/Alpha'],
                datasets: [{
                    data: total === 0 ? [1, 0, 0] : chartData,
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + ' karyawan';
                                return label;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>