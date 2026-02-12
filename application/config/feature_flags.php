<?php
/**
 * Feature Flags Configuration
 * File: application/config/feature_flags.php
 * 
 * Non-invasive features yang dapat di-enable/disable per fitur
 * Created: 11 Feb 2026
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// ======================================
// FEATURE FLAGS CONTROL
// ======================================
$config['feature_flags'] = array(
  
  // ===== PHASE 1 - CRITICAL (Week 1) =====
  
  // Fitur 7: Metode Pembayaran di Pelunasan
  'payment_method_selector'    => TRUE,
  
  // Fitur 6: Refund Feature
  'refund_feature'             => TRUE,
  
  // Fitur 1: Action Preset & Teknisi Enhancement
  'action_preset'              => TRUE,
  
  
  // ===== PHASE 2 - CORE (Week 2) =====
  
  // Fitur 11: Kalkulasi Sisa Pembayaran
  'balance_calculator'         => TRUE,
  
  // Fitur 2: Payment Summary Dashboard
  'payment_summary_dashboard'  => TRUE,
  
  // (Fitur 1 lanjutan) Auto harga dengan teknisi dashboard
  'technician_cost_calculator' => TRUE,
  
  
  // ===== PHASE 3 - WORKFLOWS (Week 3) =====
  
  // Fitur 3 & 4: Order Part Approval (OOW & IW)
  'order_part_approval'        => TRUE,
  
  // Fitur 5: Shipment Tracking
  'shipment_tracking'          => FALSE,  // Enable after testing
  
  // Fitur 10: Spare Parts Pending Status
  'spare_parts_pending'        => TRUE,
  
  
  // ===== PHASE 4 - OPTIONAL (Week 4) =====
  
  // Fitur 13: Thermal Print
  'thermal_print'              => FALSE,  // Enable after device ready
  
  // Fitur 14: Real-time Monitor Display
  'monitor_display'            => TRUE,
  
);

// ======================================
// HELP / DEBUG
// ======================================

/**
 * HOW TO USE:
 * 
 * In any controller/view:
 * 
 * if ($this->config->item('feature_flags')['refund_feature']) {
 *   // Show refund button/feature
 * }
 * 
 * Usage example in views:
 * <?php if ($this->config->item('feature_flags')['refund_feature']): ?>
 *   <button>Refund Feature</button>
 * <?php endif; ?>
 * 
 * Usage in controllers:
 * if ($this->config->item('feature_flags')['refund_feature']) {
 *   $this->load->controller('Refund_management');
 * }
 */

?>
