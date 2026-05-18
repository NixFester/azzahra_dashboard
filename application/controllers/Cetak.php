<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_cetak');
		$this->load->model('M_service');
	}

	public function index()
	{
		
	}
	function print_1()
	{
	    set_time_limit(300); // Increase execution time to 5 minutes
	    $this->load->model('M_cetak');
	    $this->load->library('Pdfgenerator');

	    $param = $this->uri->segment(3);
	    $preview = $this->input->get('preview');

	    if (!is_numeric($param)) {
	        // Assume cos_kode, check transaksi and tindakan
	        $trans = $this->db->get_where('transaksi', ['cos_kode' => $param])->row_array();
	        if ($trans) {
	            $trans_kode = $trans['trans_kode'];
	            $barang = $this->M_service->GetTindakanBy($trans_kode)->result_array();
	            if (!empty($barang)) {
	                // Use this data
	                $data['customer'] = $this->db->get_where('costomer', ['id_costomer' => $trans['cos_kode']])->row_array();
	                $data['barang'] = $barang;
	                $data['pembayaran'] = $this->M_cetak->pembayaran($trans_kode)->result_array();
	                $data['kasir'] = $this->session->userdata('nama');
	                $data['tanggal'] = date('d/m/Y', strtotime($data['customer']['created_at']));
	                $data['jam'] = date('H:i', strtotime($data['customer']['created_at']));
	                $data['dtl_status'] = 'PELUNASAN';
	                $data['dp'] = 0;
	                $data['is_cos_kode'] = true;
	                $total_barang = 0;
	                foreach ($data['barang'] as $row) {
	                    $subtotal = ($row['tdkn_qty'] ?? 1) * $row['tdkn_subtot'];
	                    $total_barang += $subtotal;
	                }
	                $data['final_total'] = $total_barang;
	                $html = $this->load->view('invoice_template', $data, TRUE);
	                $this->pdfgenerator->generate($html, 'Invoice_'.$trans_kode, 'A4', 'landscape', $preview ? false : true);
	                return;
	            }
	        }
	        show_error('Data tidak ditemukan');
	        return;
	    } else {
	        // Numeric, try as dtl_kode first
	        $detail = $this->db->get_where('transaksi_detail', ['dtl_kode' => $param])->row_array();
	        if ($detail) {
	            // Use detail logic
	            $trans_kode = $detail['trans_kode'];
	            $dtl_status = $detail['dtl_status'];
	            $data['customer'] = $this->M_cetak->trans($trans_kode)->row_array();
	            $data['barang'] = $this->M_service->GetTindakanBy($trans_kode)->result_array();
	            $data['pembayaran'] = $this->M_cetak->pembayaran($trans_kode)->result_array();
	            $data['kasir'] = $this->session->userdata('nama');
	            $data['tanggal'] = date('d/m/Y');
	            $data['dtl_status'] = $dtl_status;
	            $dp = 0;
	            if ($dtl_status == 'DP') {
	                $dp = $detail['dtl_jml_bayar'];
	            } elseif ($dtl_status == 'PELUNASAN') {
	                $dp_detail = $this->db->get_where('transaksi_detail', ['trans_kode' => $trans_kode, 'dtl_status' => 'DP'])->row_array();
	                if ($dp_detail) {
	                    $dp = $dp_detail['dtl_jml_bayar'];
	                }
	            }
	            $data['dp'] = $dp;
	            $total_barang = 0;
	            foreach ($data['barang'] as $row) {
	                $subtotal = ($row['tdkn_qty'] ?? 1) * $row['tdkn_subtot'];
	                $total_barang += $subtotal;
	            }
	            $final_total = $total_barang - $dp;
	            if ($dtl_status == 'PELUNASAN') {
	                $final_total = $total_barang;
	            }
	            $data['final_total'] = $final_total;
	            $html = $this->load->view('invoice_template', $data, TRUE);
	            $this->pdfgenerator->generate($html, 'Invoice_'.$trans_kode.'_'.$param, 'A4', 'landscape', $preview ? false : true);
	            return;
	        } else {
	            // Try as trans_kode
	            $trans = $this->db->get_where('transaksi', ['trans_kode' => $param])->row_array();
	            if ($trans) {
	                $data['customer'] = $this->db->get_where('costomer', ['id_costomer' => $trans['cos_kode']])->row_array();
	                $data['barang'] = $this->M_service->GetTindakanBy($param)->result_array();
	                $data['pembayaran'] = $this->M_cetak->pembayaran($param)->result_array();
	                $data['kasir'] = $this->session->userdata('nama');
	                $data['tanggal'] = date('d/m/Y');
	                $data['dtl_status'] = 'PELUNASAN';
	                $data['dp'] = 0;
	                $total_barang = 0;
	                foreach ($data['barang'] as $row) {
	                    $subtotal = ($row['tdkn_qty'] ?? 1) * $row['tdkn_subtot'];
	                    $total_barang += $subtotal;
	                }
	                $data['final_total'] = $total_barang;
	                $html = $this->load->view('invoice_template', $data, TRUE);
	                $this->pdfgenerator->generate($html, 'Invoice_'.$param.'_trans', 'A4', 'landscape', $preview ? false : true);
	                return;
	            }
	        }
	    }
	    show_error('Data tidak ditemukan');
	}

	function download($trans_kode, $dtl_status = 'PELUNASAN')
	{
	    $this->load->model('M_cetak');
	    $this->load->library('Pdfgenerator');

	    // Determine dtl_status and detail if needed
	    $detail = null;
	    if ($dtl_status == 'DP' || $dtl_status == 'PELUNASAN') {
	        // For specific status, find the latest detail with that status
	        $this->db->where('trans_kode', $trans_kode);
	        $this->db->where('dtl_status', $dtl_status);
	        $this->db->order_by('dtl_kode', 'DESC');
	        $this->db->limit(1);
	        $detail = $this->db->get('transaksi_detail')->row_array();
	    }

	    $data['customer'] = $this->M_cetak->trans($trans_kode)->row_array();
	    $data['barang'] = $this->M_service->GetTindakanBy($trans_kode)->result_array();
	    $data['pembayaran'] = $this->M_cetak->pembayaran($trans_kode)->result_array();
	    $data['kasir'] = $this->session->userdata('nama');
	    $data['tanggal'] = date('d/m/Y');
	    $data['dtl_status'] = $dtl_status;
	    if ($detail) {
	        $data['dtl_jml_bayar'] = $detail['dtl_jml_bayar'];
	    } else {
	        $detail_pelunasan = $this->db->get_where('transaksi_detail', ['trans_kode' => $trans_kode, 'dtl_status' => 'PELUNASAN'])->row_array();
	        $data['dtl_jml_bayar'] = $detail_pelunasan ? $detail_pelunasan['dtl_jml_bayar'] : 0;
	    }

	    // Fetch DP amount
	    $dp = 0;
	    if ($dtl_status == 'DP' && $detail) {
	        $dp = $detail['dtl_jml_bayar'];
	    } elseif ($dtl_status == 'PELUNASAN') {
	        $dp_detail = $this->db->get_where('transaksi_detail', ['trans_kode' => $trans_kode, 'dtl_status' => 'DP'])->row_array();
	        if ($dp_detail) {
	            $dp = $dp_detail['dtl_jml_bayar'];
	        }
	    }
	    $data['dp'] = $dp;

	    // Calculate final total
	    $total_barang = 0;
	    foreach ($data['barang'] as $row) {
	        $subtotal = ($row['tdkn_qty'] ?? 1) * $row['tdkn_subtot'];
	        $total_barang += $subtotal;
	    }
	    $final_total = $total_barang - $dp;
	    if ($dtl_status == 'PELUNASAN') {
	        $final_total = $total_barang;
	    }
	    $data['final_total'] = $final_total;

	    // Debug logging
	    log_message('info', 'Download trans_kode: ' . $trans_kode . ', dtl_status: ' . $dtl_status);
	    log_message('info', 'Customer data: ' . json_encode($data['customer']));
	    log_message('info', 'Barang data: ' . json_encode($data['barang']));

	    $html = $this->load->view('invoice_template', $data, TRUE);

	    // If preview, output data as JSON for debugging
	    if ($this->input->get('preview')) {
	        header('Content-Type: application/json');
	        echo json_encode($data);
	        exit;
	    }

	    // Force download without preview
	    $this->pdfgenerator->generate($html, 'Invoice_'.$trans_kode.'_'.$dtl_status, 'A4', 'landscape', true);
	}

	function auto_download()
	{
	    $dtl_kode = $this->input->get('code');
	    if (!$dtl_kode) {
	        show_error('Invalid download code');
	        return;
	    }

	    $data['dtl_kode'] = $dtl_kode;
	    $this->load->view('auto_download', $data);
	}
	function print_2()
	{
		$this->load->library('pdf');
        $kode = $this->uri->segment(3);

        $pdf = new FPDF('P','mm','a10');
        $pdf->setMargins(1,1,2);
        $pdf->SetAutoPageBreak(true,1);

        $pdf->AddPage();

        $pdf->setTitle('Kwitansi Pembayaran');
        $pdf->SetFillColor(0,0,255);

        $pdf->SetFont('times','B',14);
        $pdf->Cell(190,3,'',0,1,'C');
        $pdf->Cell(190,0,'AUTHORIZED MULTIBRAND SERVICE CENTER TEGAL',0,1,'C');
        $pdf->Cell(190,8,'AZZAHRA COMPUTER',0,1,'C');
        $pdf->SetFont('times','',10);
        $pdf->Cell(190,4,'ALAMAT : RUKO CITRALAND B/11 JL.SIPELEM - TEGAL ',0,1,'C');
        $pdf->Cell(190,4,'Telp. 0823-340909',0,1,'C');
        $pdf->Cell(190,4,'WA : 0859-4200-1720',0,1,'C');

        $pdf->SetLineWidth(0.7);
        $pdf->Line(5,25,205,25);
        $pdf->Ln();

        $pdf->SetFont('times','BU',12);
        $pdf->Cell(190,6,'KWITANSI PEMBAYARAN',0,1,'C');

        $customer = $this->M_cetak->trans_reurn($kode)->row_array();
        $bayar 	  = $this->M_cetak->bayar($kode)->row_array();

        $pdf->SetFont('times','',12);
        $pdf->SetLineWidth(0.1);
        $pdf->Cell(5,5,'',0,0,'L');
        $pdf->Cell(20,5,'Customer',0,0,'L');
        $pdf->Cell(110,5,': '.$customer['cos_nama'],0,0,'L');
        $pdf->Cell(25,5,'Invoice',0,0,'L');
        $pdf->Cell(40,5,': '.$customer['cos_kode'],0,1,'L');

        $pdf->Cell(5,5,'',0,0,'L');
        $pdf->Cell(20,5,'Hp./WA',0,0,'L');
        $pdf->Cell(110,5,': '.$customer['cos_hp'],0,0,'L');
        $pdf->Cell(25,5,'Tanggal',0,0,'L');
        $pdf->Cell(40,5,': '.date('d-F-Y H:i:s'),0,1,'L');

        $pdf->Cell(5,5,'',0,0,'L');
        $pdf->Cell(20,5,'Alamat',0,0,'L');
        $pdf->Cell(115,5,': '.$customer['cos_alamat'],0,0,'L');
        $pdf->Cell(25,5,'',0,0,'L');
        $pdf->Cell(35,5,'',0,1,'L');

        $pdf->Cell(35,2,'',0,1,'L');
        $pdf->SetFillColor(210,221,242);

        $pdf->Ln();
    	$pdf->Ln();
    	$pdf->SetFont('times','B',12);
        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(40,6,'TANGGAL',1,0,'C',true);
        $pdf->Cell(115,6,'DESCRIPTION',1,0,'L',true);
        $pdf->Cell(40,6,'TOTAL',1,1,'C',true);

        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(40,6,date('d-m-Y',strtotime($bayar['dtl_tanggal'])),1,0,'C');
        $pdf->Cell(115,6,$bayar['dtl_status'],1,0,'L');
        $pdf->Cell(40,6,'Rp.'.number_format($bayar['dtl_jml_bayar'], 0),1,1,'C');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->Cell(115,6,'',0,0,'L');
        $pdf->Cell(40,6,'Kasir',0,1,'C');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->Cell(115,6,'',0,0,'L');
        $pdf->Cell(40,6,$this->session->userdata('nama'),0,1,'C');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('times','BI',12);
        $pdf->Cell(190,10,'     Anda melakukan pembayaran pada:',0,1,'L');

        $pdf->SetFont('times','B',12);
        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(40,6,'TANGGAL',1,0,'C');
        $pdf->Cell(105,6,'DESCRIPTION',1,0,'L');
        $pdf->Cell(40,6,'SUBTOTAL',1,1,'C');

        $pembayaran = $this->M_cetak->pembayaran($kode)->result_array();

        $noo 		= 0;
        $jml_bayar 	= 0;
        foreach ($pembayaran as $key ) {
        $noo++;
        	$pdf->Cell(5,6,'',0,0,'L');
	        $pdf->Cell(10,6,$noo,1,0,'C');
	        $pdf->Cell(40,6,date('d-m-Y',strtotime($key['dtl_tanggal'])),1,0,'C');
	        $pdf->Cell(105,6,$key['dtl_status'].'/'.$key['dtl_bank'].'/'.$key['dtl_jenis_bayar'],1,0,'L');
	        if ($key['dtl_stt_stor'] == 'Menunggu') {
	        	$pdf->Cell(40,6,' Rp.0',1,1,'C');
	        } else {
	        	$pdf->Cell(40,6,' Rp.'.number_format($key['dtl_jml_bayar'], 0),1,1,'L');
	        }

	    $jml_bayar += $key['dtl_jml_bayar'];
        }

        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(10,6,'',1,0,'C');
        $pdf->Cell(40,6,'',1,0,'C');
        $pdf->Cell(105,6,'Total Pengembalian Pembayaran ',1,0,'L');
        $pdf->Cell(40,6,' Rp.'.number_format($customer['dtl_jml_bayar'] - $customer['trans_total'] ,0),1,1,'L');

        $pdf->AddPage();

        $pdf->SetFont('times','BI',12);
        $pdf->Cell(190,10,'     *Dengan rincian sebagai berikut:',0,1,'L');

        $pdf->SetFont('times','B',12);
        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(145,6,'TINDAKAN / BARANG',1,0,'L');
        $pdf->Cell(40,6,'SUBTOTAL',1,1,'C');

        $pdf->SetFont('times','',12);

        $barang = $this->M_cetak->barang($kode)->result_array();

        $no = 0;
        foreach ($barang as $row ) {
        $no++;
            $desc = $row['tdkn_nama'];

            if ($row['tdkn_nama'] == 'Custom') {
                $desc .= ' : ' . $row['tdkn_ket'];
            }
        	$pdf->Cell(5,6,'',0,0,'L');
	        $pdf->Cell(10,6,$no,1,0,'C');
            $pdf->Cell(145,6,$desc,1,0,'L');
	        $pdf->Cell(40,6,' Rp.'.number_format($row['tdkn_subtot'], 0),1,1,'L');
        }

        $pdf->Cell(5,6,'',0,0,'L');
        $pdf->Cell(10,6,'',1,0,'C');
        $pdf->Cell(145,6,'Total ',1,0,'R');
        $pdf->Cell(40,6,' Rp.'.number_format($customer['trans_total'], 0),1,1,'L');

        $pdf->Output('KWT_RETURN'.date('Y-m-d H:i:s').'.pdf','I');

}
	// SAYA NARUH NAMA
