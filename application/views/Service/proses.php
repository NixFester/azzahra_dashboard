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
        <h2 class="text-lg font-medium mr-auto">
            Data Costomer
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        	<a href="javascript:;" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#edit-costom-<?= $proses['cos_kode'];?>">
        		Edit Data
        	</a>
        </div>
    </div>
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="<?= site_url('Service/cos_baru')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Service/cos_proses')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
		            </a>
		            <a href="<?= site_url('Service/cos_konf')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
		            </a>
		            <a href="<?= site_url('Service/cos_pelunasan')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
		            </a>
		            <a href="<?= site_url('Service/cos_lunas')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="users"></i> Customer
		            </a>
		        </div>
    		</div>
    	</div>
    	<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
    		<div class="intro-y box px-5 pt-5 mt-5">
				<div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
					<div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
	                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
	                        <img alt="Azzahra" class="rounded-full" src="<?php echo base_url(); ?>assets/template/beck/dist/images/profile-14.jpg">
	                        <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
	                    </div>
	                    <div class="ml-5">
	                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?= $proses['cos_nama']; ?></div>
	                        <div class="text-gray-600"><?= $proses['cos_kode']; ?></div>
	                    </div>
	                </div>
	                <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
	                    <div class="truncate sm:whitespace-normal flex items-center"> 
	                    	<i data-feather="phone" class="w-4 h-4 mr-2"></i> <?= (strlen($proses['cos_hp']) > 8) ? substr($proses['cos_hp'],0,4)."xxxx".substr($proses['cos_hp'],-4) : ((strlen($proses['cos_hp']) > 4) ? substr($proses['cos_hp'],0,2)."xxxx".substr($proses['cos_hp'],-2) : $proses['cos_hp']); ?>
	                    	<!-- <?= $proses['cos_hp']; ?> --> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= $proses['cos_status']; ?> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= $proses['cos_tipe']; ?> 
	                	</div>
	                </div>
	                <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Keluhan</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                            	<textarea class="ml-3 font-medium text-theme-6" style="width: 300px;"><?= $proses['cos_keluhan']?> </textarea>
                            	
                            </div>
                        </div>
                    </div>                    
				</div>
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
					<a data-toggle="tab" data-target="#input-data" href="javascript:;" class="py-4 sm:mr-8 active">Input Proses</a>  
				</div>
			</div>

			<!-- input proses -->
			<div class="intro-y tab-content mt-5">
				<div class="tab-content__pane active" id="input-data">
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
											<option value="" disabled selected>Pilih Tindakan</option>
											<option value="Mengganti Part">Mengganti Part</option>
											<option value="Memperbaiki Part">Memperbaiki Part</option>
											<option value="Custom">Custom</option>
										</select>
										<input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mt-2 hidden" placeholder="Masukkan tindakan custom" id="tindakan_custom">
									</div>

									<div>
										<label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Detail</label>
										<!-- container holds either textarea or spare search -->
										<div id="area_container">
											<textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="1" placeholder="Detail perbaikan yang dilakukan" id="area"></textarea>

											<!-- spare search (hidden by default) -->
											<div id="spare_search_container" class="hidden mt-2">
												<input type="text" id="spare_search" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Cari sparepart...">
												<div id="spare_results" class="mt-2 text-sm text-gray-600"></div>
												<input type="hidden" id="spare_selected_id" />
												<input type="hidden" id="spare_selected_name" />
												<input type="hidden" id="spare_selected_price" />
											</div>
										</div>
									</div>
									<div>
										<label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
										<input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="1" value="1" min="1" id="qty">
									</div>
									<div class="flex flex-col space-y-2">
										<button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center" id="tbl-add">
											<i data-feather="plus" class="w-4 h-4 mr-2"></i>
											Tambah Tindakan
										</button>
										<button type="button" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center" id="order-sparepart-btn">
											<i data-feather="package" class="w-4 h-4 mr-2"></i>
											Order Sparepart
										</button>
									</div>
								</div>
							</div>
							<!-- Modal Order Sparepart -->
							<div id="modal-order-sparepart" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 ">
								<div class="bg-white rounded shadow-2xl w-full max-w-md relative overflow-hidden p-4">
									<!-- Header -->
									<div class="">
										<div class="flex items-center justify-between">
											<div class="flex items-center">
												<div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
												<i data-feather="package" class="w-6 h-6"></i>
												</div>
												<h3 class="text-xl font-bold ">Order Sparepart</h3>
											</div>
											<button class="hover:bg-gray-200 hover:bg-opacity-20 p-2 rounded-lg transition-colors" onclick="document.getElementById('modal-order-sparepart').classList.add('hidden')">
												<i data-feather="x" class="w-5 h-5"></i>
											</button>
										</div>
									</div>

								<!-- Body -->
								<div class="p-4">
									<form method="post" action="<?= site_url('Service/order_sparepart') ?>">
										<input type="hidden" name="trans_kode" value="<?= $proses['trans_kode']?>">
										<input type="hidden" name="ketersediaan" value="tidak_ada">

										<div class="mb-6">
											<label class="block text-sm font-semibold text-gray-700 mb-3">Nama Barang/Sparepart</label>
											<input type="text" name="barang_nama" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-colors" placeholder="Masukkan nama sparepart" required>
											<p class="text-xs text-gray-500 mt-2">Contoh: Baterai, Layar LCD, Charger, dll</p>
										</div>

										<!-- Footer -->
										<div class="flex gap-3 pt-4 border-t border-gray-200">
											<button type="button" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-lg transition-colors duration-200" onclick="document.getElementById('modal-order-sparepart').classList.add('hidden')">
												Batal
											</button>
											<button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
												<i data-feather="check-circle" class="w-5 h-5 mr-2"></i>
												Simpan Order
											</button>
										</div>
									</form>
								</div>
								</div>
							</div>

							<!-- Actions List -->
							<div class="mb-6">
								<h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
									<i data-feather="list" class="w-5 h-5 mr-2 text-blue-600"></i>
									Daftar Tindakan Perbaikan
								</h4>
								<form method="post" action="<?= site_url('Service/save_tindakan')?>" onsubmit="prepareFormData(event)">
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

