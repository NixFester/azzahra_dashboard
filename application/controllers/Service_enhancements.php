<?php
/**
 * SERVICE ENHANCEMENTS CONTROLLER
 * File: application/controllers/Service_enhancements.php
 * 
 * API & enhancement untuk fitur teknisi service
 * Features:
 * - Action preset selector
 * - Spare parts finder
 * - Cost calculation
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Service_enhancements extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('M_service_enhancements');
    $this->load->library('session');
    
    // Check if feature enabled
    if (!$this->config->item('feature_flags')['action_preset']) {
      show_error('Feature tidak aktif', 403);
    }
  }

  /**
   * Get action preset list with search
   * URL: GET /service_enhancements/get_actions?query=&category=
   */
  public function get_actions() {
    $query = $this->input->get('query');
    $category = $this->input->get('category');
    
    $actions = $this->M_service_enhancements->search_actions($query, $category);
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($actions));
  }

  /**
   * Get spare parts list with search
   * URL: GET /service_enhancements/get_parts?q=SSD
   */
  public function get_parts() {
    $query = $this->input->get('q');
    
    // Minimum length for search
    if (strlen($query) < 2) {
      echo json_encode(['success' => FALSE, 'message' => 'Query minimal 2 karakter']);
      return;
    }
    
    $parts = $this->M_service_enhancements->search_parts($query);
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($parts));
  }

  /**
   * Calculate total cost for actions and parts
   * URL: POST /service_enhancements/calculate_cost
   * Data: 
   *   - actions: [{ action_id, qty }, ...]
   *   - parts: [{ part_id, qty }, ...]
   */
  public function calculate_cost() {
    if (!$this->input->is_ajax_request()) {
      show_error('AJAX request required', 400);
    }
    
    $actions = $this->input->post('actions'); // array
    $parts = $this->input->post('parts');     // array
    
    $result = $this->M_service_enhancements->calculate_total($actions, $parts);
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($result));
  }

  /**
   * Get action detail
   * URL: GET /service_enhancements/action/{action_id}
   */
  public function action_detail($action_id) {
    $action = $this->M_service_enhancements->get_action($action_id);
    
    if (!$action) {
      $this->output->set_status_header(404);
      echo json_encode(['success' => FALSE, 'message' => 'Action tidak ditemukan']);
      return;
    }
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($action));
  }

  /**
   * Get part detail
   * URL: GET /service_enhancements/part/{part_id}
   */
  public function part_detail($part_id) {
    $part = $this->M_service_enhancements->get_part($part_id);
    
    if (!$part) {
      $this->output->set_status_header(404);
      echo json_encode(['success' => FALSE, 'message' => 'Part tidak ditemukan']);
      return;
    }
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode($part));
  }

}

?>
