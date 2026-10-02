<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Xendit Payment Gateway Library for CodeIgniter 3
 */
class Xendit_lib {

    protected $CI;
    protected $secret_key;
    protected $callback_token;
    protected $api_url;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('xendit', TRUE);

        $this->secret_key     = $this->CI->config->item('xendit_secret_key', 'xendit');
        $this->callback_token = $this->CI->config->item('xendit_callback_token', 'xendit');
        $this->api_url        = rtrim($this->CI->config->item('xendit_api_url', 'xendit'), '/');
    }

    /**
     * Create Xendit Invoice (V2 API)
     *
     * @param string $external_id Unique transaction reference ID
     * @param float|int $amount Total invoice amount
     * @param string $payer_email Customer email (optional)
     * @param string $description Description of invoice
     * @param string $customer_name Customer name (optional)
     * @param string $success_redirect_url Redirect URL after payment
     * @return array Response array containing status & data
     */
    public function create_invoice($external_id, $amount, $payer_email = '', $description = '', $customer_name = '', $success_redirect_url = '')
    {
        $payload = array(
            'external_id' => (string) $external_id,
            'amount'      => (float) $amount,
            'description' => $description ? $description : 'Pembayaran Transaksi #' . $external_id,
        );

        if (!empty($payer_email) && filter_var($payer_email, FILTER_VALIDATE_EMAIL)) {
            $payload['payer_email'] = $payer_email;
        }

        if (!empty($customer_name)) {
            $payload['customer'] = array(
                'given_names' => $customer_name
            );
        }

        if (!empty($success_redirect_url)) {
            $payload['success_redirect_url'] = $success_redirect_url;
        }

        return $this->_request('/v2/invoices', 'POST', $payload);
    }

    /**
     * Get Xendit Invoice by Invoice ID
     *
     * @param string $invoice_id
     * @return array
     */
    public function get_invoice($invoice_id)
    {
        return $this->_request('/v2/invoices/' . rawurlencode($invoice_id), 'GET');
    }

    /**
     * Expire an existing invoice
     *
     * @param string $invoice_id
     * @return array
     */
    public function expire_invoice($invoice_id)
    {
        return $this->_request('/v2/invoices/' . rawurlencode($invoice_id) . '/expire!', 'POST');
    }

    /**
     * Verify Callback Verification Token from Xendit Webhook header
     * Header: x-callback-token
     *
     * @param string $header_token
     * @return bool
     */
    public function verify_callback_token($header_token)
    {
        if (empty($this->callback_token) || $this->callback_token === 'YOUR_CALLBACK_VERIFICATION_TOKEN_HERE') {
            return TRUE; // allow in dev if not set
        }
        return hash_equals($this->callback_token, (string) $header_token);
    }

    /**
     * Execute HTTP cURL Request to Xendit API
     */
    private function _request($endpoint, $method = 'GET', $data = array())
    {
        $url = $this->api_url . $endpoint;
        $ch = curl_init();

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->secret_key . ':')
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            return array(
                'status'  => false,
                'message' => 'cURL Error: ' . $curl_error,
                'code'    => 500,
                'data'    => null
            );
        }

        $result = json_decode($response, true);

        if ($http_code >= 200 && $http_code < 300) {
            return array(
                'status'  => true,
                'message' => 'Success',
                'code'    => $http_code,
                'data'    => $result
            );
        }

        $error_msg = isset($result['message']) ? $result['message'] : (isset($result['error_code']) ? $result['error_code'] : 'HTTP Error ' . $http_code);
        return array(
            'status'  => false,
            'message' => $error_msg,
            'code'    => $http_code,
            'data'    => $result
        );
    }
}
