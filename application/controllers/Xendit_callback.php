<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xendit_callback extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_admin');
        $this->load->library('xendit_lib');
    }

    /**
     * Endpoint for Xendit Invoice Webhook / Callback
     * URL: {site_url}/Xendit_callback/invoice
     */
    public function invoice()
    {
        // 1. Verify Callback Token Header
        $header_token = $this->input->get_request_header('x-callback-token', TRUE);
        if (!$header_token) {
            $header_token = $this->input->get_request_header('X-CALLBACK-TOKEN', TRUE);
        }

        if (!$this->xendit_lib->verify_callback_token($header_token)) {
            $this->output
                 ->set_status_header(403)
                 ->set_content_type('application/json')
                 ->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid Callback Verification Token')));
            return;
        }

        // 2. Read Raw POST Request Body
        $json_input = file_get_contents('php://input');
        $data = json_decode($json_input, TRUE);

        if (empty($data) || !isset($data['id'])) {
            $this->output
                 ->set_status_header(400)
                 ->set_content_type('application/json')
                 ->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid Payload')));
            return;
        }

        $invoice_id     = $data['id'];
        $external_id    = isset($data['external_id']) ? $data['external_id'] : '';
        $status         = isset($data['status']) ? strtoupper($data['status']) : '';
        $payment_method = isset($data['payment_channel']) ? $data['payment_channel'] : (isset($data['payment_method']) ? $data['payment_method'] : NULL);

        // 3. Find database record by Xendit Invoice ID
        $dtl = $this->M_admin->get_detail_by_invoice_id($invoice_id);

        // Fallback: try parsing dtl_kode from external_id (e.g. INV-TRX001-12)
        if (!$dtl && !empty($external_id)) {
            $parts = explode('-', $external_id);
            $dtl_kode = end($parts);
            if (is_numeric($dtl_kode)) {
                $dtl = $this->M_admin->get_detail_by_kode($dtl_kode);
            }
        }

        if (!$dtl) {
            $this->output
                 ->set_status_header(404)
                 ->set_content_type('application/json')
                 ->set_output(json_encode(array('status' => 'error', 'message' => 'Transaction record not found')));
            return;
        }

        // 4. Update status in database
        $update = array(
            'xendit_status'         => $status,
            'xendit_payment_method' => $payment_method
        );

        if (in_array($status, array('PAID', 'SETTLED'))) {
            $update['dtl_stt_stor'] = 'Disetorkan';
        }

        $this->M_admin->update_xendit_detail($dtl['dtl_kode'], $update);

        $this->output
             ->set_status_header(200)
             ->set_content_type('application/json')
             ->set_output(json_encode(array(
                 'status'     => 'success',
                 'invoice_id' => $invoice_id,
                 'dtl_kode'   => $dtl['dtl_kode'],
                 'new_status' => $status
             )));
    }
}
