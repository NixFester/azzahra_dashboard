<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_kasir');
		$this->load->model('M_service');
		$this->load->model('M_order');
		if($this->session->userdata('masuk') != TRUE){
		     $url=base_url();
		     redirect($url);
		   }else{
		   	if ($this->session->userdata('level') != 'Kasir' && $this->session->userdata('level') != 'Customer Service') {
		   		$url=base_url();
		     		redirect($url);
		   	}
		   }
	}

	public function index()
	{
		$data = array(
			'title' 	=> 'Customer',
			'custom'	=> $this->M_kasir->GetCustom(),
			'no'		=> $this->uri->segment(3)
			 );
		$this->load->view('Kasir/customer',$data);
	}
	//Pembayaran
	function pembayaran()
	{
		$segment = $this->uri->segment(3);
		if ($segment == 'dp') {
			$this->db->select('*');
			$this->db->from('costomer');
			$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
			$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
			$this->db->where('transaksi.trans_status', 'Pelunasan');
			$custom = $this->db->get();
		} elseif ($segment == 'lunas') {
			$this->db->select('*');
			$this->db->from('costomer');
			$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
			$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
			$this->db->where('transaksi.trans_status', 'Lunas');
			$custom = $this->db->get();
		} elseif ($segment == 'return') {
			$this->db->select('*');
			$this->db->from('costomer');
			$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
			$this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
			$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
			$this->db->where('transaksi_detail.dtl_jenis_bayar', 'RETURN');
			$custom = $this->db->get();
		} else {
			$custom = $this->M_kasir->GetCustom();
		}
		$data = array(
			'title' 	=> 'Pembayaran',
			'custom'	=> $custom,
			'no'		=> $segment,
			'lap_bayar' => $this->M_kasir->lap_bayar(),
			'role'		=> 'kasir',
			'filter'	=> $segment
			 );
		$this->load->view('Kasir/pembayaran',$data);
	}
	function cari()
	{
		$kode = $this->uri->segment(3);

		$data = array(
			'title' 	=> 'Pembayaran',
			'custom'	=> $this->M_kasir->Histori($kode),
			'no'		=> $this->uri->segment(3),
			'trans'		=> $this->M_kasir->trans($kode)->row_array(),
			'bayar'		=> $this->M_kasir->Histori($kode)->row_array(),
			'tindakan'	=> $this->M_kasir->tindakan($kode),
			'lap_bayar' => $this->M_kasir->lap_bayar(),
			'vocher' 	=> $this->M_kasir->GetVocherBy($kode)->row_array(),
			'role'		=> 'kasir'
			 );
		$this->load->view('Kasir/cari',$data);

	}
	function vocer()
	{
		$kode = $this->input->post('kode');

		$data = array(
				'trans_kode' => $kode, 
				'voc_jumlah' => str_replace('.', '', $this->input->post('vocer')),
				'voc_tanggal'=> date('Y-m-d'),
				'voc_jam'	 => date('H:i:s'),
				'voc_status' => 'ON'
			);
		$this->M_kasir->vocer($data);

		$this->session->set_flashdata('sukses', 'DI AJUKAN');
		redirect('Kasir/cari/'.$kode,'refresh');
	}
	function pelunasan()
	{
		$kode = $this->input->post('kode');
		$jenis_bayar = $this->input->post('jenis_bayar');
		$bank = ($jenis_bayar == 'TUNAI') ? '-' : $this->input->post('bank');

		$custom = $this->db->get_where('transaksi',array('trans_kode' => $kode))->row_array();

		if ($custom['trans_status'] == 'Lunas') {
			$this->session->set_flashdata('gagal', 'Customer ini sudah melakukan pelunasan');
			redirect('Kasir/cari/'.$kode,'refresh');
		} else {
			$detail = array(
					'trans_kode'		=> $kode,
					'kry_kode' 			=> $this->session->userdata('kode'),
					'dtl_jml_bayar' 	=> $this->input->post('lunas'),
					'dtl_jenis_bayar' 	=> $jenis_bayar,
					'dtl_bank' 			=> $bank,
					'dtl_status' 		=> 'PELUNASAN',
					'dtl_tanggal' 		=> date('Y-m-d'),
					'dtl_jam' 			=> date('H:i:s'),
					'dtl_stt_stor'		=> 'Disetorkan'
				);
			$this->M_kasir->pelunasan($detail);

			$trans = array('trans_status' => 'Lunas', );
			$this->M_kasir->update_trans($trans,$kode);

			// Update order_list status to 'repairing' if exists and not already completed
			$this->db->select('cos_kode');
			$this->db->from('transaksi');
			$this->db->where('trans_kode', $kode);
			$query = $this->db->get();
			$result = $query->row();
			if ($result) {
				$cos_kode = $result->cos_kode;
				// Check if order_list exists for this cos_kode
				$this->db->where('cos_kode', $cos_kode);
				$order_exists = $this->db->get('order_list')->num_rows();
				if ($order_exists > 0) {
					// Get current status
					$this->db->select('trans_status');
					$this->db->where('cos_kode', $cos_kode);
					$current_status = $this->db->get('order_list')->row()->trans_status;
					// Only update if not already 'service_completed'
					if ($current_status != 'service_completed') {
						$this->db->where('cos_kode', $cos_kode);
						$this->db->update('order_list', array('trans_status' => 'repairing'));
					}
				} else {
					// BAYAR LUNAS LANGSUNG: Buat order_list baru dengan status repairing jika belum ada
					// Ambil data dari transaksi dan costomer
					$this->db->select('transaksi.*, costomer.cos_nama, costomer.cos_alamat, costomer.cos_hp');
					$this->db->from('transaksi');
					$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
					$this->db->where('transaksi.trans_kode', $kode);
					$trans_data = $this->db->get()->row();

					if ($trans_data) {
						$order_data = array(
							'trans_kode' => $kode,
							'cos_kode' => $trans_data->cos_kode,
							'kry_kode' => null,
							'trans_total' => $trans_data->trans_total,
							'trans_discount' => $trans_data->trans_discount ?? 0,
							'trans_tanggal' => $trans_data->trans_tanggal,
							'trans_status' => 'repairing',
							'merek' => '', // Kosong dulu, bisa diupdate nanti
							'device' => '',
							'status_garansi' => '',
							'seri' => '',
							'ket_keluhan' => '',
							'email' => '',
							'alamat' => $trans_data->cos_alamat ?? '',
							'created_at' => date('Y-m-d H:i:s')
						);
						$this->db->insert('order_list', $order_data);
						log_message('info', 'Created order_list for direct Lunas payment: ' . $kode);

						// Insert into order_part_marking
						$marking_data = array(
							'trans_kode' => $kode,
							'is_ordered' => 'no'
						);
						$marking_result = $this->M_order->insert_order_part_marking($marking_data);
						log_message('info', 'Order_part_marking insert result: ' . ($marking_result ? 'success' : 'failed'));

					}
				}
			}
			$this->session->set_flashdata('sukses', 'DI LUNASI');
			redirect('Kasir/cari/'.$kode,'refresh');
		}	

	}
	function save_dp()
	{
	 	$kode = $this->input->post('kode');
	 	$jenis_bayar = $this->input->post('jenis_bayar');
	 	$bank = ($jenis_bayar == 'TUNAI') ? '-' : $this->input->post('bank');

		$detail = array(
				'trans_kode'		=> $kode,
				'kry_kode' 			=> $this->session->userdata('kode'),
				'dtl_jml_bayar' 	=> str_replace('.', '', $this->input->post('dp')),
				'dtl_jenis_bayar' 	=> $jenis_bayar,
				'dtl_bank' 			=> $bank,
				'dtl_status' 		=> 'DP',
				'dtl_tanggal' 		=> date('Y-m-d'),
				'dtl_jam' 			=> date('H:i:s'),
				'dtl_stt_stor'		=> 'Disetorkan'
			);
		$this->M_kasir->save_dp($detail);

		$trans = array('trans_status' => 'Pelunasan', );
		$this->M_kasir->update_trans($trans,$kode);

		// Update order_list status to 'repairing' when DP is paid (only if order_list exists)
		$this->db->select('cos_kode');
		$this->db->from('transaksi');
		$this->db->where('trans_kode', $kode);
		$query = $this->db->get();
		$result = $query->row();
		if ($result) {
			$cos_kode = $result->cos_kode;
			// Check if order_list exists for this cos_kode
			$this->db->where('cos_kode', $cos_kode);
			$order_exists = $this->db->get('order_list')->num_rows();
			if ($order_exists > 0) {
				$this->db->where('cos_kode', $cos_kode);
				$this->db->update('order_list', array('trans_status' => 'repairing'));
			}
		}

		$this->session->set_flashdata('sukses', 'DI SIMPAN');
		redirect('Kasir/cari/'.$kode,'refresh');
	}

	//return transaksi
	function trans_return()
	{
		$kode = $this->uri->segment(3);

		$data = array(
			'title' 	=> 'Return Pembayaran',
			'custom'	=> $this->M_kasir->Histori($kode),
			'no'		=> $this->uri->segment(3),
			'trans'		=> $this->M_kasir->trans($kode)->row_array(),
			'bayar'		=> $this->M_kasir->Histori($kode)->row_array(),
			'tindakan'	=> $this->M_kasir->tindakan($kode),
			'lap_bayar' => $this->M_kasir->lap_bayar(),
			'vocher' 	=> $this->M_kasir->GetVocherBy($kode)->row_array()
			 );
		$this->load->view('Kasir/trans-return',$data);
	}
	function bayar_return()
	{
		$kode = $this->input->post('kode');

		$custom = $this->db->get_where('transaksi',array('trans_kode' => $kode))->row_array();

		$data = $this->db->get_where('transaksi_detail', array(
															'trans_kode' => $kode,
															'dtl_status' => 'DP',
															)
									)->row_array();
		$kd_dtl 	= $data['dtl_kode'];
		$jml_return = $data['dtl_jml_bayar'];

		if ($custom['trans_status'] == 'Lunas') {
			$this->session->set_flashdata('gagal', 'Customer ini sudah melakukan pelunasan');
			redirect('Kasir/trans_return/'.$kode,'refresh');
		} else {
			$returnn = array('dtl_jenis_bayar' => 'RETURN', );
			$this->M_kasir->returnn($kd_dtl,$returnn);

			$detail = array(
					'trans_kode'		=> $kode,
					'kry_kode' 			=> $this->session->userdata('kode'), 
					'dtl_jml_bayar' 	=> '50000',
					'dtl_jenis_bayar' 	=> 'TUNAI',
					'dtl_bank' 			=> '-',
					'dtl_status' 		=> 'PELUNASAN',
					'dtl_tanggal' 		=> date('Y-m-d'),
					'dtl_jam' 			=> date('H:i:s'),
					'dtl_stt_stor'		=> 'Disetorkan'
				);
			$this->M_kasir->pelunasan($detail);

			$save_ret = array(
				'trans_kode'  => $kode,
				'dtl_kode' 	  => $kd_dtl,
				'ret_jml' 	  => $jml_return,
				'ret_tanggal' => date('Y-m-d'),
				'ret_jam' 	  => date('H:i:s'),
			);
			$this->M_kasir->save_ret($save_ret);

			$trans = array('trans_status' => 'Lunas', );
			$this->M_kasir->update_trans($trans,$kode);

			// Update order_list status to 'Refund' when DP is returned
			$this->db->select('cos_kode');
			$this->db->from('transaksi');
			$this->db->where('trans_kode', $kode);
			$query = $this->db->get();
			$result = $query->row();
			if ($result) {
				$cos_kode = $result->cos_kode;
				$this->db->where('cos_kode', $cos_kode);
				$this->db->update('order_list', array('trans_status' => 'Refund'));
			}

			$this->session->set_flashdata('sukses', 'DI LUNASI DAN KEMBALIKAN DP YANG SUDAH DI BAYARKAN');
			redirect('Kasir/trans_return/'.$kode,'refresh');
		}

	}
	//print
	function cetak()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'data' 		 => $this->M_kasir->cetak($kode)->row_array(),
				'bayar' 	 => $this->M_kasir->bayar($kode)->row_array(),
				'pembayaran' => $this->M_kasir->cetak_pembayaran($kode),
				'barang'	 => $this->M_kasir->barang($kode)
			);
		$this->load->view('Kasir/print-pembayaran',$data);
	}
	function cetak_return()
	{
		$kode = $this->uri->segment(3);

		$data = array(
				'data' 		 => $this->M_kasir->cetak($kode)->row_array(),
				'bayar' 	 => $this->M_kasir->bayar($kode)->row_array(),
				'pembayaran' => $this->M_kasir->cetak_pembayaran($kode),
				'barang'	 => $this->M_kasir->barang($kode)
			);
		$this->load->view('Kasir/print-return',$data);
	}

	//laporan
	function laporan()
	{
		$kode = $this->session->userdata('kode');
		// Get today's payments with customer names
		$this->db->select('transaksi_detail.*, costomer.cos_nama, costomer.cos_alamat, transaksi.cos_kode');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
		$this->db->where('DATE(transaksi_detail.dtl_tanggal)', date('Y-m-d'));
		$this->db->order_by('transaksi_detail.dtl_tanggal', 'DESC');
		$today_payments = $this->db->get()->result_array();
		$dp_payments = array_filter($today_payments, function($p) { return $p['dtl_status'] == 'DP'; });
		$lunas_payments = array_filter($today_payments, function($p) { return $p['dtl_status'] == 'PELUNASAN'; });
		// No exclude for dp_payments, show all DP
		$menunggu_payments = array_filter($today_payments, function($p) { return $p['dtl_stt_stor'] == 'Menunggu'; });

		// Calculate menunggu
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as menunggu_total, COUNT(*) as menunggu_count');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		$this->db->where('DATE(dtl_tanggal)', date('Y-m-d'));
		$menunggu = $this->db->get()->row();

		// Calculate tunai total and count for PELUNASAN
		$this->db->select('SUM(dtl_jml_bayar) as total_tunai, COUNT(*) as count_tunai');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('DATE(dtl_tanggal)', date('Y-m-d'));
		$this->db->where('dtl_stt_stor', 'Disetorkan');
		$tunai_result = $this->db->get()->row();
		$total_tunai = $tunai_result->total_tunai ?? 0;
		$count_tunai = $tunai_result->count_tunai ?? 0;

		// Calculate DP totals (all DP, no exclude)
		$dp_bca_total = $this->M_service->ds_dp_bca_excl_lunas() ?: 0;
		$dp_bri_total = $this->M_service->ds_dp_bri_excl_lunas() ?: 0;
		$dp_mandiri_total = $this->M_service->ds_dp_mandiri_excl_lunas() ?: 0;
		$dp_tunai_total = $this->M_service->ds_dp_tunai_excl_lunas() ?: 0;

		// Total keseluruhan: all DP + PELUNASAN
		$dp_total = $dp_bca_total + $dp_bri_total + $dp_mandiri_total + $dp_tunai_total + $this->M_service->ds_pelunasan_NonTunai() + $total_tunai;
		$jml_dp_total = $this->M_service->jml_pelunasan()->num_rows() + $count_tunai; // PELUNASAN count

		$jml_mandiri_result = $this->M_service->jml_pelunasan_mandiri();

		$data = array(
				'title'   => 'Laporan',
				'cs'	  => $this->M_service->cs_laporan($kode)->row_array(),
				'dp'	  => $dp_total,
				'jml_dp'  => $jml_dp_total,
				'tot_dp_bca' => $dp_bca_total,
				'jml_dp_bca' => $this->M_service->jml_dp_bca_excl_lunas() ?: 0,
				'tot_dp_bri' => $dp_bri_total,
				'jml_dp_bri' => $this->M_service->jml_dp_bri_excl_lunas() ?: 0,
				'tot_dp_mandiri' => $dp_mandiri_total,
				'jml_dp_mandiri' => $this->M_service->jml_dp_mandiri_excl_lunas() ?: 0,
				'tot_dp_tunai' => $dp_tunai_total,
				'jml_dp_tunai' => $this->M_service->jml_dp_tunai_excl_lunas() ?: 0,
				'tot_bca' => $this->M_service->ds_pelunasan_bca(),
				'jml_bca' => $this->M_service->jml_pelunasan_bca(),
				'tot_bri' => $this->M_service->ds_pelunasan_bri(),
				'jml_bri' => $this->M_service->jml_pelunasan_bri(),
				'tot_mandiri' => $this->M_service->ds_pelunasan_mandiri(),
				'jml_mandiri' => $jml_mandiri_result,
				'tot_mandiri' => $this->M_service->ds_pelunasan_mandiri(),
				'jml_mandiri' => $this->M_service->jml_pelunasan_mandiri(),
				'total_tunai' => $total_tunai,
				'count_tunai' => $count_tunai,
				'menunggu_total' => $menunggu->menunggu_total,
				'menunggu_count' => $menunggu->menunggu_count,
				'dp_payments' => $dp_payments,
				'lunas_payments' => $lunas_payments,
				'menunggu_payments' => $menunggu_payments
			);
		$this->load->view('Kasir/laporan',$data);
	}

	function export_pdf()
	{
		log_message('info', 'Starting Kasir export_pdf');
		$this->load->library('pdfgenerator');
		$data = $this->laporan_data();
		log_message('info', 'Data loaded for PDF: ' . json_encode($data));
		$html = $this->load->view('Kasir/laporan_pdf', $data, true);
		log_message('info', 'HTML generated, length: ' . strlen($html));
		$this->pdfgenerator->generate($html, 'Laporan_Harian_Kasir_'.date('Y-m-d'));
		log_message('info', 'PDF generation completed');
	}

	private function laporan_data()
	{
		$kode = $this->session->userdata('kode');
		log_message('debug', 'Starting laporan_data for kode: ' . $kode);
		// Calculate tunai total and count for PELUNASAN
		$this->db->select('SUM(dtl_jml_bayar) as total_tunai, COUNT(*) as count_tunai');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('DATE(dtl_tanggal)', date('Y-m-d'));
		$this->db->where('dtl_stt_stor', 'Disetorkan');
		$tunai_result = $this->db->get()->row();
		$total_tunai = $tunai_result->total_tunai ?? 0;
		$count_tunai = $tunai_result->count_tunai ?? 0;
		log_message('debug', 'Tunai result: total=' . $total_tunai . ', count=' . $count_tunai);
		$jml_mandiri_result = $this->M_service->jml_pelunasan_mandiri();
		log_message('debug', 'jml_mandiri result: ' . (is_object($jml_mandiri_result) ? 'object with ' . $jml_mandiri_result->num_rows() . ' rows' : 'not object: ' . var_export($jml_mandiri_result, true)));

		// Calculate DP totals (all DP, no exclude)
		$dp_bca_total = $this->M_service->ds_dp_bca_excl_lunas() ?: 0;
		$dp_bri_total = $this->M_service->ds_dp_bri_excl_lunas() ?: 0;
		$dp_mandiri_total = $this->M_service->ds_dp_mandiri_excl_lunas() ?: 0;
		$dp_tunai_total = $this->M_service->ds_dp_tunai_excl_lunas() ?: 0;

		// Total keseluruhan: all DP + PELUNASAN
		$dp_total = $dp_bca_total + $dp_bri_total + $dp_mandiri_total + $dp_tunai_total + $this->M_service->ds_pelunasan_NonTunai() + $total_tunai;
		$jml_dp_total = $this->M_service->jml_pelunasan()->num_rows() + $count_tunai;

		// Get today's payments with customer names
		$this->db->select('transaksi_detail.*, costomer.cos_nama, costomer.cos_alamat, transaksi.cos_kode');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->join('costomer', 'transaksi.cos_kode = costomer.id_costomer');
		$this->db->where('DATE(transaksi_detail.dtl_tanggal)', date('Y-m-d'));
		$this->db->order_by('transaksi_detail.dtl_tanggal', 'DESC');
		$today_payments = $this->db->get()->result_array();
		$dp_payments = array_filter($today_payments, function($p) { return $p['dtl_status'] == 'DP'; });
		$lunas_payments = array_filter($today_payments, function($p) { return $p['dtl_status'] == 'PELUNASAN'; });
		$menunggu_payments = array_filter($today_payments, function($p) { return $p['dtl_stt_stor'] == 'Menunggu'; });

		// Calculate menunggu
		$this->db->select('COALESCE(SUM(dtl_jml_bayar), 0) as menunggu_total, COUNT(*) as menunggu_count');
		$this->db->from('transaksi_detail');
		$this->db->where('dtl_stt_stor', 'Menunggu');
		$this->db->where('DATE(dtl_tanggal)', date('Y-m-d'));
		$menunggu = $this->db->get()->row();

		return array(
			'title'   	 => 'Laporan',
			'kasir'	  	 => $this->M_kasir->ks_laporan($kode)->row_array(),
			'dp'	  	 => $dp_total,
			'jml_dp'  	 => $jml_dp_total,
			'sum_dp'  	 => $this->M_kasir->Sum_DP_Tunai(),
			'lunas'	  	 => $this->M_kasir->Lunas_Tunai()->num_rows(),
			'sum_lunas'  => $this->M_kasir->Sum_Lunas_Tunai(),
			'return'	 => $this->M_kasir->jml_return()->num_rows(),
			'sum_return' => $this->M_kasir->sum_return(),
			'total_tunai' => $total_tunai,
			'count_tunai' => $count_tunai,
			'tot_dp_bca' => $dp_bca_total,
			'jml_dp_bca' => $this->M_service->jml_dp_bca_excl_lunas() ?: 0,
			'tot_dp_bri' => $dp_bri_total,
			'jml_dp_bri' => $this->M_service->jml_dp_bri_excl_lunas() ?: 0,
			'tot_dp_mandiri' => $dp_mandiri_total,
			'jml_dp_mandiri' => $this->M_service->jml_dp_mandiri_excl_lunas() ?: 0,
			'tot_dp_tunai' => $dp_tunai_total,
			'jml_dp_tunai' => $this->M_service->jml_dp_tunai_excl_lunas() ?: 0,
			'tot_bca' => $this->M_service->ds_pelunasan_bca(),
			'jml_bca' => $this->M_service->jml_pelunasan_bca(),
			'tot_bri' => $this->M_service->ds_pelunasan_bri(),
			'jml_bri' => $this->M_service->jml_pelunasan_bri(),
			'tot_mandiri' => $this->M_service->ds_pelunasan_mandiri(),
			'jml_mandiri' => $this->M_service->jml_pelunasan_mandiri(),
			'dp_total' => $dp_total,
			'menunggu_total' => $menunggu->menunggu_total,
			'menunggu_count' => $menunggu->menunggu_count,
			'dp_payments' => $dp_payments,
			'lunas_payments' => $lunas_payments,
			'menunggu_payments' => $menunggu_payments
		);
	}


}

/* End of file Kasir.php */
/* Location: ./application/controllers/Kasir.php */