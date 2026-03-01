<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_service extends CI_Model {

	//dashboard
	function ds_cos_baru()
	{
		$this->db->where('trans_status', 'Baru');
		return $this->db->get('transaksi');
	}
	
	function ds_cos_proses()
	{
		$this->db->where('trans_status', 'Diproses');
		return $this->db->get('transaksi');	
	}
	function ds_cos_knf()
	{
		$this->db->where('trans_status', 'Konfirmasi');
		return $this->db->get('transaksi');
	}
	function ds_cos_pelunasan()
	{
		$this->db->where('trans_status', 'Pelunasan');
		return $this->db->get('transaksi');
	}
	function ds_dp_NonTunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_dp_Tunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_bca()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_bri()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_pelunasan_NonTunai()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_stt_stor', 'Disetorkan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_pelunasan_bca()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_stt_stor', 'Disetorkan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function ds_pelunasan_bri()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}

	//transaksi
	function save_custom($customer)
	{
		return $this->db->insert('costomer', $customer);
	}
	function save_trans($trans)
	{
		return $this->db->insert('transaksi', $trans);
	}
	function save_order_list($order_list)
	{
		return $this->db->insert('order_list', $order_list);
	}
	function update_custom($customer,$kode)
	{
		$this->db->where('id_costomer', $kode);
		return $this->db->update('costomer', $customer);
	}
	function update_trans($trans,$kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}
	function save_tindakan($data)
	{
		return $this->db->insert_batch('tindakan', $data);
	}
	function status($status,$kd_trans)
	{
		$this->db->where('trans_kode', $kd_trans);
		return $this->db->update('transaksi', $status);
	}
	//liat
	function cos_baru()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->join('tindakan','transaksi.trans_kode=tindakan.trans_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Baru');
	    $this->db->where('tindakan.trans_kode IS NULL'); // Only show transactions that don't have any actions yet
	    $this->db->group_by('costomer.id_costomer');
	    $query = $this->db->get();
	    return $query;
	}
	
	function cos_proses()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where_in('transaksi.trans_status', ['Diproses']);
	    $query = $this->db->get();
	    return $query;
	}
	function cos_konf()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Konfirmasi');
	    $query = $this->db->get();
	    return $query;
	}
	function cos_pelunasan()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->join('order_list','transaksi.cos_kode=order_list.cos_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    $query = $this->db->get();
	    return $query;
	}

	function cos_lunas()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status', 'Lunas');
	    $query = $this->db->get();
	    return $query;
	}
	function all()
	{
		return $this->db->get('costomer');
	}
	//action
	function proses($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.cos_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function konfirmasi($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}

	//batal teransaksi
	function up_btl_detail($kode,$up_btl_tdkn)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('tindakan', $up_btl_tdkn);
	}
	function save_btl_detail($save_tdkn)
	{
		return $this->db->insert('tindakan', $save_tdkn);
	}
	function up_btl_trans($kode,$trans)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}

	//return pembayaran
	function up_ret_detail($kode,$up_ret_tdkn)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('tindakan', $up_ret_tdkn);
	}
	function save_ret_detail($save_tdkn)
	{
		return $this->db->insert('tindakan', $save_tdkn);
	}
	function up_ret_trans($kode,$trans)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->update('transaksi', $trans);
	}
	

	function up_knf_trans($kode_trans,$up_trans)
	{
		$this->db->where('trans_kode', $kode_trans);
		return $this->db->update('transaksi', $up_trans);
	}
	function up_knf_tdkn($kode_tdkn,$up_tdkn)
	{
		$this->db->where('tdkn_kode', $kode_tdkn);
		return $this->db->update('tindakan', $up_tdkn);
	}
	function GetTindakanBy($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('tindakan');
	}
	function vocer($data)
	{
		return $this->db->insert('vocer', $data);
	}
	function GetVocherBy($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('vocer');
	}
	function save_dp($detail)
	{
		return $this->db->insert('transaksi_detail', $detail);
	}
	function pelunasan($kode)
	{
		$this->db->select('costomer.*, transaksi.*, karyawan.*, COALESCE(transaksi_detail.dtl_jml_bayar, 0) as dtl_jml_bayar');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode AND transaksi_detail.dtl_status="DP"', 'left');
	    $this->db->where('costomer.id_costomer', $kode);
	    $this->db->where('transaksi.trans_status', 'Pelunasan');
	    $query = $this->db->get();
	    return $query;
	}
	function histori($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi_detail');
	}
	function histori_transaksi($kode)
	{
		$this->db->where('trans_kode', $kode);
		return $this->db->get('transaksi');
	}
	function GetCustom()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_status !=', 'Lunas');
	    $query = $this->db->get();
	    return $query;
	}
	function trans($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi.trans_kode', $kode);
	    $query = $this->db->get();
	    return $query;
	}
	function tindakan($kode)
	{
	 	$this->db->where('trans_kode', $kode);
	 	return $this->db->get('tindakan');
	}
	function lap_bayar()
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->join('transaksi_detail','transaksi.trans_kode=transaksi_detail.trans_kode');
	    $this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode', 'left');
	    $this->db->where('transaksi_detail.dtl_tanggal', date('Y-m-d'));
	    $query = $this->db->get();
	    return $query;
	}

	//print

	function printe($kode)
	{
		$this->db->select('*');
	    $this->db->from('costomer');
	    $this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
	    $this->db->where('costomer.id_costomer', $kode);
	    $query = $this->db->get();
	    return $query;
	}

	//laporan

	function cs_laporan($kode)
	{
		$this->db->where('kry_kode', $kode);
		return $this->db->get('karyawan');
	}
	function jml_dp()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}
	function tot_bca()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function jml_bca()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}
	function tot_bri()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}
	function jml_bri()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('trans_status', 'Pelunasan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}
	function jml_pelunasan()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}
	function jml_pelunasan_bca()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}
	function jml_pelunasan_bri()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}

	function ds_pelunasan_mandiri()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_stt_stor', 'Disetorkan');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get()->row()->total;
	}

	function jml_pelunasan_mandiri()
	{
		$this->db->select('transaksi_detail.*');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_status', 'PELUNASAN');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('trans_status', 'Lunas');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		return $this->db->get();
	}

	// New functions for DP excluding lunas
	function ds_dp_bca_excl_lunas()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->total;
	}

	function jml_dp_bca_excl_lunas()
	{
		$this->db->select('COUNT(*) as count');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->count;
	}

	function ds_dp_bri_excl_lunas()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->total;
	}

	function jml_dp_bri_excl_lunas()
	{
		$this->db->select('COUNT(*) as count');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->count;
	}

	function ds_dp_mandiri_excl_lunas()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->total;
	}

	function jml_dp_mandiri_excl_lunas()
	{
		$this->db->select('COUNT(*) as count');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'MANDIRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->count;
	}

	function ds_dp_tunai_excl_lunas()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->total;
	}

	function jml_dp_tunai_excl_lunas()
	{
		$this->db->select('COUNT(*) as count');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		// Removed exclude condition
		return $this->db->get()->row()->count;
	}

	// Functions for total calculation (exclude DP if PELUNASAN exists for same cos_kode)
	function ds_dp_bca_for_total()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BCA');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		$this->db->where('transaksi.cos_kode NOT IN (SELECT t2.cos_kode FROM transaksi_detail td2 JOIN transaksi t2 ON td2.trans_kode = t2.trans_kode WHERE td2.dtl_status = "PELUNASAN" AND td2.dtl_tanggal = "' . date('Y-m-d') . '")', NULL, FALSE);
		return $this->db->get()->row()->total;
	}

	function ds_dp_bri_for_total()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TRANFER');
		$this->db->where('dtl_bank', 'BRI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		$this->db->where('transaksi.cos_kode NOT IN (SELECT t2.cos_kode FROM transaksi_detail td2 JOIN transaksi t2 ON td2.trans_kode = t2.trans_kode WHERE td2.dtl_status = "PELUNASAN" AND td2.dtl_tanggal = "' . date('Y-m-d') . '")', NULL, FALSE);
		return $this->db->get()->row()->total;
	}

	function ds_dp_tunai_for_total()
	{
		$this->db->select('SUM(dtl_jml_bayar) as total');
		$this->db->from('transaksi_detail');
		$this->db->join('transaksi', 'transaksi_detail.trans_kode = transaksi.trans_kode');
		$this->db->where('dtl_jenis_bayar', 'TUNAI');
		$this->db->where('dtl_status', 'DP');
		$this->db->where('dtl_tanggal', date('Y-m-d'));
		$this->db->where('transaksi.cos_kode NOT IN (SELECT t2.cos_kode FROM transaksi_detail td2 JOIN transaksi t2 ON td2.trans_kode = t2.trans_kode WHERE td2.dtl_status = "PELUNASAN" AND td2.dtl_tanggal = "' . date('Y-m-d') . '")', NULL, FALSE);
		return $this->db->get()->row()->total;
	}

	function insert_tindakan($data)
	{
		return $this->db->insert('tindakan', $data);
	}

	function insert_cabang($data)
	{
		return $this->db->insert('cabang', $data);
	}

	function update_total_transaksi($trans_kode)
	{
		$this->db->select_sum('tdkn_subtot');
		$this->db->where('trans_kode', $trans_kode);
		$query = $this->db->get('tindakan');
		$total = $query->row()->tdkn_subtot;

		$this->db->where('trans_kode', $trans_kode);
		return $this->db->update('transaksi', array('trans_total' => $total));
	}

	function get_customers_by_status($status)
	{
		$this->db->select('*');
		$this->db->from('costomer');
		$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
		$this->db->join('karyawan','transaksi.kry_kode=karyawan.kry_kode');
		$this->db->where('transaksi.trans_status', $status);
		$query = $this->db->get();
		return $query;
	}

	function get_orders_repairing()
	{
		$this->db->select('costomer.*, transaksi.*, order_list.trans_status as order_status, order_list.created_at as order_created');
		$this->db->from('costomer');
		$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
		$this->db->join('order_list','costomer.id_costomer=order_list.cos_kode');
		$this->db->where('order_list.trans_status', 'repairing');
		$this->db->order_by('order_list.created_at', 'DESC');
		$query = $this->db->get();
		return $query;
	}

	function get_latest_new_orders($limit = 5)
	{
		$this->db->select('costomer.cos_nama, transaksi.trans_kode, transaksi.trans_tanggal, costomer.cos_alamat');
		$this->db->from('costomer');
		$this->db->join('transaksi','costomer.id_costomer=transaksi.cos_kode');
		$this->db->where('transaksi.trans_status', 'Baru');
		$this->db->order_by('transaksi.trans_kode', 'DESC'); // Largest trans_kode first (newest)
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query;
	}
	
	// Count all records
    public function cos_konf_count_all()
    {
        $this->db->from('costomer');
        $this->db->join('transaksi', 'costomer.id_costomer=transaksi.cos_kode');
        $this->db->where('transaksi.trans_status', 'Konfirmasi');
        return $this->db->count_all_results();
    }
    
    // Count filtered records
    public function cos_konf_count_filtered($search)
    {
        $this->db->from('costomer');
        $this->db->join('transaksi', 'costomer.id_costomer=transaksi.cos_kode');
        $this->db->where('transaksi.trans_status', 'Konfirmasi');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('transaksi.cos_kode', $search);
            $this->db->or_like('costomer.cos_nama', $search);
            $this->db->or_like('costomer.cos_alamat', $search);
            $this->db->or_like('costomer.cos_hp', $search);
            $this->db->or_like('costomer.cos_tipe', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    
    // Get actual data with pagination
    public function cos_konf_ajax($start, $length, $search, $order_column, $order_dir)
    {
        $this->db->select('costomer.*, transaksi.*, COALESCE(transaksi.follow_up_count, 0) as follow_up_count');
        $this->db->from('costomer');
        $this->db->join('transaksi', 'costomer.id_costomer=transaksi.cos_kode');
        $this->db->join('karyawan', 'transaksi.kry_kode=karyawan.kry_kode', 'left');
        $this->db->where('transaksi.trans_status', 'Konfirmasi');
        
        // Only apply search if not empty
        if (!empty($search) && $search !== '') {
            $this->db->group_start();
            $this->db->like('transaksi.cos_kode', $search);
            $this->db->or_like('costomer.cos_nama', $search);
            $this->db->or_like('costomer.cos_alamat', $search);
            $this->db->or_like('costomer.cos_hp', $search);
            $this->db->or_like('costomer.cos_tipe', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by($order_column, $order_dir);
        
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        
        // execute the query and return the result set
        $query = $this->db->get();
        return $query->result();
    }

    // payment listing helpers for server-side DataTable
    public function pembayaran_count_all($filter)
    {
        $this->db->from('costomer');
        $this->db->join('transaksi', 'costomer.id_costomer=transaksi.cos_kode');

        if ($filter === 'dp') {
            $this->db->where('transaksi.trans_status', 'Pelunasan');
        } elseif ($filter === 'lunas') {
            $this->db->where('transaksi.trans_status', 'Lunas');
        } else {
            // all except final lunas
            $this->db->where('transaksi.trans_status !=', 'Lunas');
        }

        return $this->db->count_all_results();
    }

    public function pembayaran_count_filtered($filter, $search)
    {
        $this->db->from('costomer');
        $this->db->join('transaksi', 'costomer.id_costomer=transaksi.cos_kode');

        if ($filter === 'dp') {
            $this->db->where('transaksi.trans_status', 'Pelunasan');
        } elseif ($filter === 'lunas') {
            $this->db->where('transaksi.trans_status', 'Lunas');
        } else {
            $this->db->where('transaksi.trans_status !=', 'Lunas');
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('transaksi.cos_kode', $search);
            $this->db->or_like('costomer.cos_nama', $search);
            $this->db->or_like('costomer.cos_model', $search);
            $this->db->or_like('costomer.cos_status', $search);
            $this->db->or_like('costomer.cos_tanggal', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

	public function pembayaran_ajax($start, $length, $search, $order_column, $order_dir, $filter)
	{
		$this->db->select('costomer.*, transaksi.*');
		$this->db->from('costomer');
		$this->db->join('transaksi', 'costomer.id_costomer=transaksi.cos_kode');

		if ($filter === 'dp') {
			$this->db->where('transaksi.trans_status', 'Pelunasan');
		} elseif ($filter === 'lunas') {
			$this->db->where('transaksi.trans_status', 'Lunas');
		} else {
			$this->db->where('transaksi.trans_status !=', 'Lunas');
		}

		if (!empty($search) && $search !== '') {
			$this->db->group_start();
			$this->db->like('transaksi.cos_kode', $search);
			$this->db->or_like('costomer.cos_nama', $search);
			$this->db->or_like('costomer.cos_model', $search);
			$this->db->or_like('costomer.cos_status', $search);
			$this->db->or_like('costomer.cos_tanggal', $search);
			$this->db->group_end();
		}

		$this->db->order_by($order_column, $order_dir);

		if ($length != -1) {
			$this->db->limit($length, $start);
		}

		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file M_service.php */
/* Location: ./application/models/M_service.php */