<?php $this->load->view('Template/header'); ?>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1> <i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Laporan</h1>
                <p>Daily Report</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn">
                    <i data-feather="bell"></i>
                    <div class="badge-dot"></div>
                </div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Laporan Hari Ini
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button class="button text-white bg-theme-1 shadow-md mr-2">Print</button>
            <div class="dropdown relative ml-auto sm:ml-0">
                <button class="dropdown-toggle button px-2 box text-gray-700">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                </button>
                <div class="dropdown-box mt-10 absolute w-40 top-0 right-0 z-20">
                    <div class="dropdown-box__content box p-2">
                    <a href="export_pdf" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Export PDF </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y box overflow-hidden mt-5">
    	<div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
            <div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
                <div class="text-xl text-theme-1 font-medium"><?= $cs['kry_nama']?></div>
                <div class="mt-1">Tegal, <?= date('d-F-Y')?></div>
            </div>
        </div>
        <div class="px-5 sm:px-16 py-10 sm:py-20">
	    	<div class="overflow-x-auto">
	    		<table class="table">
	    			<thead>
	    				<tr>
	    					<th class="border-b-2 whitespace-no-wrap">DESCRIPTION</th>
	    					<th class="border-b-2 text-right whitespace-no-wrap">JUMLAH</th>
	    					<th class="border-b-2 text-right whitespace-no-wrap">SUBTOTAL</th>
	    				</tr>
	    			</thead>
	    			<tbody>
	    				<tr>
	    				<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK BCA</div>
	    				                         <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
	    				</td>
	    				<td class="text-right border-b w-32"><?= $jml_dp_bca ?? 0 ?></td>
	    				<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_dp_bca ?? 0, 0).",-"; ?>
	    				</td>
	    				</tr>
	    				<tr>
	    				<td class="border-b ">
	    						<div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK BRI</div>
	    				          	<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
	    				</td>
	    				<td class="text-right border-b w-32"><?= $jml_dp_bri ?? 0 ?></td>
	    				<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_dp_bri ?? 0, 0).",-"; ?>
	    				</td>
	    				</tr>
	    				<tr>
	    				<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK MANDIRI</div>
	    				          	<!-- <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. [Mandiri Account]</div> -->
	    				</td>
	    				<td class="text-right border-b w-32"><?= $jml_dp_mandiri ?? 0 ?></td>
	    				<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_dp_mandiri ?? 0, 0).",-"; ?>
	    				</td>
	    				</tr>
	    				<tr>
	    				<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">DOWN PAYMENT TUNAI</div>
	    				</td>
	    				<td class="text-right border-b w-32"><?= $jml_dp_tunai ?? 0 ?></td>
	    				<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_dp_tunai ?? 0, 0).",-"; ?>
	    				</td>
	    				</tr>
	    				<tr>
	    					<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">PELUNASAN BANK BCA</div>
	    				                         <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $jml_bca->num_rows();?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_bca ?: 0, 0).",-"; ?>
	    					</td>
	    				</tr>
	    				<tr>
	    					<td class="border-b ">
	    						<div class="font-medium whitespace-no-wrap">PELUNASAN BANK BRI</div>
	    				          	<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $jml_bri->num_rows();?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_bri ?: 0, 0).",-"; ?>
	    					</td>
	    				</tr>
	    				<tr>
	    					<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">PELUNASAN BANK MANDIRI</div>
	    				          	<!-- <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. [Mandiri Account]</div> -->
	    					</td>
	    					<td class="text-right border-b w-32"><?= $jml_mandiri->num_rows();?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($tot_mandiri ?: 0, 0).",-"; ?>
	    					</td>
	    				</tr>
	    				<tr>
	    					<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">PELUNASAN TUNAI</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $count_tunai ?? 0 ?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($total_tunai ?? 0, 0).",-"; ?>
	    					</td>
	    				</tr>
	    				<tr>
	    					<td class="border-b">
	    						<div class="font-medium whitespace-no-wrap">STATUS SETOR MENUNGGU</div>
	    					</td>
	    					<td class="text-right border-b w-32"><?= $menunggu_count;?></td>
	    					<td class="text-right border-b w-32">
	    						<?= "Rp. ".number_format($menunggu_total, 0).",-"; ?>
	    					</td>
	    				</tr>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    <!-- DP Payments Detail -->
	    <div class="px-5 sm:px-16 py-10 sm:py-20">
	        <h3 class="text-lg font-medium mb-5">Detail Pembayaran DP Hari Ini</h3>
	        <div class="overflow-x-auto">
	            <table class="table">
	                <thead>
	                    <tr>
	                        <th class="border-b-2 whitespace-no-wrap">TTS</th>
	                        <th class="border-b-2 whitespace-no-wrap">Nama Customer</th>
	                        <th class="border-b-2 text-right whitespace-no-wrap">Jumlah Bayar</th>
	                        <th class="border-b-2 whitespace-no-wrap">Jenis</th>
	                        <th class="border-b-2 whitespace-no-wrap">Status Setor</th>
	                        <th class="border-b-2 whitespace-no-wrap">Waktu</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php if (!empty($dp_payments)): ?>
	                        <?php foreach ($dp_payments as $payment): ?>
	                            <tr>
	                                <td class="border-b"><?= $payment['cos_kode'] ?></td>
	                                <td class="border-b"><?= $payment['cos_nama'] ?></td>
	                                <td class="text-right border-b">
	                                    <?= "Rp. ".number_format($payment['dtl_jml_bayar'], 0).",-"; ?>
	                                </td>
	                                <td class="border-b"><?= $payment['dtl_jenis_bayar'] ?></td>
	                                <td class="border-b"><?= $payment['dtl_stt_stor'] ?></td>
	                                <td class="border-b">
	                                    <div class="font-medium whitespace-no-wrap">
	                                        <?php echo date('d-m-Y', strtotime($payment['dtl_tanggal'])) ?>
	                                    </div>
	                                    <div class="text-gray-600 text-xs whitespace-no-wrap">
	                                        <?= $payment['dtl_jam'] ?>
	                                    </div>
	                                </td>
	                            </tr>
	                        <?php endforeach; ?>
	                    <?php else: ?>
	                        <tr>
	                            <td colspan="6" class="text-center border-b text-gray-500">Tidak ada pembayaran DP hari ini</td>
	                        </tr>
	                    <?php endif; ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	    <!-- Pelunasan Payments Detail -->
	    <div class="px-5 sm:px-16 py-10 sm:py-20">
	        <h3 class="text-lg font-medium mb-5">Detail Pembayaran Pelunasan Hari Ini</h3>
	        <div class="overflow-x-auto">
	            <table class="table">
	                <thead>
	                    <tr>
	                        <th class="border-b-2 whitespace-no-wrap">TTS</th>
	                        <th class="border-b-2 whitespace-no-wrap">Nama Customer</th>
	                        <th class="border-b-2 text-right whitespace-no-wrap">Jumlah Bayar</th>
	                        <th class="border-b-2 whitespace-no-wrap">Jenis</th>
	                        <th class="border-b-2 whitespace-no-wrap">Status Setor</th>
	                        <th class="border-b-2 whitespace-no-wrap">Waktu</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php if (!empty($lunas_payments)): ?>
	                        <?php foreach ($lunas_payments as $payment): ?>
	                            <tr>
	                                <td class="border-b"><?= $payment['cos_kode'] ?></td>
	                                <td class="border-b"><?= $payment['cos_nama'] ?></td>
	                                <td class="text-right border-b">
	                                    <?= "Rp. ".number_format($payment['dtl_jml_bayar'], 0).",-"; ?>
	                                </td>
	                                <td class="border-b"><?= $payment['dtl_jenis_bayar'] ?></td>
	                                <td class="border-b"><?= $payment['dtl_stt_stor'] ?></td>
	                                <td class="border-b">
	                                    <div class="font-medium whitespace-no-wrap">
	                                        <?php echo date('d-m-Y', strtotime($payment['dtl_tanggal'])) ?>
	                                    </div>
	                                    <div class="text-gray-600 text-xs whitespace-no-wrap">
	                                        <?= $payment['dtl_jam'] ?>
	                                    </div>
	                                </td>
	                            </tr>
	                        <?php endforeach; ?>
	                    <?php else: ?>
	                        <tr>
	                            <td colspan="6" class="text-center border-b text-gray-500">Tidak ada pembayaran pelunasan hari ini</td>
	                        </tr>
	                    <?php endif; ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	    <!-- Menunggu Payments Detail -->
	    <div class="px-5 sm:px-16 py-10 sm:py-20">
	        <h3 class="text-lg font-medium mb-5">Detail Pembayaran Status Menunggu Hari Ini</h3>
	        <div class="overflow-x-auto">
	            <table class="table">
	                <thead>
	                    <tr>
	                        <th class="border-b-2 whitespace-no-wrap">TTS</th>
	                        <th class="border-b-2 whitespace-no-wrap">Nama Customer</th>
	                        <th class="border-b-2 text-right whitespace-no-wrap">Jumlah Bayar</th>
	                        <th class="border-b-2 whitespace-no-wrap">Tipe</th>
	                        <th class="border-b-2 whitespace-no-wrap">Jenis</th>
	                        <th class="border-b-2 whitespace-no-wrap">Status Setor</th>
	                        <th class="border-b-2 whitespace-no-wrap">Waktu</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php if (!empty($menunggu_payments)): ?>
	                        <?php foreach ($menunggu_payments as $payment): ?>
	                            <tr>
	                                <td class="border-b"><?= $payment['cos_kode'] ?></td>
	                                <td class="border-b"><?= $payment['cos_nama'] ?></td>
	                                <td class="text-right border-b">
	                                    <?= "Rp. ".number_format($payment['dtl_jml_bayar'], 0).",-"; ?>
	                                </td>
	                                <td class="border-b"><?= $payment['dtl_status'] ?></td>
	                                <td class="border-b"><?= $payment['dtl_jenis_bayar'] ?></td>
	                                <td class="border-b"><?= $payment['dtl_stt_stor'] ?></td>
	                                <td class="border-b">
	                                    <div class="font-medium whitespace-no-wrap">
	                                        <?php echo date('d-m-Y', strtotime($payment['dtl_tanggal'])) ?>
	                                    </div>
	                                    <div class="text-gray-600 text-xs whitespace-no-wrap">
	                                        <?= $payment['dtl_jam'] ?>
	                                    </div>
	                                </td>
	                            </tr>
	                        <?php endforeach; ?>
	                    <?php else: ?>
	                        <tr>
	                            <td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran dengan status menunggu hari ini</td>
	                        </tr>
	                    <?php endif; ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	    <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
	            <div class="text-center sm:text-left mt-10 sm:mt-0">
	                <div class="text-base text-gray-600"><?= $cs['kry_nama']?></div>
	                <div class="text-lg text-theme-1 font-medium mt-2">TTD</div>
	                <div class="mt-1">Azzahra Computer Tegal</div>
	            </div>
	            <div class="text-center sm:text-right sm:ml-auto">
	                <div class="text-base text-gray-600">Total Keseluruhan</div>
	                <div class="text-xl text-theme-1 font-medium mt-2">
	                	<?= "Rp. ".number_format($dp ?: 0, 0).",-"; ?>
	                </div>
	                <div class="mt-1 tetx-xs">Dengan Total Jumlah - <?= $jml_dp;?></div>
	            </div>
	        </div>
    </div>

        </div>
    </main>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<script>
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }

    // Toggle Mobile Sidebar
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('mobile-active');
        overlay.classList.toggle('active');
    }

    // Remember sidebar state
    window.addEventListener('DOMContentLoaded', () => {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth > 1024) {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    });
</script>

<?php $this->load->view('Template/footer'); ?>
