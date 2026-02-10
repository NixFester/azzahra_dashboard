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
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
		<div class="flex items-center">
			<div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
				<i data-feather="wrench" class="w-5 h-5 text-white"></i>
			</div>
			<div>
				<h2 class="text-lg font-bold text-gray-800">
					Input Tindakan Perbaikan
				</h2>
				<p class="text-sm text-gray-600">Catat semua langkah perbaikan yang dilakukan</p>
			</div>
		</div>
		<div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
			<a href="<?= site_url('Teknisi') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
				<i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Kembali ke Dashboard
			</a>
		</div>
	</div>
	<div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
		<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
			<div class="intro-y box p-5 mt-6">
				<div class="mt-1">
					<div class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
						<i class="w-4 h-4 mr-2" data-feather="tool"></i> Input Tindakan
					</div>
				</div>
			</div>
		</div>
		<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
			<!-- Customer & Device Info Card -->
			<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
				<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
					<!-- Customer Info -->
					<div class="flex items-center">
						<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
							<i data-feather="user" class="w-8 h-8 text-blue-600"></i>
						</div>
						<div>
							<h3 class="font-bold text-gray-800 text-lg"><?= $proses['cos_nama']; ?></h3>
							<p class="text-sm text-gray-600">Invoice: <?= $proses['cos_kode']; ?></p>
							<p class="text-sm text-gray-500 flex items-center mt-1">
								<i data-feather="phone" class="w-3 h-3 mr-1"></i>
								<?= (strlen($proses['cos_hp']) > 8) ? substr($proses['cos_hp'],0,4)."xxxx".substr($proses['cos_hp'],-4) : ((strlen($proses['cos_hp']) > 4) ? substr($proses['cos_hp'],0,2)."xxxx".substr($proses['cos_hp'],-2) : $proses['cos_hp']); ?>
							</p>
						</div>
					</div>

					<!-- Device Info -->
					<div class="flex items-center">
						<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mr-4">
							<i data-feather="smartphone" class="w-8 h-8 text-green-600"></i>
						</div>
						<div>
							<h4 class="font-semibold text-gray-800"><?= $proses['cos_tipe'] . ' ' . $proses['cos_model'] ?></h4>
							<p class="text-sm text-gray-600">Status: <?= $proses['cos_status']; ?></p>
							<p class="text-sm text-gray-500">SN: <?= $proses['cos_no_seri'] ?></p>
						</div>
					</div>

					<!-- Complaint -->
					<div>
						<h4 class="font-medium text-gray-800 mb-2">Keluhan Customer:</h4>
						<div class="bg-red-50 border border-red-200 rounded-lg p-3">
							<p class="text-sm text-red-800"><?= $proses['cos_keluhan']?></p>
						</div>
					</div>
				</div>
			</div>

			<!-- Input Form Card -->
			<div class="intro-y bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
				<div class="flex items-center mb-6">
					<div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
						<i data-feather="plus" class="w-4 h-4 text-white"></i>
					</div>
					<h3 class="text-lg font-bold text-gray-800">Catat Tindakan Perbaikan</h3>
				</div>
				<!-- Add New Action Form -->
				<div class="bg-gray-50 rounded-lg p-4 mb-6">
					<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Tindakan Perbaikan</label>
							<select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="tindakan_select">
								<option value="">Pilih Tindakan</option>
								<option value="Mengganti Part">Mengganti Part</option>
								<option value="Memperbaiki Part">Memperbaiki Part</option>
								<option value="Custom">Custom</option>
							</select>
							<input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-2 hidden" placeholder="Masukkan tindakan custom" id="tindakan_custom">
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Detail</label>
							<textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="1" placeholder="Detail perbaikan yang dilakukan" id="area"></textarea>
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
							<input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="1" value="1" min="1" id="qty">
						</div>
						<div class="flex items-end">
							<button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center" id="tbl-add">
								<i data-feather="plus" class="w-4 h-4 mr-2"></i>
								Tambah Tindakan
							</button>
						</div>
					</div>
				</div>

				<!-- Actions List -->
				<div class="mb-6">
					<h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
						<i data-feather="list" class="w-5 h-5 mr-2 text-blue-600"></i>
						Daftar Tindakan Perbaikan
					</h4>
					<form method="post" action="<?= site_url('Teknisi/save_tindakan')?>" onsubmit="prepareFormData(event)">
						<!-- Desktop Table View -->
						<div class="hidden md:block overflow-x-auto">
							<table class="min-w-full bg-white border border-gray-200 rounded-lg" id="tbl-trans">
								<thead class="bg-gray-50">
									<tr>
										<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
										<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
										<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Qty</th>
										<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
										<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Biaya</th>
										<th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Aksi</th>
									</tr>
								</thead>
								<tbody id="tbl" class="divide-y divide-gray-200">
									<!-- Actions will be added here -->
								</tbody>
							</table>
						</div>

						<!-- Mobile Card View -->
						<div class="md:hidden space-y-3" id="mobile-cards">
							<!-- Cards will be added here dynamically -->
						</div>

						<!-- Empty state for desktop -->
						<div id="empty-state" class="hidden md:block text-center py-8 text-gray-500">
							<i data-feather="tool" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
							<p>Belum ada tindakan yang ditambahkan</p>
							<p class="text-sm">Gunakan form di atas untuk menambah tindakan perbaikan</p>
						</div>

						<!-- Empty state for mobile -->
						<div id="empty-state-mobile" class="md:hidden text-center py-8 text-gray-500">
							<i data-feather="tool" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
							<p>Belum ada tindakan yang ditambahkan</p>
							<p class="text-sm">Gunakan form di atas untuk menambah tindakan perbaikan</p>
						</div>

						<input type="text" name="tras_kode" value="<?= $proses['trans_kode']?>" hidden>

						<!-- Submit Button -->
						<div class="flex justify-end mt-6">
							<button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center" onclick="prepareFormData(event)">
								<i data-feather="save" class="w-5 h-5 mr-2"></i>
								Simpan Tindakan
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Handle tindakan select change
	document.getElementById('tindakan_select').addEventListener('change', function() {
		const customInput = document.getElementById('tindakan_custom');
		if (this.value === 'Custom') {
			customInput.classList.remove('hidden');
			customInput.focus();
		} else {
			customInput.classList.add('hidden');
			customInput.value = '';
		}
	});

	// Add row to table and mobile cards
	document.getElementById('tbl-add').addEventListener('click', function() {
		let tindakan = document.getElementById('tindakan_select').value;
		if (tindakan === 'Custom') {
			tindakan = document.getElementById('tindakan_custom').value.trim();
		}
		const area = document.getElementById('area').value.trim();
		const qty = document.getElementById('qty').value.trim();

		if (!tindakan || tindakan === '' || !qty) {
			Swal.fire({
				icon: 'warning',
				title: 'Peringatan',
				text: 'Tindakan dan Qty harus diisi',
				confirmButtonColor: '#F59E0B'
			});
			return;
		}

		const table = document.getElementById('tbl');
		const mobileCards = document.getElementById('mobile-cards');
		const rowCount = table.rows.length + 1;

		// Add to desktop table
		const row = table.insertRow();
		row.className = 'hover:bg-gray-50';
		row.innerHTML = `
			<td class="px-3 py-2 text-sm text-gray-900 row-number">${rowCount}</td>
			<td class="px-3 py-2 text-sm text-gray-900">${tindakan}</td>
			<td class="px-3 py-2 text-sm text-gray-900">${qty}</td>
			<td class="px-3 py-2 text-sm text-gray-600">${area}</td>
			<td class="px-3 py-2 text-sm text-gray-900">0</td>
			<td class="px-3 py-2 text-center">
				<button type="button" class="delete-row-btn inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200" title="Hapus tindakan">
					<i data-feather="trash-2" class="w-3 h-3"></i>
				</button>
			</td>
		`;

		// Add to mobile cards
		const card = document.createElement('div');
		card.className = 'bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow';
		card.setAttribute('data-row-index', rowCount);
		card.innerHTML = `
			<div class="flex justify-between items-start mb-3">
				<div class="flex items-center">
					<span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-bold rounded-full mr-2 row-number">${rowCount}</span>
					<h4 class="font-medium text-gray-900">${tindakan}</h4>
				</div>
				<button type="button" class="delete-card-btn inline-flex items-center p-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200" title="Hapus tindakan">
					<i data-feather="trash-2" class="w-3 h-3"></i>
				</button>
			</div>
			<div class="space-y-2 text-sm">
				<div class="flex justify-between">
					<span class="text-gray-500">Qty:</span>
					<span class="font-medium">${qty}</span>
				</div>
				<div class="flex justify-between">
					<span class="text-gray-500">Biaya:</span>
					<span class="font-medium">0</span>
				</div>
				${area ? `<div class="pt-2 border-t border-gray-100">
					<span class="text-gray-500 text-xs">Keterangan:</span>
					<p class="text-gray-700 text-sm mt-1">${area}</p>
				</div>` : ''}
			</div>
		`;
		mobileCards.appendChild(card);

		// Add event listeners to delete buttons
		const deleteBtn = row.querySelector('.delete-row-btn');
		const deleteCardBtn = card.querySelector('.delete-card-btn');

		const deleteHandler = function() {
			deleteRow(row, card);
		};

		deleteBtn.addEventListener('click', deleteHandler);
		deleteCardBtn.addEventListener('click', deleteHandler);

		// Hide empty states if this is the first row
		const emptyState = document.getElementById('empty-state');
		const emptyStateMobile = document.getElementById('empty-state-mobile');
		if (rowCount === 1) {
			if (emptyState) emptyState.style.display = 'none';
			if (emptyStateMobile) emptyStateMobile.style.display = 'none';
		}

		// Clear inputs
		document.getElementById('tindakan_select').value = '';
		document.getElementById('tindakan_custom').value = '';
		document.getElementById('tindakan_custom').classList.add('hidden');
		document.getElementById('area').value = '';
		document.getElementById('qty').value = '1';

		// Re-initialize feather icons for new elements
		if (typeof feather !== 'undefined') {
			feather.replace();
		}
	});

	// Function to delete a row and its corresponding card
	function deleteRow(row, card = null) {
		Swal.fire({
			title: 'Hapus Tindakan?',
			text: 'Apakah Anda yakin ingin menghapus tindakan ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#EF4444',
			cancelButtonColor: '#6B7280',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				// Remove table row
				if (row) {
					row.remove();
				}

				// Remove mobile card (if not provided, find it by row index)
				if (!card && row) {
					const rowIndex = row.querySelector('.row-number').textContent;
					const mobileCards = document.getElementById('mobile-cards');
					const cards = mobileCards.querySelectorAll('[data-row-index]');
					cards.forEach(card => {
						if (card.getAttribute('data-row-index') === rowIndex) {
							card.remove();
						}
					});
				} else if (card) {
					card.remove();
				}

				renumberRows();

				// Show empty states if no rows left
				const table = document.getElementById('tbl');
				const emptyState = document.getElementById('empty-state');
				const emptyStateMobile = document.getElementById('empty-state-mobile');
				if (table.rows.length === 0) {
					if (emptyState) emptyState.style.display = 'block';
					if (emptyStateMobile) emptyStateMobile.style.display = 'block';
				}
			}
		});
	}

	// Function to renumber rows and cards after deletion
	function renumberRows() {
		const table = document.getElementById('tbl');
		const mobileCards = document.getElementById('mobile-cards');

		// Renumber table rows
		const rows = table.querySelectorAll('tr');
		rows.forEach((row, index) => {
			const numberCell = row.querySelector('.row-number');
			if (numberCell) {
				numberCell.textContent = index + 1;
			}
		});

		// Renumber mobile cards
		const cards = mobileCards.querySelectorAll('[data-row-index]');
		cards.forEach((card, index) => {
			const newIndex = index + 1;
			const numberElement = card.querySelector('.row-number');
			if (numberElement) {
				numberElement.textContent = newIndex;
			}
			card.setAttribute('data-row-index', newIndex);
		});
	}

	// Prepare form data from table (works for both desktop and mobile)
	function prepareFormData(event) {
		const table = document.getElementById('tbl');
		const rows = table.querySelectorAll('tr');
		const no = [];
		const tindakan = [];
		const ket = [];
		const qty = [];
		const subtot = [];

		rows.forEach((row, index) => {
			const cells = row.querySelectorAll('td');
			if (cells.length >= 5) {
				no.push(index + 1);
				tindakan.push(cells[1].textContent.trim() || 'Tindakan');
				qty.push(cells[2].textContent.trim() || '1');
				ket.push(cells[3].textContent.trim());
				subtot.push(cells[4].textContent.trim().replace(/[^\d]/g, '') || '0');
			}
		});

		const form = event.target.closest('form');
		addHiddenInput(form, 'no', no);
		addHiddenInput(form, 'tindakan', tindakan);
		addHiddenInput(form, 'ket', ket);
		addHiddenInput(form, 'qty', qty);
		addHiddenInput(form, 'subtot', subtot);
	}

	function addHiddenInput(form, name, value) {
		const input = document.createElement('input');
		input.type = 'hidden';
		input.name = name + '[]';
		input.value = JSON.stringify(value);
		form.appendChild(input);
	}

	// Handle flashdata alerts
	document.addEventListener('DOMContentLoaded', function() {
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
	});

	// Initialize Feather Icons
	if (typeof feather !== 'undefined') {
		feather.replace();
	}
</script>

<?php $this->load->view('Template/footer'); ?>