<?php $this->load->view('Template/header'); ?>
<?php if (!isset($trans)) $trans = []; ?>
<?php if (!isset($bayar)) $bayar = []; ?>
<?php if (!isset($vocher)) $vocher = []; ?>
<?php if (!isset($role)) $role = 'cs'; ?>
<?php // ensure role variable tells links to use Service routes ?>

<div class="content">
    <div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
    <div class="gagal" data-gagal="<?php echo $this->session->flashdata('gagal');?>"></div>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Pembayaran
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a role="button" class="button text-white bg-theme-1 shadow-md mr-2">Pembayaran hari ini
            </a> 
            <div class="pos-dropdown dropdown relative ml-auto sm:ml-0">
                <button class="dropdown-toggle button px-2 box text-gray-700">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="chevron-down"></i> </span>
                </button>
                <div class="pos-dropdown__dropdown-box dropdown-box mt-10 absolute top-0 right-0 z-20">
                    <div class="dropdown-box__content box p-2">
                        <?php
                        foreach ($lap_bayar->result_array() as $lap) :?>
                            <a href="<?= site_url('Service/cari/'.$lap['trans_kode'])?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> 
                                <i data-feather="activity" class="w-4 h-4 mr-2"></i> 
                                <span class="truncate"><?= $lap['cos_kode'];?> - <?= $lap['cos_nama'];?></span> 
                            </a>
                        <?php endforeach;?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p class="mt-2 mb-5 flex flex-wrap items-center gap-2">
    <span>Klik icon</span>

    <span class="w-5 h-5 flex items-center justify-center bg-theme-6 text-white">
        <i data-feather="printer" class="w-4 h-4"></i>
    </span>

    <span>untuk cetak nota pembayaran dan icon</span>

    <span class="w-5 h-5 flex items-center justify-center bg-blue-500 text-white">
        <i data-feather="file-text" class="w-4 h-4"></i>
    </span>

    <span>untuk cetak thermal print nota pembayaran, dan icon</span>

    <span class="w-5 h-5 flex items-center justify-center bg-green-500 text-white">
        <i data-feather="message-circle" class="w-4 h-4"></i>
    </span>

    <span>untuk mengirim pesan WhatsApp kepada customer terkait pembayaran ini.</span>
