<?php
// Set content type for PDF
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Admin Range</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 13px;
        }

        h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }

        /* ===== TABLE FIX ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 20px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 12px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ===== TOTAL ROW FIX ===== */
        .total-row td {
            background-color: #e6e6e6;
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #000;
        }

        /* ===== TOTAL SECTION ===== */
        .total-section {
            margin-top: 30px;
            font-size: 14px;
        }

        .total-section p {
            margin: 6px 0;
            line-height: 1.6;
        }

        /* ===== SIGNATURE ===== */
        .signature {
            margin-top: 50px;
            font-size: 12px;
        }

        .signature p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN</h1>
        <p>Azzahra Computer Tegal</p>
        <p>Tanggal, <?php echo date('d-m-Y', strtotime($tgl_awal)) ?> Sampai <?php echo date('d-m-Y', strtotime($tgl_akhir)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>DOWN PATMENT BANK BCA</td>
                <td class="text-center"><?= $jml_DP_bca->num_rows(); ?></td>
                <td class="text-right">Rp. <?= number_format($tot_DP_bca, 0) ?>,-</td>
            </tr>
            <tr>
                <td>DOWN PATMENT BANK BRI</td>
                <td class="text-center"><?= $jml_DP_bri->num_rows(); ?></td>
                <td class="text-right">Rp. <?= number_format($tot_DP_bri, 0) ?>,-</td>
            </tr>
            <tr>
                <td>DOWN PATMENT TUNAI</td>
                <td class="text-center"><?= $jml_DP_tunai->num_rows(); ?></td>
                <td class="text-right">Rp. <?= number_format($tot_DP_tunai, 0) ?>,-</td>
            </tr>
            <tr>
                <td>PELUNASAN BANK BCA</td>
                <td class="text-center"><?= $jml_lns_bca->num_rows(); ?></td>
                <td class="text-right">Rp. <?= number_format($tot_lns_bca, 0) ?>,-</td>
            </tr>
            <tr>
                <td>PELUNASAN BANK BRI</td>
                <td class="text-center"><?= $jml_lns_bri->num_rows(); ?></td>
                <td class="text-right">Rp. <?= number_format($tot_lns_bri, 0) ?>,-</td>
            </tr>
            <tr>
                <td>PELUNASAN TUNAI</td>
                <td class="text-center"><?= $jml_lns_tunai->num_rows(); ?></td>
                <td class="text-right">Rp. <?= number_format($tot_lns_tunai, 0) ?>,-</td>
            </tr>
            <tr>
                <td>STATUS SETOR MENUNGGU</td>
                <td class="text-center"><?= $menunggu_count; ?></td>
                <td class="text-right">Rp. <?= number_format($menunggu_total, 0) ?>,-</td>
            </tr>
        </tbody>
    </table>

    <!-- DP Payments Detail -->
    <h3>Detail Pembayaran DP</h3>
    <table>
        <thead>
            <tr>
                <th>TTS</th>
                <th>Nama Customer</th>
                <th>Domisili</th>
                <th>Jenis</th>
                <th>Status Setor</th>
                <th>Waktu</th>
                <th class="text-right">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_dp = 0;
            $count_dp = 0;
            if (!empty($dp_payments)):
                foreach ($dp_payments as $payment):
                    $total_dp += $payment['dtl_jml_bayar'];
                    $count_dp++;
            ?>
                    <tr>
                        <td><?= $payment['cos_kode'] ?></td>
                        <td><?= $payment['cos_nama'] ?></td>
                        <td><?= $payment['cos_alamat'] ?></td>
                        <td><?= $payment['dtl_jenis_bayar'] ?></td>
                        <td><?= $payment['dtl_stt_stor'] ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($payment['dtl_tanggal'] . ' ' . $payment['dtl_jam'])) ?></td>
                        <td class="text-right">Rp. <?= number_format($payment['dtl_jml_bayar'], 0) ?>,-</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada pembayaran DP dalam periode ini</td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($dp_payments)): ?>
                <tr class="total-row">
                    <td colspan="1" class="text-center">TOTAL</td>
                    <td colspan="6" class="text-right">Rp. <?= number_format($total_dp, 0) ?>,-</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pelunasan Payments Detail -->
    <h3>Detail Pembayaran Pelunasan</h3>
    <table>
        <thead>
            <tr>
                <th>TTS</th>
                <th>Nama Customer</th>
                <th>Domisili</th>
                <th>Jenis</th>
                <th>Status Setor</th>
                <th>Waktu</th>
                <th class="text-right">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_lunas = 0;
            $count_lunas = 0;
            if (!empty($lunas_payments)):
                foreach ($lunas_payments as $payment):
                    $total_lunas += $payment['dtl_jml_bayar'];
                    $count_lunas++;
            ?>
                    <tr>
                        <td><?= $payment['cos_kode'] ?></td>
                        <td><?= $payment['cos_nama'] ?></td>
                        <td><?= $payment['cos_alamat'] ?></td>
                        <td><?= $payment['dtl_jenis_bayar'] ?></td>
                        <td><?= $payment['dtl_stt_stor'] ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($payment['dtl_tanggal'] . ' ' . $payment['dtl_jam'])) ?></td>
                        <td class="text-right">Rp. <?= number_format($payment['dtl_jml_bayar'], 0) ?>,-</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada pembayaran pelunasan dalam periode ini</td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($lunas_payments)): ?>
                <tr class="total-row">
                    <td colspan="1" class="text-center">TOTAL</td>
                    <td colspan="6" class="text-right">Rp. <?= number_format($total_lunas, 0) ?>,-</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    // Hitung total keseluruhan dari detail tabel DP dan Pelunasan
    $grand_total = ($total_dp ?? 0) + ($total_lunas ?? 0);
    $grand_count = ($count_dp ?? 0) + ($count_lunas ?? 0);
    ?>

    <div class="total-section">
        <p><strong>Total Keseluruhan: Rp. <?= number_format($grand_total, 0) ?>,-</strong></p>
        <p>Dengan Total Customer - <?= $grand_count ?></p>
    </div>

    <div class="total-section">
        <p><strong>Bank Transfer Yang Belum disetorkan: Rp. <?= number_format($blm_setor, 0) ?>,-</strong></p>
        <p>Dengan Total Customer - <?= $jml_setor->num_rows(); ?></p>
    </div>

    <div class="total-section">
        <p><strong>Pembayaran Tunai DP dan Pelunasan: Rp. <?= number_format($tot_tunai, 0) ?>,-</strong></p>
        <p>Dengan Total Customer - <?= $jml_tunai->num_rows(); ?></p>
    </div>

    <div class="signature">
        <div>
            <p>Azzahra Computer Tegal</p>
            <p>TTD</p>
            <p>Admin</p>
        </div>
    </div>
</body>
</html>