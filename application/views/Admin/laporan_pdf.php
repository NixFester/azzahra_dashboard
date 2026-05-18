<?php
// Set content type for PDF
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian Admin</title>
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
    <?php if (isset($all_have_cabang) && $all_have_cabang && isset($payments_by_cabang) && is_array($payments_by_cabang)): ?>
        <?php foreach (["Tegal", "Cibubur", "Kampus Saintek", "Kampus PKTJ"] as $cabang): ?>
            <div class="header">
                <h1>LAPORAN HARIAN</h1>
                <p>Tanggal: <?= date('d F Y') ?></p>
                <p>Admin: Azzahra Computer <?= $cabang ?></p>
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
                    <?php 
                        $cabang_data = $payments_by_cabang[$cabang] ?? [];
                        $dp_bca = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'DP' && $p['dtl_bank'] == 'BCA'; });
                        $dp_mandiri = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'DP' && $p['dtl_bank'] == 'MANDIRI'; });
                        $dp_tunai = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'DP' && $p['dtl_jenis_bayar'] == 'TUNAI'; });
                        $lunas_bca = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'PELUNASAN' && $p['dtl_bank'] == 'BCA'; });
                        $lunas_mandiri = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'PELUNASAN' && $p['dtl_bank'] == 'MANDIRI'; });
                        $lunas_tunai = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'PELUNASAN' && $p['dtl_jenis_bayar'] == 'TUNAI'; });
                        $menunggu = array_filter($cabang_data, function($p){ return $p['dtl_stt_stor'] == 'Menunggu'; });
                        
                        $sum_dp_bca = array_sum(array_column($dp_bca, 'dtl_jml_bayar'));
                        $sum_dp_mandiri = array_sum(array_column($dp_mandiri, 'dtl_jml_bayar'));
                        $sum_dp_tunai = array_sum(array_column($dp_tunai, 'dtl_jml_bayar'));
                        $sum_lunas_bca = array_sum(array_column($lunas_bca, 'dtl_jml_bayar'));
                        $sum_lunas_mandiri = array_sum(array_column($lunas_mandiri, 'dtl_jml_bayar'));
                        $sum_lunas_tunai = array_sum(array_column($lunas_tunai, 'dtl_jml_bayar'));
                        $sum_menunggu = array_sum(array_column($menunggu, 'dtl_jml_bayar'));
                    ?>
                    <tr>
                        <td>DOWN PAYMENT BANK BCA</td>
                        <td class="text-center"><?= count($dp_bca); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_dp_bca, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>DOWN PAYMENT BANK BRI</td>
                        <td class="text-center"><?= count($dp_mandiri); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_dp_mandiri, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>DOWN PAYMENT BANK MANDIRI</td>
                        <td class="text-center"><?= count($dp_tunai); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_dp_tunai, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>DOWN PAYMENT TUNAI</td>
                        <td class="text-center"><?= count($dp_tunai); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_dp_tunai, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>PELUNASAN BANK BCA</td>
                        <td class="text-center"><?= count($lunas_bca); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_lunas_bca, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>PELUNASAN BANK BRI</td>
                        <td class="text-center"><?= count($lunas_mandiri); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_lunas_mandiri, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>PELUNASAN BANK MANDIRI</td>
                        <td class="text-center"><?= count($lunas_mandiri); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_lunas_mandiri, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>PELUNASAN TUNAI</td>
                        <td class="text-center"><?= count($lunas_tunai); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_lunas_tunai, 0) ?>,-</td>
                    </tr>
                    <tr>
                        <td>STATUS SETOR MENUNGGU</td>
                        <td class="text-center"><?= count($menunggu); ?></td>
                        <td class="text-right">Rp. <?= number_format($sum_menunggu, 0) ?>,-</td>
                    </tr>
                </tbody>
            </table>

            <!-- DP Payments Detail -->
            <h3>Detail Pembayaran DP Hari Ini</h3>
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
                    $dp = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'DP'; });
                    if (!empty($dp)): 
                        foreach ($dp as $payment): 
                            $total_dp += $payment['dtl_jml_bayar'];
                            $count_dp++;
                    ?>
                            <tr>
                                <td><?= $payment['cos_kode'] ?></td>
                                <td><?= $payment['cos_nama'] ?></td>
                                <td><?= $payment['cos_alamat'] ?? '' ?></td>
                                <td><?= $payment['dtl_jenis_bayar'] ?></td>
                                <td><?= $payment['dtl_stt_stor'] ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($payment['dtl_tanggal'] . ' ' . $payment['dtl_jam'])) ?></td>
                                <td class="text-right">Rp. <?= number_format($payment['dtl_jml_bayar'], 0) ?>,-</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pembayaran DP hari ini</td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($dp)): ?>
                        <tr class="total-row">
                            <td colspan="1" class="text-center">TOTAL</td>
                            <td colspan="6" class="text-right">Rp. <?= number_format($total_dp, 0) ?>,-</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pelunasan Payments Detail -->
            <h3>Detail Pembayaran Pelunasan Hari Ini</h3>
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
                    $lunas = array_filter($cabang_data, function($p){ return $p['dtl_status'] == 'PELUNASAN'; });
                    if (!empty($lunas)): 
                        foreach ($lunas as $payment): 
                            $total_lunas += $payment['dtl_jml_bayar'];
                            $count_lunas++;
                    ?>
                            <tr>
                                <td><?= $payment['cos_kode'] ?></td>
                                <td><?= $payment['cos_nama'] ?></td>
                                <td><?= $payment['cos_alamat'] ?? '' ?></td>
                                <td><?= $payment['dtl_jenis_bayar'] ?></td>
                                <td><?= $payment['dtl_stt_stor'] ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($payment['dtl_tanggal'] . ' ' . $payment['dtl_jam'])) ?></td>
                                <td class="text-right">Rp. <?= number_format($payment['dtl_jml_bayar'], 0) ?>,-</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pembayaran pelunasan hari ini</td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($lunas)): ?>
                        <tr class="total-row">
                            <td colspan="1" class="text-center">TOTAL</td>
                            <td colspan="6" class="text-right">Rp. <?= number_format($total_lunas, 0) ?>,-</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php 
            $grand_total = ($total_dp ?? 0) + ($total_lunas ?? 0);
            $grand_count = ($count_dp ?? 0) + ($count_lunas ?? 0);
            ?>

            <div class="total-section">
                <p><strong>Total Keseluruhan: Rp. <?= number_format($grand_total, 0) ?>,-</strong></p>
                <p>Dengan Total Jumlah: <?= $grand_count ?> transaksi</p>
            </div>

            <div class="signature">
                <div>
                    <p>Azzahra Computer <?= $cabang ?></p>
                    <p>TTD</p>
                    <p>Admin</p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Fallback to original implementation -->
        <div class="header">
            <h1>LAPORAN HARIAN</h1>
            <p>Tanggal: <?= date('d F Y') ?></p>
            <p>Admin: Azzahra Computer Tegal</p>
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
                    <td>DOWN PAYMENT BANK BCA</td>
                    <td class="text-center"><?= $jml_dp_bca ? $jml_dp_bca->num_rows() : 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_dp_bca ?? 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>DOWN PAYMENT BANK BRI</td>
                    <td class="text-center"><?= $jml_dp_bri ? $jml_dp_bri->num_rows() : 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_dp_bri ?? 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>DOWN PAYMENT BANK MANDIRI</td>
                    <td class="text-center"><?= $jml_dp_mandiri ? $jml_dp_mandiri->num_rows() : 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_dp_mandiri ?? 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>DOWN PAYMENT TUNAI</td>
                    <td class="text-center"><?= $jml_dp_tunai ? $jml_dp_tunai->num_rows() : 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_dp_tunai ?? 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>PELUNASAN BANK BCA</td>
                    <td class="text-center"><?= $jml_bca->num_rows() ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_bca ?: 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>PELUNASAN BANK BRI</td>
                    <td class="text-center"><?= $jml_bri->num_rows() ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_bri ?: 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>PELUNASAN BANK MANDIRI</td>
                    <td class="text-center"><?= (isset($jml_mandiri) && is_object($jml_mandiri)) ? $jml_mandiri->num_rows() : 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($tot_mandiri ?: 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>PELUNASAN TUNAI</td>
                    <td class="text-center"><?= $count_tunai ?? 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($total_tunai ?? 0, 0) ?>,-</td>
                </tr>
                <tr>
                    <td>STATUS SETOR MENUNGGU</td>
                    <td class="text-center"><?= $menunggu_count ?? 0 ?></td>
                    <td class="text-right">Rp. <?= number_format($menunggu_total ?? 0, 0) ?>,-</td>
                </tr>
            </tbody>
        </table>

        <!-- DP Payments Detail -->
        <h3>Detail Pembayaran DP Hari Ini</h3>
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
                            <td><?= $payment['cos_alamat'] ?? '' ?></td>
                            <td><?= $payment['dtl_jenis_bayar'] ?></td>
                            <td><?= $payment['dtl_stt_stor'] ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($payment['dtl_tanggal'] . ' ' . $payment['dtl_jam'])) ?></td>
                            <td class="text-right">Rp. <?= number_format($payment['dtl_jml_bayar'], 0) ?>,-</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada pembayaran DP hari ini</td>
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
        <h3>Detail Pembayaran Pelunasan Hari Ini</h3>
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
                            <td><?= $payment['cos_alamat'] ?? '' ?></td>
                            <td><?= $payment['dtl_jenis_bayar'] ?></td>
                            <td><?= $payment['dtl_stt_stor'] ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($payment['dtl_tanggal'] . ' ' . $payment['dtl_jam'])) ?></td>
                            <td class="text-right">Rp. <?= number_format($payment['dtl_jml_bayar'], 0) ?>,-</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada pembayaran pelunasan hari ini</td>
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
        // Hitung total keseluruhan dari detail tabel
        $grand_total = ($total_dp ?? 0) + ($total_lunas ?? 0);
        $grand_count = ($count_dp ?? 0) + ($count_lunas ?? 0);
        ?>

        <div class="total-section">
            <p><strong>Total Keseluruhan: Rp. <?= number_format($grand_total, 0) ?>,-</strong></p>
            <p>Dengan Total Jumlah: <?= $grand_count ?> transaksi</p>
        </div>

        <div class="signature">
            <div>
                <p>Azzahra Computer Tegal</p>
                <p>TTD</p>
                <p>Admin</p>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>