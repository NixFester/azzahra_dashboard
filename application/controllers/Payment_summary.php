<?php
/**
 * PAYMENT SUMMARY CONTROLLER
 * File: application/controllers/Payment_summary.php
 * 
 * Dashboard untuk melihat ringkasan pembayaran per hari
 * Features:
 * - Daily payment summary (DP + Lunas)
 * - Payment method breakdown
 * - Transaction statistics
 * - Real-time updates via AJAX
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_summary extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('M_payment_summary');
    $this->load->library('session');
    
    // Only Kasir/Admin can access
    $level = $this->session->userdata('karyawan_level');
    if ($level != 'Kasir' && $level != 'Admin') {
      redirect('Auth/login');
    }
    
    // Check if feature enabled
    if (!$this->config->item('feature_flags')['payment_summary_dashboard']) {
      show_error('Feature tidak aktif', 403);
    }
  }

  /**
   * Daily payment summary dashboard
   * URL: /Payment_summary/daily
   */
  public function daily() {
    $date = $this->input->get('date') ?? date('Y-m-d');
    
    $data['page_title'] = 'Payment Summary - ' . date('d M Y', strtotime($date));
    $data['selected_date'] = $date;
    $data['summary'] = $this->M_payment_summary->get_daily_summary($date);
    $data['detail_dp'] = $this->M_payment_summary->get_dp_detail($date);
    $data['detail_lunas'] = $this->M_payment_summary->get_lunas_detail($date);
    
    $this->load->view('Payment/summary_dashboard', $data);
  }

  /**
   * Get JSON summary for AJAX refresh
   * URL: GET /Payment_summary/json?date=2026-02-11
   */
  public function json() {
    $date = $this->input->get('date') ?? date('Y-m-d');
    
    $summary = $this->M_payment_summary->get_daily_summary($date);
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($summary));
  }

  /**
   * Get pending transfer list
   * URL: /Payment_summary/pending_transfers
   */
  public function pending_transfers() {
    $data['page_title'] = 'Pending Transfers';
    $data['transfers'] = $this->M_payment_summary->get_pending_transfers();
    
    $this->load->view('Payment/pending_transfers', $data);
  }

  /**
   * Export daily summary to Excel
   * URL: GET /Payment_summary/export?date=2026-02-11
   */
  public function export() {
    $date = $this->input->get('date') ?? date('Y-m-d');
    
    $summary = $this->M_payment_summary->get_daily_summary($date);
    $detail_dp = $this->M_payment_summary->get_dp_detail($date);
    $detail_lunas = $this->M_payment_summary->get_lunas_detail($date);
    
    $data['summary'] = $summary;
    $data['detail_dp'] = $detail_dp;
    $data['detail_lunas'] = $detail_lunas;
    $data['date'] = $date;
    
    // Generate Excel using your existing Excel library
    $this->load->library('Excel');
    $this->load->view('Payment/export_summary', $data);
  }

  /**
   * Print daily summary
   * URL: GET /Payment_summary/print?date=2026-02-11
   */
  public function print_summary() {
    $date = $this->input->get('date') ?? date('Y-m-d');
    
    $data['summary'] = $this->M_payment_summary->get_daily_summary($date);
    $data['detail_dp'] = $this->M_payment_summary->get_dp_detail($date);
    $data['detail_lunas'] = $this->M_payment_summary->get_lunas_detail($date);
    $data['date'] = $date;
    
    $this->load->view('Payment/print_summary', $data);
  }

  /**
   * Get monthly summary
   * URL: GET /Payment_summary/monthly?month=2&year=2026
   */
  public function monthly() {
    $month = $this->input->get('month') ?? date('m');
    $year = $this->input->get('year') ?? date('Y');
    
    $data['page_title'] = 'Payment Summary - ' . date('F Y', mktime(0, 0, 0, $month, 1, $year));
    $data['month'] = $month;
    $data['year'] = $year;
    $data['summary'] = $this->M_payment_summary->get_monthly_summary($month, $year);
    
    $this->load->view('Payment/monthly_summary', $data);
  }

}

?>