// KODE DIBAWAH DIBUAT PAKE AI KEMUNGKINAN
// GATAU

// ============================================================
// HELPER FUNCTION - Brand Logo Section (reusable)
// ============================================================
// Taruh helper ini di atas function print_3 / atau bisa
// dijadikan private method di controller kamu.

/*
 * _render_brand_logos($pdf, $w, $lm)
 * Menggambar section brand logo di struk thermal.
 */

// ============================================================
// HELPER — shared receipt renderer
// ============================================================
// Dipanggil oleh print_3 / print_4 / print_5
// Parameter $opts:
//   title        => judul utama (KWITANSI PEMBAYARAN / PELUNASAN / DOWN PAYMENT (DP))
//   section_title=> judul section pembayaran (PEMBAYARAN / PEMBAYARAN PELUNASAN / PEMBAYARAN DP)
//   customer     => array data customer
//   bayar        => array data pembayaran
//   barang       => array rincian layanan
//   total        => angka total transaksi
//   output_prefix=> prefix nama file PDF output
// ----------------------------------------------------------------
private function _render_thermal_receipt(array $opts)
{
    // ── Layout constants ──────────────────────────────────
    $w   = 76;   // lebar isi (80mm - 2mm margin kiri - 2mm margin kanan)
    $lm  = 2;    // left margin

    // Kolom kiri (label) + separator + kolom kanan (nilai)
    // Proporsi: label 22mm | sep 4mm | nilai 50mm  → total 76mm
    $wL  = 22;
    $wSep= 4;
    $wR  = $w - $wL - $wSep;   // = 50

    // Untuk rincian layanan: nomor 5mm | nama 41mm | harga 30mm
    $wNo   = 5;
    $wNama = 41;
    $wHrg  = $w - $wNo - $wNama;  // = 30

    // Garis — isi penuh lebar $w (76 karakter Courier 6pt ≈ 76mm)
    // Pakai karakter '-' dan '=' sebanyak 45 karakter (empiris pas untuk font 6pt di 76mm)
    $line_s = str_repeat('-', 57);   // single line
    $line_d = str_repeat('=', 57);   // double line

    // ── Setup PDF ─────────────────────────────────────────
    $pdf = new FPDF('P', 'mm', array(80, 200));
    $pdf->setMargins($lm, $lm, $lm);
    $pdf->SetAutoPageBreak(true, 2);
    $pdf->AddPage();
    $pdf->setTitle('Thermal Receipt');

    // ── Logo Perusahaan ───────────────────────────────────
    $logo_path = FCPATH . 'assets/image/logo-thermal.png';
    if (file_exists($logo_path)) {
        $logo_width = 10;
        $logo_x     = $lm + ($w - $logo_width) / 2;
        $y_logo     = $pdf->GetY() + 0.5;
        $pdf->Image($logo_path, $logo_x, $y_logo, $logo_width);
        $pdf->SetY($y_logo + $logo_width + 1);
    }

    // ── Header ────────────────────────────────────────────
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 3, 'AUTHORIZED MULTIBRAND SERVICE CENTER', 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 9);
    $pdf->Cell($w, 4, 'AZZAHRA COMPUTER', 0, 1, 'C');
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 3, 'Telp: 0823-340909  |  WA: 0859-4200-1720', 0, 1, 'C');

    // ── Judul ─────────────────────────────────────────────
    $pdf->Ln(1);
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_d, 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell($w, 5, $opts['title'], 0, 1, 'C');
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_d, 0, 1, 'C');
    $pdf->Ln(1);

    // ── Helper closure: satu baris label : nilai ──────────
    // Untuk nilai yang bisa multi-line gunakan $multiline = true
    $row = function($label, $value, $bold = false, $multiline = false)
           use ($pdf, $lm, $wL, $wSep, $wR) {
        $pdf->SetFont('Courier', '', 7);
        $pdf->Cell($wL,   4, $label, 0, 0, 'L');
        $pdf->Cell($wSep, 4, ':',    0, 0, 'L');
        if ($multiline) {
            $pdf->SetFont('Courier', $bold ? 'B' : '', 7);
            $pdf->MultiCell($wR, 4, $value, 0, 'L');
        } else {
            $pdf->SetFont('Courier', $bold ? 'B' : '', 7);
            $pdf->Cell($wR, 4, $value, 0, 1, 'L');
        }
    };

    // ── Data Customer ─────────────────────────────────────
    $customer = $opts['customer'];
    $pdf->SetFont('Courier', '', 7);
    $row('Invoice',  $customer['cos_kode']  ?? $customer['id_costomer'] ?? '');
    $row('Customer', $customer['cos_nama']  ?? '', false, true);
    $row('Tanggal',  date('d-m-Y H:i:s'));

    // ── Section Pembayaran ────────────────────────────────
    $bayar = $opts['bayar'];
    $pdf->Ln(1);
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_s, 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 7);
    $pdf->Cell($w, 4, $opts['section_title'], 0, 1, 'C');
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_s, 0, 1, 'C');
    $pdf->Ln(1);

    $row('Tanggal', date('d-m-Y', strtotime($bayar['dtl_tanggal'] ?? date('Y-m-d'))));
    $row('Status',  $bayar['dtl_status']      ?? '');
    $row('Jenis',   $bayar['dtl_jenis_bayar'] ?? '-');
    if (!empty($bayar['dtl_bank'])) {
        $row('Bank', $bayar['dtl_bank']);
    }
    $row('Dibayar', 'Rp. ' . number_format($bayar['dtl_jml_bayar'] ?? 0, 0, ',', '.'), true);

    // ── Section Rincian Layanan ───────────────────────────
    $barang = $opts['barang'];
    $pdf->Ln(1);
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_s, 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 7);
    $pdf->Cell($w, 4, 'RINCIAN LAYANAN', 0, 1, 'C');
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_s, 0, 1, 'C');
    $pdf->Ln(1);

    $no = 1;
    $pdf->SetFont('Courier', '', 7);
    foreach ($barang as $item) {
        $nomor = $no . '.';
        $nama  = $item['tdkn_nama'] . ":" . $item['tdkn_ket'];
        $harga = 'Rp. ' . number_format($item['tdkn_subtot'], 0, ',', '.');

        $y_start = $pdf->GetY();

        // Kolom nomor — tetap di posisi atas baris
        $pdf->SetXY($lm, $y_start);
        $pdf->Cell($wNo, 4, $nomor, 0, 0, 'L');

        // Kolom nama — multi-line, mulai setelah nomor
        $pdf->SetXY($lm + $wNo, $y_start);
        $pdf->MultiCell($wNama, 4, $nama, 0, 'L');
        $y_end = $pdf->GetY();

        // Kolom harga — rata kanan, sejajar baris PERTAMA nama
        $pdf->SetXY($lm + $wNo + $wNama, $y_start);
        $pdf->Cell($wHrg, 4, $harga, 0, 0, 'R');

        // Lanjut ke bawah nama (baris paling bawah)
        $pdf->SetY($y_end);
        $no++;
    }

    // ── Total ─────────────────────────────────────────────
    $pdf->Ln(1);
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_s, 0, 1, 'C');
    $pdf->SetFont('Courier', 'B', 9);
    $pdf->Cell($wL + $wSep + $wNama - $wHrg, 5, 'TOTAL', 0, 0, 'L');
    $pdf->Cell($wHrg + $wNo, 5, 'Rp. ' . number_format($opts['total'], 0, ',', '.'), 0, 1, 'R');
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 2, $line_d, 0, 1, 'C');

    // ── Footer ────────────────────────────────────────────
    $pdf->Ln(1);
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 3, 'Terima Kasih Atas Kunjungan Anda',      0, 1, 'C');
    $pdf->Cell($w, 3, 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan',  0, 1, 'C');
    
    $pdf->Cell($w, 3, 'Authorized Service Center',             0, 1, 'C');

    // ── Brand Logo ────────────────────────────────────────
    $pdf->Ln(2);
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 3, '- Brand yang kami layani -', 0, 1, 'C');
    $pdf->Ln(1);

    $brands = [
        'lenovo' => FCPATH . 'assets/image/LENOVO_Thermal.jpeg',
        'asus'   => FCPATH . 'assets/image/Asus_Thermal.jpeg',
        'msi'    => FCPATH . 'assets/image/MSI_Thermal.jpeg',
        'advan'  => FCPATH . 'assets/image/ADVAN_Thermal.jpeg',
        'dac'    => FCPATH . 'assets/image/DAC_Thermal.jpeg',
        'spc'    => FCPATH . 'assets/image/SPC_Thermal.jpeg',
        'xiaomi' => FCPATH . 'assets/image/xiaomi_Thermal.jpeg',
        'canon'  => FCPATH . 'assets/image/CANON_Thermal.jpeg',
    ];

    $logo_h  = 5;
    $logo_w  = 8;
    $gap     = 1.5;
    $per_row = 8;

    $brands_keys = array_keys($brands);
    $brands_list = array_values($brands);
    $total_brand = count($brands_list);
    $row_width   = ($logo_w + $gap) * $per_row - $gap;
    $start_x     = $lm + ($w - $row_width) / 2;

    $y_row = $pdf->GetY();
    for ($i = 0; $i < $total_brand; $i++) {
        $col = $i % $per_row;
        if ($col === 0 && $i !== 0) {
            $y_row += $logo_h + $gap;
        }
        $bx = $start_x + $col * ($logo_w + $gap);

        if (file_exists($brands_list[$i])) {
            $pdf->Image($brands_list[$i], $bx, $y_row, $logo_w, $logo_h);
        } else {
            $pdf->SetXY($bx, $y_row);
            $pdf->SetFont('Courier', '', 5);
            $pdf->Cell($logo_w, $logo_h, strtoupper($brands_keys[$i]), 1, 0, 'C');
        }
    }
    $pdf->SetY($y_row + $logo_h + 2);

    // ── QR Code ───────────────────────────────────────────
    $pdf->SetFont('Courier', '', 6);
    $pdf->Cell($w, 3, '- Invoice QR Code -', 0, 1, 'C');

    $qr_size = 28;
    $qr_x    = $lm + ($w - $qr_size) / 2;

    $customer_id = $customer['id_costomer'] ?? ($customer['cos_kode'] ?? '');
    $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='
            . urlencode('https://dashboard.azzahracomputertegal.com/user/' . $customer_id);

    $temp_qr_file = sys_get_temp_dir() . '/qr_' . time() . '.png';
    $qr_generated = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($qr_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $qr_data = curl_exec($ch);
        curl_close($ch);

        if ($qr_data !== false && !empty($qr_data)) {
            @file_put_contents($temp_qr_file, $qr_data);
            if (file_exists($temp_qr_file) && filesize($temp_qr_file) > 0) {
                $y_before = $pdf->GetY() + 2;
                $pdf->Image($temp_qr_file, $qr_x, $y_before, $qr_size, $qr_size);
                $pdf->SetY($y_before + $qr_size + 3);
                @unlink($temp_qr_file);
                $qr_generated = true;
            }
        }
    }

    if (!$qr_generated) {
        $qr_image = FCPATH . 'assets/image/qrwaaz.jpg';
        if (file_exists($qr_image)) {
            $y_before = $pdf->GetY() + 2;
            $pdf->Image($qr_image, $qr_x, $y_before, $qr_size, $qr_size);
            $pdf->SetY($y_before + $qr_size + 3);
        }
    }

    $pdf->SetFont('Courier', '', 6);

    $pdf->Output($opts['output_prefix'] . '_' . date('Y-m-d_H-i-s') . '.pdf', 'I');
}


