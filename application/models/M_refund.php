<?php
/**
 * REFUND MODEL
 * File: application/models/M_refund.php
 * 
 * Database operations untuk refund requests
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_refund extends CI_Model {

  private $table = 'refund_requests';

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Get all pending refund requests
   */
  public function get_pending_refunds() {
    return $this->db->where('refund_status', 'pending')
                    ->order_by('created_at', 'DESC')
                    ->get($this->table)
                    ->result_array();
  }

  /**
   * Get refund by ID
   */
  public function get_by_id($id) {
    return $this->db->where('id', $id)
                    ->get($this->table)
                    ->row_array();
  }

  /**
   * Get refund by transaction code
   */
  public function get_by_trans_kode($trans_kode) {
    return $this->db->where('trans_kode', $trans_kode)
                    ->get($this->table)
                    ->row_array();
  }

  /**
   * Insert new refund request
   */
  public function insert($data) {
    if ($this->db->insert($this->table, $data)) {
      return $this->db->insert_id();
    }
    return FALSE;
  }

  /**
   * Update refund request
   */
  public function update($id, $data) {
    return $this->db->where('id', $id)
                    ->update($this->table, $data);
  }

  /**
   * Get total amount by status
   */
  public function get_total_by_status($status) {
    $query = $this->db->select('SUM(refund_amount) as total')
                      ->where('refund_status', $status)
                      ->get($this->table);
    
    $result = $query->row_array();
    return $result['total'] ?? 0;
  }

  /**
   * Get pending refund total
   */
  public function get_pending_total() {
    return $this->get_total_by_status('pending');
  }

  /**
   * Count refunds by status
   */
  public function count_by_status($status) {
    return $this->db->where('refund_status', $status)
                    ->count_all_results($this->table);
  }

  /**
   * Get all refunds for a transaction
   */
  public function get_by_transaction($trans_kode) {
    return $this->db->where('trans_kode', $trans_kode)
                    ->order_by('created_at', 'DESC')
                    ->get($this->table)
                    ->result_array();
  }

  /**
   * Get refunds processed today
   */
  public function get_today_processed() {
    $today = date('Y-m-d');
    return $this->db->select('*')
                    ->where('refund_status', 'processed')
                    ->where('DATE(processed_at)', $today)
                    ->order_by('processed_at', 'DESC')
                    ->get($this->table)
                    ->result_array();
  }

  /**
   * Get statistics
   */
  public function get_statistics() {
    return array(
      'total_pending' => $this->count_by_status('pending'),
      'total_approved' => $this->count_by_status('approved'),
      'total_processed' => $this->count_by_status('processed'),
      'total_cancelled' => $this->count_by_status('cancelled'),
      'amount_pending' => $this->get_total_by_status('pending'),
      'amount_processed' => $this->get_total_by_status('processed'),
    );
  }

}

?>
