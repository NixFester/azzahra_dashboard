<?php
// Controller untuk tab order CS (ketersediaan sparepart)
class Ketersediaan_sparepart extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('M_ketersediaan_sparepart');
        if($this->session->userdata('masuk') != TRUE){
            $url=base_url();
            redirect($url);
        }
    }
    public function index() {
        $data = array(
            'title' => 'Ketersediaan Sparepart',
            'spareparts' => $this->M_ketersediaan_sparepart->get_all_menunggu()
        );
        $this->load->view('Admin/ketersediaan_sparepart', $data);
    }
    public function barang_sampai($id) {
        // Ambil data sparepart
        $sparepart = $this->db->get_where('ketersediaan_sparepart', ['id' => $id])->row();
        if ($sparepart) {
            // Update status sparepart
            $this->M_ketersediaan_sparepart->update_status_sampai($id);
            // Update status transaksi ke 'Diproses' agar muncul di /admin/konfirmasi
            $this->db->where('trans_kode', $sparepart->trans_kode);
            $this->db->update('transaksi', ['trans_status' => 'Diproses']);
        }
        $this->session->set_flashdata('sukses', 'Barang telah sampai! Order kembali ke konfirmasi.');
        redirect('Ketersediaan_sparepart');
    }
}