// ============================================================
// FUNCTION print_3 — KWITANSI PEMBAYARAN
// ============================================================
function print_3()
{
    ob_start();
    $this->load->library('pdf');
    $kode = $this->uri->segment(3);

    $customer = $this->M_cetak->trans_reurn($kode)->row_array();
    $bayar    = $this->M_cetak->bayar($kode)->row_array();
    $barang   = $this->M_cetak->barang($kode)->result_array();

    $this->_render_thermal_receipt([
        'title'         => 'KWITANSI PEMBAYARAN',
        'section_title' => 'PEMBAYARAN',
        'customer'      => $customer,
        'bayar'         => $bayar,
        'barang'        => $barang,
        'total'         => $customer['trans_total'],
        'output_prefix' => 'THERMAL_RECEIPT',
    ]);
}


// ============================================================
// FUNCTION print_4 — PELUNASAN
// ============================================================
function print_4()
{
    ob_start();
    $this->load->library('pdf');
    $trans_kode = $this->uri->segment(3);

    if (!$trans_kode) { show_error('Kode transaksi tidak ditemukan'); return; }

    $trans = $this->db->get_where('transaksi', ['trans_kode' => $trans_kode])->row_array();
    if (!$trans) { show_error('Data transaksi tidak ditemukan'); return; }

    $customer = $this->db->get_where('costomer', ['id_costomer' => $trans['cos_kode']])->row_array();
    if (!$customer) { show_error('Data customer tidak ditemukan'); return; }

    $this->db->where('trans_kode', $trans_kode);
    $this->db->where('dtl_status', 'PELUNASAN');
    $this->db->order_by('dtl_kode', 'DESC');
    $this->db->limit(1);
    $bayar = $this->db->get('transaksi_detail')->row_array();

    if (!$bayar) {
        $bayar_list = $this->M_cetak->bayar($trans_kode)->result_array();
        $bayar = !empty($bayar_list) ? end($bayar_list) : [
            'dtl_tanggal'     => date('Y-m-d H:i:s'),
            'dtl_status'      => 'PELUNASAN',
            'dtl_jenis_bayar' => '-',
            'dtl_bank'        => '',
            'dtl_jml_bayar'   => $trans['trans_total'],
        ];
    }

    $barang = $this->M_service->GetTindakanBy($trans_kode)->result_array();

    $this->_render_thermal_receipt([
        'title'         => 'PELUNASAN',
        'section_title' => 'PEMBAYARAN PELUNASAN',
        'customer'      => array_merge($customer, ['cos_kode' => $customer['id_costomer'] ?? '']),
        'bayar'         => $bayar,
        'barang'        => $barang,
        'total'         => $trans['trans_total'],
        'output_prefix' => 'THERMAL_PELUNASAN',
    ]);
}


