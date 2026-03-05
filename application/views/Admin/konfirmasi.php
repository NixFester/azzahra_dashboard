<?php $this->load->view('Template/header'); ?>
<div class="content">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Costomer
        </h2>
    </div>
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="<?= site_url('Admin/cus_baru')?>" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="<?= site_url('Admin/cus_konf')?>" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Konfirmasi
		            </a>
		            <a href="<?= site_url('Admin/cus_konf_bank')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Bank Transfer 
		            </a>
		            <a href="<?= site_url('Admin/cus_proses')?>" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="play-circle"></i> Di proses 
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
	                    	<i data-feather="phone" class="w-4 h-4 mr-2"></i> <?= $proses['cos_hp']; ?> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="eye" class="w-4 h-4 mr-2"></i> <?= $proses['cos_status']; ?> 
	                	</div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
	                    	<i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> <?= $proses['cos_tipe']; ?> 
	                	</div>
	                </div>
	                <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                            	<textarea class="ml-3 font-medium" style="width: 300px;"><?= $proses['cos_alamat']?> </textarea>
                            	
                            </div>
                        </div>
                    </div>                    
				</div>
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
					<a data-toggle="tab" data-target="#konfirmasi" role="button" class="py-4 sm:mr-8 active">Konfirmasi</a> 
					<a data-toggle="tab" data-target="#unit" role="button" class="py-4 sm:mr-8">Unit</a>
					<a data-toggle="tab" data-target="#kelket" role="button" class="py-4 sm:mr-8">Keluhan & Keterangan</a> 				<a data-toggle="tab" data-target="#sparepart" role="button" class="py-4 sm:mr-8">Detail Sparepart</a> 				</div>
			</div>
			<div class="intro-y tab-content mt-5">
				<div class="tab-content__pane active" id="konfirmasi">
					<div class="intro-y box col-span-12 lg:col-span-6">
                        <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Konfirmasi
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="mt-5">
                            	<form method="post" action="<?= site_url('Admin/update_konf')?>">
                            		<table class="table">
	                                	<thead>
	                                        <tr class="bg-gray-700 text-white">
	                                            <th class="whitespace-no-wrap">#</th>
	                                            <th class="whitespace-no-wrap">Tindakan</th>
	                                            <th class="whitespace-no-wrap">Keterangan</th>
	                                            <th class="whitespace-no-wrap">Qty</th>
	                                            <th class="whitespace-no-wrap">Subtotal</th>
												<th class="whitespace-no-wrap">Ketersediaan</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                    	<?php
	                                    		$no=1;
		                                    	foreach ($data->result_array() as $row):?>
		                                    		<tr>
		                                    			<td class="border-b">
		                                    				<?= $no; ?>
		                                    				<input type="hidden" name="no[]" value="<?= $no; ?>">
		                                    				<input type="hidden" name="tdkn[]" value="<?= $row['tdkn_kode']?>">
		                                    			</td>
		                                    			<td class="border-b">
		                                    				<input type="hidden" name="tindakan[]"  value="<?= $row['tdkn_nama']?>">
		                                    				<?= $row['tdkn_nama']?>
		                                    			</td>
		                                    			<td class="border-b">
		                                    				<input type="hidden" name="ket[]"  value="<?= $row['tdkn_ket']?>">
		                                    				<?= $row['tdkn_ket']?>
		                                    			</td>
		                                    			<td class="border-b">
		                                    				<input type="hidden" name="qty[]" value="<?= $row['tdkn_qty']?>">
		                                    				<?= $row['tdkn_qty']?>
		                                    			</td>
		                                    			<td class="border-b">
		                                    				<input type="text" name="subtot[]" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" value="<?= $row['tdkn_subtot']?>">
		                                    			</td>
														<td class="border-b">
															<label>
																<input type="radio" name="ketersediaan[<?= $no-1; ?>]" value="ada" checked> <span style="color:green">Ada</span>
															</label>
															<label style="margin-left:10px;">
																<input type="radio" name="ketersediaan[<?= $no-1; ?>]" value="tidak_ada"> <span style="color:red">Tidak Ada</span>
															</label>
														</td>
		                                    		</tr>	
		                                    	<?php $no++; endforeach;?>	
	                                    </tbody>
	                                </table>
                            		<input type="text" name="tras_kode" value="<?= $proses['trans_kode']?>" hidden>
                            		<button class="button w-40 mx-auto justify-center block bg-gray-200 text-gray-600 mt-5">Simpan</button>
                            	</form>
                                
                            </div>
                        </div>
                    </div>
				</div>
				<div class="tab-content__pane" id="unit">
					<div class="intro-y box col-span-12 lg:col-span-6">
						<div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Data Unit
                            </h2>
                        </div>
			            <div class="p-5">
			            	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
				            	<div class="intro-y col-span-12">
				                    <div class="mb-2">Status </div>
				                    	<input type="text" class="input w-full border flex-1" value="<?= $proses['cos_status']; ?>">
					                </div>
					            <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Type </div>
					                    <input type="text" class="input w-full border flex-1"  value="<?= $proses['cos_tipe']; ?>" >
					                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Model </div>
				                    <input type="text" class="input w-full border flex-1" name="model" value="<?= $proses['cos_model']; ?>" required oninvalid="this.setCustomValidity('Model unit tidak boleh kosong ?')" oninput="setCustomValidity('')"placeholder="Masukan model unit">
				                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">No seri </div>
					                    <input type="text" class="input w-full border flex-1" value="<?= $proses['cos_no_seri']; ?>">
					                </div>
				                <div class="intro-y col-span-12 sm:col-span-6">
				                    <div class="mb-2">Password </div>
				                    <input type="text" class="input w-full border flex-1" value="<?= $proses['cos_pswd']; ?>">
				                </div>
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Asesoris</div>
				                    <textarea class="input w-full border mt-2 flex-1" name="asesoris"><?= $proses['cos_asesoris']; ?></textarea>
				                </div>
					        </div>
			            </div>
			            			            
			        </div>
				</div>
				<div class="tab-content__pane" id="kelket">
					<div class="intro-y box col-span-12 lg:col-span-6">
						<div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Keluhan dan Keterangan
                            </h2>
                        </div>
                        <div class="p-5">
                        	<div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Keluhan</div>
				                    <textarea class="input w-full border mt-2 flex-1"><?= $proses['cos_keluhan']; ?></textarea>
				                </div>
				                <div class="intro-y col-span-12">
				                    <div class="mb-2">Keterangan</div>
				                    <textarea class="input w-full border mt-2 flex-1"><?= $proses['cos_keterangan']; ?></textarea>
				                </div>
				            </div>
                        </div>
					</div>
	        	</div>				<div class="tab-content__pane" id="sparepart">
					<div class="intro-y box col-span-12">
						<div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Detail Sparepart
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="mt-5">
                                <div class="overflow-x-auto">
                                    <table class="table table-striped" id="sparepartTable">
                                        <thead>
                                            <tr class="bg-gray-700 text-white">
                                                <th class="whitespace-no-wrap">Gambar</th>
                                                <th class="whitespace-no-wrap">Nama</th>
                                                <th class="whitespace-no-wrap">Brand</th>
                                                <th class="whitespace-no-wrap">Kategori</th>
                                                <th class="whitespace-no-wrap">Kondisi</th>
                                                <th class="whitespace-no-wrap">Stock</th>
                                                <th class="whitespace-no-wrap">Lokasi</th>
                                                <th class="whitespace-no-wrap">Harga</th>
                                                <th class="whitespace-no-wrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sparepartTableBody">
                                            <!-- Data akan diisi oleh JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>			</div>
    	</div>
    	
