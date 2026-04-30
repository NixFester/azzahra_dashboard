<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_customer');
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
}

/* End of file User.php */