<!-- modal tambah cotomer-->
<div class="modal" id="edit-costom-<?= $proses['cos_kode'];?>">
	<div class="modal__content modal__content--xl p-10 intro-y box sm:py-15 mt-2">
		<div class="nav-tabs wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10 ">
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1 active" data-toggle="tab" data-target="#custom">1</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Customer</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1"  data-toggle="tab" data-target="#unit">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Unit</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
            	<a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1"  data-toggle="tab" data-target="#kelket">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Keluhan dan Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-2"></div>
        </div>
        <form method="post" action="<?= site_url('Service/update_trans/'.$proses['cos_kode'])?>">
        	<div class="tab-content">
	        	<div class="tab-content__pane active" id="custom">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Data Customer</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Nama</div>
			                    <input type="text" class="input w-full border flex-1" name="nama" value="<?= $proses['cos_nama']; ?>" required oninvalid="this.setCustomValidity('Nama customer tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan nama customer">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">No Tlep</div>
			                    <input type="number" class="input w-full border flex-1" name="tlp" value="<?= $proses['cos_hp']; ?>"required oninvalid="this.setCustomValidity('No tlep customer tidak boleh kosong ?')" oninput="setCustomValidity('')" placeholder="Masukan no tlep customer">
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Alamat</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="alamat" required oninvalid="this.setCustomValidity('Alamat tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $proses['cos_alamat']; ?></textarea>
			                </div>
			            </div>
			        </div>
	        	</div>
	        	<div class="tab-content__pane" id="unit">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Data Unit</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			            	<div class="intro-y col-span-12">
			                    <div class="mb-2">Status </div>
			                    <select class="input w-full border flex-1" name="status" required oninvalid="this.setCustomValidity('Setatus unit tidak boleh kosong ?')" oninput="setCustomValidity('')">
						             <option value="<?= $proses['cos_status']; ?>"><?= $proses['cos_status']; ?></option>
						             <option value="CID">CID</option>
						             <option value="IW">IW</option>
						             <option value="OOW">OOW</option>
						         </select>
			                </div>
				            <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Type </div>
			                    <input type="text" class="input w-full border flex-1" name="type" value="<?= $proses['cos_tipe']; ?>" required oninvalid="this.setCustomValidity('Type unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan type unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Model </div>
			                    <input type="text" class="input w-full border flex-1" name="model" value="<?= $proses['cos_model']; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">No seri </div>
			                    <input type="text" class="input w-full border flex-1" name="seri" value="<?= $proses['cos_no_seri']; ?>" required oninvalid="this.setCustomValidity('No seri unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12 sm:col-span-6">
			                    <div class="mb-2">Password </div>
			                    <input type="text" class="input w-full border flex-1" name="pswd" value="<?= $proses['cos_pswd']; ?>" required oninvalid="this.setCustomValidity('Password unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Asesoris</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= $proses['cos_asesoris']; ?></textarea>
			                </div>
				        </div>			            
			        </div>
	        	</div>
	        	<div class="tab-content__pane" id="kelket">
	        		<div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
			            <div class="font-medium text-base">Keluhan dan Keterangan</div>
			            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Keluhan</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="keluhan" required oninvalid="this.setCustomValidity('Keluhan tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $proses['cos_keluhan']; ?></textarea>
			                </div>
			                <div class="intro-y col-span-12">
			                    <div class="mb-2">Keterangan</div>
			                    <textarea class="input w-full border mt-2 flex-1" name="ket" required oninvalid="this.setCustomValidity('Keterangan tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $proses['cos_keterangan']; ?></textarea>
			                </div>
			            </div>
			        </div>
			        <div class="px-5 py-3 text-right border-t border-gray-200">
			            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
			            <button type="submit" class="button w-20 bg-theme-1 text-white">Update</button>
			        </div>
	        	</div>
	        </div>
        </form>
        

	</div>
