<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test_accounts_model extends CI_Model {

    private $table = 'karyawan';

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }
}
