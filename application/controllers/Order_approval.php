<?php
/**
 * ORDER APPROVAL CONTROLLER
 * File: application/controllers/Order_approval.php
 * 
 * Pengelolaan approval untuk order spare parts
 * Features:
 * - OOW (Out of Warranty) approval oleh Management
 * - IW (In Warranty) approval oleh Admin
 * - Approval history dan audit trail
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_approval extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('M_order_approval');
    $this->load->library('session');
    
    // Check if feature enabled
    if (!$this->config->item('feature_flags')['order_part_approval']) {
      show_error('Feature tidak aktif', 403);
    }
  }

  /**
   * View pending OOW (Out of Warranty) approvals
   * URL: /Order_approval/pending_oow
   */
  public function pending_oow() {
    // Verify access - Management/Admin
    $level = $this->session->userdata('karyawan_level');
    if (!in_array($level, ['Admin', 'Manager'])) {
      show_error('Anda tidak memiliki akses ke fitur ini', 403);
    }
    
    $data['page_title'] = 'OOW Part Approval - Pending';
    $data['approvals'] = $this->M_order_approval->get_pending_oow();
    $data['count'] = count($data['approvals']);
    
    $this->load->view('Order/part_approval_oow', $data);
  }

  /**
   * View pending IW (In Warranty) approvals
   * URL: /Order_approval/pending_iw
   */
  public function pending_iw() {
    // Verify access - Admin
    $level = $this->session->userdata('karyawan_level');
    if ($level != 'Admin') {
      show_error('Anda tidak memiliki akses ke fitur ini', 403);
    }
    
    $data['page_title'] = 'IW Part Approval - Pending';
    $data['approvals'] = $this->M_order_approval->get_pending_iw();
    $data['count'] = count($data['approvals']);
    
    $this->load->view('Order/part_approval_iw', $data);
  }

  /**
   * Approve part order
   * URL: POST /Order_approval/approve/{id}
   */
  public function approve($approval_id) {
    if (!$this->input->is_ajax_request()) {
      show_error('AJAX request required', 400);
    }
    
    $approval = $this->M_order_approval->get_by_id($approval_id);
    if (!$approval) {
      $this->output->set_status_header(404);
      echo json_encode(['success' => FALSE, 'message' => 'Approval tidak ditemukan']);
      return;
    }
    
    // Get additional info from request
    $supplier_id = $this->input->post('supplier_id');
    $lead_time = $this->input->post('lead_time');
    $note = $this->input->post('note');
    
    // Update approval status
    $update_data = array(
      'approval_status' => 'approved',
      'approved_by' => $this->session->userdata('karyawan_id'),
      'approved_at' => date('Y-m-d H:i:s')
    );
    
    if ($this->M_order_approval->update($approval_id, $update_data)) {
      echo json_encode([
        'success' => TRUE,
        'message' => 'Part order approved',
        'id' => $approval_id
      ]);
    } else {
      $this->output->set_status_header(500);
      echo json_encode(['success' => FALSE, 'message' => 'Gagal approve']);
    }
  }

  /**
   * Reject part order
   * URL: POST /Order_approval/reject/{id}
   */
  public function reject($approval_id) {
    if (!$this->input->is_ajax_request()) {
      show_error('AJAX request required', 400);
    }
    
    $reason = $this->input->post('reject_reason');
    
    if (empty($reason)) {
      $this->output->set_status_header(400);
      echo json_encode(['success' => FALSE, 'message' => 'Alasan penolakan harus diisi']);
      return;
    }
    
    $update_data = array(
      'approval_status' => 'rejected',
      'rejected_reason' => $reason,
      'approved_by' => $this->session->userdata('karyawan_id'),
      'rejected_at' => date('Y-m-d H:i:s')
    );
    
    if ($this->M_order_approval->update($approval_id, $update_data)) {
      echo json_encode([
        'success' => TRUE,
        'message' => 'Part order rejected'
      ]);
    } else {
      $this->output->set_status_header(500);
      echo json_encode(['success' => FALSE, 'message' => 'Gagal reject']);
    }
  }

  /**
   * View pending parts status
   * URL: /Order_approval/pending_parts
   */
  public function pending_parts() {
    $data['page_title'] = 'Pending Parts Status';
    $data['pending_parts'] = $this->M_order_approval->get_all_pending_parts();
    
    $this->load->view('Order/pending_parts', $data);
  }

  /**
   * Get approval detail
   * URL: GET /Order_approval/detail/{id}
   */
  public function detail($approval_id) {
    $approval = $this->M_order_approval->get_by_id($approval_id);
    
    if (!$approval) {
      show_error('Approval tidak ditemukan', 404);
    }
    
    $data['approval'] = $approval;
    $data['history'] = $this->M_order_approval->get_history($approval_id);
    
    $this->load->view('Order/approval_detail', $data);
  }

  /**
   * Get JSON data for approval list (AJAX)
   * URL: GET /Order_approval/json_pending?type=oow
   */
  public function json_pending() {
    $type = $this->input->get('type'); // 'oow' atau 'iw'
    
    if ($type == 'oow') {
      $data = $this->M_order_approval->get_pending_oow();
    } elseif ($type == 'iw') {
      $data = $this->M_order_approval->get_pending_iw();
    } else {
      $data = array();
    }
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($data));
  }

}

?>