// ============================================================
// FUNCTION print_5 — DOWN PAYMENT (DP)
// ============================================================
function print_5()
{
    ob_start();
    $this->load->library('pdf');
    $trans_kode = $this->uri->segment(3);

    if (!$trans_kode) { show_error('Kode transaksi tidak ditemukan'); return; }

    $trans = $this->db->get_where('transaksi', ['trans_kode' => $trans_kode])->row_array();
    if (!$trans) { show_error('Data transaksi tidak ditemukan'); return; }

    $customer = $this->db->get_where('costomer', ['id_costomer' => $trans['cos_kode']])->row_array();
    if (!$customer) { show_error('Data customer tidak ditemukan'); return; }

    $this->db->where('trans_kode', $trans_kode);
    $this->db->where('dtl_status', 'DP');
    $this->db->order_by('dtl_kode', 'DESC');
    $this->db->limit(1);
    $bayar = $this->db->get('transaksi_detail')->row_array();

    if (!$bayar) {
        $bayar_list = $this->M_cetak->bayar($trans_kode)->result_array();
        $bayar = !empty($bayar_list) ? end($bayar_list) : [
            'dtl_tanggal'     => date('Y-m-d H:i:s'),
            'dtl_status'      => 'DP',
            'dtl_jenis_bayar' => '-',
            'dtl_bank'        => '',
            'dtl_jml_bayar'   => $trans['trans_total'],
        ];
    }

    $barang = $this->M_service->GetTindakanBy($trans_kode)->result_array();

    $this->_render_thermal_receipt([
        'title'         => 'DOWN PAYMENT (DP)',
        'section_title' => 'PEMBAYARAN DP',
        'customer'      => array_merge($customer, ['cos_kode' => $customer['id_costomer'] ?? '']),
        'bayar'         => $bayar,
        'barang'        => $barang,
        'total'         => $trans['trans_total'],
        'output_prefix' => 'THERMAL_DP',
    ]);
}

