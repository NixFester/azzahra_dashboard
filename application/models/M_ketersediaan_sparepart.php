<?php
// Model untuk ketersediaan_sparepart
class M_ketersediaan_sparepart extends CI_Model {
    public function count_menunggu() {
        $this->db->from('ketersediaan_sparepart');
        $this->db->where('status', 'menunggu');
        return $this->db->count_all_results();
    }
    public function get_all_menunggu() {
        $this->db->select('*');
        $this->db->from('ketersediaan_sparepart');
        $this->db->where('status', 'menunggu');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get();
    }
    public function update_status_sampai($id) {
        $this->db->where('id', $id);
        $this->db->update('ketersediaan_sparepart', ['status' => 'sampai']);
    }
}
