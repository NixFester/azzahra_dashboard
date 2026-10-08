<?php $this->load->view('Template/header'); ?>
<div class="content">
	<?php
	// Get flashdata and immediately clear it to prevent re-showing on refresh
	$suksesMsg = $this->session->flashdata('sukses');
	$gagalMsg = $this->session->flashdata('gagal');
	$this->session->set_flashdata('sukses', '');
	$this->session->set_flashdata('gagal', '');
	?>
	<div class="sukses" data-sukses="<?php echo $suksesMsg; ?>"></div>
	<div class="gagal" data-gagal="<?php echo $gagalMsg; ?>"></div>

	<!-- Technician Header -->
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
		<div class="flex items-center">
			<div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
				<i data-feather="wrench" class="w-6 h-6 text-white"></i>
			</div>
			<div>
				<h2 class="text-xl font-bold text-gray-800">Dashboard Teknisi</h2>
				<p class="text-gray-600">Kelola order service yang perlu diperbaiki</p>
			</div>
		</div>
		<div class="ml-auto mt-4 sm:mt-0 flex items-center space-x-3">
			<div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-200 stat-counter" id="stat-baru">
				<div class="text-sm text-blue-600 font-medium">
					<i data-feather="clock" class="w-4 h-4 inline mr-1"></i>
					Order Baru: <span class="font-bold" id="order-baru-count"><?php echo $orders_baru->num_rows(); ?></span>
				</div>
			</div>
			<div class="bg-orange-50 px-4 py-2 rounded-lg border border-orange-200 stat-counter" id="stat-repairing">
				<div class="text-sm text-orange-600 font-medium">
					<i data-feather="tool" class="w-4 h-4 inline mr-1"></i>
					Sedang Dikerjakan: <span class="font-bold" id="order-repairing-count"><?php echo $orders_repairing->num_rows(); ?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- Search and Filter Bar -->
	<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
		<div class="flex flex-col lg:flex-row gap-4">
			<!-- Search Bar -->
			<div class="flex-1">
				<div class="relative">
					<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
						<i data-feather="search" class="w-5 h-5 text-gray-400"></i>
					</div>
					<input type="text" id="search-input" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Cari berdasarkan nama customer, invoice, atau device...">
				</div>
			</div>

			<!-- Status Filter -->
			<div class="lg:w-48">
				<select id="status-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
					<option value="all">Semua Status</option>
					<option value="baru" selected>Order Baru</option>
					<option value="diproses">Sedang Dikerjakan</option>
				</select>
			</div>

			<!-- Date Filter -->
			<div class="lg:w-48">
				<select id="date-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
					<option value="all">Semua Tanggal</option>
					<option value="today" selected>Hari Ini</option>
					<option value="week">Minggu Ini</option>
					<option value="month">Bulan Ini</option>
				</select>
			</div>

			<!-- Clear Filters -->
			<div>
				<button id="clear-filters" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
					<p>Reset</p>
				</button>
			</div>
		</div>
	</div>

	<!-- Order Cards Grid -->
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8" id="orders-grid">
		<?php
		// Display New Orders
		foreach ($orders_baru->result_array() as $row) :
			$statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
			$statusIcon = 'alert-circle';
			$statusText = 'Baru';
			$statusValue = 'baru';
			$actionText = 'Mulai Perbaikan';
			$actionIcon = 'wrench';
			$actionColor = 'bg-blue-600 hover:bg-blue-700';
		?>
		<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 order-card"
			 data-customer="<?= strtolower($row['cos_nama']) ?>"
			 data-invoice="<?= strtolower($row['cos_kode']) ?>"
			 data-device="<?= strtolower($row['cos_tipe'] . ' ' . $row['cos_model']) ?>"
			 data-status="<?= $statusValue ?>"
			 data-date="<?= date('Y-m-d', strtotime($row['trans_tanggal'] ?? 'now')) ?>">
			<div class="p-6">
				<!-- Header -->
				<div class="flex items-start justify-between mb-4">
					<div class="flex items-center">
						<div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
							<i data-feather="user" class="w-5 h-5 text-gray-600"></i>
						</div>
						<div>
							<h3 class="font-semibold text-gray-800 text-lg customer-name"><?= $row['cos_nama']; ?></h3>
							<p class="text-sm text-gray-500 invoice-code">Invoice: <?= $row['cos_kode']; ?></p>
							<p class="text-xs text-gray-400 flex items-center mt-1">
								<i data-feather="calendar" class="w-3 h-3 mr-1"></i>
								<?php
								$date = strtotime($row['trans_tanggal']);
								$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
								$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
								$day = $days[date('w', $date)];
								$date_num = date('j', $date);
								$month = $months[date('n', $date) - 1];
								$year = date('Y', $date);
								echo $day . ', ' . $date_num . ' - ' . $month . ' - ' . $year;
								?>
							</p>
						</div>
					</div>
					<div class="flex items-center">
						<span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusColor ?> border status-badge">
							<i data-feather="<?= $statusIcon ?>" class="w-3 h-3 inline mr-1"></i>
							<?= $statusText ?>
						</span>
					</div>
				</div>

				<!-- Device Info -->
				<div class="bg-gray-50 rounded-lg p-4 mb-4">
					<div class="flex items-center mb-2">
						<i data-feather="smartphone" class="w-4 h-4 text-gray-600 mr-2"></i>
						<span class="font-medium text-gray-800 device-info"><?= $row['cos_tipe'] . ' ' . $row['cos_model'] ?></span>
					</div>
					<div class="text-sm text-gray-600">
						<div class="flex items-center mb-1">
							<i data-feather="hash" class="w-3 h-3 mr-2"></i>
							SN: <span class="serial-number"><?= $row['cos_no_seri'] ?></span>
						</div>
						<div class="flex items-center">
							<i data-feather="phone" class="w-3 h-3 mr-2"></i>
							<?php
							$hp = $row['cos_hp'];
							$masked_hp = substr($hp, 0, -4) . 'XXXX';
							echo $masked_hp;
							?>
						</div>
					</div>
				</div>

				<!-- Complaint -->
				<div class="mb-4">
					<h4 class="text-sm font-medium text-gray-700 mb-2">Keluhan:</h4>
					<p class="text-sm text-gray-600 bg-red-50 p-3 rounded-lg border border-red-100 complaint-text">
						<?= strlen($row['cos_keluhan']) > 100 ? substr($row['cos_keluhan'], 0, 100) . '...' : $row['cos_keluhan'] ?>
					</p>
				</div>

				<!-- Address -->
				<div class="mb-4">
					<h4 class="text-sm font-medium text-gray-700 mb-1">Alamat:</h4>
					<p class="text-sm text-gray-600">
						<i data-feather="map-pin" class="w-3 h-3 inline mr-1"></i>
						<?= strlen($row['cos_alamat']) > 80 ? substr($row['cos_alamat'], 0, 80) . '...' : $row['cos_alamat'] ?>
					</p>
				</div>

				<!-- Action Button -->
				<div class="flex justify-end">
					<a href="<?= site_url('Teknisi/input_tindakan/'.$row['cos_kode'])?>"
					   class="inline-flex items-center px-4 py-2 <?= $actionColor ?> text-white text-sm font-medium rounded-lg transition-colors duration-200 action-btn">
						<i data-feather="<?= $actionIcon ?>" class="w-4 h-4 mr-2"></i>
						<?= $actionText ?>
					</a>
				</div>
			</div>
		</div>
		<?php endforeach; ?>

		<?php
		// Display Repairing Orders
		foreach ($orders_repairing->result_array() as $row) :
			$statusColor = 'bg-orange-100 text-orange-800 border-orange-200';
			$statusIcon = 'tool';
			$statusText = 'Sedang Dikerjakan';
			$statusValue = 'diproses';
			$actionText = 'Lanjutkan Perbaikan';
			$actionIcon = 'play-circle';
			$actionColor = 'bg-orange-600 hover:bg-orange-700';
		?>
		<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 order-card"
			 data-customer="<?= strtolower($row['cos_nama']) ?>"
			 data-invoice="<?= strtolower($row['cos_kode']) ?>"
			 data-device="<?= strtolower($row['cos_tipe'] . ' ' . $row['cos_model']) ?>"
			 data-status="<?= $statusValue ?>"
			 data-date="<?= date('Y-m-d', strtotime($row['order_created'] ?? 'now')) ?>">
			<div class="p-6">
				<!-- Header -->
				<div class="flex items-start justify-between mb-4">
					<div class="flex items-center">
						<div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
							<i data-feather="user" class="w-5 h-5 text-gray-600"></i>
						</div>
						<div>
							<h3 class="font-semibold text-gray-800 text-lg customer-name"><?= $row['cos_nama']; ?></h3>
							<p class="text-sm text-gray-500 invoice-code">Invoice: <?= $row['cos_kode']; ?></p>
							<p class="text-xs text-gray-400 flex items-center mt-1">
								<i data-feather="calendar" class="w-3 h-3 mr-1"></i>
								<?php
								$date_str = $row['order_created'] ?? date('Y-m-d H:i:s');
								$date = strtotime($date_str);
								$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
								$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
								$day = $days[date('w', $date)];
								$date_num = date('j', $date);
								$month = $months[date('n', $date) - 1];
								$year = date('Y', $date);
								echo $day . ', ' . $date_num . ' - ' . $month . ' - ' . $year;
								?>
							</p>
						</div>
					</div>
					<div class="flex items-center">
						<span class="px-2 py-1 text-xs font-medium rounded-full <?= $statusColor ?> border status-badge">
							<i data-feather="<?= $statusIcon ?>" class="w-3 h-3 inline mr-1"></i>
							<?= $statusText ?>
						</span>
					</div>
				</div>

				<!-- Device Info -->
				<div class="bg-gray-50 rounded-lg p-4 mb-4">
					<div class="flex items-center mb-2">
						<i data-feather="smartphone" class="w-4 h-4 text-gray-600 mr-2"></i>
						<span class="font-medium text-gray-800 device-info"><?= $row['cos_tipe'] . ' ' . $row['cos_model'] ?></span>
					</div>
					<div class="text-sm text-gray-600">
						<div class="flex items-center mb-1">
							<i data-feather="hash" class="w-3 h-3 mr-2"></i>
							SN: <span class="serial-number"><?= $row['cos_no_seri'] ?></span>
						</div>
						<div class="flex items-center">
							<i data-feather="phone" class="w-3 h-3 mr-2"></i>
							<?php
							$hp = $row['cos_hp'];
							$masked_hp = substr($hp, 0, -4) . 'XXXX';
							echo $masked_hp;
							?>
						</div>
					</div>
				</div>

				<!-- Complaint -->
				<div class="mb-4">
					<h4 class="text-sm font-medium text-gray-700 mb-2">Keluhan:</h4>
					<p class="text-sm text-gray-600 bg-red-50 p-3 rounded-lg border border-red-100 complaint-text">
						<?= strlen($row['cos_keluhan']) > 100 ? substr($row['cos_keluhan'], 0, 100) . '...' : $row['cos_keluhan'] ?>
					</p>
				</div>

				<!-- Address -->
				<div class="mb-4">
					<h4 class="text-sm font-medium text-gray-700 mb-1">Alamat:</h4>
					<p class="text-sm text-gray-600">
						<i data-feather="map-pin" class="w-3 h-3 inline mr-1"></i>
						<?= strlen($row['cos_alamat']) > 80 ? substr($row['cos_alamat'], 0, 80) . '...' : $row['cos_alamat'] ?>
					</p>
				</div>

				<!-- Action Button -->
				<?php if ($statusValue == 'baru'): ?>
				<div class="flex justify-end">
					<a href="<?= site_url('Teknisi/input_tindakan/'.$row['cos_kode'])?>"
					   class="inline-flex items-center px-4 py-2 <?= $actionColor ?> text-white text-sm font-medium rounded-lg transition-colors duration-200 action-btn">
						<i data-feather="<?= $actionIcon ?>" class="w-4 h-4 mr-2"></i>
						<?= $actionText ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>

		<!-- Empty State -->
		<div class="col-span-full" id="no-results" style="display: none;">
			<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
				<div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
					<i data-feather="search" class="w-8 h-8 text-gray-400"></i>
				</div>
				<h3 class="text-lg font-medium text-gray-800 mb-2">Tidak ada hasil ditemukan</h3>
				<p class="text-gray-600">Coba ubah kata kunci pencarian atau filter yang Anda gunakan.</p>
			</div>
		</div>

		<div class="col-span-full" id="no-orders">
			<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
				<div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
					<i data-feather="check-circle" class="w-8 h-8 text-gray-400"></i>
				</div>
				<h3 class="text-lg font-medium text-gray-800 mb-2">Tidak ada order baru</h3>
				<p class="text-gray-600">Semua order sudah diproses. Silakan tunggu order baru dari Customer Service.</p>
			</div>
		</div>
	</div>

	<!-- Recent Activity Section -->
	<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-8">
		<div class="flex items-center justify-between mb-6">
			<h3 class="text-lg font-bold text-gray-800 flex items-center">
				<i data-feather="activity" class="w-5 h-5 mr-2 text-blue-600"></i>
				Aktivitas Terbaru
			</h3>
			<a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
		</div>
		<div class="space-y-4" id="recent-activity">
			<?php if ($latest_orders->num_rows() > 0): ?>
				<?php foreach ($latest_orders->result_array() as $order): ?>
				<div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
					<div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
						<i data-feather="user-plus" class="w-4 h-4 text-blue-600"></i>
					</div>
					<div class="flex-1">
						<p class="text-sm text-gray-800">Order baru dari <strong><?php echo $order['cos_nama']; ?></strong></p>
						<p class="text-xs text-gray-500">Invoice: <?php echo $order['trans_kode']; ?> • <?php
							$date = strtotime($order['trans_tanggal']);
							$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
							$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
							$day = $days[date('w', $date)];
							$date_num = date('j', $date);
							$month = $months[date('n', $date) - 1];
							$year = date('Y', $date);
							echo $day . ', ' . $date_num . ' ' . $month . ' ' . $year;
						?></p>
					</div>
				</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="flex items-center p-3 bg-gray-50 rounded-lg">
					<div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
						<i data-feather="user-plus" class="w-4 h-4 text-blue-600"></i>
					</div>
					<div class="flex-1">
						<p class="text-sm text-gray-800">Belum ada order baru</p>
						<p class="text-xs text-gray-500">Order baru akan muncul di sini</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Initialize Feather Icons
		if (typeof feather !== 'undefined') {
			feather.replace();
		}

		// Handle flashdata alerts
		var suksesMsg = document.querySelector('.sukses')?.getAttribute('data-sukses') || '';
		var gagalMsg = document.querySelector('.gagal')?.getAttribute('data-gagal') || '';

		if (typeof Swal !== 'undefined') {
			if (gagalMsg) {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: gagalMsg,
					confirmButtonColor: '#EF4444'
				});
				document.querySelector('.gagal').setAttribute('data-gagal', '');
			} else if (suksesMsg) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: suksesMsg,
					timer: 1600,
					showConfirmButton: false
				});
				document.querySelector('.sukses').setAttribute('data-sukses', '');
			}
		} else {
			if (gagalMsg) {
				alert(gagalMsg);
				document.querySelector('.gagal').setAttribute('data-gagal', '');
			} else if (suksesMsg) {
				alert(suksesMsg);
				document.querySelector('.sukses').setAttribute('data-sukses', '');
			}
		}

		// Search and Filter Functionality
		const searchInput = document.getElementById('search-input');
		const statusFilter = document.getElementById('status-filter');
		const dateFilter = document.getElementById('date-filter');
		const clearFiltersBtn = document.getElementById('clear-filters');
		const orderCards = document.querySelectorAll('.order-card');
		const noResults = document.getElementById('no-results');
		const noOrders = document.getElementById('no-orders');
		const orderBaruCount = document.getElementById('order-baru-count');
		const orderRepairingCount = document.getElementById('order-repairing-count');

		// Store original counts for dynamic display
		const originalCounts = {
			baru: <?php echo $orders_baru->num_rows(); ?>,
			repairing: <?php echo $orders_repairing->num_rows(); ?>
		};

		// Function to show/hide statistics counters based on selected status and date filters
		function updateStatisticsCounters(selectedStatus, selectedDate) {
			// Calculate counts based on both status and date filters
			let baruCount = 0;
			let repairingCount = 0;

			// Count orders based on status and date filters
			orderCards.forEach(card => {
				const cardStatus = card.dataset.status || '';
				const cardDate = card.dataset.date || '';

				// Check if card matches the date filter
				let matchesDate = true;
				const today = new Date().toISOString().split('T')[0];
				const currentWeek = getWeekRange();
				const currentMonth = getMonthRange();

				if (selectedDate === 'today') {
					matchesDate = cardDate === today;
				} else if (selectedDate === 'week') {
					matchesDate = cardDate >= currentWeek.start && cardDate <= currentWeek.end;
				} else if (selectedDate === 'month') {
					matchesDate = cardDate >= currentMonth.start && cardDate <= currentMonth.end;
				}
				// For 'all' dates, matchesDate remains true

				// Count based on status if date matches
				if (matchesDate) {
					if (cardStatus === 'baru') baruCount++;
					else if (cardStatus === 'diproses') repairingCount++;
				}
			});

			// Hide all counters first
			document.getElementById('stat-baru').style.display = 'none';
			document.getElementById('stat-repairing').style.display = 'none';

			// Show only the relevant counter with filtered count
			if (selectedStatus === 'baru') {
				document.getElementById('stat-baru').style.display = 'block';
				orderBaruCount.textContent = baruCount;
			} else if (selectedStatus === 'diproses') {
				document.getElementById('stat-repairing').style.display = 'block';
				orderRepairingCount.textContent = repairingCount;
			} else {
				// Show all counts (status === 'all') with filtered counts
				document.getElementById('stat-baru').style.display = 'block';
				document.getElementById('stat-repairing').style.display = 'block';
				orderBaruCount.textContent = baruCount;
				orderRepairingCount.textContent = repairingCount;
			}
		}

		function filterOrders() {
			const searchTerm = searchInput.value.toLowerCase();
			const statusValue = statusFilter.value;
			const dateValue = dateFilter.value;

			let visibleCount = 0;

			orderCards.forEach(card => {
				const customer = card.dataset.customer || '';
				const invoice = card.dataset.invoice || '';
				const device = card.dataset.device || '';
				const status = card.dataset.status || '';
				const date = card.dataset.date || '';

				// Search filter
				const matchesSearch = searchTerm === '' ||
					customer.includes(searchTerm) ||
					invoice.includes(searchTerm) ||
					device.includes(searchTerm);

				// Status filter
				const matchesStatus = statusValue === 'all' || status === statusValue;

				// Date filter
				let matchesDate = true;
				const today = new Date().toISOString().split('T')[0];
				const currentWeek = getWeekRange();
				const currentMonth = getMonthRange();

				if (dateValue === 'today') {
					matchesDate = date === today;
				} else if (dateValue === 'week') {
					matchesDate = date >= currentWeek.start && date <= currentWeek.end;
				} else if (dateValue === 'month') {
					matchesDate = date >= currentMonth.start && date <= currentMonth.end;
				}

				// Show/hide card
				if (matchesSearch && matchesStatus && matchesDate) {
					card.style.display = 'block';
					visibleCount++;
				} else {
					card.style.display = 'none';
				}
			});

			// Update UI states - count visible cards by status
			let baruCount = 0;
			let repairingCount = 0;

			orderCards.forEach(card => {
				if (card.style.display !== 'none') {
					const status = card.dataset.status;
					if (status === 'baru') baruCount++;
					else if (status === 'diproses') repairingCount++;
				}
			});

			// Update statistics counters based on selected status and date filters
			updateStatisticsCounters(statusValue, dateValue);

			if (visibleCount === 0 && orderCards.length > 0) {
				noResults.style.display = 'block';
				noOrders.style.display = 'none';
			} else if (orderCards.length === 0) {
				noResults.style.display = 'none';
				noOrders.style.display = 'block';
			} else {
				noResults.style.display = 'none';
				noOrders.style.display = 'none';
			}
		}

		function getWeekRange() {
			const today = new Date();
			const startOfWeek = new Date(today);
			startOfWeek.setDate(today.getDate() - today.getDay());
			const endOfWeek = new Date(startOfWeek);
			endOfWeek.setDate(startOfWeek.getDate() + 6);

			return {
				start: startOfWeek.toISOString().split('T')[0],
				end: endOfWeek.toISOString().split('T')[0]
			};
		}

		function getMonthRange() {
			const today = new Date();
			const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
			const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

			return {
				start: startOfMonth.toISOString().split('T')[0],
				end: endOfMonth.toISOString().split('T')[0]
			};
		}

		function clearFilters() {
			searchInput.value = '';
			statusFilter.value = 'baru';
			dateFilter.value = 'today';
			filterOrders();
		}

		// Event listeners
		searchInput.addEventListener('input', filterOrders);
		statusFilter.addEventListener('change', filterOrders);
		dateFilter.addEventListener('change', filterOrders);
		clearFiltersBtn.addEventListener('click', clearFilters);

		// Initialize filter on page load
		filterOrders();


	});
</script>

<?php $this->load->view('Template/footer'); ?>