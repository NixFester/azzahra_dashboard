    <?php defined('BASEPATH') OR exit('No direct script access allowed');

    class HR extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('M_hr');
            $this->load->helper(['url', 'form']);
            $this->load->library(['session', 'form_validation']);

            // Security & Auth Check
            if ($this->session->userdata('masuk') != TRUE) {
                redirect('Auth');
            }

            $level = $this->session->userdata('level');
            if ($level != 'HR' && $level != 'Admin' && $level != 'Owner') {
                $this->session->set_flashdata('gagal', 'Anda tidak memiliki akses ke modul HR.');
                redirect('Auth');
            }

            // Clear old flashdata to prevent stale messages
            $this->session->unset_userdata('sukses');
            $this->session->unset_userdata('gagal');
        }

        public function index()
        {
            $data['title'] = 'HR Dashboard';

            // Get period from GET
            $periode = $this->input->get('periode') ?: 'hari_ini';
            $data['selected_periode'] = $periode;

            // Determine date range based on periode
            $start_date = null;
            $end_date = null;
            $today = date('Y-m-d');

            if ($periode == 'hari_ini') {
                $start_date = $end_date = $today;
            } elseif ($periode == 'minggu_ini') {
                $start_date = date('Y-m-d', strtotime('monday this week'));
                $end_date = date('Y-m-d', strtotime('sunday this week'));
            } elseif ($periode == 'bulan_ini') {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-t');
            } elseif ($periode == 'tanggal') {
                $tanggal = $this->input->get('tanggal') ?: $today;
                $start_date = $end_date = $tanggal;
                $data['selected_tanggal'] = $tanggal;
            }

            // Stats
            $absensi_data = $this->M_hr->get_absensi_by_date_range($start_date, $end_date);
            $data['stats'] = [
                'hadir' => 0,
                'izin' => 0,
                'telat' => 0,
                'alpa' => 0,
                'avg_kpi' => 0
            ];

            $data['detail_absensi'] = ['TELAT' => [], 'IZIN' => [], 'CUTI' => [], 'ALPA' => [], 'HADIR' => []];

            foreach ($absensi_data as $ab) {
                $s = strtoupper($ab['status']);
                if ($s == 'HADIR')
                    $data['stats']['hadir']++;
                elseif ($s == 'IZIN' || $s == 'CUTI')
                    $data['stats']['izin']++;
                elseif ($s == 'TELAT')
                    $data['stats']['telat']++;
                elseif ($s == 'ALPA')
                    $data['stats']['alpa']++;
                elseif ($s == 'SAKIT')
                    $data['stats']['izin']++;

                if (isset($data['detail_absensi'][$s])) {
                    $data['detail_absensi'][$s][] = $ab['nama_karyawan'];
                }
            }

            // Get total hadir from absensi table in the period
            $total_hadir = $this->M_hr->get_total_hadir_by_date_range($start_date, $end_date);
            $data['stats']['hadir'] = $total_hadir;

            // KPI Async
            $kpi_month = $this->M_hr->get_kpi_by_siklus('bulanan', date('Y-m'));
            $total_score = 0;
            $count = 0;
            foreach ($kpi_month as $k) {
                $total_score += $k['rata_rata'];
                $count++;
            }
            $data['stats']['avg_kpi'] = $count > 0 ? number_format($total_score / $count, 2) : 0;
            $data['kpi_data'] = $kpi_month;

            // Chart Data
            $data['chart_absensi'] = json_encode([
                $data['stats']['hadir'],
                $data['stats']['izin'],
                $data['stats']['telat'] + $data['stats']['alpa']
            ]);

            $this->load->view('HR/overview', $data);
        }

        // --- KARYAWAN ---

        public function karyawan()
        {
            $data['title'] = 'Data Karyawan';
            $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

            $this->load->view('HR/karyawan', $data);
        }

        public function save_karyawan()
        {
            $kry_kode = $this->input->post('kry_kode');
            $data = [
                'kry_kode' => $kry_kode,
                'kry_nama' => $this->input->post('kry_nama'),
                'kry_level' => $this->input->post('kry_level'),
                'kry_tlp' => $this->input->post('kry_tlp'),
                'kry_alamat' => $this->input->post('kry_alamat'),
                'kry_status' => $this->input->post('kry_status'),
                'kry_tgl_masuk' => $this->input->post('kry_tgl_masuk')
            ];

            if ($this->M_hr->save_karyawan($data)) {
                $this->session->set_flashdata('sukses', 'Karyawan berhasil disimpan');
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menyimpan karyawan');
            }
            redirect('HR/karyawan');
        }

        public function update_karyawan()
        {
            $kry_kode = $this->input->post('kry_kode');
            $data = [
                'kry_nama' => $this->input->post('kry_nama'),
                'kry_level' => $this->input->post('kry_level'),
                'kry_tlp' => $this->input->post('kry_tlp'),
                'kry_alamat' => $this->input->post('kry_alamat'),
                'kry_status' => $this->input->post('kry_status'),
                'kry_tgl_masuk' => $this->input->post('kry_tgl_masuk')
            ];

            if ($this->M_hr->update_karyawan($kry_kode, $data)) {
                $this->session->set_flashdata('sukses', 'Karyawan berhasil diupdate');
            } else {
                $this->session->set_flashdata('gagal', 'Gagal mengupdate karyawan');
            }
            redirect('HR/karyawan');
        }

        public function delete_karyawan($kry_kode)
        {
            if ($this->M_hr->delete_karyawan($kry_kode)) {
                $this->session->set_flashdata('sukses', 'Karyawan berhasil dihapus');
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menghapus karyawan');
            }
            redirect('HR/karyawan');
        }

        // --- PERFORMANCE SYSTEM ---

        public function save_performance_points()
        {
            $kry_kode = $this->input->post('kry_kode');
            $periode = $this->input->post('periode');
            $criteria_points = $this->input->post('criteria_points');

            $saved = 0;
            if (!empty($criteria_points)) {
                foreach ($criteria_points as $criteria_id => $points) {
                    $data = [
                        'kry_kode' => $kry_kode,
                        'periode' => $periode,
                        'criteria_id' => $criteria_id,
                        'points' => (int)$points,
                        'notes' => $this->input->post('notes_' . $criteria_id),
                        'created_by' => $this->session->userdata('nama')
                    ];

                    if ($this->M_hr->save_performance_points($data)) {
                        $saved++;
                    }
                }
            }

            if ($saved > 0) {
                $this->session->set_flashdata('sukses', "Berhasil menyimpan $saved poin performa");
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menyimpan poin performa');
            }
            redirect('HR/karyawan');
        }

        public function calculate_monthly_performance()
        {
            $periode = $this->input->post('periode') ?: date('Y-m');

            $this->M_hr->calculate_monthly_performance($periode);
            $this->session->set_flashdata('sukses', 'Rekap performa bulanan berhasil dihitung');
            redirect('HR/karyawan');
        }

        public function input_performance()
        {
            $data['title'] = 'Input Poin Performa Mingguan';
            $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

            $this->load->view('HR/input_performance', $data);
        }

        public function calculate_performance()
        {
            $data['title'] = 'Hitung Rekap Performa Bulanan';

            $this->load->view('HR/calculate_performance', $data);
        }

        public function get_performance_criteria()
        {
            $type = $this->input->get('type');
            $criteria = $this->M_hr->get_performance_criteria($type);
            echo json_encode($criteria);
        }

        public function get_monthly_performance()
        {
            $periode = $this->input->get('periode');
            $data = $this->M_hr->get_monthly_performance($periode);
            echo json_encode($data);
        }

        // --- ABSENSI ---

        public function absensi()
        {
            $data['title'] = 'Absensi Karyawan';

            // Get period from GET, default to hari_ini
            $periode = $this->input->get('periode') ?: 'hari_ini';
            $data['selected_periode'] = $periode;

            // Determine date range based on periode
            $start_date = null;
            $end_date = null;
            $today = date('Y-m-d');

            if ($periode == 'hari_ini') {
                $start_date = $end_date = $today;
            } elseif ($periode == 'minggu_ini') {
                $start_date = date('Y-m-d', strtotime('monday this week'));
                $end_date = date('Y-m-d', strtotime('sunday this week'));
            } elseif ($periode == 'bulan_ini') {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-t');
            } elseif ($periode == 'tanggal') {
                $tanggal = $this->input->get('tanggal') ?: $today;
                $start_date = $end_date = $tanggal;
                $data['selected_tanggal'] = $tanggal;
            }

            // For display, use start_date as selected_date
            $data['selected_date'] = $start_date;

            // Ambil data absensi offline for the date range
            $absensi_offline = $this->M_hr->get_absensi_from_external_db_by_range($start_date, $end_date);
            foreach ($absensi_offline as &$absensi) {
                if (empty($absensi['nama_karyawan'])) {
                    $absensi['nama_karyawan'] = 'Unknown (' . $absensi['nokartu'] . ')';
                }
            }
            $data['absensi_offline'] = $absensi_offline;

            // Ambil data absensi WFH for the date range
            $absensi_wfh = $this->M_hr->get_absensi_wfh_from_external_db_by_range($start_date, $end_date);
            foreach ($absensi_wfh as &$absensi) {
                if (empty($absensi['nama_karyawan'])) {
                    $absensi['nama_karyawan'] = 'Unknown (' . $absensi['nokartu'] . ')';
                }
            }
            $data['absensi_wfh'] = $absensi_wfh;

            $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

            $this->load->view('HR/absensi', $data);
        }

        public function save_absensi()
        {
            $id_karyawan = $this->input->post('id_karyawan');
            $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

            if (!$karyawan) {
                $this->session->set_flashdata('gagal', 'Karyawan tidak ditemukan');
                redirect('HR/absensi');
            }

            $data = [
                'tanggal' => $this->input->post('tanggal'),
                'id_karyawan' => $id_karyawan,
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level, // Menggunakan kry_level bukan kry_jabatan
                'status' => $this->input->post('status'),
                'jam_masuk' => $this->input->post('jam_masuk'),
                'jam_pulang' => $this->input->post('jam_pulang'),
                'keterangan' => $this->input->post('keterangan')
            ];

            if ($this->M_hr->save_absensi($data)) {
                $this->session->set_flashdata('sukses', 'Absensi berhasil disimpan');
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menyimpan absensi');
            }
            redirect('HR/absensi?tanggal=' . $data['tanggal']);
        }

        public function delete_absensi($id)
        {
            $this->M_hr->delete_absensi($id);
            redirect($_SERVER['HTTP_REFERER']);
        }

        // --- KPI ---

        public function kpi()
        {
            $data['title'] = 'KPI Karyawan';
            $siklus = $this->input->get('siklus') ?: 'harian';

            if ($siklus == 'harian')
                $periode = $this->input->get('periode_harian') ?: date('Y-m-d');
            elseif ($siklus == 'mingguan')
                $periode = $this->input->get('periode_mingguan') ?: date('Y') . '-W' . date('W');
            elseif ($siklus == 'bulanan')
                $periode = $this->input->get('periode_bulanan') ?: date('Y-m');
            else
                $periode = $this->input->get('periode_tahunan') ?: date('Y');

            $data['selected_siklus'] = $siklus;
            $data['selected_periode'] = $periode;
            $data['kpi_list'] = $this->M_hr->get_kpi_by_siklus($siklus, $periode);
            $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

            $this->load->view('HR/kpi', $data);
        }

        public function save_kpi()
        {
            $id_karyawan = $this->input->post('id_karyawan');
            $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

            $dis = $this->input->post('kedisiplinan');
            $kua = $this->input->post('kualitas_kerja');
            $prod = $this->input->post('produktivitas');
            $team = $this->input->post('kerja_tim');
            $avg = ($dis + $kua + $prod + $team) / 4;

            $cat = 'Kurang';
            if ($avg >= 4.5)
                $cat = 'Sangat Baik';
            elseif ($avg >= 3.5)
                $cat = 'Baik';
            elseif ($avg >= 2.5)
                $cat = 'Cukup';

            $data = [
                'id_karyawan' => $id_karyawan,
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level, // Menggunakan kry_level bukan kry_jabatan
                'status_kerja' => 'Karyawan',
                'siklus' => 'harian',
                'periode' => $this->input->post('periode_harian'),
                'kedisiplinan' => $dis,
                'kualitas_kerja' => $kua,
                'produktivitas' => $prod,
                'kerja_tim' => $team,
                'total' => ($dis + $kua + $prod + $team),
                'rata_rata' => $avg,
                'kategori' => $cat,
                'catatan' => $this->input->post('catatan')
            ];

            $this->M_hr->save_kpi($data);
            $this->session->set_flashdata('sukses', 'KPI Harian berhasil disimpan');
            redirect('HR/kpi?siklus=harian&periode_harian=' . $data['periode']);
        }

        public function delete_kpi($id)
        {
            $this->M_hr->delete_kpi($id);
            redirect($_SERVER['HTTP_REFERER']);
        }

        // --- ARSIP ---

        public function arsip()
        {
            $data['title'] = 'Arsip Dokumen';
            $data['arsip_dreame'] = $this->M_hr->get_arsip('Dreame');
            $data['arsip_laptop'] = $this->M_hr->get_arsip('Laptop');

            $this->load->view('HR/arsip', $data);
        }

        public function add_arsip_dreame()
        {
            $this->_save_arsip('Dreame');
        }
        public function add_arsip_laptop()
        {
            $this->_save_arsip('Laptop');
        }

        private function _save_arsip($tipe)
        {
            $data = [
                'tipe' => $tipe,
                'nama' => $this->input->post('nama'),
                'tanggal' => $this->input->post('tanggal'),
                'no_hp' => $this->input->post('no_hp'),
                'tipe_detail' => $this->input->post('tipe_detail'),
                'kerusakan' => $this->input->post('kerusakan'),
                'alamat' => $this->input->post('alamat')
            ];
            $this->M_hr->save_arsip($data);
            $this->session->set_flashdata('sukses', 'Arsip berhasil disimpan');
            redirect('HR/arsip');
        }

        public function edit_arsip($id)
        {
            $data = [
                'nama' => $this->input->post('nama'),
                'tanggal' => $this->input->post('tanggal'),
                'no_hp' => $this->input->post('no_hp'),
                'tipe_detail' => $this->input->post('tipe_detail'),
                'kerusakan' => $this->input->post('kerusakan'),
                'alamat' => $this->input->post('alamat')
            ];
            $this->M_hr->update_arsip($id, $data);
            $this->session->set_flashdata('sukses', 'Arsip berhasil diupdate');
            redirect('HR/arsip');
        }

        public function delete_arsip($id)
        {
            $this->M_hr->delete_arsip($id);
            redirect('HR/arsip');
        }

        // --- REKAP ---

        public function rekap()
        {
            $data['title'] = 'Rekap HR';
            $siklus_kpi = $this->input->get('siklus_kpi') ?: 'bulanan';
            $periode_kpi = $this->input->get('periode_kpi') ?: date('Y-m');

            $data['selected_periode'] = $periode_kpi;
            $data['selected_siklus'] = $siklus_kpi;
            $data['kpi_list'] = $this->M_hr->get_kpi_by_siklus($siklus_kpi, $periode_kpi);
            
            // Get laporan mingguan
            $periode_laporan = $this->input->get('periode_arsip_mingguan') ?: date('Y') . '-W' . date('W');
            $data['laporan_list'] = $this->M_hr->get_laporan_mingguan($periode_laporan);

            if (file_exists(APPPATH . 'views/HR/rekap.php')) {
                $this->load->view('HR/rekap', $data);
            } else {
                $this->load->view('HR/overview', $data);
            }
        }

        // --- LAPORAN MINGGUAN ---

        public function laporan_mingguan()
        {
            $data['title'] = 'Laporan Mingguan';
            $periode = $this->input->get('periode') ?: date('Y') . '-W' . date('W');
            
            $data['selected_periode'] = $periode;
            $data['laporan_list'] = $this->M_hr->get_laporan_mingguan($periode);
            $data['karyawan_list'] = $this->M_hr->get_all_karyawan_from_db();

            $this->load->view('HR/laporan_mingguan', $data);
        }

        public function save_laporan_mingguan()
        {
            $id_karyawan = $this->input->post('id_karyawan');
            $karyawan = $this->db->get_where('karyawan', ['kry_kode' => $id_karyawan])->row();

            if (!$karyawan) {
                $this->session->set_flashdata('gagal', 'Karyawan tidak ditemukan');
                redirect('HR/laporan_mingguan');
            }

            $data = [
                'id_karyawan' => $id_karyawan,
                'nama_karyawan' => $karyawan->kry_nama,
                'posisi' => $karyawan->kry_level,
                'periode' => $this->input->post('periode'),
                'target_mingguan' => $this->input->post('target_mingguan'),
                'tugas_dilakukan' => $this->input->post('tugas_dilakukan'),
                'hasil' => $this->input->post('hasil'),
                'kendala' => $this->input->post('kendala'),
                'solusi' => $this->input->post('solusi')
            ];

            $this->M_hr->save_laporan_mingguan($data);
            $this->session->set_flashdata('sukses', 'Laporan Mingguan berhasil disimpan');
            redirect('HR/laporan_mingguan?periode=' . $data['periode']);
        }

        public function delete_laporan_mingguan($id)
        {
            $this->M_hr->delete_laporan_mingguan($id);
            redirect($_SERVER['HTTP_REFERER']);
        }

        // --- EXPORTS ---

        public function export_absensi_csv()
        {
            $per = $this->input->get('periode');
            $tipe = $this->input->get('tipe');
            $data = $this->M_hr->get_absensi_all_by_periode($per, $tipe);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="Absensi_' . $per . '.csv"');
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['Tanggal', 'Nama', 'Jam Masuk', 'Jam Pulang', 'Status', 'Ket']);
            foreach ($data as $d) {
                fputcsv($fp, [$d['tanggal'], $d['nama_karyawan'], $d['jam_masuk'], $d['jam_pulang'], $d['status'], $d['keterangan']]);
            }
            fclose($fp);
        }

        public function export_rekap_csv()
        {
            $periode = $this->input->get('periode');
            $kpi_list = $this->M_hr->get_kpi_by_periode($periode);

            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="Rekap_KPI_' . $periode . '.csv"');

            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['ID', 'Nama', 'Posisi', 'Disiplin', 'Kualitas', 'Produktivitas', 'Kerja Tim', 'Total', 'Rata-rata', 'Kategori']);
            foreach ($kpi_list as $k) {
                fputcsv($fp, [
                    $k['id_karyawan'],
                    $k['nama_karyawan'],
                    $k['posisi'],
                    $k['kedisiplinan'],
                    $k['kualitas_kerja'],
                    $k['produktivitas'],
                    $k['kerja_tim'],
                    $k['total'],
                    $k['rata_rata'],
                    $k['kategori']
                ]);
            }
            fclose($fp);
        }

        public function export_rekap_pdf()
        {
            if (file_exists(FCPATH . 'vendor/autoload.php')) {
                require_once FCPATH . 'vendor/autoload.php';
                $periode = $this->input->get('periode') ?: date('Y-m');
                $kpi_list = $this->M_hr->get_kpi_by_periode($periode);

                $html = '<h2>Rekap KPI ' . $periode . '</h2><table border="1" cellpadding="5" cellspacing="0" width="100%"><thead><tr><th>Nama</th><th>Posisi</th><th>Nilai</th><th>Kategori</th></tr></thead><tbody>';
                foreach ($kpi_list as $k) {
                    $html .= '<tr><td>' . $k['nama_karyawan'] . '</td><td>' . $k['posisi'] . '</td><td>' . $k['rata_rata'] . '</td><td>' . $k['kategori'] . '</td></tr>';
                }
                $html .= '</tbody></table>';

                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream("Rekap_KPI_" . $periode . ".pdf");
            } else {
                $this->session->set_flashdata('gagal', 'Library Dompdf tidak ditemukan');
                redirect('HR/kpi');
            }
        }

        // --- PENCATATAN ---

        public function pencatatan()
        {
            $data['title'] = 'Pencatatan Barang';
            $filter_type = $this->input->get('filter_type') ?: 'month';
            $data['is_all'] = false;

            // Array nama bulan Indonesia
            $bulan_indonesia = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            );

            if ($filter_type == 'date') {
                $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
                $data['selected_tanggal'] = $tanggal;
                $data['selected_date'] = $tanggal; // For modal
                $data['pencatatan_list'] = $this->M_hr->get_pencatatan_by_date($tanggal);
                $data['filter_title'] = ' - ' . date('d/m/Y', strtotime($tanggal));
            } else {
                $bulan_tahun = $this->input->get('bulan_tahun');

                if (!$bulan_tahun) {
                    $bulan_tahun = null;
                    $data['is_all'] = true;
                } elseif ($bulan_tahun == 'all' || $bulan_tahun == 'semua') {
                    $bulan_tahun = null;
                    $data['is_all'] = true;
                }

                $data['selected_bulan_tahun'] = $bulan_tahun;
                $data['selected_date'] = $bulan_tahun ? ($bulan_tahun . '-01') : date('Y-m-d');
                $data['pencatatan_list'] = $this->M_hr->get_pencatatan($bulan_tahun);
                $month_num = (int)date('m', strtotime($bulan_tahun . '-01'));
                $year = date('Y', strtotime($bulan_tahun . '-01'));
                $data['filter_title'] = $data['is_all'] ? ' (Semua Data)' : ($bulan_tahun ? ' - ' . $bulan_indonesia[$month_num] . ' ' . $year : ' (Bulan Ini)');
            }

            $data['filter_type'] = $filter_type;

            $this->load->view('HR/pencatatan', $data);
        }

        public function save_pencatatan()
        {
            $nama_barang_arr = $this->input->post('nama_barang');
            $qty_arr = $this->input->post('qty');
            $harga_satuan_arr = $this->input->post('harga_satuan');
            $kategori_global = $this->input->post('kategori_global');
            $tanggal = $this->input->post('tanggal');

            // Generate unique batch_id for this transaction
            $batch_id = 'BATCH_' . time() . '_' . rand(1000, 9999);

            // Handle file upload (shared for all items in this batch)
            $gambar_path = '';
            if (!empty($_FILES['gambar']['name'])) {
                $upload_path = FCPATH . 'uploads/barang/';

                // Create directory if it doesn't exist
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                // Configure upload
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['gambar']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar_path = 'uploads/barang/' . $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('gagal', 'Gagal upload gambar: ' . $this->upload->display_errors());
                    redirect('HR/pencatatan?tanggal=' . $tanggal);
                }
            }

            $saved_count = 0;
            $errors = [];

            // Loop through each item
            for ($i = 0; $i < count($nama_barang_arr); $i++) {
                $nama_barang = trim($nama_barang_arr[$i]);
                $qty = (int)$qty_arr[$i];
                $harga_satuan = (float)$harga_satuan_arr[$i];

                // Validate required fields
                if (empty($nama_barang) || $qty <= 0 || $harga_satuan < 0) {
                    $errors[] = "Item " . ($i + 1) . ": Data tidak lengkap";
                    continue;
                }

                $total = $qty * $harga_satuan;

                $data = [
                    'batch_id' => $batch_id,
                    'nama_barang' => $nama_barang,
                    'qty' => $qty,
                    'harga_satuan' => $harga_satuan,
                    'total' => $total,
                    'tanggal' => $tanggal,
                    'gambar' => $gambar_path,
                    'kategori_global' => $kategori_global
                ];

                if ($this->M_hr->save_pencatatan($data)) {
                    $saved_count++;
                } else {
                    $errors[] = "Item " . ($i + 1) . ": Gagal menyimpan";
                }
            }

            if ($saved_count > 0) {
                $this->session->set_flashdata('sukses', "Berhasil menyimpan $saved_count item pencatatan");
            }

            if (!empty($errors)) {
                $this->session->set_flashdata('gagal', 'Beberapa item gagal disimpan: ' . implode('; ', $errors));
            }

            redirect('HR/pencatatan?tanggal=' . $tanggal);
        }

        public function delete_pencatatan($id)
        {
            // Get the record first to delete the image file
            $this->db->where('pencatatan_id', $id);
            $record = $this->db->get('pencatatan')->row();

            if ($record && $record->gambar) {
                $file_path = FCPATH . $record->gambar;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            $this->M_hr->delete_pencatatan($id);
            redirect($_SERVER['HTTP_REFERER']);
        }

        public function delete_batch_pencatatan($batch_id)
        {
            // Get all records in this batch to delete image files
            $this->db->where('batch_id', $batch_id);
            $records = $this->db->get('pencatatan')->result();

            // Delete image files
            foreach ($records as $record) {
                if ($record->gambar) {
                    $file_path = FCPATH . $record->gambar;
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            }

            // Delete all records in this batch
            $this->db->where('batch_id', $batch_id);
            $this->db->delete('pencatatan');

            $this->session->set_flashdata('sukses', 'Batch pencatatan berhasil dihapus');
            redirect($_SERVER['HTTP_REFERER']);
        }

        public function get_batch_detail()
        {
            $batch_id = $this->input->get('batch_id');

            if (!$batch_id) {
                echo '<div class="alert alert-danger">Batch ID tidak ditemukan</div>';
                return;
            }

            // Get batch data
            $this->db->where('batch_id', $batch_id);
            $batch_items = $this->db->get('pencatatan')->result_array();

            if (empty($batch_items)) {
                echo '<div class="alert alert-warning">Data batch tidak ditemukan</div>';
                return;
            }

            $batch = $batch_items[0]; // Get batch info from first item
            $total_batch = 0;
            $total_qty = 0;

            foreach ($batch_items as $item) {
                $total_batch += $item['total'];
                $total_qty += $item['qty'];
            }

            // Generate HTML content
            $html = '
            <div class="batch-detail-info" style="margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.25rem;">Batch ID</div>
                        <div style="font-weight: 600; color: #1e293b;">' . htmlspecialchars($batch_id) . '</div>
                    </div>
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.25rem;">Kategori</div>
                        <div style="font-weight: 600; color: #1e293b;">' . htmlspecialchars($batch['kategori_global']) . '</div>
                    </div>
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.25rem;">Tanggal</div>
                        <div style="font-weight: 600; color: #1e293b;">' . date('d/m/Y', strtotime($batch['tanggal'])) . '</div>
                    </div>
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.25rem;">Total Item</div>
                        <div style="font-weight: 600; color: #1e293b;">' . count($batch_items) . ' jenis</div>
                    </div>
                </div>
            </div>

            <div class="batch-items-table">
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    <i data-feather="list" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                    Detail Item Barang
                </h4>

                <div style="overflow-x: auto;">
                    <table class="table table-bordered" style="background: white;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="font-weight: 600; color: #374151; padding: 0.75rem;">No</th>
                                <th style="font-weight: 600; color: #374151; padding: 0.75rem;">Nama Barang</th>
                                <th style="font-weight: 600; color: #374151; padding: 0.75rem;">QTY</th>
                                <th style="font-weight: 600; color: #374151; padding: 0.75rem;">Harga Satuan</th>
                                <th style="font-weight: 600; color: #374151; padding: 0.75rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>';

            $no = 1;
            foreach ($batch_items as $item) {
                $html .= '
                            <tr>
                                <td style="padding: 0.75rem; text-align: center;">' . $no++ . '</td>
                                <td style="padding: 0.75rem; font-weight: 500;">' . htmlspecialchars($item['nama_barang']) . '</td>
                                <td style="padding: 0.75rem; text-align: center;">' . $item['qty'] . '</td>
                                <td style="padding: 0.75rem; text-align: right;">Rp ' . number_format($item['harga_satuan'], 0, ',', '.') . '</td>
                                <td style="padding: 0.75rem; text-align: right; font-weight: 600;">Rp ' . number_format($item['total'], 0, ',', '.') . '</td>
                            </tr>';
            }

            $html .= '
                            <tr style="background: #f8fafc; font-weight: 600;">
                                <td colspan="2" style="padding: 0.75rem; text-align: right;">TOTAL BATCH:</td>
                                <td style="padding: 0.75rem; text-align: center;">' . $total_qty . '</td>
                                <td style="padding: 0.75rem; text-align: right;">-</td>
                                <td style="padding: 0.75rem; text-align: right; color: #0041c3;">Rp ' . number_format($total_batch, 0, ',', '.') . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';

            if ($batch['gambar']) {
                $html .= '
            <div class="batch-image" style="margin-top: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    <i data-feather="image" style="width: 20px; height: 20px; margin-right: 8px;"></i>
                    Gambar Bukti
                </h4>
                <div style="text-align: center;">
                    <img src="' . base_url($batch['gambar']) . '" alt="Gambar Bukti" style="max-width: 300px; max-height: 300px; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer;" onclick="showImageModal(\'' . base_url($batch['gambar']) . '\')">
                </div>
            </div>';
            }

            echo $html;
        }

        public function edit_batch_pencatatan($batch_id)
        {
            // Get batch data
            $this->db->where('batch_id', $batch_id);
            $batch_items = $this->db->get('pencatatan')->result_array();

            if (empty($batch_items)) {
                $this->session->set_flashdata('gagal', 'Batch tidak ditemukan');
                redirect('HR/pencatatan');
            }

            $batch = $batch_items[0];
            $data['batch'] = $batch;
            $data['batch_items'] = $batch_items;
            $data['title'] = 'Edit Pencatatan Barang';
            $data['selected_date'] = $batch['tanggal'];

            $this->load->view('HR/edit_pencatatan', $data);
        }

        public function update_pencatatan()
        {
            $batch_id = $this->input->post('batch_id');
            $nama_barang_arr = $this->input->post('nama_barang');
            $qty_arr = $this->input->post('qty');
            $harga_satuan_arr = $this->input->post('harga_satuan');
            $kategori_global = $this->input->post('kategori_global');
            $tanggal = $this->input->post('tanggal');

            // Delete existing items in batch
            $this->db->where('batch_id', $batch_id);
            $this->db->delete('pencatatan');

            // Handle file upload
            $gambar_path = $this->input->post('existing_gambar'); // Keep existing if no new upload
            if (!empty($_FILES['gambar']['name'])) {
                $upload_path = FCPATH . 'uploads/barang/';

                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['gambar']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar_path = 'uploads/barang/' . $upload_data['file_name'];

                    // Delete old image if exists
                    $old_gambar = $this->input->post('existing_gambar');
                    if ($old_gambar && $old_gambar != $gambar_path && file_exists(FCPATH . $old_gambar)) {
                        unlink(FCPATH . $old_gambar);
                    }
                } else {
                    $this->session->set_flashdata('gagal', 'Gagal upload gambar: ' . $this->upload->display_errors());
                    redirect('HR/edit_batch_pencatatan/' . $batch_id);
                }
            }

            $saved_count = 0;
            $errors = [];

            for ($i = 0; $i < count($nama_barang_arr); $i++) {
                $nama_barang = trim($nama_barang_arr[$i]);
                $qty = (int)$qty_arr[$i];
                $harga_satuan = (float)$harga_satuan_arr[$i];

                if (empty($nama_barang) || $qty <= 0 || $harga_satuan < 0) {
                    $errors[] = "Item " . ($i + 1) . ": Data tidak lengkap";
                    continue;
                }

                $total = $qty * $harga_satuan;

                $data = [
                    'batch_id' => $batch_id,
                    'nama_barang' => $nama_barang,
                    'qty' => $qty,
                    'harga_satuan' => $harga_satuan,
                    'total' => $total,
                    'tanggal' => $tanggal,
                    'gambar' => $gambar_path,
                    'kategori_global' => $kategori_global
                ];

                if ($this->M_hr->save_pencatatan($data)) {
                    $saved_count++;
                } else {
                    $errors[] = "Item " . ($i + 1) . ": Gagal menyimpan";
                }
            }

            if ($saved_count > 0) {
                $this->session->set_flashdata('sukses', "Berhasil update $saved_count item pencatatan");
            }

            if (!empty($errors)) {
                $this->session->set_flashdata('gagal', 'Beberapa item gagal diupdate: ' . implode('; ', $errors));
            }

            redirect('HR/pencatatan');
        }

        public function import_pencatatan()
        {
            $this->load->library('excel');

            if (!empty($_FILES['file_excel']['name'])) {
                $file = $_FILES['file_excel']['tmp_name'];
                $file_name = $_FILES['file_excel']['name'];

                try {
                    // Check file extension
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    // For XLSX files, check if ZipArchive is available
                    if ($file_ext === 'xlsx' && !class_exists('ZipArchive')) {
                        $this->session->set_flashdata('gagal', 'File XLSX memerlukan ekstensi ZipArchive yang tidak tersedia. Silakan gunakan file XLS atau hubungi administrator untuk mengaktifkan ekstensi ZipArchive.');
                        redirect('HR/pencatatan');
                    }

                    // Load Excel file
                    $objPHPExcel = PHPExcel_IOFactory::load($file);
                    $sheet = $objPHPExcel->getActiveSheet();
                    $highestRow = $sheet->getHighestRow();

                    $imported = 0;
                    $errors = [];
                    $batch_data = []; // Group data by date

                    // Start from row 2 (assuming row 1 is header)
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $tanggal = trim($sheet->getCell('A' . $row)->getValue() ?? '');
                        $nama_barang = trim($sheet->getCell('B' . $row)->getValue() ?? '');
                        $qty = trim($sheet->getCell('C' . $row)->getValue() ?? '');
                        $kategori = trim($sheet->getCell('D' . $row)->getValue() ?? '');
                        $harga_satuan = trim($sheet->getCell('E' . $row)->getValue() ?? '');

                        // Clean Rupiah format from harga_satuan: remove "Rp" and dots
                        $harga_satuan = str_replace(['Rp', '.'], '', $harga_satuan);

                        // Skip empty rows
                        if (empty($nama_barang) && empty($tanggal)) continue;

                        // Validate required fields
                        if (empty($tanggal) || empty($nama_barang) || empty($qty) || empty($kategori) || $harga_satuan === '') {
                            $errors[] = "Row $row: Missing required fields";
                            continue;
                        }

                        // Convert date if needed
                        if (is_numeric($tanggal)) {
                            // Excel date format - convert from Excel serial date
                            $unixDate = ($tanggal - 25569) * 86400; // 25569 is days between 1900-01-01 and 1970-01-01
                            $tanggal_formatted = date('Y-m-d', $unixDate);
                        } else {
                            // Parse as DD/MM/YYYY format (strict format as specified)
                            $date_parts = explode('/', $tanggal);
                            if (count($date_parts) !== 3) {
                                $errors[] = "Row $row: Invalid date format. Expected DD/MM/YYYY (e.g., 06/01/2026)";
                                continue;
                            }

                            list($day, $month, $year) = $date_parts;
                            $day = (int)$day;
                            $month = (int)$month;
                            $year = (int)$year;

                            // Validate date components
                            if ($day < 1 || $day > 31 || $month < 1 || $month > 12 || $year < 1900 || $year > 2100) {
                                $errors[] = "Row $row: Invalid date values. Day: 1-31, Month: 1-12, Year: 1900-2100";
                                continue;
                            }

                            // Check if date is valid (e.g., not 31/02/2026)
                            if (!checkdate($month, $day, $year)) {
                                $errors[] = "Row $row: Invalid date combination (e.g., 31/02 is invalid)";
                                continue;
                            }

                            $tanggal_formatted = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        }

                        // Calculate total
                        $total = (float)$qty * (float)$harga_satuan;

                        // Group by date
                        if (!isset($batch_data[$tanggal_formatted])) {
                            $batch_data[$tanggal_formatted] = [
                                'tanggal' => $tanggal_formatted,
                                'kategori_global' => $kategori,
                                'items' => []
                            ];
                        }

                        // Add item to batch
                        $batch_data[$tanggal_formatted]['items'][] = [
                            'nama_barang' => $nama_barang,
                            'qty' => (int)$qty,
                            'harga_satuan' => (float)$harga_satuan,
                            'total' => $total
                        ];
                    }

                    // Process each batch
                    foreach ($batch_data as $tanggal => $batch) {
                        // Generate batch_id for this date
                        $batch_id = 'IMPORT_' . time() . '_' . rand(1000, 9999) . '_' . str_replace('-', '', $tanggal);

                        foreach ($batch['items'] as $item) {
                            $data = [
                                'batch_id' => $batch_id,
                                'nama_barang' => $item['nama_barang'],
                                'qty' => $item['qty'],
                                'harga_satuan' => $item['harga_satuan'],
                                'total' => $item['total'],
                                'tanggal' => $batch['tanggal'],
                                'gambar' => '', // Not imported from Excel
                                'kategori_global' => $batch['kategori_global']
                            ];

                            if ($this->M_hr->save_pencatatan($data)) {
                                $imported++;
                            } else {
                                $errors[] = "Failed to save item: " . $item['nama_barang'];
                            }
                        }
                    }

                    if ($imported > 0) {
                        $this->session->set_flashdata('sukses', "Berhasil import $imported data pencatatan");
                    }

                    if (!empty($errors)) {
                        $this->session->set_flashdata('gagal', 'Beberapa data gagal diimport: ' . implode('; ', array_slice($errors, 0, 5)));
                    }

                } catch (Exception $e) {
                    $error_message = $e->getMessage();
                    if (strpos($error_message, 'ZipArchive') !== false) {
                        $this->session->set_flashdata('gagal', 'File XLSX memerlukan ekstensi ZipArchive. Silakan gunakan file XLS atau hubungi administrator.');
                    } else {
                        $this->session->set_flashdata('gagal', 'Error processing file: ' . $error_message);
                    }
                }
            } else {
                $this->session->set_flashdata('gagal', 'File Excel tidak ditemukan');
            }

            redirect('HR/pencatatan');
        }

        public function download_template_pencatatan()
        {
            $this->load->library('excel');

            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);

            // Set headers
            $object->getActiveSheet()->setCellValue('A1', 'Tanggal');
            $object->getActiveSheet()->setCellValue('B1', 'Nama Barang / Keperluan');
            $object->getActiveSheet()->setCellValue('C1', 'QTY');
            $object->getActiveSheet()->setCellValue('D1', 'Kategori');
            $object->getActiveSheet()->setCellValue('E1', 'Harga Satuan');

            // Sample data - Case 1: Multiple items on same date (will create 1 batch)
            $object->getActiveSheet()->setCellValue('A2', '18/11/2025');
            $object->getActiveSheet()->setCellValue('B2', 'Snowman Spidol');
            $object->getActiveSheet()->setCellValue('C2', '1');
            $object->getActiveSheet()->setCellValue('D2', 'ATK');
            $object->getActiveSheet()->setCellValue('E2', '10000');

            $object->getActiveSheet()->setCellValue('A3', '18/11/2025'); // Same date - will be grouped in same batch
            $object->getActiveSheet()->setCellValue('B3', 'Keyboard Wireless Logitech');
            $object->getActiveSheet()->setCellValue('C3', '2');
            $object->getActiveSheet()->setCellValue('D3', 'ATK');
            $object->getActiveSheet()->setCellValue('E3', '150000');

            $object->getActiveSheet()->setCellValue('A4', '18/11/2025'); // Same date - will be grouped in same batch
            $object->getActiveSheet()->setCellValue('B4', 'Mouse Wireless');
            $object->getActiveSheet()->setCellValue('C4', '1');
            $object->getActiveSheet()->setCellValue('D4', 'ATK');
            $object->getActiveSheet()->setCellValue('E4', '75000');

            // Case 2: Different date (will create separate batch)
            $object->getActiveSheet()->setCellValue('A5', '19/11/2025');
            $object->getActiveSheet()->setCellValue('B5', 'Printer Epson L120');
            $object->getActiveSheet()->setCellValue('C5', '1');
            $object->getActiveSheet()->setCellValue('D5', 'Elektronik');
            $object->getActiveSheet()->setCellValue('E5', '1200000');

            $object->getActiveSheet()->setCellValue('A6', '19/11/2025'); // Same date - grouped in batch 2
            $object->getActiveSheet()->setCellValue('B6', 'Kertas A4 80gsm');
            $object->getActiveSheet()->setCellValue('C6', '5');
            $object->getActiveSheet()->setCellValue('D6', 'Elektronik');
            $object->getActiveSheet()->setCellValue('E6', '50000');

            // Another different date (will create separate batch)
            $object->getActiveSheet()->setCellValue('A7', '20/11/2025');
            $object->getActiveSheet()->setCellValue('B7', 'Snack Box Meeting');
            $object->getActiveSheet()->setCellValue('C7', '10');
            $object->getActiveSheet()->setCellValue('D7', 'Konsumsi');
            $object->getActiveSheet()->setCellValue('E7', '25000');

            // Style headers
            $style_col = array(
                'font' => array('bold' => true),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                ),
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                )
            );

            $object->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);

            // Add borders to sample data rows
            for ($row = 2; $row <= 6; $row++) {
                $object->getActiveSheet()->getStyle('A'.$row)->applyFromArray([
                    'borders' => [
                        'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
                    ]
                ]);
                $object->getActiveSheet()->getStyle('B'.$row)->applyFromArray([
                    'borders' => [
                        'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
                    ]
                ]);
                $object->getActiveSheet()->getStyle('C'.$row)->applyFromArray([
                    'borders' => [
                        'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
                    ]
                ]);
                $object->getActiveSheet()->getStyle('D'.$row)->applyFromArray([
                    'borders' => [
                        'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
                    ]
                ]);
                $object->getActiveSheet()->getStyle('E'.$row)->applyFromArray([
                    'borders' => [
                        'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                        'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
                    ]
                ]);
            }

            // Auto size columns
            foreach(range('A','E') as $columnID) {
                $object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Use Excel5 (XLS) format for better compatibility
            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Template_Pencatatan.xls"');
            $object_writer->save('php://output');
        }

        // --- INTERVIEW ---

        public function interview()
        {
            $data['title'] = 'Interview Kandidat';
            $bulan_tahun = $this->input->get('bulan_tahun');

            $data['selected_bulan_tahun'] = $bulan_tahun;
            $data['interview_list'] = $this->M_hr->get_interview($bulan_tahun);

            $this->load->view('HR/interview', $data);
        }

        public function save_interview()
        {
            $nama_kandidat = $this->input->post('nama_kandidat');
            $domisili = $this->input->post('domisili');
            $keterangan_domisili = $this->input->post('keterangan_domisili');
            $no_hp = $this->input->post('no_hp');
            $divisi = $this->input->post('divisi');
            $status = $this->input->post('status');
            $keterangan_konfirmasi = $this->input->post('keterangan_konfirmasi');

            $data = [
                'nama_kandidat' => $nama_kandidat,
                'domisili' => $domisili,
                'keterangan_domisili' => $keterangan_domisili,
                'no_hp' => $no_hp,
                'divisi' => $divisi,
                'status' => $status,
                'keterangan_konfirmasi' => $keterangan_konfirmasi
            ];

            if ($this->M_hr->save_interview($data)) {
                $this->session->set_flashdata('sukses', 'Interview berhasil disimpan');
            } else {
                $this->session->set_flashdata('gagal', 'Gagal menyimpan interview');
            }
            redirect('HR/interview');
        }

        public function delete_interview($id)
        {
            $this->M_hr->delete_interview($id);
            redirect($_SERVER['HTTP_REFERER']);
        }

        public function import_interview()
        {
            $this->load->library('excel');

            if (!empty($_FILES['file_excel']['name'])) {
                $file = $_FILES['file_excel']['tmp_name'];
                $file_name = $_FILES['file_excel']['name'];

                try {
                    // Check file extension
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    // For XLSX files, check if ZipArchive is available
                    if ($file_ext === 'xlsx' && !class_exists('ZipArchive')) {
                        $this->session->set_flashdata('gagal', 'File XLSX memerlukan ekstensi ZipArchive yang tidak tersedia. Silakan gunakan file XLS atau hubungi administrator untuk mengaktifkan ekstensi ZipArchive.');
                        redirect('HR/interview');
                    }

                    // Load Excel file
                    $objPHPExcel = PHPExcel_IOFactory::load($file);
                    $sheet = $objPHPExcel->getActiveSheet();
                    $highestRow = $sheet->getHighestRow();

                    $imported = 0;
                    $errors = [];

                    // Start from row 2 (assuming row 1 is header)
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $nama_kandidat = trim($sheet->getCell('A' . $row)->getValue());
                        $domisili = trim($sheet->getCell('B' . $row)->getValue());
                        $no_hp = trim($sheet->getCell('C' . $row)->getValue());
                        $divisi = trim($sheet->getCell('D' . $row)->getValue());
                        $status = trim($sheet->getCell('E' . $row)->getValue());
                        $keterangan_konfirmasi = trim($sheet->getCell('F' . $row)->getValue());
                        $keterangan_domisili = trim($sheet->getCell('G' . $row)->getValue());

                        // Skip empty rows
                        if (empty($nama_kandidat)) continue;

                        // Only nama_kandidat is required, others can be empty
                        // Set defaults
                        if (empty($status)) $status = 'Belum dihubungi';

                        $data = [
                            'nama_kandidat' => $nama_kandidat,
                            'domisili' => $domisili ?: '',
                            'keterangan_domisili' => $keterangan_domisili ?: '',
                            'no_hp' => $no_hp ?: '',
                            'divisi' => $divisi ?: '',
                            'status' => $status,
                            'keterangan_konfirmasi' => $keterangan_konfirmasi ?: ''
                        ];

                        if ($this->M_hr->save_interview($data)) {
                            $imported++;
                        } else {
                            $errors[] = "Row $row: Failed to save";
                        }
                    }

                    if ($imported > 0) {
                        $this->session->set_flashdata('sukses', "Berhasil import $imported data interview");
                    }

                    if (!empty($errors)) {
                        $this->session->set_flashdata('gagal', 'Beberapa data gagal diimport: ' . implode('; ', array_slice($errors, 0, 5)));
                    }

                } catch (Exception $e) {
                    $error_message = $e->getMessage();
                    if (strpos($error_message, 'ZipArchive') !== false) {
                        $this->session->set_flashdata('gagal', 'File XLSX memerlukan ekstensi ZipArchive. Silakan gunakan file XLS atau hubungi administrator.');
                    } else {
                        $this->session->set_flashdata('gagal', 'Error processing file: ' . $error_message);
                    }
                }
            } else {
                $this->session->set_flashdata('gagal', 'File Excel tidak ditemukan');
            }

            redirect('HR/interview');
        }

        public function download_template_interview()
        {
            $this->load->library('excel');

            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);

            // Set headers
            $object->getActiveSheet()->setCellValue('A1', 'Nama Kandidat');
            $object->getActiveSheet()->setCellValue('B1', 'Domisili');
            $object->getActiveSheet()->setCellValue('C1', 'No HP');
            $object->getActiveSheet()->setCellValue('D1', 'Role / Divisi');
            $object->getActiveSheet()->setCellValue('E1', 'Status');
            $object->getActiveSheet()->setCellValue('F1', 'Keterangan');
            $object->getActiveSheet()->setCellValue('G1', 'Keterangan Domisili');

            // Sample data
            $object->getActiveSheet()->setCellValue('A2', 'Fakhrul Khusaeni');
            $object->getActiveSheet()->setCellValue('B2', 'Tegal');
            $object->getActiveSheet()->setCellValue('C2', '0895704307742');
            $object->getActiveSheet()->setCellValue('D2', 'Programmer / IT');
            $object->getActiveSheet()->setCellValue('E2', 'Sudah dihubungi');
            $object->getActiveSheet()->setCellValue('F2', 'tidak ada konfirmasi');
            $object->getActiveSheet()->setCellValue('G2', 'Terjangkau');

            // Style headers
            $style_col = array(
                'font' => array('bold' => true),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                ),
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                )
            );

            $object->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
            $object->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);

            // Auto size columns
            foreach(range('A','G') as $columnID) {
                $object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Clear output buffer
            ob_clean();

            // Use Excel5 (XLS) format for better compatibility
            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Template_Interview.xls"');
            header('Cache-Control: max-age=0');
            $object_writer->save('php://output');
            exit();
        }

        // --- CERTIFICATE GENERATOR ---

        public function certificate_generator()
        {
            $data['title'] = 'Generator Sertifikat';
            $this->load->view('HR/certificate_generator', $data);
        }
    }