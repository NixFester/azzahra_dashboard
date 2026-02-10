<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hr extends CI_Model
{

    private $cache_path;
    private $db_absensi;

    public function __construct()
    {
        parent::__construct();
        $this->cache_path = APPPATH . 'cache/hr_data/';

        // Ensure cache directory exists
        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0777, true);
        }

        // Load database absensi
        $this->db_absensi = $this->load->database('absensi', TRUE);

        // Load PHPExcel Library (now using PhpSpreadsheet wrapper)
        // require_once APPPATH . 'libraries/PHPExcel.php';
        // Commented out to avoid errors if not present, assuming direct DB operations mostly
    }

    // --- ABSENSI METHODS ---

    public function save_absensi($data)
    {
        // Ensure table exists
        if (!$this->db->table_exists('absensi')) {
            return false;
        }

        // Check if record exists (Single date per employee)
        $existing = $this->db->get_where('absensi', [
            'tanggal' => $data['tanggal'],
            'id_karyawan' => $data['id_karyawan']
        ])->row();

        if ($existing) {
            // Update existing record
            $this->db->where('tanggal', $data['tanggal']);
            $this->db->where('id_karyawan', $data['id_karyawan']);
            return $this->db->update('absensi', $data);
        } else {
            // Insert new record
            return $this->db->insert('absensi', $data);
        }
    }

    public function delete_absensi($id)
    {
        if (!$this->db->table_exists('absensi'))
            return false;
        $this->db->where('absensi_id', $id);
        return $this->db->delete('absensi');
    }

    public function get_absensi_by_date($tanggal)
    {
        if (!$this->db->table_exists('absensi'))
            return [];
        $this->db->where('tanggal', $tanggal);
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('absensi')->result_array();
    }

    public function get_absensi_by_date_range($start_date, $end_date)
    {
        if (!$this->db->table_exists('absensi'))
            return [];
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('absensi')->result_array();
    }

    public function get_total_hadir()
    {
        // Count all records from external absensi DB (all records represent hadir)
        return $this->db_absensi->count_all_results('absensi');
    }

    public function get_total_hadir_by_date_range($start_date, $end_date)
    {
        if ($start_date && $end_date) {
            $this->db_absensi->where('tanggal >=', $start_date);
            $this->db_absensi->where('tanggal <=', $end_date);
        }
        return $this->db_absensi->count_all_results('absensi');
    }

    public function get_absensi_all_by_periode($periode, $tipe = 'bulanan')
    {
        if (!$this->db->table_exists('absensi'))
            return [];

        if ($tipe === 'harian') {
            $this->db->where('tanggal', $periode);
        } elseif ($tipe === 'mingguan') {
            // Parse week format YYYY-W## or W##-YYYY
            if (strpos($periode, 'W') !== false) {
                $parts = explode('-W', $periode);
                if (count($parts) == 2) {
                    $year = $parts[0];
                    $week = $parts[1];
                } else {
                    $parts = explode('-', $periode);
                    // Try to guess if W01-2025 format
                    if (strpos($parts[0], 'W') !== false) {
                        $week = intval(substr($parts[0], 1));
                        $year = $parts[1];
                    } else {
                        // Fallback
                        $year = date('Y');
                        $week = date('W');
                    }
                }

                $dto = new DateTime();
                $dto->setISODate($year, $week);
                $start = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $end = $dto->format('Y-m-d');

                $this->db->where('tanggal >=', $start);
                $this->db->where('tanggal <=', $end);
            }
        } else {
            // Bulanan
            $this->db->like('tanggal', $periode, 'after');
        }

        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('absensi')->result_array();
    }

    // --- ABSENSI FROM EXTERNAL DATABASE ---

    public function get_absensi_from_external_db($tanggal = null)
    {
        $this->db_absensi->select('absensi.*, karyawan.nama as nama_karyawan, karyawan.alamat as alamat');
        $this->db_absensi->from('absensi');
        $this->db_absensi->join('karyawan', 'absensi.nokartu = karyawan.nokartu', 'left');

        if ($tanggal) {
            $this->db_absensi->where('absensi.tanggal', $tanggal);
        }
        $this->db_absensi->order_by('absensi.tanggal', 'DESC');
        $this->db_absensi->order_by('absensi.jam_masuk', 'ASC');
        return $this->db_absensi->get()->result_array();
    }

    public function get_absensi_from_external_db_by_range($start_date, $end_date)
    {
        $this->db_absensi->select('absensi.*, karyawan.nama as nama_karyawan, karyawan.alamat as alamat');
        $this->db_absensi->from('absensi');
        $this->db_absensi->join('karyawan', 'absensi.nokartu = karyawan.nokartu', 'left');

        if ($start_date && $end_date) {
            $this->db_absensi->where('absensi.tanggal >=', $start_date);
            $this->db_absensi->where('absensi.tanggal <=', $end_date);
        }
        $this->db_absensi->order_by('absensi.tanggal', 'DESC');
        $this->db_absensi->order_by('absensi.jam_masuk', 'ASC');
        return $this->db_absensi->get()->result_array();
    }

    public function get_absensi_wfh_from_external_db($tanggal = null)
    {
        $this->db_absensi->select('absensi_wfh.*, karyawan.nama as nama_karyawan, karyawan.alamat as alamat');
        $this->db_absensi->from('absensi_wfh');
        $this->db_absensi->join('karyawan', 'absensi_wfh.nokartu = karyawan.nokartu', 'left');

        if ($tanggal) {
            $this->db_absensi->where('absensi_wfh.tanggal', $tanggal);
        }
        $this->db_absensi->order_by('absensi_wfh.tanggal', 'DESC');
        $this->db_absensi->order_by('absensi_wfh.jam_masuk', 'ASC');
        return $this->db_absensi->get()->result_array();
    }

    public function get_absensi_wfh_from_external_db_by_range($start_date, $end_date)
    {
        $this->db_absensi->select('absensi_wfh.*, karyawan.nama as nama_karyawan, karyawan.alamat as alamat');
        $this->db_absensi->from('absensi_wfh');
        $this->db_absensi->join('karyawan', 'absensi_wfh.nokartu = karyawan.nokartu', 'left');

        if ($start_date && $end_date) {
            $this->db_absensi->where('absensi_wfh.tanggal >=', $start_date);
            $this->db_absensi->where('absensi_wfh.tanggal <=', $end_date);
        }
        $this->db_absensi->order_by('absensi_wfh.tanggal', 'DESC');
        $this->db_absensi->order_by('absensi_wfh.jam_masuk', 'ASC');
        return $this->db_absensi->get()->result_array();
    }


    // --- KPI METHODS ---

    public function migrate_kpi_file()
    {
        return true;
    }

    public function save_kpi($data)
    {
        if (!$this->db->table_exists('kpi'))
            return false;

        // Check if record exists
        $this->db->where('id_karyawan', $data['id_karyawan']);
        $this->db->where('periode', $data['periode']);
        $this->db->where('siklus', isset($data['siklus']) ? $data['siklus'] : 'bulanan');
        $existing = $this->db->get('kpi')->row();

        if ($existing) {
            $this->db->where('kpi_id', $existing->kpi_id);
            return $this->db->update('kpi', $data);
        } else {
            return $this->db->insert('kpi', $data);
        }
    }

    public function update_kpi_by_id($id, $data)
    {
        if (!$this->db->table_exists('kpi'))
            return false;
        $this->db->where('kpi_id', $id);
        return $this->db->update('kpi', $data);
    }

    public function delete_kpi($id)
    {
        if (!$this->db->table_exists('kpi'))
            return false;
        $this->db->where('kpi_id', $id);
        return $this->db->delete('kpi');
    }

    public function get_kpi_by_id($id)
    {
        return $this->db->get_where('kpi', ['kpi_id' => $id])->row_array();
    }

    public function get_kpi_by_siklus($siklus, $periode)
    {
        if (!$this->db->table_exists('kpi'))
            return [];

        switch ($siklus) {
            case 'harian':
                $this->db->where('siklus', 'harian');
                $this->db->where('periode', $periode);
                $this->db->order_by('nama_karyawan', 'ASC');
                return $this->db->get('kpi')->result_array();

            case 'mingguan':
                return $this->get_aggregated_kpi($periode, 'mingguan');
            case 'bulanan':
                return $this->get_aggregated_kpi($periode, 'bulanan');
            case 'tahunan':
                return $this->get_aggregated_kpi($periode, 'tahunan');
            default:
                return [];
        }
    }

    private function get_aggregated_kpi($periode, $type)
    {
        // Smart Aggregation Logic
        $this->db->select('
            id_karyawan,
            nama_karyawan,
            posisi,
            status_kerja,
            ROUND(AVG(kedisiplinan), 1) as kedisiplinan,
            ROUND(AVG(kualitas_kerja), 1) as kualitas_kerja,
            ROUND(AVG(produktivitas), 1) as produktivitas,
            ROUND(AVG(kerja_tim), 1) as kerja_tim,
            ROUND(AVG(total), 1) as total,
            ROUND(AVG(rata_rata), 2) as rata_rata,
            CASE 
                WHEN AVG(rata_rata) >= 4.5 THEN "Sangat Baik"
                WHEN AVG(rata_rata) >= 3.5 THEN "Baik"
                WHEN AVG(rata_rata) >= 2.5 THEN "Cukup"
                ELSE "Kurang"
            END as kategori,
            GROUP_CONCAT(catatan SEPARATOR "; ") as catatan
        ');
        $this->db->from('kpi');
        $this->db->where('siklus', 'harian');

        if ($type == 'mingguan') {
            // Parse week
            $year = substr($periode, 0, 4);
            $week = substr($periode, 6); // 2025-W05
            $dto = new DateTime();
            $dto->setISODate(intval($year), intval($week));
            $start = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $end = $dto->format('Y-m-d');
            $this->db->where('periode >=', $start);
            $this->db->where('periode <=', $end);
        } elseif ($type == 'bulanan') {
            $this->db->like('periode', $periode, 'after'); // 2025-01
        } elseif ($type == 'tahunan') {
            $this->db->like('periode', $periode, 'after'); // 2025
        }

        $this->db->group_by('id_karyawan');
        $this->db->order_by('rata_rata', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_kpi_by_periode($periode)
    {
        // For reports - basically monthly aggregation
        return $this->get_aggregated_kpi($periode, 'bulanan');
    }

    // --- OTHER METHODS ---

    public function get_all_karyawan_from_db()
    {
        // Check if karyawan table exists
        if (!$this->db->table_exists('karyawan')) {
            return [];
        }

        $this->db->order_by('kry_nama', 'ASC');
        $query = $this->db->get('karyawan');

        if ($query) {
            return $query->result();
        }

        return [];
    }

    public function get_karyawan_by_id($id)
    {
        if (!$this->db->table_exists('karyawan')) {
            return null;
        }

        return $this->db->get_where('karyawan', ['kry_kode' => $id])->row();
    }

    public function save_karyawan($data)
    {
        if (!$this->db->table_exists('karyawan')) {
            return false;
        }

        return $this->db->insert('karyawan', $data);
    }

    public function update_karyawan($kry_kode, $data)
    {
        if (!$this->db->table_exists('karyawan')) {
            return false;
        }

        $this->db->where('kry_kode', $kry_kode);
        return $this->db->update('karyawan', $data);
    }

    public function delete_karyawan($kry_kode)
    {
        if (!$this->db->table_exists('karyawan')) {
            return false;
        }

        $this->db->where('kry_kode', $kry_kode);
        return $this->db->delete('karyawan');
    }

    // --- Laporan Mingguan & Arsip (Standard CRUD) ---
    public function get_laporan_mingguan($periode = null)
    {
        if (!$this->db->table_exists('laporan_mingguan'))
            return [];
        
        if ($periode) {
            // Exact match for week format (e.g., 2026-W01)
            $this->db->where('periode', $periode);
        }
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get('laporan_mingguan')->result_array();
    }
    
    public function save_laporan_mingguan($data)
    {
        if (!$this->db->table_exists('laporan_mingguan'))
            return false;
        
        // Check if record exists (same employee + period)
        $existing = $this->db->get_where('laporan_mingguan', [
            'id_karyawan' => $data['id_karyawan'],
            'periode' => $data['periode']
        ])->row();
        
        if ($existing) {
            // Update existing record
            $this->db->where('laporan_id', $existing->laporan_id);
            return $this->db->update('laporan_mingguan', $data);
        } else {
            // Insert new record
            return $this->db->insert('laporan_mingguan', $data);
        }
    }
    public function update_laporan_mingguan($id, $data)
    {
        $this->db->where('laporan_id', $id);
        return $this->db->update('laporan_mingguan', $data);
    }
    public function delete_laporan_mingguan($id)
    {
        $this->db->where('laporan_id', $id);
        return $this->db->delete('laporan_mingguan');
    }

    public function get_arsip($tipe = null)
    {
        if ($tipe)
            $this->db->where('tipe', $tipe);
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get('arsip')->result_array();
    }
    public function get_arsip_by_periode($tipe, $periode, $siklus)
    {
        // Simple logic reused from above
        $this->db->where('tipe', $tipe);
        if ($siklus == 'harian')
            $this->db->where('tanggal', $periode);
        else if ($siklus == 'bulanan')
            $this->db->like('tanggal', $periode, 'after');
        else if ($siklus == 'tahunan')
            $this->db->like('tanggal', $periode, 'after');
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get('arsip')->result_array();
    }
    public function save_arsip($data)
    {
        return $this->db->insert('arsip', $data);
    }
    public function update_arsip($id, $data)
    {
        $this->db->where('arsip_id', $id);
        return $this->db->update('arsip', $data);
    }
    public function delete_arsip($id)
    {
        $this->db->where('arsip_id', $id);
        return $this->db->delete('arsip');
    }

    // --- PENCATATAN METHODS ---

    public function get_pencatatan($bulan_tahun = null)
    {
        if (!$this->db->table_exists('pencatatan'))
            return [];

        if ($bulan_tahun) {
            $this->db->like('tanggal', $bulan_tahun, 'after');
        }
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('created_at', 'DESC');

        $result = $this->db->get('pencatatan')->result_array();

        // Group by batch_id
        $grouped = [];
        foreach ($result as $row) {
            $batch_id = $row['batch_id'];
            if (!isset($grouped[$batch_id])) {
                $grouped[$batch_id] = [
                    'batch_id' => $batch_id,
                    'tanggal' => $row['tanggal'],
                    'kategori_global' => $row['kategori_global'],
                    'gambar' => $row['gambar'],
                    'created_at' => $row['created_at'],
                    'items' => [],
                    'total_batch' => 0
                ];
            }
            $grouped[$batch_id]['items'][] = [
                'nama_barang' => $row['nama_barang'],
                'qty' => $row['qty'],
                'harga_satuan' => $row['harga_satuan'],
                'total' => $row['total']
            ];
            $grouped[$batch_id]['total_batch'] += $row['total'];
        }

        return array_values($grouped);
    }

    public function get_pencatatan_by_date($tanggal)
    {
        if (!$this->db->table_exists('pencatatan'))
            return [];

        $this->db->where('tanggal', $tanggal);
        $this->db->order_by('created_at', 'DESC');

        $result = $this->db->get('pencatatan')->result_array();

        // Group by batch_id
        $grouped = [];
        foreach ($result as $row) {
            $batch_id = $row['batch_id'];
            if (!isset($grouped[$batch_id])) {
                $grouped[$batch_id] = [
                    'batch_id' => $batch_id,
                    'tanggal' => $row['tanggal'],
                    'kategori_global' => $row['kategori_global'],
                    'gambar' => $row['gambar'],
                    'created_at' => $row['created_at'],
                    'items' => [],
                    'total_batch' => 0
                ];
            }
            $grouped[$batch_id]['items'][] = [
                'nama_barang' => $row['nama_barang'],
                'qty' => $row['qty'],
                'harga_satuan' => $row['harga_satuan'],
                'total' => $row['total']
            ];
            $grouped[$batch_id]['total_batch'] += $row['total'];
        }

        return array_values($grouped);
    }

    public function save_pencatatan($data)
    {
        if (!$this->db->table_exists('pencatatan'))
            return false;

        return $this->db->insert('pencatatan', $data);
    }

    public function update_pencatatan($id, $data)
    {
        if (!$this->db->table_exists('pencatatan'))
            return false;

        $this->db->where('pencatatan_id', $id);
        return $this->db->update('pencatatan', $data);
    }

    public function delete_pencatatan($id)
    {
        if (!$this->db->table_exists('pencatatan'))
            return false;

        $this->db->where('pencatatan_id', $id);
        return $this->db->delete('pencatatan');
    }

    // --- INTERVIEW METHODS ---

    public function get_interview($bulan_tahun = null)
    {
        if (!$this->db->table_exists('interview'))
            return [];

        if ($bulan_tahun) {
            $this->db->like('created_at', $bulan_tahun, 'after');
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('interview')->result_array();
    }

    public function save_interview($data)
    {
        if (!$this->db->table_exists('interview'))
            return false;

        return $this->db->insert('interview', $data);
    }

    public function update_interview($id, $data)
    {
        if (!$this->db->table_exists('interview'))
            return false;

        $this->db->where('interview_id', $id);
        return $this->db->update('interview', $data);
    }

    public function delete_interview($id)
    {
        if (!$this->db->table_exists('interview'))
            return false;

        $this->db->where('interview_id', $id);
        return $this->db->delete('interview');
    }

    // --- PERFORMANCE SYSTEM METHODS ---

    public function get_performance_criteria($type = null)
    {
        if (!$this->db->table_exists('performance_criteria'))
            return [];

        if ($type) {
            $this->db->where('type', $type);
        }
        $this->db->where('is_active', 1);
        $this->db->order_by('criteria_name', 'ASC');
        return $this->db->get('performance_criteria')->result_array();
    }

    public function get_performance_points($kry_kode = null, $periode = null)
    {
        if (!$this->db->table_exists('performance_points'))
            return [];

        $this->db->select('pp.*, pc.criteria_name, pc.max_points, pc.type');
        $this->db->from('performance_points pp');
        $this->db->join('performance_criteria pc', 'pp.criteria_id = pc.criteria_id', 'left');

        if ($kry_kode) {
            $this->db->where('pp.kry_kode', $kry_kode);
        }
        if ($periode) {
            $this->db->where('pp.periode', $periode);
        }

        $this->db->order_by('pp.periode', 'DESC');
        $this->db->order_by('pc.criteria_name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function save_performance_points($data)
    {
        if (!$this->db->table_exists('performance_points'))
            return false;

        // Check if record exists
        $this->db->where('kry_kode', $data['kry_kode']);
        $this->db->where('periode', $data['periode']);
        $this->db->where('criteria_id', $data['criteria_id']);
        $existing = $this->db->get('performance_points')->row();

        if ($existing) {
            $this->db->where('point_id', $existing->point_id);
            return $this->db->update('performance_points', $data);
        } else {
            return $this->db->insert('performance_points', $data);
        }
    }

    public function delete_performance_points($kry_kode, $periode, $criteria_id)
    {
        if (!$this->db->table_exists('performance_points'))
            return false;

        $this->db->where('kry_kode', $kry_kode);
        $this->db->where('periode', $periode);
        $this->db->where('criteria_id', $criteria_id);
        return $this->db->delete('performance_points');
    }

    public function get_monthly_performance($periode = null)
    {
        if (!$this->db->table_exists('performance_monthly'))
            return [];

        $this->db->select('pm.*, k.kry_nama, k.kry_level');
        $this->db->from('performance_monthly pm');
        $this->db->join('karyawan k', 'pm.kry_kode = k.kry_kode', 'left');

        if ($periode) {
            $this->db->where('pm.periode', $periode);
        }

        $this->db->order_by('pm.ranking', 'ASC');
        $this->db->order_by('pm.total_points', 'DESC');
        return $this->db->get()->result_array();
    }

    public function calculate_monthly_performance($periode)
    {
        // Get all employees
        $employees = $this->get_all_karyawan_from_db();

        foreach ($employees as $emp) {
            // Determine employee type
            $type = (stripos($emp->kry_level, 'magang') !== false || stripos($emp->kry_level, 'intern') !== false) ? 'magang' : 'karyawan';

            // Get criteria for this type
            $criteria = $this->get_performance_criteria($type);

            // Get weekly points for the month
            $year = substr($periode, 0, 4);
            $month = substr($periode, 5, 2);

            // Calculate total points for the month
            $total_points = 0;
            $max_possible = 0;

            foreach ($criteria as $crit) {
                // Get points for each week in the month
                for ($week = 1; $week <= 5; $week++) { // Max 5 weeks per month
                    $week_periode = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);

                    $this->db->where('kry_kode', $emp->kry_kode);
                    $this->db->where('periode', $week_periode);
                    $this->db->where('criteria_id', $crit['criteria_id']);
                    $point_record = $this->db->get('performance_points')->row();

                    if ($point_record) {
                        $total_points += $point_record->points * $crit['weight'];
                    }
                    $max_possible += $crit['max_points'] * $crit['weight'];
                }
            }

            $percentage = $max_possible > 0 ? ($total_points / $max_possible) * 100 : 0;

            // Determine level
            if ($percentage >= 91) {
                $level = 'Top Performer';
            } elseif ($percentage >= 71) {
                $level = 'Advanced';
            } elseif ($percentage >= 41) {
                $level = 'Intermediate';
            } else {
                $level = 'Beginner';
            }

            // Save monthly performance
            $monthly_data = [
                'kry_kode' => $emp->kry_kode,
                'periode' => $periode,
                'total_points' => $total_points,
                'max_possible_points' => $max_possible,
                'percentage' => $percentage,
                'level' => $level
            ];

            $this->save_monthly_performance($monthly_data);
        }

        // Calculate rankings
        $this->calculate_rankings($periode);
    }

    private function save_monthly_performance($data)
    {
        if (!$this->db->table_exists('performance_monthly'))
            return false;

        // Check if record exists
        $this->db->where('kry_kode', $data['kry_kode']);
        $this->db->where('periode', $data['periode']);
        $existing = $this->db->get('performance_monthly')->row();

        if ($existing) {
            $this->db->where('monthly_id', $existing->monthly_id);
            return $this->db->update('performance_monthly', $data);
        } else {
            return $this->db->insert('performance_monthly', $data);
        }
    }

    private function calculate_rankings($periode)
    {
        // Get all monthly performances for the period, ordered by total_points DESC
        $this->db->where('periode', $periode);
        $this->db->order_by('total_points', 'DESC');
        $performances = $this->db->get('performance_monthly')->result_array();

        $rank = 1;
        foreach ($performances as $perf) {
            $this->db->where('monthly_id', $perf['monthly_id']);
            $this->db->update('performance_monthly', ['ranking' => $rank]);
            $rank++;
        }
    }

}