</div>

<script>
const Token = "1|Ci58W5WrCPJgEmM1Mg35JIRFqCBWyZsU2n6sLnFAd4397a08";

// Helper to escape html for insertion
function escapeHtml(str){ return String(str).replace(/[&<>\"']/g, function(m){ return { '&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#39;' }[m]; }); }

// Modal Order Sparepart
document.getElementById('order-sparepart-btn').addEventListener('click', function() {
	document.getElementById('modal-order-sparepart').classList.remove('hidden');
	if (typeof feather !== 'undefined') feather.replace();
});

// Toggle tindakan custom / spare search
document.getElementById('tindakan_select').addEventListener('change', function() {
    const customInput = document.getElementById('tindakan_custom');
    const spareContainer = document.getElementById('spare_search_container');
    const textarea = document.getElementById('area');
    if (this.value === 'Mengganti Part') {
        // show spare search, hide textarea
        customInput.classList.add('hidden');
        customInput.value = '';
        textarea.classList.add('hidden');
        spareContainer.classList.remove('hidden');
        document.getElementById('spare_search').focus();
    } else {
        customInput.classList.add('hidden');
        customInput.value = '';
        textarea.classList.remove('hidden');
        spareContainer.classList.add('hidden');
    }
});

// Debounced search and render max 3 results, show "Loading..." while fetching
let spareSearchTimer = null;
document.getElementById('spare_search').addEventListener('input', function() {
    const q = this.value.trim();
    const resultsEl = document.getElementById('spare_results');
    const selectedIdEl = document.getElementById('spare_selected_id');
    const selectedNameEl = document.getElementById('spare_selected_name');
    selectedIdEl.value = '';
    selectedNameEl.value = '';

    if (spareSearchTimer) clearTimeout(spareSearchTimer);
    if (!q) {
        resultsEl.innerHTML = '';
        return;
    }
    resultsEl.innerHTML = 'Loading...';
    spareSearchTimer = setTimeout(() => {
        const url = `https://azventory.azzahracomputertegal.com/api/v1/inventory?per_page=3&search=${encodeURIComponent(q)}`;
        fetch(url, {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Authorization": `Bearer ${Token}`
            }
        })
        .then(r => r.json())
        .then(res => {
            const items = res?.data || [];
            if (!items.length) {
                resultsEl.innerHTML = '<div class="text-gray-500">Sparepart tidak ada, Silahkan Order/Gunakan Opsi Custom</div>';
                return;
            }
            resultsEl.innerHTML = items.slice(0,3).map(it => {
                // clickable item
                return `<div class="p-2 hover:bg-gray-100 cursor-pointer spare-item" data-id="${it.id}" data-name="${escapeHtml(it.name)}" data-price="${it.price}">
                    <div class="font-medium">${escapeHtml(it.name)}</div>
                    <div class="text-xs text-gray-500">${escapeHtml(it.part_number)} • Stok: ${it.stock?.current || 0} ${escapeHtml(it.stock?.unit||'')} • Harga: Rp${it.price?.toLocaleString() || '0'}</div>
                </div>`;
            }).join('');
            // add click listeners
            resultsEl.querySelectorAll('.spare-item').forEach(el => {
                el.addEventListener('click', function(){
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const price = this.getAttribute('data-price');
                    console.log('Spare part selected:', {id, name, price});
                    document.getElementById('spare_selected_id').value = id;
                    document.getElementById('spare_selected_name').value = name;
                    document.getElementById('spare_selected_price').value = price;
                    console.log('Hidden inputs set to:', {
                        id: document.getElementById('spare_selected_id').value,
                        name: document.getElementById('spare_selected_name').value,
                        price: document.getElementById('spare_selected_price').value
                    });
                    resultsEl.innerHTML = `<div class="p-2 bg-green-50 border border-green-100 rounded text-sm">Dipilih: <strong>${name}</strong> (Rp${parseInt(price).toLocaleString()})</div>`;
                });
            });
        })
        .catch(()=> {
            resultsEl.innerHTML = '<div class="text-red-500">Gagal mengambil data</div>';
        });
    }, 350);
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
// Accept either the submit event or the form element itself
function prepareFormData(eventOrForm) {
	const table = document.getElementById('tbl');
	const rows = table.querySelectorAll('tr');
	const no = [];
	const tindakan = [];
	const ket = [];
	const qty = [];
	const subtot = [];
	const inventory_ids = [];

	rows.forEach((row, index) => {
		const cells = row.querySelectorAll('td');
		if (cells.length >= 5) {
			no.push(index + 1);
			tindakan.push(cells[1].textContent.trim() || 'Tindakan');
			qty.push(cells[2].textContent.trim() || '1');
			ket.push(cells[3].textContent.trim());
			// Parse biaya: remove "Rp" and commas, keep numbers
			const biayaText = cells[4].textContent.trim();
			const biayaValue = parseInt(biayaText.replace(/[^0-9]/g, '')) || 0;
			subtot.push(biayaValue);
			const invId = row.getAttribute('data-inventory-id') || '';
			inventory_ids.push(invId);
		}
	});
	// Resolve form from event or direct form reference
	let form = null;
	if (eventOrForm && eventOrForm.target) form = eventOrForm.target.closest('form');
	else if (eventOrForm instanceof HTMLFormElement) form = eventOrForm;
	else form = document.querySelector('form[method="post"][action*="Service/save_tindakan"]');
	if (!form) {
		console.warn('prepareFormData: form not found');
		return;
	}

	// Remove previous hidden inputs with same names to avoid duplicates
	['no','tindakan','ket','qty','subtot','inventory_id'].forEach(n => {
		const existing = form.querySelectorAll(`input[name="${n}[]"]`);
		existing.forEach(e => e.remove());
	});

	addHiddenInput(form, 'no', no);
	addHiddenInput(form, 'tindakan', tindakan);
	addHiddenInput(form, 'ket', ket);
	addHiddenInput(form, 'qty', qty);
	addHiddenInput(form, 'subtot', subtot);
	addHiddenInput(form, 'inventory_id', inventory_ids);
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

// Add row to table and mobile cards (handles Mengganti Part with spare selection)
document.getElementById('tbl-add').addEventListener('click', function() {
    let tindakan = document.getElementById('tindakan_select').value;
    let area = document.getElementById('area').value.trim();
    const qty = document.getElementById('qty').value.trim();

    // Handle Mengganti Part: require spare selected and fill area with spare name
    let inventoryId = '';
    let selectedName = '';
    let selectedPrice = 0;
    if (tindakan === 'Mengganti Part') {
        inventoryId = document.getElementById('spare_selected_id').value;
        selectedName = document.getElementById('spare_selected_name').value;
        selectedPrice = parseInt(document.getElementById('spare_selected_price').value) || 0;
        console.log('Mengganti Part button clicked:', {inventoryId, selectedName, selectedPrice});
        
        if (!inventoryId) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih sparepart terlebih dahulu',
                confirmButtonColor: '#F59E0B'
            });
            return;
        }
        tindakan = `Mengganti ${selectedName}`;
        area = tindakan + ` (ID: ${inventoryId})`; // Add spare part ID to keterangan
    }

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
    
    // Calculate biaya (price * quantity)
    const biaya = selectedPrice * parseInt(qty);

    // Add to desktop table
    const row = table.insertRow();
    row.className = 'hover:bg-gray-50';
    if (inventoryId) {
        row.setAttribute('data-inventory-id', inventoryId);
        console.log('Setting row data-inventory-id:', inventoryId);
    }
    if (selectedName) {
        row.setAttribute('data-inventory-name', selectedName);
        console.log('Setting row data-inventory-name:', selectedName);
    }
    row.innerHTML = `
        <td class="px-3 py-2 text-sm text-gray-900 row-number">${rowCount}</td>
        <td class="px-3 py-2 text-sm text-gray-900">${escapeHtml(tindakan)}</td>
        <td class="px-3 py-2 text-sm text-gray-900">${qty}</td>
        <td class="px-3 py-2 text-sm text-gray-600">${escapeHtml(area)}</td>
        <td class="px-3 py-2 text-sm text-gray-900">Rp${biaya.toLocaleString()}</td>
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
    if (inventoryId) card.setAttribute('data-inventory-id', inventoryId);
    if (selectedName) card.setAttribute('data-inventory-name', selectedName);
    card.innerHTML = `
        <div class="flex justify-between items-start mb-3">
            <div class="flex items-center">
                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-bold rounded-full mr-2 row-number">${rowCount}</span>
                <h4 class="font-medium text-gray-900">${escapeHtml(tindakan)}</h4>
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
                <span class="font-medium">Rp${biaya.toLocaleString()}</span>
            </div>
            ${area ? `<div class="pt-2 border-t border-gray-100">
                <span class="text-gray-500 text-xs">Keterangan:</span>
                <p class="text-gray-700 text-sm mt-1">${escapeHtml(area)}</p>
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

    // Hide empty states
    const emptyState = document.getElementById('empty-state');
    const emptyStateMobile = document.getElementById('empty-state-mobile');
    if (rowCount === 1) {
        if (emptyState) emptyState.style.display = 'none';
        if (emptyStateMobile) emptyStateMobile.style.display = 'none';
    }

    // Clear inputs and reset UI
    document.getElementById('tindakan_select').value = '';
    document.getElementById('tindakan_custom').value = '';
    document.getElementById('tindakan_custom').classList.add('hidden');
    document.getElementById('area').value = '';
    document.getElementById('qty').value = '1';
    document.getElementById('spare_search').value = '';
    document.getElementById('spare_results').innerHTML = '';
    document.getElementById('spare_selected_id').value = '';
    document.getElementById('spare_selected_name').value = '';
    document.getElementById('spare_selected_price').value = '';
    document.getElementById('spare_search_container').classList.add('hidden');
    document.getElementById('area').classList.remove('hidden');

    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// New function: update stock for actions that replaced parts
// Returns a Promise that resolves when all API calls complete
function updateStocksFromActions() {
    console.log('updateStocksFromActions called');
    try {
        const table = document.getElementById('tbl');
        const rows = table.querySelectorAll('tr');
        console.log('Total rows found:', rows.length);

        const promises = [];

        rows.forEach((row, idx) => {
            const invId = row.getAttribute('data-inventory-id');
            console.log(`Row ${idx}: inventory-id = ${invId}`);

            if (!invId) {
                console.log(`Row ${idx}: Skipped (no inventory-id)`);
                return;
            }

            const itemName = row.getAttribute('data-inventory-name') || 'sparepart';
            const qtyCell = row.querySelectorAll('td')[2];
            const qtyText = qtyCell ? qtyCell.textContent.trim() : '1';
            const actionQty = parseInt(qtyText, 10) || 1;

            const url = `https://azventory.azzahracomputertegal.com/api/v1/inventory/${invId}/adjust-stock`;
            const body = {
                type: "decrement",
                quantity: actionQty,
                description: `Teknisi Mengganti ${itemName}`
            };

            console.log('Making API call:', {
                url,
                body,
                qtyText,
                actionQty,
                itemName,
                invId,
                token: Token ? Token.substring(0, 10) + '...' : 'MISSING'
            });

            // Push fetch promise to array so we can await all of them
            const p = fetch(url, {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${Token}`
                },
                body: JSON.stringify(body)
            })
            .then(res => {
                console.log('API Response status:', res.status, res.statusText);
                return res.json().catch(() => ({}));
            })
            .then(data => {
                console.log('Stock adjustment response:', invId, JSON.stringify(data));
                return data;
            })
            .catch(err => {
                console.error('Stock adjustment fetch error:', invId, err);
                // Resolve to an error object so Promise.all won't reject early
                return { error: String(err), invId };
            });

            promises.push(p);
        });

        // If no promises (no inventory adjustments), resolve immediately
        if (!promises.length) return Promise.resolve();
        return Promise.all(promises);
    } catch (e) {
        console.error('updateStocksFromActions error:', e);
        return Promise.resolve();
    }
}

// Ensure form calls both prepareFormData and updateStocksFromActions on submit
document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form[method="post"][action*="Service/save_tindakan"]');
    if (form) {
        form.addEventListener('submit', function(event) {
            // Prevent immediate navigation so we can await stock updates
            event.preventDefault();
            // Run stock adjustments and then submit the form
            updateStocksFromActions()
            .then((results) => {
                console.log('updateStocksFromActions completed', results);
                // Prepare form data and submit
                prepareFormData(form);
                form.submit();
            })
            .catch(err => {
                console.error('updateStocksFromActions failed', err);
                // Still prepare data and submit to avoid blocking the workflow
                prepareFormData(form);
                form.submit();
            });
        });
    }
});

</script>

<?php $this->load->view('Template/footer'); ?>