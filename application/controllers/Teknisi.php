<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_service');
		if($this->session->userdata('masuk') != TRUE){
	      $url=base_url();
	      redirect($url);
	    }else{
	    	if ($this->session->userdata('level') != 'Teknisi') {
	    		$url=base_url();
	      		redirect($url);
	    	}
	    }
	}

	public function index()
	{
		$data = array(
				'title' => 'Dashboard Teknisi',
				'orders_baru' => $this->M_service->cos_baru(),
				'orders_repairing' => $this->M_service->get_orders_repairing(),
				'latest_orders' => $this->M_service->get_latest_new_orders(1)
			);
		$this->load->view('Teknisi/dashboard', $data);
	}

	public function input_tindakan($kode)
	{
		$data = array(
				'title' => 'Input Tindakan',
				'proses' => $this->M_service->proses($kode)->row_array()
			);
		$this->load->view('Teknisi/input_tindakan', $data);
	}

	public function save_tindakan()
	{
		$kode 	  = json_decode($_POST['no'][0]);
		$tindakan = json_decode($_POST['tindakan'][0]);
		$ket 	  = json_decode($_POST['ket'][0]);
		$qty	  = json_decode($_POST['qty'][0]);
		$subtot	  = json_decode($_POST['subtot'][0]);
		$kd_trans = $this->input->post('tras_kode');

		$data  = array();
		$index = 0;
		foreach ($kode as $cek) {
			array_push($data, array(
				'trans_kode' 	=> $this->input->post('tras_kode'),
				'tdkn_nama' 	=> $tindakan[$index],
				'tdkn_ket' 		=> $ket[$index],
				'tdkn_qty' 		=> $qty[$index],
				'tdkn_subtot'  	=> $subtot[$index],
				'tdkn_tanggal' 	=> date('Y-m-d'),
				'tdkn_jam'		=> date('H:i:s')
			));
			$index++;
		}
		log_message('info', 'Data to insert into tindakan: ' . json_encode($data));
		$result = $this->M_service->save_tindakan($data);
		log_message('info', 'Insert result: ' . $result);
		if (!empty($data) && $result !== false && $result > 0) {
			// Update transaction status to 'Diproses'
			$this->M_service->status(array('trans_status' => 'Diproses'), $kd_trans);

			// Check if order_list exists for this cos_kode, if not, create it
			$trans_data = $this->M_service->histori_transaksi($kd_trans)->row_array();
			$cos_kode = $trans_data['cos_kode'];

			$this->db->where('cos_kode', $cos_kode);
			$order_exists = $this->db->get('order_list')->num_rows();

			if ($order_exists == 0) {
				// Get transaction and customer data
				$trans_data = $this->M_service->histori_transaksi($kd_trans)->row_array();
				$customer_data = $this->M_service->printe($cos_kode)->row_array();

				// Generate trans_kode for order_list: TR + dd + mm + yy + increment
				$date = date('dmy'); // ddmmyy
				$this->db->like('trans_kode', 'TR' . $date, 'after');
				$this->db->order_by('trans_kode', 'DESC');
				$this->db->limit(1);
				$query = $this->db->get('order_list');
				if ($query->num_rows() > 0) {
					$last_code = $query->row()->trans_kode;
					$last_num = (int)substr($last_code, -3);
					$new_num = str_pad($last_num + 1, 3, '0', STR_PAD_LEFT);
				} else {
					$new_num = '001';
				}
				$order_trans_kode = 'TR' . $date . $new_num;

				$order_data = array(
					'trans_kode' => $order_trans_kode,
					'cos_kode' => $cos_kode,
					'kry_kode' => $trans_data['kry_kode'],
					'trans_total' => $trans_data['trans_total'],
					'trans_discount' => $trans_data['trans_discount'],
					'trans_tanggal' => $trans_data['trans_tanggal'],
					'trans_status' => 'repairing',
					'merek' => $customer_data['cos_model'],
					'device' => $customer_data['cos_tipe'],
					'status_garansi' => $customer_data['cos_status'],
					'seri' => $customer_data['cos_no_seri'],
					'ket_keluhan' => $customer_data['cos_keluhan'],
					'email' => 'example@gmail.com', // Default email
					'alamat' => $customer_data['cos_alamat'],
					'created_at' => date('Y-m-d H:i:s')
				);
				$this->M_service->save_order_list($order_data);
			} else {
				// Update existing order_list status to 'repairing'
				$this->db->where('cos_kode', $cos_kode);
				$this->db->update('order_list', array('trans_status' => 'repairing'));
			}

			$this->session->set_flashdata('sukses', 'Tindakan berhasil disimpan');
		} else {
			log_message('error', 'Failed to insert tindakan data');
			$this->session->set_flashdata('gagal', 'Gagal menyimpan data tindakan');
		}
		redirect('Teknisi','refresh');
	}

	public function get_stats()
	{
		// Get completed orders today by current technician
		$this->db->select('COUNT(*) as completed_today');
		$this->db->from('transaksi');
		$this->db->where('trans_status', 'Diproses');
		$this->db->where('DATE(updated_at)', date('Y-m-d'));
		$this->db->where('kry_kode', $this->session->userdata('kode'));
		$query = $this->db->get();
		$result = $query->row();

		echo json_encode([
			'completed_today' => $result->completed_today ?? 0
		]);
	}
}