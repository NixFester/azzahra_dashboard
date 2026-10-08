<?php
/**
 * PAYMENT SUMMARY MODEL
 * File: application/models/M_payment_summary.php
 * 
 * Database operations untuk payment summary dan reporting
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_payment_summary extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Get daily payment summary
   * Returns breakdown by payment method and status
   */
  public function get_daily_summary($date = NULL) {
    if ($date === NULL) {
      $date = date('Y-m-d');
    }
    
    $summary = array(
      'date' => $date,
      'dp_summary' => array(),
      'lunas_summary' => array(),
      'total_dp' => 0,
      'total_lunas' => 0,
      'total_all' => 0,
      'transaction_count' => 0,
      'pending_transfers' => 0
    );
    
    // DP Summary by method
    $dp_query = $this->db->select("
                SUM(dtl_jml_bayar) as amount,
                COALESCE(dtl_payment_method, dtl_jenis_bayar) as method
              ")
              ->where('trans_status', 'Pelunasan')
              ->where('dtl_status', 'DP')
              ->where('DATE(dtl_tanggal)', $date)
              ->group_by('method')
              ->get('transaksi_detail');
    
    if ($dp_query->num_rows() > 0) {
      $summary['dp_summary'] = $dp_query->result_array();
      
      foreach ($summary['dp_summary'] as $row) {
        $summary['total_dp'] += $row['amount'];
      }
    }
    
    // Lunas Summary by method
    $lunas_query = $this->db->select("
                SUM(dtl_jml_bayar) as amount,
                COALESCE(dtl_payment_method, 'tunai') as method,
                dtl_bank as bank
              ")
              ->where('trans_status', 'Lunas')
              ->where('dtl_status', 'PELUNASAN')
              ->where('DATE(dtl_tanggal)', $date)
              ->group_by('method', 'bank')
              ->get('transaksi_detail');
    
    if ($lunas_query->num_rows() > 0) {
      $summary['lunas_summary'] = $lunas_query->result_array();
      
      foreach ($summary['lunas_summary'] as $row) {
        $summary['total_lunas'] += $row['amount'];
      }
    }
    
    // Calculate totals
    $summary['total_all'] = $summary['total_dp'] + $summary['total_lunas'];
    
    // Transaction count
    $trans_count = $this->db->select('COUNT(DISTINCT trans_kode) as count')
                            ->where('DATE(dtl_tanggal)', $date)
                            ->where('dtl_status IN ("DP", "PELUNASAN")')
                            ->get('transaksi_detail')
                            ->row_array();
    $summary['transaction_count'] = $trans_count['count'] ?? 0;
    
    // Pending transfers count
    $pending = $this->db->select('COUNT(*) as count')
                        ->where('dtl_transfer_status', 'pending')
                        ->where('DATE(dtl_tanggal)', $date)
                        ->get('transaksi_detail')
                        ->row_array();
    $summary['pending_transfers'] = $pending['count'] ?? 0;
    
    return $summary;
  }

  /**
   * Get DP payment detail by method
   */
  public function get_dp_detail($date = NULL) {
    if ($date === NULL) {
      $date = date('Y-m-d');
    }
    
    return $this->db->select("
                td.*,
                t.cos_kode,
                c.cos_nama,
                c.cos_hp,
                COALESCE(td.dtl_payment_method, td.dtl_jenis_bayar) as payment_method
              ")
              ->from('transaksi_detail td')
              ->join('transaksi t', 'td.trans_kode = t.trans_kode')
              ->join('costomer c', 't.cos_kode = c.cos_kode')
              ->where('t.trans_status', 'Pelunasan')
              ->where('td.dtl_status', 'DP')
              ->where('DATE(td.dtl_tanggal)', $date)
              ->order_by('td.dtl_tanggal', 'DESC')
              ->get()
              ->result_array();
  }

  /**
   * Get Lunas payment detail by method
   */
  public function get_lunas_detail($date = NULL) {
    if ($date === NULL) {
      $date = date('Y-m-d');
    }
    
    return $this->db->select("
                td.*,
                t.cos_kode,
                c.cos_nama,
                c.cos_hp,
                COALESCE(td.dtl_payment_method, 'tunai') as payment_method
              ")
              ->from('transaksi_detail td')
              ->join('transaksi t', 'td.trans_kode = t.trans_kode')
              ->join('costomer c', 't.cos_kode = c.cos_kode')
              ->where('t.trans_status', 'Lunas')
              ->where('td.dtl_status', 'PELUNASAN')
              ->where('DATE(td.dtl_tanggal)', $date)
              ->order_by('td.dtl_tanggal', 'DESC')
              ->get()
              ->result_array();
  }

  /**
   * Get pending transfers (transfer payment status = pending)
   */
  public function get_pending_transfers() {
    return $this->db->select("
                td.*,
                t.cos_kode,
                c.cos_nama,
                c.cos_hp
              ")
              ->from('transaksi_detail td')
              ->join('transaksi t', 'td.trans_kode = t.trans_kode')
              ->join('costomer c', 't.cos_kode = c.cos_kode')
              ->where('td.dtl_transfer_status', 'pending')
              ->where('td.dtl_payment_method', 'transfer')
              ->order_by('td.dtl_tanggal', 'DESC')
              ->get()
              ->result_array();
  }

  /**
   * Get monthly payment summary
   */
  public function get_monthly_summary($month, $year) {
    $summary = array(
      'month' => $month,
      'year' => $year,
      'total_dp' => 0,
      'total_lunas' => 0,
      'daily_breakdown' => array()
    );
    
    $query = $this->db->select("
                DATE(dtl_tanggal) as date,
                COUNT(DISTINCT td.trans_kode) as trans_count,
                SUM(CASE WHEN td.dtl_status = 'DP' THEN td.dtl_jml_bayar ELSE 0 END) as dp_total,
                SUM(CASE WHEN td.dtl_status = 'PELUNASAN' THEN td.dtl_jml_bayar ELSE 0 END) as lunas_total
              ")
              ->from('transaksi_detail td')
              ->where('MONTH(dtl_tanggal)', $month)
              ->where('YEAR(dtl_tanggal)', $year)
              ->group_by('date')
              ->order_by('date', 'ASC')
              ->get();
    
    if ($query->num_rows() > 0) {
      $summary['daily_breakdown'] = $query->result_array();
      
      foreach ($summary['daily_breakdown'] as $day) {
        $summary['total_dp'] += $day['dp_total'];
        $summary['total_lunas'] += $day['lunas_total'];
      }
    }
    
    return $summary;
  }

  /**
   * Get payment method statistics
   */
  public function get_method_statistics($date = NULL) {
    if ($date === NULL) {
      $date = date('Y-m-d');
    }
    
    return $this->db->select("
                COALESCE(dtl_payment_method, dtl_jenis_bayar) as method,
                COUNT(*) as count,
                SUM(dtl_jml_bayar) as total
              ")
              ->where('DATE(dtl_tanggal)', $date)
              ->where('dtl_status IN ("DP", "PELUNASAN")')
              ->group_by('method')
              ->order_by('total', 'DESC')
              ->get('transaksi_detail')
              ->result_array();
  }

  /**
   * Get bank transfer statistics for a date
   */
  public function get_bank_statistics($date = NULL) {
    if ($date === NULL) {
      $date = date('Y-m-d');
    }
    
    return $this->db->select("
                dtl_bank as bank,
                COUNT(*) as count,
                SUM(dtl_jml_bayar) as total,
                SUM(CASE WHEN dtl_transfer_status = 'pending' THEN 1 ELSE 0 END) as pending_count
              ")
              ->where('DATE(dtl_tanggal)', $date)
              ->where('dtl_payment_method', 'transfer')
              ->group_by('bank')
              ->get('transaksi_detail')
              ->result_array();
  }

}

?>