function print_6()
{
    $this->load->library('pdf');
    $trans_kode = $this->uri->segment(3);

    if (!$trans_kode) {
        show_error('Kode transaksi tidak ditemukan');
        return;
    }

    $trans = $this->db->get_where('transaksi', ['trans_kode' => $trans_kode])->row_array();
    if (!$trans) {
        show_error('Data transaksi tidak ditemukan');
        return;
    }

    $customer = $this->db->get_where('costomer', ['id_costomer' => $trans['cos_kode']])->row_array();
    if (!$customer) {
        show_error('Data customer tidak ditemukan');
        return;
    }

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->setMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();
    $pdf->setTitle('Surat Pernyataan Garansi');

    $logo_path = FCPATH . 'assets/image/logo.png';
    if (file_exists($logo_path)) {
        $pdf->Image($logo_path, 15, 15, 30);
    }

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, 'AUTHORIZED MULTIBRAND SERVICE CENTER', 0, 1, 'C');
    $pdf->Cell(0, 8, 'AZZAHRA COMPUTER', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5, 'RUKO CITRALAND B/11 JL. SIPELEM - TEGAL', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Telp. 0823-340909   WA: 0859-4200-1720', 0, 1, 'C');
    $pdf->Ln(4);

    $pdf->SetFont('Arial', 'BU', 12);
    $pdf->Cell(0, 8, 'SURAT PERNYATAAN', 0, 1, 'C');
    $pdf->Ln(2);

    $tanggal = date('d F Y');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 7, 'Dengan ini saya yang bertanda tangan di bawah ini menyatakan bahwa perangkat yang telah diperbaiki oleh AZZAHRA COMPUTER dalam kondisi baik dan sesuai, serta tidak akan mengajukan tuntutan garansi apabila terdapat kerusakan akibat kelalaian pengguna.', 0, 'J');
    $pdf->Ln(6);
    $pdf->Cell(0, 7, 'Tegal, ' . $tanggal, 0, 1, 'R');
    $pdf->Ln(30); // ruang tanda tangan
    $pdf->Cell(0, 7, '( ' . $customer['cos_nama'] . ' )', 0, 1, 'R');


    $pdf->Output('SURAT_PERNYATAAN_' . $customer['id_costomer'] . '_' . $trans_kode . '.pdf', 'I');
}

function print_tts()
{
   $this->load->library('Pdfgenerator');

   $param = $this->uri->segment(3);
   log_message('info', 'print_tts called with param: ' . $param);

   // Always treat param as cos_kode
   $cos_kode = $param;
   log_message('info', 'cos_kode set to param: ' . $cos_kode);

   $customer_row = $this->M_service->printe($cos_kode)->row_array();
   if (!$customer_row) {
       show_error('Data customer tidak ditemukan');
       return;
   }

   $view_data = array('data' => $customer_row);
   $html = $this->load->view('Service/print-tts', $view_data, TRUE);

   $filename = $param;
   log_message('info', 'param: ' . $param . ', cos_kode: ' . $cos_kode . ', filename: ' . $filename);
   log_message('info', 'Generating PDF with filename: ' . $filename);
   $this->pdfgenerator->generate($html, $filename, 'A4', 'landscape', true);
}

}

/* End of file Cetak.php */
/* Location: ./application/controllers/Cetak.php */