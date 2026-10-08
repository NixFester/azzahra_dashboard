<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test_accounts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('test_accounts_model');
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        $this->load->view('test_accounts_form');
    }

    public function create() {
        $data = $this->input->post();

        $insert = [
            'kry_kode'       => $data['kry_kode'],
            'kry_nik'        => $data['kry_nik'],
            'kry_nama'       => $data['kry_nama'],
            'kry_tempat'     => $data['kry_tempat'],
            'kry_tgl_lahir'  => $data['kry_tgl_lahir'],
            'kry_alamat'     => $data['kry_alamat'],
            'kry_tlp'        => $data['kry_tlp'],
            'kry_username'   => $data['kry_username'],
            'kry_pswd'       => password_hash($data['kry_pswd'], PASSWORD_BCRYPT),
            'kry_level'      => $data['kry_level'],
            'kry_tgl_masuk'  => $data['kry_tgl_masuk'],
            'kry_tgl_keluar' => $data['kry_tgl_keluar'] ?: null
        ];

        $this->test_accounts_model->insert($insert);

        redirect('test_accounts');
    }
}
