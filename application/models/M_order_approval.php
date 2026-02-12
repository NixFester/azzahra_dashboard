<?php
/**
 * ORDER APPROVAL MODEL
 * File: application/models/M_order_approval.php
 * 
 * Database operations untuk order part approval
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_order_approval extends CI_Model {

  private $table = 'order_part_approvals';
  private $history_table = 'order_part_approval_history';

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Get all pending OOW (Out of Warranty) approvals
   */
  public function get_pending_oow() {
    return $this->db->select('*')
                    ->where('warranty_type', 'oow')
                    ->where('approval_status', 'pending')
                    ->order_by('created_at', 'DESC')
                    ->get($this->table)
                    ->result_array();
  }

  /**
   * Get all pending IW (In Warranty) approvals
   */
  public function get_pending_iw() {
    return $this->db->select('*')
                    ->where('warranty_type', 'iw')
                    ->where('approval_status', 'pending')
                    ->order_by('created_at', 'DESC')
                    ->get($this->table)
                    ->result_array();
  }

  /**
   * Get all pending parts (with transaction info)
   */
  public function get_all_pending_parts() {
    return $this->db->select('
                opa.*,
                t.trans_total,
                DATEDIFF(CURDATE(), o.created_at) as days_pending
              ')
                    ->from($this->table . ' opa')
                    ->join('transaksi t', 'opa.trans_kode = t.trans_kode')
                    ->join('order_list o', 'opa.trans_kode = o.trans_kode')
                    ->where('opa.approval_status', 'pending')
                    ->order_by('opa.created_at', 'DESC')
                    ->get()
                    ->result_array();
  }

  /**
   * Get approval by ID
   */
  public function get_by_id($id) {
    return $this->db->where('id', $id)
                    ->get($this->table)
                    ->row_array();
  }

  /**
   * Get approvals by transaction code
   */
  public function get_by_trans_kode($trans_kode) {
    return $this->db->where('trans_kode', $trans_kode)
                    ->order_by('created_at', 'DESC')
                    ->get($this->table)
                    ->result_array();
  }

  /**
   * Insert new approval
   */
  public function insert($data) {
    if ($this->db->insert($this->table, $data)) {
      return $this->db->insert_id();
    }
    return FALSE;
  }

  /**
   * Update approval
   */
  public function update($id, $data) {
    return $this->db->where('id', $id)
                    ->update($this->table, $data);
  }

  /**
   * Delete approval (only if still pending)
   */
  public function delete($id) {
    $approval = $this->get_by_id($id);
    if ($approval && $approval['approval_status'] == 'pending') {
      return $this->db->where('id', $id)
                      ->delete($this->table);
    }
    return FALSE;
  }

  /**
   * Get approval history (audit trail)
   */
  public function get_history($approval_id) {
    return $this->db->where('approval_id', $approval_id)
                    ->order_by('created_at', 'DESC')
                    ->get($this->history_table)
                    ->result_array();
  }

  /**
   * Add history entry (audit trail)
   */
  public function add_history($approval_id, $action, $action_by, $note = '') {
    $history_data = array(
      'approval_id' => $approval_id,
      'action' => $action,
      'action_by' => $action_by,
      'note' => $note,
      'action_at' => date('Y-m-d H:i:s')
    );
    
    return $this->db->insert($this->history_table, $history_data);
  }

  /**
   * Get count of pending approvals
   */
  public function count_pending() {
    return $this->db->where('approval_status', 'pending')
                    ->count_all_results($this->table);
  }

  /**
   * Get count of pending OOW approvals
   */
  public function count_pending_oow() {
    return $this->db->where('warranty_type', 'oow')
                    ->where('approval_status', 'pending')
                    ->count_all_results($this->table);
  }

  /**
   * Get count of pending IW approvals
   */
  public function count_pending_iw() {
    return $this->db->where('warranty_type', 'iw')
                    ->where('approval_status', 'pending')
                    ->count_all_results($this->table);
  }

  /**
   * Get total pending value
   */
  public function get_total_pending() {
    $result = $this->db->select('SUM(part_price * part_qty) as total')
                       ->where('approval_status', 'pending')
                       ->get($this->table)
                       ->row_array();
    return $result['total'] ?? 0;
  }

  /**
   * Get approved approvals that need processing
   */
  public function get_approved_pending_process() {
    return $this->db->where('approval_status', 'approved')
                    ->where('processed_at IS NULL', NULL, FALSE)
                    ->order_by('approved_at', 'ASC')
                    ->get($this->table)
                    ->result_array();
  }

}

?>
