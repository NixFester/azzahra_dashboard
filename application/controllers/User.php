<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_customer');
        // load M_service for signature operations
        $this->load->model('M_service');
    }

    public function detail($trans_kode = '')
    {
        // Debug - log what we're receiving
    
    if (empty($trans_kode)) {
        show_404();
    }

    // Get customer and transaction info
    $proses = $this->M_customer->konfirmasi($trans_kode)->row_array();
    
    // Debug - log what we're getting from the database
    
    if ($proses === null) {
        show_404();
    }
        if (empty($trans_kode)) {
            show_404();
        }

        //get code from trans_kode
        $kode = $this->M_customer->GetTransCode($trans_kode)->row_array()['trans_kode'] ?? null;
        if ($kode === null) {
            show_404();
        }

        // Get customer and transaction info
        $proses = $this->M_customer->konfirmasi($kode)->row_array();
        if ($proses === null) {
            show_404();
        }

        // Get transaction history
        $hist_trans = $this->M_customer->histori_transaksi($kode)->row_array();
        if ($hist_trans === null) {
            $hist_trans = array('trans_discount' => 0, 'trans_total' => 0);
        }

        $data = array(
            'title'  	 => 'Transaction Detail',
            'proses' 	 => $proses,
            'data'	 	 => $this->M_customer->GetTindakanBy($kode),
            'custom' 	 => $this->M_customer->histori($kode),
            'hist_trans' => $hist_trans
        );
        $this->load->view('User/detail', $data);
    }

    // Public signature page (no login required)
    public function public_signature($kode = null)
    {
        if (empty($kode)) {
            echo 'Signature not found';
            return;
        }

        $sig = $this->M_service->getSignature($kode)->row_array();
        $data = [
            'signature_url' => isset($sig['signature_url']) ? $sig['signature_url'] : null,
            'kode' => $kode,
            'cos_hp' => $this->M_customer->get_hp_by_trans($kode) ?? ''
        ];
        $this->load->view('User/public_signature', $data);
    }

    // Upload signature (public)
    public function upload_signature_public()
    {
        $kode = $this->input->post('kode');
        $image_data = $this->input->post('signature');
        if (empty($kode) || empty($image_data)) {
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            return;
        }

        $image_data = str_replace('data:image/png;base64,', '', $image_data);
        $image_data = str_replace(' ', '+', $image_data);

        $cloud_name    = 'dbwvddsvb';
        $upload_preset = 'ttd_customer';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloud_name}/image/upload");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'file'           => 'data:image/png;base64,' . $image_data,
            'upload_preset'  => $upload_preset,
            'folder'         => 'tanda_tangan',
            'public_id' => $kode . '-' . preg_replace('/\s+/', '_', strtoupper($this->M_service->trans($kode)->row()->cos_nama ?? $kode)),
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['secure_url'])) {
            $this->M_service->saveSignature($kode, $result['secure_url']);
            echo json_encode(['success' => true, 'url' => $result['secure_url']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed']);
        }
    }

    // Delete signature (public)
    public function delete_signature_public()
    {
        $kode = $this->input->post('kode');
        if (empty($kode)) {
            echo json_encode(['success' => false]);
            return;
        }
        $this->M_service->deleteSignature($kode);
        echo json_encode(['success' => true]);
    }
}

/* End of file User.php */