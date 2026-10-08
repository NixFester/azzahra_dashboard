<?php
/**
 * REFUND MANAGEMENT CONTROLLER
 * File: application/controllers/Refund_management.php
 * 
 * Pengelolaan refund untuk service gagal dan DP yang sudah diproses
 * Features:
 * - View pending refund requests
 * - Approve refund
 * - Process refund to customer
 * - Track refund history
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Refund_management extends CI_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load required libraries and models
    $this->load->model('M_refund');
    $this->load->model('M_transaksi');
    $this->load->library('session');
    
    // Check if user is logged in (Kasir atau Admin)
    if (!$this->session->userdata('karyawan_id')) {
      redirect('Auth/login');
    }
    
    // Check if feature is enabled
    if (!$this->config->item('feature_flags')['refund_feature']) {
      show_error('Feature tidak aktif', 403);
    }
  }

  /**
   * View all pending refund requests
   * URL: /Refund_management/pending
   */
  public function pending() {
    $data['page_title'] = 'Pending Refund Requests';
    $data['refunds'] = $this->M_refund->get_pending_refunds();
    $data['total_pending'] = count($data['refunds']);
    $data['total_amount'] = $this->M_refund->get_pending_total();
    
    $this->load->view('Refund/pending_list', $data);
  }

  /**
   * View detail refund request
   * URL: /Refund_management/detail/{id}
   */
  public function detail($refund_id) {
    $refund = $this->M_refund->get_by_id($refund_id);
    
    if (!$refund) {
      show_error('Refund request tidak ditemukan', 404);
    }
    
    // Get transaction details
    $trans = $this->M_transaksi->get_by_kode($refund['trans_kode']);
    
    $data['page_title'] = 'Refund Detail';
    $data['refund'] = $refund;
    $data['transaksi'] = $trans;
    
    $this->load->view('Refund/detail', $data);
  }

  /**
   * Create new refund request
   * URL: POST /Refund_management/create
   */
  public function create() {
    if ($this->input->is_ajax_request()) {
      
      $trans_kode = $this->input->post('trans_kode');
      $dp_paid = $this->input->post('dp_paid');
      $refund_reason = $this->input->post('refund_reason');
      $refund_method = $this->input->post('refund_method');
      
      // Validation
      if (empty($trans_kode) || empty($dp_paid)) {
        $this->output->set_status_header(400);
        echo json_encode(['success' => FALSE, 'message' => 'Data tidak lengkap']);
        return;
      }
      
      // Get transaction
      $trans = $this->M_transaksi->get_by_kode($trans_kode);
      if (!$trans) {
        $this->output->set_status_header(404);
        echo json_encode(['success' => FALSE, 'message' => 'Transaksi tidak ditemukan']);
        return;
      }
      
      // Calculate refund amount (DP - diagnosis fee)
      $diagnosis_fee = 50000;
      $refund_amount = $dp_paid - $diagnosis_fee;
      
      // Prepare refund data
      $refund_data = array(
        'trans_kode' => $trans_kode,
        'cos_kode' => $trans['cos_kode'],
        'dp_paid' => $dp_paid,
        'diagnosis_fee' => $diagnosis_fee,
        'refund_amount' => $refund_amount,
        'refund_reason' => $refund_reason,
        'refund_method' => $refund_method,
        'refund_status' => 'pending',
        'created_by' => $this->session->userdata('karyawan_id')
      );
      
      // If bank transfer, need account info
      if ($refund_method == 'bank_transfer') {
        $refund_data['bank_name'] = $this->input->post('bank_name');
        $refund_data['bank_account'] = $this->input->post('bank_account');
      }
      
      // Save to database
      $insert_id = $this->M_refund->insert($refund_data);
      
      if ($insert_id) {
        echo json_encode(['success' => TRUE, 'message' => 'Refund request created', 'id' => $insert_id]);
      } else {
        $this->output->set_status_header(500);
        echo json_encode(['success' => FALSE, 'message' => 'Gagal membuat refund request']);
      }
    }
  }

  /**
   * Approve refund request (by Admin/Manager)
   * URL: POST /Refund_management/approve/{id}
   */
  public function approve($refund_id) {
    if ($this->input->is_ajax_request()) {
      
      $refund = $this->M_refund->get_by_id($refund_id);
      if (!$refund) {
        $this->output->set_status_header(404);
        echo json_encode(['success' => FALSE, 'message' => 'Refund tidak ditemukan']);
        return;
      }
      
      // Update status to approved
      $update_data = array(
        'refund_status' => 'approved',
        'approved_by' => $this->session->userdata('karyawan_id'),
        'approved_at' => date('Y-m-d H:i:s')
      );
      
      $updated = $this->M_refund->update($refund_id, $update_data);
      
      if ($updated) {
        echo json_encode(['success' => TRUE, 'message' => 'Refund approved']);
      } else {
        $this->output->set_status_header(500);
        echo json_encode(['success' => FALSE, 'message' => 'Gagal approve refund']);
      }
    }
  }

  /**
   * Process refund payment to customer
   * URL: POST /Refund_management/process/{id}
   */
  public function process($refund_id) {
    if ($this->input->is_ajax_request()) {
      
      $refund = $this->M_refund->get_by_id($refund_id);
      if (!$refund) {
        $this->output->set_status_header(404);
        echo json_encode(['success' => FALSE, 'message' => 'Refund tidak ditemukan']);
        return;
      }
      
      if ($refund['refund_status'] != 'approved') {
        $this->output->set_status_header(400);
        echo json_encode(['success' => FALSE, 'message' => 'Refund harus di-approve terlebih dahulu']);
        return;
      }
      
      // Update status to processed
      $update_data = array(
        'refund_status' => 'processed',
        'processed_by' => $this->session->userdata('karyawan_id'),
        'processed_at' => date('Y-m-d H:i:s')
      );
      
      $updated = $this->M_refund->update($refund_id, $update_data);
      
      if ($updated) {
        // TODO: Integrate dengan payment gateway untuk auto-transfer
        echo json_encode(['success' => TRUE, 'message' => 'Refund processed']);
      } else {
        $this->output->set_status_header(500);
        echo json_encode(['success' => FALSE, 'message' => 'Gagal process refund']);
      }
    }
  }

  /**
   * Reject refund request
   * URL: POST /Refund_management/reject/{id}
   */
  public function reject($refund_id) {
    if ($this->input->is_ajax_request()) {
      
      $reason = $this->input->post('reason');
      
      $refund = $this->M_refund->get_by_id($refund_id);
      if (!$refund) {
        $this->output->set_status_header(404);
        echo json_encode(['success' => FALSE, 'message' => 'Refund tidak ditemukan']);
        return;
      }
      
      $update_data = array(
        'refund_status' => 'cancelled',
        'rejected_reason' => $reason,
        'approved_by' => $this->session->userdata('karyawan_id'),
        'approved_at' => date('Y-m-d H:i:s')
      );
      
      $updated = $this->M_refund->update($refund_id, $update_data);
      
      if ($updated) {
        echo json_encode(['success' => TRUE, 'message' => 'Refund rejected']);
      } else {
        $this->output->set_status_header(500);
        echo json_encode(['success' => FALSE, 'message' => 'Gagal reject refund']);
      }
    }
  }

  /**
   * Get refund statistics
   * URL: GET /Refund_management/stats
   */
  public function stats() {
    $stats = array(
      'total_pending' => $this->M_refund->count_by_status('pending'),
      'total_approved' => $this->M_refund->count_by_status('approved'),
      'total_processed' => $this->M_refund->count_by_status('processed'),
      'total_cancelled' => $this->M_refund->count_by_status('cancelled'),
      'total_amount_pending' => $this->M_refund->get_total_by_status('pending'),
      'total_amount_processed' => $this->M_refund->get_total_by_status('processed')
    );
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($stats));
  }

}

?>