</div>
<?php $this->load->view('Template/footer'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
     // Extract sparepart IDs from visible tdkn_ket values in the table
    function extractSparepartIds() {
        const ids = [];
        const konfirmasiPane = document.getElementById('konfirmasi');
        
        if (!konfirmasiPane) {
            return ids;
        }
        
        // Find the table within the konfirmasi pane and get all rows
        const tableRows = konfirmasiPane.querySelectorAll('table tbody tr');
        
        tableRows.forEach(row => {
            // get hidden tindakan input
            const tindakanInput = row.querySelector('input[name="ket[]"]');
            
            if (tindakanInput) {
                const text = tindakanInput.value.trim();

                // retain regex
                const idMatch = text.match(/\(ID:\s*(\d+)\)/);

                if (idMatch) {
                    ids.push(parseInt(idMatch[1], 10));
                }
            }
        });
        
        return ids;
    }
    
    // Fetch sparepart details from API
    async function fetchSparepartDetails(id) {
        try {
            const response = await fetch(`https://azventory.azzahracomputertegal.com/api/v1/inventory/${id}`, {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Authorization": `Bearer <?php echo $this->config->item('azventory_api_key'); ?>`
                }
            });
            
            if (!response.ok) {
                console.error(`Failed to fetch sparepart ${id}:`, response.statusText);
                return null;
            }
            
            const data = await response.json();
            return data.data || data;
        } catch (error) {
            console.error(`Error fetching sparepart ${id}:`, error);
            return null;
        }
    }
    
    // Format currency to IDR
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }
    
    // Populate datatable with sparepart data
    async function populateSparepartTable() {
        const ids = extractSparepartIds();
        const tableBody = document.getElementById('sparepartTableBody');
        const sparepartPane = document.getElementById('sparepart');
        
        if (!tableBody || !sparepartPane) {
            return;
        }
        
        // Hide pane if no IDs found
        if (ids.length === 0) {
            sparepartPane.style.display = 'none';
            // Also hide the tab link
            const sparepartTab = document.querySelector('a[data-target="#sparepart"]');
            if (sparepartTab) {
                sparepartTab.style.display = 'none';
            }
            return;
        }
        
        // Clear existing rows
        tableBody.innerHTML = '';
        
        // Fetch and populate data
        const promises = ids.map(id => fetchSparepartDetails(id));
        const results = await Promise.all(promises);
        
        results.forEach((sparepart, index) => {
            if (sparepart) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border-b"><img src="${sparepart.image_url || ''}" alt="${sparepart.name}" style="max-width: 80px; max-height: 80px; object-fit: cover;"></td>
                    <td class="border-b">${sparepart.name || '-'}</td>
                    <td class="border-b">${sparepart.brand || '-'}</td>
                    <td class="border-b">${sparepart.category || '-'}</td>
                    <td class="border-b">${sparepart.condition || '-'}</td>
                    <td class="border-b">
                        ${sparepart.stock ? sparepart.stock.current + ' ' + (sparepart.stock.unit || 'pcs') : '-'}
                        ${sparepart.stock && sparepart.stock.is_low ? '<span style="color: red; margin-left: 5px;">(LOW)</span>' : ''}
                    </td>
                    <td class="border-b">${sparepart.location || '-'}</td>
                    <td class="border-b">${sparepart.price ? formatCurrency(sparepart.price) : '-'}</td>
                    <td class="border-b">
                        <span style="padding: 3px 8px; border-radius: 3px; background-color: ${sparepart.status === 'aktif' ? '#10b981' : '#ef4444'}; color: white; font-size: 12px;">
                            ${sparepart.status || '-'}
                        </span>
                    </td>
                `;
                tableBody.appendChild(row);
            }
        });
        
        // Show pane if data is populated
        if (tableBody.children.length === 0) {
            sparepartPane.style.display = 'none';
            const sparepartTab = document.querySelector('a[data-target="#sparepart"]');
            if (sparepartTab) {
                sparepartTab.style.display = 'none';
            }
        } else {
            sparepartPane.style.display = 'block';
            const sparepartTab = document.querySelector('a[data-target="#sparepart"]');
            if (sparepartTab) {
                sparepartTab.style.display = 'block';
            }
        }
    }
    
    // Initialize on page load
    populateSparepartTable();
});
</script>