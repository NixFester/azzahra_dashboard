<?php $this->load->view('Template/header'); ?>
<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Ketersediaan Sparepart</h2>
    </div>
    <div class="intro-y box mt-5 p-5">
        <table class="table table-bordered">
            <thead>
                <tr class="bg-gray-700 text-white">
                    <th>Nomor</th>
                    <th>No Transaksi</th>
                    <th>Nama Customer</th>
                    <th>Nama Barang/Sparepart</th>
                    <th>Ketersediaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($spareparts->result() as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row->trans_kode; ?></td>
                    <td><?= $row->cos_nama; ?></td>
                    <td><?= $row->barang_nama; ?></td>
                    <td>
                        <?php if($row->ketersediaan=='ada'): ?>
                            <span style="color:green;font-weight:bold">Ada</span>
                        <?php else: ?>
                            <span style="color:red;font-weight:bold">Tidak Ada di Gudang</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($row->status=='menunggu'): ?>
                        <a href="<?= site_url('Ketersediaan_sparepart/barang_sampai/'.$row->id) ?>" class="btn btn-success">Barang Telah Sampai</a>
                        <?php else: ?>
                        <span class="text-success">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->load->view('Template/footer'); ?>