</p>
    <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="intro-y datatable-wrapper box p-5 mt-5">
                <h2 class="text-lg font-medium mr-auto">
                    Histori Pembayaran
                </h2>
            	<table class="table table-report table-report--bordered display datatable w-full">
            		<thead>
		    			<tr>
		    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
		    				<th class="border-b-2 text-center whitespace-no-wrap">TOTAL BAYAR</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">JENIS</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">ACTION</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<?php
                        $no = 0;
		    			foreach ($custom->result_array() as $row) :
                            $no++;
                         ?>
		    				<tr class="cursor-pointer zoom-in">
                                <td class="text-center border-b"><?= $no?></td>
                                <td class="text-center border-b">
                                    <?php
                                    if ($row['dtl_stt_stor'] == 'Menunggu') { ?>
                                        <div class="text-theme-6">
                                            <?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
                                        </div>
                                    <?php } else { ?>
                                        <?= "Rp. ".number_format($row['dtl_jml_bayar'], 0).",-"; ?>
                                    <?php } ?>
                                    
                                </td>
                                <td class="text-center border-b">
                                    <?= $row['dtl_jenis_bayar']?>                                
                                </td>
                                <td class="text-center border-b"><?= $row['dtl_status']?></td>
                                <td class="border-b">
                                    <div class="font-medium whitespace-no-wrap">
                                        <?php echo date('d-m-Y',strtotime($row['dtl_tanggal'])) ?>
                                    </div>
                                    <div class="text-gray-600 text-xs whitespace-no-wrap">
                                        JAM : <?= $row['dtl_jam']?>
                                    </div>                              
                                </td>
                                <td class="text-center border-b">
                                    <div class="flex sm:justify-center items-center">
                                        <a href="<?= site_url('Cetak/print_1/'.$row['dtl_kode'].'?preview=1')?>" target="_blank" class="button px-2 mr-1 mb-2 bg-theme-6 text-white">
                                        <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
                                    </a>
                                        <a href="<?= site_url('Cetak/print_3/'.$trans['trans_kode'])?>" target="_blank" class="button px-2 mr-1 mb-2 bg-blue-500 text-white">
                                        <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="file-text" class="w-4 h-4"></i> </span>
                                    </a>
                                    <a role="button" onclick="sendToWA(
                                        '<?= $row['dtl_status'] ?>',
                                        '<?= $trans['cos_kode'] ?>',
                                        '<?= number_format($row['dtl_jml_bayar'], 0, ',', '.') ?>',
                                        '<?= date('d M Y', strtotime($row['dtl_tanggal'])) ?>',
                                        '<?= $trans['cos_nama'] ?>',
                                        '<?= site_url('Cetak/print_1/'.$row['dtl_kode']) ?>',
                                        '<?= $trans['cos_hp'] ?>'
                                    )" class="button px-2 mr-1 mb-2 bg-green-500 text-white">
                                            <span class="w-5 h-5 flex items-center justify-center">
                                                <i data-feather="message-circle" class="w-4 h-4"></i>
                                            </span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
		    				.
		    			<?php endforeach; ?>    			
		    		</tbody>
            	</table>                
            </div>
        </div>
        <!-- END: Item List -->
        <!-- BEGIN: Ticket -->
        <div class="col-span-12 lg:col-span-4">
            <div class="intro-y pr-1">
                <div class="box p-2">
                    <div class="pos__tabs nav-tabs justify-center flex"> 
                    	<a data-toggle="tab" data-target="#details" role="button" class="flex-1 py-2 rounded-md text-center active">Detail</a>
                    	<a data-toggle="tab" data-target="#pelunasan" role="button" class="flex-1 py-2 rounded-md text-center ">Pelunasan</a> 
                    	<a data-toggle="tab" data-target="#dp" role="button" class="flex-1 py-2 rounded-md text-center">DP</a>                    	
                    </div>
                </div>
            </div>
            <div class="tab-content">
            	<div class="tab-content__pane active" id="details">
                    <div class="box p-5 mt-5">
                        <div class="flex items-center border-b pb-5">
                            <div class="">
                                <div class="text-gray-600">Invoice</div>
                                <div><?= $trans['cos_kode']?></div>
                            </div>
                            <i data-feather="credit-card" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Nama Customer</div>
                                <div><?= $trans['cos_nama']?></div>
                            </div>
                            <i data-feather="user" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Model Unit</div>
                                <div><?= $trans['cos_model']?></div>
                            </div>
                            <i data-feather="monitor" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center border-b py-5">
                            <div class="">
                                <div class="text-gray-600">Alamat</div>
                                <div><p><?= $trans['cos_alamat']?></p></div>
                            </div>
                            <i data-feather="map-pin" class="w-4 h-4 text-gray-600 ml-auto"></i> 
                        </div>
                        <div class="flex items-center pt-5">
                            <div class="">
                                <div class="text-gray-600">Tanggal dan waktu</div>
                                <div>
                                    <?php echo date('d-m-Y',strtotime($trans['cos_tanggal'])) ?>
                                    <?= $trans['cos_jam']?>
                                </div>
                            </div>
                            <i data-feather="clock" class="w-4 h-4 text-gray-600 ml-auto"></i>
                        </div>
                        <div class="flex  pt-5">
                            <?php if ($trans['trans_status'] == 'Lunas'): ?>
                            <button onclick="sendToWA('', '<?= $trans['cos_kode'] ?>', '', '', '<?= $trans['cos_nama'] ?>', 'https://dashboard.azzahracomputertegal.com/Cetak/print_1/<?= $trans['trans_kode'] ?>', '<?= $trans['cos_hp'] ?>', 'service')" class="button text-white bg-green-500 shadow-md">
                                Kirim Pesan Selesai Service
                            </button>
                            <?php endif; ?>
                            <a href="<?= site_url('Cetak/print_6/'.$trans['trans_kode'])?>" target="_blank" class="button text-white bg-gray-600 shadow-md ml-2">
                                Print Surat Pernyataan
                            </a>
                        </div>
                    </div>
                </div>
                <!-- TANDA TANGAN DIGITAL -->
                <div class="box mt-5" style="border-radius:12px; overflow:hidden;">
                    <div class="p-3" style="background:#1a3c6e;">
                        <h5 style="color:#fff; margin:0; font-size:14px;">
                            <i data-feather="edit-3" class="w-4 h-4" style="display:inline; margin-right:6px;"></i>
                            Tanda Tangan Pelanggan
                        </h5>
                    </div>
                    <div class="p-4" style="background:#f9fafb;">

                        <?php if(!empty($signature['signature_url'])): ?>
                            
                        <div class="text-center mb-3">
                            <img src="<?= $signature['signature_url'] ?>" 
                                style="border:1px solid #ddd; border-radius:8px; width:100%; background:#fff; padding:6px;">
                        </div>
                        <div class="text-center mb-3">
                            <button onclick="gantiTTD()" 
                                class="button px-3 py-2 bg-yellow-500 text-white" 
                                style="border-radius:8px; font-size:13px; margin-right:8px;">
                                <i data-feather="refresh-cw" class="w-3 h-3" style="display:inline;"></i>
                                Ganti TTD
                            </button>
                            <button onclick="kirimTTD()" 
                                class="button px-3 py-2 bg-green-500 text-white" 
                                style="border-radius:8px; font-size:13px;">
                                <i data-feather="send" class="w-3 h-3" style="display:inline;"></i>
                                Kirim link TTD Via WA
                            </button>
                        </div>
                        <div id="area-ttd" style="display:none;">
                        <?php else: ?>
                        <div id="area-ttd">
                        <?php endif; ?>
                            <button onclick="kirimTTD()" 
                                class="button px-3 py-2 bg-green-500 text-white" 
                                style="border-radius:8px; font-size:13px;">
                                <i data-feather="send" class="w-3 h-3" style="display:inline;"></i>
                                Kirim link TTD via WA 
                            </button>
                            <p style="color:#9ca3af; font-size:12px; margin-bottom:8px; margin-top:8px; text-align:center;">
                                Gambar tanda tangan di dalam kotak berikut
                            </p>
                            <canvas id="canvas-ttd" width="400" height="160"
                             style="border:2px dashed #1a3c6e; border-radius:8px; background:#fff; cursor:crosshair; display:block; width:100%;">
                            </canvas>
                            <div style="display:flex; gap:8px; margin-top:10px; justify-content:center;">
                                <button onclick="clearTTD()" 
                                    class="button px-3 py-2 border text-gray-600"
                                    style="border-radius:8px; font-size:13px; flex:1;">
                                    Hapus
                                </button>
                                <button onclick="simpanTTD()" 
                                    class="button px-3 py-2 text-white"
                                    style="border-radius:8px; font-size:13px; flex:1; background:#1a3c6e;">
                                    Simpan
                                </button>
                            </div>
                            <div id="ttd-pesan" style="text-align:center; margin-top:8px; font-size:13px;"></div>

                        </div>
                    </div>
                </div>
                <!-- END TANDA TANGAN -->
                <div class="tab-content__pane" id="pelunasan">
                    <div class="pos__ticket box p-2 mt-5">
                        <?php
                        foreach ($tindakan->result_array() as $key ) {?>
                            <a role="button" class="flex items-center p-3 cursor-pointer transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">
                                <div class="pos__ticket__item-name truncate mr-1"><?= $key['tdkn_nama']?></div>
                                <div class="text-gray-600">x <?= $key['tdkn_qty']?></div>
                                <div class="ml-auto">
                                    <?= "Rp. ".number_format($key['tdkn_subtot'], 0).",-"; ?>
                                </div>
                            </a>
                        <?php } ?>
                        
                    </div>
                    <form method="post" action="<?= site_url($role == 'cs' ? 'Service/pelunasan_save' : 'Kasir/pelunasan')?>">
                        <div class="box p-5 mt-5">
                            <div class="flex">
                                <div class="mr-auto">Total</div>
                                <div>
                                    <?= "Rp. ".number_format($trans['trans_total'], 0).",-"; ?>
                                </div>
                            </div>
                            <div class="flex mt-4">
                                <div class="mr-auto">Discount</div>
                                <div class="text-theme-6">
                                    <?= "Rp. ".number_format($trans['trans_discount'], 0).",-"; ?> 
                                </div>
                            </div>
                            <?php
                            if (isset($bayar['dtl_stt_stor']) && $bayar['dtl_stt_stor'] == 'Menunggu') { ?>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Down Payment</div>
                                    <div class="text-theme-6"> Menunggu * &nbsp;</div>
                                    <div>
                                        <?= "Rp. ".number_format(isset($bayar['dtl_jml_bayar']) ? $bayar['dtl_jml_bayar'] : 0, 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">Pelunasan</div>
                                    <div class="font-medium text-base">
                                        <?= "Rp. ".number_format($trans['trans_total'] - $trans['trans_discount'], 0).",-"; ?>
                                        <input type="hidden" name="lunas" value="<?= $trans['trans_total'] - $trans['trans_discount']?>">
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Down Payment</div>
                                    <div>
                                        <?= "Rp. ".number_format(isset($bayar['dtl_jml_bayar']) ? $bayar['dtl_jml_bayar'] : 0, 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">Pelunasan</div>
                                    <div class="font-medium text-base">
                                        <?= "Rp. ".number_format($trans['trans_total'] - $trans['trans_discount'] - (isset($bayar['dtl_jml_bayar']) ? $bayar['dtl_jml_bayar'] : 0), 0).",-"; ?>
                                        <input type="hidden" name="lunas" value="<?= $trans['trans_total'] - $trans['trans_discount'] - (isset($bayar['dtl_jml_bayar']) ? $bayar['dtl_jml_bayar'] : 0)?>">
                                    </div>
                                </div>
                            <?php } ?>
                            
                        </div>
                        <div class="flex mt-4">
                            <div class="mr-auto font-medium text-base">Jenis Pembayaran</div>
                            <select name="jenis_bayar" class="input input--lg" required onchange="toggleBankFieldPelunasan(this)">
                                <option value="TUNAI">Tunai</option>
                                <option value="DEBIT">Debit</option>
                                <option value="TRANFER">Transfer</option>
                            </select>
                        </div>
                        <div class="flex mt-4" id="bank_field_pelunasan" style="display: none;">
                            <div class="mr-auto font-medium text-base">Bank</div>
                            <select name="bank" class="input input--lg" onchange="showAccountInfoPelunasan(this)">
                                <option value="">Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="MANDIRI">Mandiri</option>
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                                <option value="LAINNYA">Lainnya</option>
                            </select>
                        </div>
                        <div class="flex mt-4" id="account_info_pelunasan" style="display: none;">
                            <div class="mr-auto font-medium text-base">Rekening Transfer</div>
                            <div class="font-medium">FERRY JUANDA - 0470727705</div>
                        </div>
                        <div class="flex mt-5">
                            <input type="hidden" name="kode" value="<?= $trans['trans_kode']?>">
                            <button class="button w-32 text-white bg-theme-1 shadow-md ml-auto block" style="width: 500px;">Bayar Pelunasan</button>
                        </div>
                    </form>
                    <div class="flex mt-3">
                        <a href="<?= site_url('Cetak/print_4/'.$trans['trans_kode'])?>" target="_blank" class="button text-white bg-gray-600 shadow-md ml-auto block" style="width: 500px;">
                            Print Thermal
                        </a>
                    </div>
                    
                </div>
                <div class="tab-content__pane" id="dp">
                    <div class="pos__ticket box p-2 mt-5">
                        <?php
                        foreach ($tindakan->result_array() as $key ) {?>
                            <a role="button" class="flex items-center p-3 cursor-pointer transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">
                                <div class="pos__ticket__item-name truncate mr-1"><?= $key['tdkn_nama']?></div>
                                <div class="text-gray-600">x <?= $key['tdkn_qty']?></div>
                                <div class="ml-auto">
                                    <?= "Rp. ".number_format($key['tdkn_subtot'], 0).",-"; ?>
                                </div>
                            </a>
                        <?php } ?>
                        
                    </div>
                    <?php
                    if (isset($bayar['dtl_jml_bayar']) && $bayar['dtl_jml_bayar'] > 0) { ?>
                       <div class="box p-5 mt-5">
                            <div class="flex">
                                <div class="mr-auto">Total</div>
                                <div>
                                    <?= "Rp. ".number_format($trans['trans_total'], 0).",-"; ?>
                                </div>
                            </div>
                            <div class="flex mt-4">
                                <div class="mr-auto">Discount</div>
                                <div class="text-theme-6">
                                    <?= "Rp. ".number_format($trans['trans_discount'], 0).",-"; ?>
                                </div>
                            </div>
                            <?php
                            if (isset($bayar['dtl_stt_stor']) && $bayar['dtl_stt_stor'] == 'Menunggu') { ?>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Down Payment</div>
                                    <div class="text-theme-6"> Menunggu * &nbsp;</div>
                                    <div>
                                        <?= "Rp. ".number_format($bayar['dtl_jml_bayar'], 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">Pelunasan</div>
                                    <div class="font-medium text-base">
                                        <?= "Rp. ".number_format($trans['trans_total'] - $trans['trans_discount'], 0).",-"; ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Down Payment</div>
                                    <div>
                                        <?= "Rp. ".number_format($bayar['dtl_jml_bayar'], 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">Pelunasan</div>
                                    <div class="font-medium text-base">
                                        <?= "Rp. ".number_format($trans['trans_total'] - $trans['trans_discount'] - $bayar['dtl_jml_bayar'], 0).",-"; ?>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    <?php } else { ?>
                        <form method="post" action="<?= site_url($role == 'cs' ? 'Service/vocer_cari' : 'Kasir/vocer')?>">
                            <div class="box flex p-5 mt-5">
                                <input type="hidden" name="kode" value="<?= $trans['trans_kode']?>">
                                <div class="w-full relative text-gray-700">
                                    <input type="text" class="input input--lg w-full bg-gray-200 pr-10 placeholder-theme-13" name="vocer" placeholder="Jumlah Discount" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    <i class="w-4 h-4 sm:absolute my-auto inset-y-0 mr-3 right-0" data-feather="send"></i> 
                                </div>
                                <button type="submit" class="button text-white bg-theme-1 ml-2">Kirim</button>
                            </div>
                        </form>
                        <form method="post" action="<?= site_url($role == 'cs' ? 'Service/save_dp_cari' : 'Kasir/save_dp')?>">
                            <div class="box p-5 mt-5">
                                <div class="flex">
                                    <div class="mr-auto">Total</div>
                                    <div>
                                        <?= "Rp. ".number_format($trans['trans_total'], 0).",-"; ?>
                                    </div>
                                </div>
                                <div class="flex mt-4">
                                    <div class="mr-auto">Discount</div>
                                <div class="text-theme-9"> <?= isset($vocher['voc_status']) ? $vocher['voc_status'] : '' ?> &nbsp;</div>
                                    <div class="text-theme-6">
                                        <?= "Rp. ".number_format($trans['trans_discount'], 0).",-"; ?> 
                                    </div>
                                </div>
                                <div class="flex mt-4 pt-4 border-t border-gray-200">
                                    <div class="mr-auto font-medium text-base">
                                        <input type="text" name="dp" class="input input--lg w-full pr-10 placeholder-theme-13" required placeholder="Jumlah DP" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" style="width: 350px;">
                                    </div>
                                </div>
                                <div class="flex mt-4">
                                    <div class="mr-auto font-medium text-base">Jenis Pembayaran</div>
                                    <select name="jenis_bayar" class="input input--lg" required onchange="toggleBankField(this)">
                                        <option value="TUNAI">Tunai</option>
                                        <option value="DEBIT">Debit</option>
                                        <option value="TRANFER">Transfer</option>
                                    </select>
                                </div>
                                <div class="flex mt-4" id="bank_field" style="display: none;">
                                    <div class="mr-auto font-medium text-base">Bank</div>
                                    <select name="bank" class="input input--lg" onchange="showAccountInfo(this)">
                                        <option value="">Pilih Bank</option>
                                        <option value="BCA">BCA</option>
                                        <option value="MANDIRI">Mandiri</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BNI">BNI</option>
                                        <option value="LAINNYA">Lainnya</option>
                                    </select>
                                </div>
                                <div class="flex mt-4" id="account_info" style="display: none;">
                                    <div class="mr-auto font-medium text-base">Rekening Transfer</div>
                                    <div class="font-medium">FERRY JUANDA - 0470727705</div>
                                </div>
                            </div>
                            <div class="flex mt-5">
                                <input type="hidden" name="kode" value="<?= $trans['trans_kode']?>">
                                <button type="submit" class="button w-32 text-white bg-theme-1 shadow-md ml-auto block" style="width: 500px;">Bayar DP</button>
                            </div>
                        </form>
                        <div class="flex mt-3">
                            <a href="<?= site_url('Cetak/print_5/'.$trans['trans_kode'])?>" target="_blank" class="button text-white bg-gray-600 shadow-md ml-auto block" style="width: 500px;">
                                Print Thermal
                            </a>
                        </div>
                        
                    <?php } ?>
                    
                </div>                
            </div>
        </div>
        <!-- END: Ticket -->
    </div>
</div>
<script>
function toggleBankField(select) {
    const bankField = document.getElementById('bank_field');
    if (select.value === 'DEBIT' || select.value === 'TRANFER') {
        bankField.style.display = 'flex';
    } else {
        bankField.style.display = 'none';
    }
}

function toggleBankFieldPelunasan(select) {
    const bankField = document.getElementById('bank_field_pelunasan');
    if (select.value === 'DEBIT' || select.value === 'TRANFER') {
        bankField.style.display = 'flex';
    } else {
        bankField.style.display = 'none';
    }
}

function showAccountInfo(select) {
    const accountInfo = document.getElementById('account_info');
    if (select.value) {
        accountInfo.style.display = 'flex';
    } else {
        accountInfo.style.display = 'none';
    }
}

function showAccountInfoPelunasan(select) {
    const accountInfo = document.getElementById('account_info_pelunasan');
    if (select.value) {
        accountInfo.style.display = 'flex';
    } else {
        accountInfo.style.display = 'none';
    }
}

function sendToWA(status, nota, jumlah, tanggal, nama, link, hp, type = 'payment') {
    // Format phone number: remove leading 0 and add 62 for Indonesian numbers
    if (hp.startsWith('0')) {
        hp = '62' + hp.substring(1);
    }
    // Remove any spaces or special characters
    hp = hp.replace(/\D/g, '');

    let message = '';
    if (type === 'payment') {
        // Get service details
        let details = '';
        <?php foreach ($tindakan->result_array() as $key): ?>
            details += '- <?= $key['tdkn_nama'] ?>\n';
        <?php endforeach; ?>

        let transStatus = '<?= $trans['trans_status'] == 'Lunas' ? 'Lunas' : 'DP' ?>';

        message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami untuk melakukan service, jika ada keluhan setelah service bisa hubungi 085942001720 atau datang kembali ke Azzaha Computer - Authorized Service Center.\n\nUntuk Detail:\n${details}\nUntuk Status Transaksi nya:\n${transStatus}\n\nDownload aplikasi AzzaService di Playstore lalu Booking Service, Jangan lupa untuk memberikan rating pada aplikasi AzzaService ya! 😊\n\nAnda dapat melihat tanda terima digital di:\n👉 ${link}\n\nTERIMA KASIH`;
    } else if (type === 'service') {
        // Get service details
        let details = '';
        <?php foreach ($tindakan->result_array() as $key): ?>
            details += '<?= $key['tdkn_nama'] ?> - Qty: <?= $key['tdkn_qty'] ?> - Rp <?= number_format($key['tdkn_subtot'], 0, ',', '.') ?>,-\n';
        <?php endforeach; ?>

        let transStatus = '<?= $trans['trans_status'] == 'Lunas' ? 'Lunas' : 'DP' ?>';

        message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami untuk melakukan service, jika ada keluhan setelah service bisa hubungi 085942001720 atau datang kembali ke Azzaha Computer - Authorized Service Center.\n\nUntuk Detail:\n${details}\nUntuk Status Transaksi nya:\n${transStatus}\n\nDownload aplikasi AzzaService di Playstore lalu Booking Service, Jangan lupa untuk memberikan rating pada aplikasi AzzaService ya! 😊${link ? '\n\nAnda dapat melihat tanda terima digital di:\n👉 ' + link : ''}\n\nTERIMA KASIH`;
    }
    const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
    window.open(waUrl, '_blank');
}
</script>
<script>
var canvas  = document.getElementById('canvas-ttd');
var ctx     = canvas.getContext('2d');
var isDrawing = false;

// Ambil inisial dari nama pelanggan
var namaPelanggan = '<?= $trans['cos_nama'] ?>';
var inisial = namaPelanggan.split(' ').map(function(n) { 
    return n[0]; 
}).join('').toUpperCase().substring(0, 3);

function getPos(e) {
    var rect = canvas.getBoundingClientRect();
    var scaleX = canvas.width / rect.width;
    var scaleY = canvas.height / rect.height;
    if (e.touches) {
        return {
            x: (e.touches[0].clientX - rect.left) * scaleX,
            y: (e.touches[0].clientY - rect.top) * scaleY
        };
    }
    return {
        x: (e.clientX - rect.left) * scaleX,
        y: (e.clientY - rect.top) * scaleY
    };
}

canvas.addEventListener('mousedown', function(e) {
    isDrawing = true;
    var pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});

canvas.addEventListener('mousemove', function(e) {
    if (!isDrawing) return;
    var pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.strokeStyle = '#000';
    ctx.lineWidth   = 2;
    ctx.lineCap     = 'round';
    ctx.stroke();
});

canvas.addEventListener('mouseup',    function() { isDrawing = false; });
canvas.addEventListener('mouseleave', function() { isDrawing = false; });

canvas.addEventListener('touchstart', function(e) {
    e.preventDefault();
    isDrawing = true;
    var pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
});

canvas.addEventListener('touchmove', function(e) {
    e.preventDefault();
    if (!isDrawing) return;
    var pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.strokeStyle = '#000';
    ctx.lineWidth   = 2;
    ctx.lineCap     = 'round';
    ctx.stroke();
});

canvas.addEventListener('touchend', function() { isDrawing = false; });

function clearTTD() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function gantiTTD() {
    document.getElementById('area-ttd').style.display = 'block';
}

function tambahInisial() {
    ctx.font         = 'italic 13px Arial';
    ctx.fillStyle    = '#aaaaaa';
    ctx.textAlign    = 'right';
    ctx.fillText('<?= $trans['trans_kode'] ?>' + ' - ' + namaPelanggan, canvas.width - 10, canvas.height - 10);
}

function simpanTTD() {
    // Tambahkan inisial sebelum disimpan
    tambahInisial();

    var dataURL = canvas.toDataURL('image/png');
    var kode    = '<?= $trans['trans_kode'] ?>';
    var pesan   = document.getElementById('ttd-pesan');

    pesan.innerHTML   = 'Menyimpan...';
    pesan.style.color = '#888';

    fetch('<?= base_url() ?>Service/upload_signature', {
        method : 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body   : 'kode=' + encodeURIComponent(kode) + '&signature=' + encodeURIComponent(dataURL)
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            pesan.innerHTML   = 'Tanda tangan berhasil disimpan!';
            pesan.style.color = 'green';
            setTimeout(function() { location.reload(); }, 1500);
        } else {
            pesan.innerHTML   = 'Gagal menyimpan, coba lagi.';
            pesan.style.color = 'red';
        }
    });
}
</script>
<script>
function kirimTTD() {
    var hp = '<?= $trans['cos_hp'] ?>' || '';
    if (!hp) { alert('Nomor HP customer tidak tersedia'); return; }
    // format phone number
    if (hp.startsWith('0')) hp = '62' + hp.substring(1);
    hp = hp.replace(/\D/g, '');

    var link = '<?= site_url('User/public_signature/'.$trans['trans_kode']) ?>';
    var message = 'Tanda tangan digital Anda:\n' + link;
    var wa = 'https://wa.me/' + hp + '?text=' + encodeURIComponent(message);
    window.open(wa, '_blank');
}
</script>
<?php $this->load->view('Template/footer'); ?>
