# IMPLEMENTATION GUIDE — Non‑Invasive Enhancements

Tanggal: 11 Februari 2026

Ringkasan singkat:
- Tujuan: Tambah/tingkatkan fitur untuk nomor 9 (Approval), 10 (Spare Part Pending), 12 (Teknisi Dashboard) tanpa mengubah alur/kode produksi yang sedang berjalan.
- Prinsip: perubahan bersifat additive (tabel baru, controller baru, view baru, API baru, draft/approval tables). Tidak memodifikasi controller/view/struktur data existing kecuali menambahkan kolom non-mengganggu jika benar-benar perlu dan dikerjakan saat maintenance.

**Scope & Constraints**
- Tidak merubah `application/controllers/*` existing yang aktif (kecuali menambah controller baru).
- Semua penyimpanan baru menggunakan tabel baru (`*_draft`, `*_approvals`, `order_part_status`, dsb).
- Semua endpoint baru harus cek session/role (Admin/Teknisi/Kasir).
- Deploy bertahap: staging → feature-flag → production.

**Urutan Pelaksanaan Singkat**
1. Buat file migration SQL additive (CREATE TABLE saja).
2. Deploy ke staging dan load data sampel.
3. Buat controller API + views baru (tidak menggantikan yang lama).
4. Uji end‑to‑end di staging; aktifkan feature-flag saat siap.

---

**Detail per fitur**

**No 9 — Konfirmasi / Approval Order oleh Admin (Additive)**
- Pendekatan: Buat tabel approval trail + controller/view terpisah.
- Tabel contoh:
```sql
CREATE TABLE order_approvals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  trans_kode VARCHAR(50) NOT NULL,
  order_list_id INT NULL,
  action ENUM('approve','reject','edit') NOT NULL,
  note TEXT,
  admin_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
- Flow:
  - Admin buka `Admin_approval` (baru) → lihat list pending (baca dari `order_list`).
  - Admin tekan Approve/Reject/Edit → backend hanya INSERT ke `order_approvals`.
  - Opsional: sinkronisasi ke `order_list` hanya lewat proses manual/batch ketika aman.
- Benefit: audit lengkap, rollback mudah, tidak mengganggu flow produksi.

**No 10 — Status Spare Part "Pending" (Belum Order)**
- Tujuan: Track parts yang confirmed admin tapi belum dipesan.
- Tabel status part contoh:
```sql
CREATE TABLE order_part_status (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_list_id INT,
  part_code VARCHAR(50),
  requested_qty INT,
  assigned_supplier_id INT NULL,
  status ENUM('pending','assigned','ordered','received','rejected') DEFAULT 'pending',
  note TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
- Workflow:
  - Saat admin konfirmasi tindakan, sistem (API baru) INSERT record ke `order_part_status` dengan status=`pending`.
  - Buat view `Admin/pending_parts.php` (baru) yang membaca `order_part_status` untuk dashboard.
  - Notifikasi stok habis: cron sederhana yang cek `spare_parts.current_stock <= reorder_level` dan INSERT ke tabel `notifications`.
- Query dashboard contoh:
```sql
SELECT ops.*, ol.trans_kode, ol.cos_kode
FROM order_part_status ops
JOIN order_list ol ON ol.id = ops.order_list_id
WHERE ops.status = 'pending';
```

**No 12 — Teknisi Dashboard (`Service/proses/{trans_kode}`) — UX enhancements non-invasif**
- Pendekatan: Jangan ubah route/logic existing. Tambah route/partial baru (mis. `Service/proses_ext/{trans_kode}`) dan API untuk action/parts/draft.
- Tabel master (jika belum ada):
```sql
CREATE TABLE action_preset (
  action_id INT AUTO_INCREMENT PRIMARY KEY,
  action_name VARCHAR(100),
  action_category VARCHAR(50),
  base_price DECIMAL(12,2),
  warranty_applicable BOOLEAN,
  active BOOLEAN DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE spare_parts (
  part_id INT AUTO_INCREMENT PRIMARY KEY,
  part_code VARCHAR(50),
  part_name VARCHAR(100),
  current_stock INT,
  selling_price DECIMAL(12,2),
  supplier_id INT,
  active BOOLEAN DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tindakan_draft (
  id INT AUTO_INCREMENT PRIMARY KEY,
  trans_kode VARCHAR(50),
  payload JSON,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
- API controller contoh: `Service_api` dengan endpoint:
  - `GET /service_api/actions` — list/search `action_preset`
  - `GET /service_api/parts?q=` — search `spare_parts`
  - `POST /service_api/calc` — terima items, kembalikan total (server validate)
  - `POST /service_api/save_draft` — simpan ke `tindakan_draft`
- Frontend: partial `Service/proses_enhanced.php` (AJAX) atau route baru `Service/proses_ext/{trans_kode}`. Kalkulasi client + verifikasi server melalui `/service_api/calc`.
- Penyimpanan produksi: hanya pindahkan dari `tindakan_draft` → `tindakan` setelah review/approve (manual atau batch), sehingga data produksi tidak langsung tersentuh.

---

**Testing & Deployment Checklist**
- [ ] Buat migration SQL (CREATE TABLE) dan apply di staging DB.
- [ ] Deploy controller API dan views baru ke staging.
- [ ] Buat sample data (action_preset, spare_parts, order_list contoh).
- [ ] Tes: buat draft tindakan teknisi → server calc → simpan draft → admin approve → pindahkan ke produksi.
- [ ] Review security: cek session/role pada endpoint baru.
- [ ] Tambah feature-flag di `application/config/feature_flags.php` untuk men-enable per fitur.

**Rollback**
- Karena perubahan additive, rollback cukup dengan: disable feature-flag + drop tabel yang baru (jika perlu). Jangan drop tabel lama.

**Next actions (pilih salah satu):**
A) Saya buat file migration SQL (semua CREATE TABLE di atas) dan taruh di `DB_MYSQL/migrations/`.
B) Saya scaffold `Service_api` controller + example view partial `Service/proses_enhanced.php` (staging-only).
C) Saya buat snippet AJAX + small HTML partial untuk dropdown action + spare-part finder.

Pilih A, B, atau C untuk saya lanjutkan sekarang.

---

Catatan: semua referensi file yang disebut di atas ada di repository; saya tidak akan menimpa file existing kecuali Anda minta eksplisit.
