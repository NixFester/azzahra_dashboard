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
                <i data-feather="activity" class="w-10 h-10 inline-block mr-2"></i>
                Hitung Rekap Performa Bulanan
            </h1>
            <p class="page-subtitle">
                <i data-feather="bar-chart-2"></i>
                Hitung akumulasi poin performa bulanan dari data mingguan
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
                 <h4 class="mb-0">Form Hitung Rekap Bulanan</h4>
             </div>

             <div style="padding: 1.5rem;">
                 <form action="<?= site_url('HR/calculate_monthly_performance'); ?>" method="POST" id="calculate-form">
                     <div class="mb-4">
                         <label class="font-medium text-sm">Periode Bulan <span class="text-red-500">*</span></label>
                         <input type="month" name="periode" id="periode" class="form-control w-full mt-1" required
                                value="<?= date('Y-m'); ?>">
                         <small class="text-muted">Sistem akan menghitung akumulasi poin dari semua minggu dalam bulan ini</small>
                     </div>

                     <div class="flex justify-end gap-2 pt-3 border-t">
                         <a href="<?= site_url('HR/karyawan'); ?>" class="btn btn-secondary py-2 px-4">Batal</a>
                         <button type="submit" class="btn btn-primary py-2 px-4">
                             <i data-feather="activity"></i> Hitung Rekap
                         </button>
                     </div>
                 </form>
             </div>
         </div>

         <!-- Results Section -->
         <div id="results-section" style="display: none;">
             <div class="chart-card">
                 <div class="chart-header">
                     <h4 class="mb-0">Hasil Rekap Performa Bulanan</h4>
                     <small class="text-muted" id="results-periode"></small>
                 </div>

                 <div style="padding: 1.5rem;">
                     <!-- Performance Levels Summary -->
                     <div class="row mb-4">
                         <div class="col-md-3">
                             <div class="text-center p-3 bg-light rounded">
                                 <h5 class="text-success">Top Performer</h5>
                                 <span class="h4" id="top-performer-count">0</span>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="text-center p-3 bg-light rounded">
                                 <h5 class="text-primary">Advanced</h5>
                                 <span class="h4" id="advanced-count">0</span>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="text-center p-3 bg-light rounded">
                                 <h5 class="text-warning">Intermediate</h5>
                                 <span class="h4" id="intermediate-count">0</span>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="text-center p-3 bg-light rounded">
                                 <h5 class="text-danger">Beginner</h5>
                                 <span class="h4" id="beginner-count">0</span>
                             </div>
                         </div>
                     </div>

                     <!-- Performance Chart -->
                     <div class="mb-4">
                         <canvas id="performanceChart" width="400" height="200"></canvas>
                     </div>

                     <!-- Rankings Table -->
                     <div class="table-responsive">
                         <table class="table table-striped">
                             <thead>
                                 <tr>
                                     <th>Ranking</th>
                                     <th>Nama Karyawan</th>
                                     <th>Level</th>
                                     <th>Total Poin</th>
                                     <th>Persentase</th>
                                     <th>Level Performa</th>
                                 </tr>
                             </thead>
                             <tbody id="rankings-table">
                                 <!-- Rankings will be loaded here -->
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>

<?php $this->load->view('Template/footer'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        // Initialize Feather Icons
        feather.replace();

        // Handle form submission
        $('#calculate-form').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Load results
                    loadResults($('#periode').val());
                },
                error: function() {
                    alert('Error calculating performance');
                }
            });
        });

        function loadResults(periode) {
            $.ajax({
                url: '<?= site_url("HR/get_monthly_performance"); ?>',
                type: 'GET',
                data: { periode: periode },
                success: function(response) {
                    var data = JSON.parse(response);
                    displayResults(data, periode);
                },
                error: function() {
                    alert('Error loading results');
                }
            });
        }

        function displayResults(data, periode) {
            $('#results-periode').text('Periode: ' + periode);

            // Count levels
            var counts = { 'Top Performer': 0, 'Advanced': 0, 'Intermediate': 0, 'Beginner': 0 };
            data.forEach(function(item) {
                counts[item.level]++;
            });

            $('#top-performer-count').text(counts['Top Performer']);
            $('#advanced-count').text(counts['Advanced']);
            $('#intermediate-count').text(counts['Intermediate']);
            $('#beginner-count').text(counts['Beginner']);

            // Build rankings table
            var tableHtml = '';
            data.forEach(function(item) {
                var levelClass = '';
                switch(item.level) {
                    case 'Top Performer': levelClass = 'text-success'; break;
                    case 'Advanced': levelClass = 'text-primary'; break;
                    case 'Intermediate': levelClass = 'text-warning'; break;
                    case 'Beginner': levelClass = 'text-danger'; break;
                }

                tableHtml += `
                    <tr>
                        <td>${item.ranking}</td>
                        <td>${item.kry_nama}</td>
                        <td>${item.kry_level}</td>
                        <td>${item.total_points}</td>
                        <td>${item.percentage}%</td>
                        <td class="${levelClass}"><strong>${item.level}</strong></td>
                    </tr>
                `;
            });
            $('#rankings-table').html(tableHtml);

            // Create chart
            var ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Top Performer', 'Advanced', 'Intermediate', 'Beginner'],
                    datasets: [{
                        label: 'Jumlah Karyawan',
                        data: [counts['Top Performer'], counts['Advanced'], counts['Intermediate'], counts['Beginner']],
                        backgroundColor: [
                            'rgba(25, 135, 84, 0.8)',
                            'rgba(13, 110, 253, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgba(25, 135, 84, 1)',
                            'rgba(13, 110, 253, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            $('#results-section').show();
        }
    });
</script>