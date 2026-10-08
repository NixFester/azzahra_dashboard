<?php
/**
 * SERVICE ENHANCEMENTS MODEL
 * File: application/models/M_service_enhancements.php
 * 
 * Database operations untuk action preset dan parts
 * 
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_service_enhancements extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  // ==========================================
  // ACTION PRESET METHODS
  // ==========================================

  /**
   * Search actions by query and category
   */
  public function search_actions($query = '', $category = '') {
    $this->db->where('active', TRUE);
    
    if (!empty($query)) {
      $this->db->like('action_name', $query);
    }
    
    if (!empty($category)) {
      $this->db->where('action_category', $category);
    }
    
    return $this->db->order_by('action_name', 'ASC')
                    ->limit(50)
                    ->get('action_preset')
                    ->result_array();
  }

  /**
   * Get action detail by ID
   */
  public function get_action($action_id) {
    return $this->db->where('action_id', $action_id)
                    ->where('active', TRUE)
                    ->get('action_preset')
                    ->row_array();
  }

  /**
   * Get all active actions by category
   */
  public function get_actions_by_category($category) {
    return $this->db->where('active', TRUE)
                    ->where('action_category', $category)
                    ->order_by('base_price', 'ASC')
                    ->get('action_preset')
                    ->result_array();
  }

  /**
   * Insert new action preset
   */
  public function insert_action($data) {
    return $this->db->insert('action_preset', $data);
  }

  /**
   * Update action preset
   */
  public function update_action($action_id, $data) {
    return $this->db->where('action_id', $action_id)
                    ->update('action_preset', $data);
  }

  // ==========================================
  // SPARE PARTS METHODS
  // ==========================================

  /**
   * Search spare parts by name or code
   * Note: This assumes a spare_parts or barang table exists
   */
  public function search_parts($query = '') {
    if (empty($query)) {
      return array();
    }
    
    // Try different column names based on your database structure
    // Adjust according to your actual table structure
    
    // If using produk table:
    $this->db->select('id as part_id, nama as part_name, kode as part_code, harga as selling_price, stok as current_stock')
             ->where('aktif', 1)
             ->group_start()
             ->like('nama', $query)
             ->or_like('kode', $query)
             ->group_end()
             ->order_by('nama', 'ASC')
             ->limit(50);
    
    $result = $this->db->get('produk')->result_array();
    
    return $result;
  }

  /**
   * Get part detail
   */
  public function get_part($part_id) {
    return $this->db->select('id as part_id, nama as part_name, kode as part_code, harga as selling_price, stok as current_stock')
                    ->where('id', $part_id)
                    ->where('aktif', 1)
                    ->get('produk')
                    ->row_array();
  }

  /**
   * Check part availability (stock > 0)
   */
  public function is_part_available($part_id) {
    $result = $this->db->where('id', $part_id)
                       ->get('produk')
                       ->row_array();
    return $result && $result['stok'] > 0;
  }

  // ==========================================
  // COST CALCULATION METHODS
  // ==========================================

  /**
   * Calculate total cost from actions and parts
   * 
   * @param array $actions [{ action_id, qty }, ...]
   * @param array $parts [{ part_id, qty }, ...]
   * @return array [total, actions_breakdown, parts_breakdown, success]
   */
  public function calculate_total($actions = array(), $parts = array()) {
    $result = array(
      'success' => FALSE,
      'total_actions' => 0,
      'total_parts' => 0,
      'grand_total' => 0,
      'actions_breakdown' => array(),
      'parts_breakdown' => array(),
      'errors' => array()
    );
    
    // Calculate actions
    if (is_array($actions) && count($actions) > 0) {
      foreach ($actions as $action) {
        if (empty($action['action_id']) || empty($action['qty'])) {
          continue;
        }
        
        $action_data = $this->get_action($action['action_id']);
        if ($action_data) {
          $subtotal = $action_data['base_price'] * $action['qty'];
          $result['total_actions'] += $subtotal;
          
          $result['actions_breakdown'][] = array(
            'action_id' => $action['action_id'],
            'action_name' => $action_data['action_name'],
            'qty' => $action['qty'],
            'unit_price' => $action_data['base_price'],
            'subtotal' => $subtotal
          );
        }
      }
    }
    
    // Calculate parts
    if (is_array($parts) && count($parts) > 0) {
      foreach ($parts as $part) {
        if (empty($part['part_id']) || empty($part['qty'])) {
          continue;
        }
        
        $part_data = $this->get_part($part['part_id']);
        if ($part_data) {
          $subtotal = $part_data['selling_price'] * $part['qty'];
          $result['total_parts'] += $subtotal;
          
          $result['parts_breakdown'][] = array(
            'part_id' => $part['part_id'],
            'part_name' => $part_data['part_name'],
            'qty' => $part['qty'],
            'unit_price' => $part_data['selling_price'],
            'subtotal' => $subtotal
          );
        }
      }
    }
    
    // Calculate grand total
    $result['grand_total'] = $result['total_actions'] + $result['total_parts'];
    $result['success'] = TRUE;
    
    return $result;
  }

  /**
   * Calculate action price for technician
   */
  public function get_action_price($action_id, $qty = 1) {
    $action = $this->get_action($action_id);
    if ($action) {
      return $action['base_price'] * $qty;
    }
    return 0;
  }

  /**
   * Calculate part price
   */
  public function get_part_price($part_id, $qty = 1) {
    $part = $this->get_part($part_id);
    if ($part) {
      return $part['selling_price'] * $qty;
    }
    return 0;
  }

}

?>
