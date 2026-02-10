<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_auth');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->load->view('Auth/login');
	}
	function login()
	{
		$username = htmlspecialchars($this->input->post('username',TRUE), ENT_QUOTES);
		$pswd	  = htmlspecialchars($this->input->post('pswd',TRUE), ENT_QUOTES);

		$login = $this->db->get_where('karyawan', ['kry_username' => $username])->row_array();

		if ($login) {
			$this->session->set_userdata('masuk',TRUE);
			if (password_verify($pswd, $login['kry_pswd'])) {
				$data = array(
					'kode'  => $login['kry_kode'] ,
					'nama'  => $login['kry_nama'] ,
					'level' => $login['kry_level'] 
					 );
				if ($login['kry_level'] == 'Admin') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Admin','refresh');
				}elseif ($login['kry_level'] == 'Kasir') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Kasir','refresh');
				}elseif ($login['kry_level'] == 'Customer Service') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Service','refresh');
				}elseif ($login['kry_level'] == 'Teknisi') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('Teknisi','refresh');
				}elseif ($login['kry_level'] == 'HR') {
					$this->session->set_userdata($data);
					$this->session->set_flashdata('login', $login['kry_nama']);
					$this->session->set_userdata('just_logged_in', TRUE); // Flag for first login
					redirect('HR','refresh');
				}

			} else {
				$this->session->set_flashdata('gagal', 'Password yang dimasukan salah');
				redirect('Auth','refresh');
			}
			
		} else {
			$this->session->set_flashdata('gagal', 'Username tidak ditemukan');
			redirect('Auth','refresh');
		}
		
	}
	function logout()
	{
		$this->session->sess_destroy();
		redirect('Auth','refresh');
	}
	function reset()
	{
		$this->load->view('Auth/reset');
	}
	function change_pswd()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required', array('required' => 'Username belum di isi' ));
		$this->form_validation->set_rules('pswd', 'Pasword', 'trim|required', array('required' => 'Pasword belum di isi' ));
		$this->form_validation->set_rules('con_pswd', 'Confirmasi Pasword', 'trim|required|matches[pswd]', array('required' => 'Pasword belum di isi','matches' => 'Pasword tidak sama' ));
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('Auth/reset');
		} else {
			
			$username = htmlspecialchars($this->input->post('username',TRUE), ENT_QUOTES);
			$pswd	  = htmlspecialchars($this->input->post('pswd',TRUE), ENT_QUOTES);

			$kode = $this->session->userdata('kode');

			$cek  = $this->db->get_where('karyawan', ['kry_kode' => $kode])->row_array();

			if ($cek) {
				$update = array(
						'kry_username' => $username,
						'kry_pswd' 	   => password_hash($pswd, PASSWORD_DEFAULT), 
					);
				$this->M_auth->change_pswd($kode,$update);

				$this->session->sess_destroy();
				$this->session->set_flashdata('sukses', 'DI PERBAHARI SILAHKAN LOGIN');
				redirect('Auth','refresh');
			} else {
				$this->session->sess_destroy();
				$this->session->set_flashdata('gagal', 'Username tidak ditemukan');
				redirect('Auth','refresh');
			}
			
		}
		
	}

	function tes_buat_akun()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			// Set validation rules
			$this->form_validation->set_rules('kry_kode', 'Kode Karyawan', 'trim|required', array('required' => 'Kode karyawan belum diisi'));
			$this->form_validation->set_rules('kry_nik', 'NIK', 'trim|required', array('required' => 'NIK belum diisi'));
			$this->form_validation->set_rules('kry_nama', 'Nama', 'trim|required', array('required' => 'Nama belum diisi'));
			$this->form_validation->set_rules('kry_tempat', 'Tempat Lahir', 'trim|required', array('required' => 'Tempat lahir belum diisi'));
			$this->form_validation->set_rules('kry_tgl_lahir', 'Tanggal Lahir', 'trim|required', array('required' => 'Tanggal lahir belum diisi'));
			$this->form_validation->set_rules('kry_alamat', 'Alamat', 'trim|required', array('required' => 'Alamat belum diisi'));
			$this->form_validation->set_rules('kry_tlp', 'Telepon', 'trim|required', array('required' => 'Telepon belum diisi'));
			$this->form_validation->set_rules('kry_username', 'Username', 'trim|required|is_unique[karyawan.kry_username]', array('required' => 'Username belum diisi', 'is_unique' => 'Username sudah digunakan'));
			$this->form_validation->set_rules('kry_pswd', 'Password', 'trim|required|min_length[6]', array('required' => 'Password belum diisi', 'min_length' => 'Password minimal 6 karakter'));
			$this->form_validation->set_rules('con_pswd', 'Konfirmasi Password', 'trim|required|matches[kry_pswd]', array('required' => 'Konfirmasi password belum diisi', 'matches' => 'Password tidak sama'));
			$this->form_validation->set_rules('kry_level', 'Level', 'trim|required', array('required' => 'Level belum dipilih'));
			$this->form_validation->set_rules('kry_tgl_masuk', 'Tanggal Masuk', 'trim|required', array('required' => 'Tanggal masuk belum diisi'));

			if ($this->form_validation->run() == FALSE) {
				$data['error'] = 'Validasi gagal';
				$this->load->view('Auth/tes_buat_akun', $data);
			} else {
				$data = array(
					'kry_kode' => htmlspecialchars($this->input->post('kry_kode', TRUE), ENT_QUOTES),
					'kry_nik' => htmlspecialchars($this->input->post('kry_nik', TRUE), ENT_QUOTES),
					'kry_nama' => htmlspecialchars($this->input->post('kry_nama', TRUE), ENT_QUOTES),
					'kry_tempat' => htmlspecialchars($this->input->post('kry_tempat', TRUE), ENT_QUOTES),
					'kry_tgl_lahir' => htmlspecialchars($this->input->post('kry_tgl_lahir', TRUE), ENT_QUOTES),
					'kry_alamat' => htmlspecialchars($this->input->post('kry_alamat', TRUE), ENT_QUOTES),
					'kry_tlp' => htmlspecialchars($this->input->post('kry_tlp', TRUE), ENT_QUOTES),
					'kry_username' => htmlspecialchars($this->input->post('kry_username', TRUE), ENT_QUOTES),
					'kry_pswd' => password_hash($this->input->post('kry_pswd', TRUE), PASSWORD_DEFAULT),
					'kry_level' => htmlspecialchars($this->input->post('kry_level', TRUE), ENT_QUOTES),
					'kry_tgl_masuk' => htmlspecialchars($this->input->post('kry_tgl_masuk', TRUE), ENT_QUOTES),
					'kry_tgl_keluar' => ($this->input->post('kry_tgl_keluar') ? htmlspecialchars($this->input->post('kry_tgl_keluar', TRUE), ENT_QUOTES) : NULL)
				);

				if ($this->db->insert('karyawan', $data)) {
					$this->session->set_flashdata('sukses', 'Akun baru berhasil dibuat! Silahkan login.');
					redirect('Auth', 'refresh');
				} else {
					$data['error'] = 'Gagal membuat akun baru';
					$this->load->view('Auth/tes_buat_akun', $data);
				}
			}
		} else {
			$this->load->view('Auth/tes_buat_akun');
		}
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */