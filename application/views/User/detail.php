<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $proses['cos_nama']; ?> - Detail Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 10px;
            overflow-y: auto;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header-section {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .customer-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 15px;
        }
        .info-card {
            padding: 10px 12px;
            background-color: #f9fafb;
            border-radius: 6px;
        }
        .info-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 13px;
            color: #1f2937;
            font-weight: 500;
        }
        h1 {
            font-size: 22px;
            color: #1f2937;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 16px;
            color: #1f2937;
            margin-top: 18px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }
        .text-area {
            width: 100%;
            padding: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-family: inherit;
            background-color: #f9fafb;
            color: #1f2937;
            resize: vertical;
            min-height: 60px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 12px;
        }
        th {
            background-color: #374151;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .highlight {
            font-weight: 600;
            color: #1f2937;
        }
        .discount {
            color: #dc2626;
            font-weight: 600;
        }
        .total {
            color: #059669;
            font-weight: 700;
            font-size: 13px;
        }
        .empty-message {
            padding: 15px;
            text-align: center;
            color: #6b7280;
            background-color: #f9fafb;
            border-radius: 6px;
            font-size: 12px;
        }

        /* Mobile - Larger text and more spacing */
        @media (max-width: 1024px) {
            body {
                padding: 15px;
            }
            .container {
                padding: 25px;
            }
            .info-label {
                font-size: 12px;
            }
            .info-value {
                font-size: 15px;
            }
            h1 {
                font-size: 26px;
            }
            h2 {
                font-size: 18px;
                margin-top: 25px;
                margin-bottom: 15px;
            }
            .text-area {
                font-size: 14px;
                min-height: 80px;
            }
            table {
                font-size: 14px;
            }
            th {
                font-size: 13px;
                padding: 10px;
            }
            td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <h1><?= htmlspecialchars($proses['cos_nama']); ?></h1>
            <div class="customer-info">
                <div class="info-card">
                    <div class="info-label">Kode Pelanggan</div>
                    <div class="info-value"><?= htmlspecialchars($proses['cos_kode']); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value"><?= htmlspecialchars($proses['cos_hp']); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">Status</div>
                    <div class="info-value"><?= htmlspecialchars($proses['cos_status']); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">Tipe Unit</div>
                    <div class="info-value"><?= htmlspecialchars($proses['cos_tipe']); ?></div>
                </div>
            </div>
            <div style="margin-top: 10px;">
                <div class="info-label">Alamat</div>
                <textarea class="text-area" readonly><?= htmlspecialchars($proses['cos_alamat']); ?></textarea>
            </div>
        </div>

        <!-- Unit Details Section -->
        <h2>Detail Unit</h2>
        <table>
            <tr>
                <td><strong>Model</strong></td>
                <td><?= htmlspecialchars($proses['cos_model']); ?></td>
                <td><strong>No. Seri</strong></td>
                <td><?= htmlspecialchars($proses['cos_no_seri']); ?></td>
            </tr>
            <tr>
                <td><strong>Password</strong></td>
                <td><?= htmlspecialchars($proses['cos_pswd']); ?></td>
                <td><strong>Aksesoris</strong></td>
                <td><?= htmlspecialchars($proses['cos_asesoris']); ?></td>
            </tr>
        </table>

        <!-- Keluhan & Keterangan Section -->
        <h2>Keluhan dan Keterangan</h2>
        <div class="grid-2">
            <div>
                <div class="info-label">Keluhan</div>
                <textarea class="text-area" readonly><?= htmlspecialchars($proses['cos_keluhan']); ?></textarea>
            </div>
            <div>
                <div class="info-label">Keterangan</div>
                <textarea class="text-area" readonly><?= htmlspecialchars($proses['cos_keterangan']); ?></textarea>
            </div>
        </div>

        <!-- Tindakan Teknisi Section -->
        <h2>Tindakan Teknisi</h2>
        <?php if ($data->num_rows() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Tindakan</th>
                        <th width="10%" class="text-right">Qty</th>
                        <th width="20%" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $grand_total = 0;
                    foreach ($data->result_array() as $row):
                        $grand_total += $row['tdkn_subtot'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $no; ?></td>
                            <td><?= htmlspecialchars($row['tdkn_nama']); ?> : <?= htmlspecialchars($row['tdkn_ket']); ?></td>
                            <td class="text-right"><?= htmlspecialchars($row['tdkn_qty']); ?></td>
                            <td class="text-right">Rp. <?= number_format($row['tdkn_subtot'], 0, ',', '.'); ?>,-</td>
                        </tr>
                    <?php $no++; endforeach; ?>
                    <tr style="background-color: #f3f4f6;">
                        <td colspan="2" class="text-right"><strong>Subtotal</strong></td>
                        <td></td>
                        <td class="text-right"><strong>Rp. <?= number_format($grand_total, 0, ',', '.'); ?>,-</strong></td>
                    </tr>
                    <tr style="background-color: #fef2f2;">
                        <td colspan="2" class="text-right"><strong>Discount</strong></td>
                        <td></td>
                        <td class="text-right discount">- Rp. <?= number_format($hist_trans['trans_discount'], 0, ',', '.'); ?>,-</td>
                    </tr>
                    <tr style="background-color: #f0fdf4;">
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
                        <td></td>
                        <td class="text-right total">Rp. <?= number_format($hist_trans['trans_total'] - $hist_trans['trans_discount'], 0, ',', '.'); ?>,-</td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">Belum ada tindakan yang dicatat</div>
        <?php endif; ?>

        <!-- Histori Pembayaran Section -->
        <h2>Histori Pembayaran</h2>
        <?php if ($custom->num_rows() > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th class="text-center">Total Bayar</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal & Jam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($custom->result_array() as $row):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no; ?></td>
                            <td class="text-right">
                                <?php if ($row['dtl_stt_stor'] == 'Menunggu'): ?>
                                    <span style="color: #dc2626;">Rp. <?= number_format($row['dtl_jml_bayar'], 0, ',', '.'); ?>,-</span>
                                <?php else: ?>
                                    Rp. <?= number_format($row['dtl_jml_bayar'], 0, ',', '.'); ?>,-
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($row['dtl_jenis_bayar']); ?></td>
                            <td class="text-center">
                                <?php
                                $status_color = match($row['dtl_status']) {
                                    'Lunas' => '#059669',
                                    'Menunggu' => '#dc2626',
                                    default => '#6b7280'
                                };
                                ?>
                                <span style="color: <?= $status_color; ?>; font-weight: 600;">
                                    <?= htmlspecialchars($row['dtl_status']); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div><?= date('d-m-Y', strtotime($row['dtl_tanggal'])); ?></div>
                                <div style="font-size: 11px; color: #6b7280;">Jam: <?= htmlspecialchars($row['dtl_jam']); ?></div>
                            </td>
                        </tr>
                    <?php $no++; endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">Belum ada histori pembayaran</div>
        <?php endif; ?>
    </div>
</body>
</html>