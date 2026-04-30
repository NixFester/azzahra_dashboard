# Panduan Praktis — Bugfix, Implementasi, dan Pengetesan

Dokumen singkat ini berisi langkah cepat dan best-practice untuk memudahkan proses bugfix, implementasi fitur baru, dan pengetesan pada project Dashboard Azzahra.

---

## Ringkasan Singkat
- Tujuan: meminimalkan gangguan pada sistem produksi dan mempercepat debugging, implementasi, pengujian.
- Prinsip: non-invasive (additive), feature-flag, backup sebelum perubahan, staging sebelum produksi.

---

## Quick Start — Hari Pertama
1. Backup database dan file konfigurasi.
2. Jalankan migration di environment development/staging dulu.
3. Aktifkan feature flag per fitur yang diuji.
4. Buat/cek view UI sebelum mengaktifkan di production.

---

## Backup (WAJIB)
- Backup database (MySQL) via CLI di Windows (PowerShell):

```powershell
# ganti sesuai path MySQL dan nama DB
mysqldump -u root --password= --databases azzahra2_azza > C:\backup_azzahra_$(Get-Date -Format yyyyMMddHHmm).sql
```

- Backup file penting (config, controller, model, migration): copy file ke folder backup.

---

## Menjalankan Migration SQL
File migration: DB_MYSQL/migration_2026_02_11_features.sql

Via MySQL CLI (Windows PowerShell):

```powershell
# Pastikan mysql ada di PATH atau gunakan path lengkap ke mysql.exe
mysql -u root --password= azzahra2_azza < "C:\xampp\htdocs\dashboardAzzahra\DB_MYSQL\migration_2026_02_11_features.sql"
```

Atau pakai phpMyAdmin: import file SQL di tab Import.

---

## Menjalankan Seed Script (yang sudah ada)
Contoh perintah yang pernah dipakai:

```powershell
php tools/seed_models.php --host=localhost --user=root --pass= --db=azzahra2_azza
```

Pastikan PHP CLI yang dipakai sama versi dengan environment server (XAMPP).

---

## Feature Flags: Aktifkan / Non-aktifkan
File: application/config/feature_flags.php

- Untuk menonaktifkan fitur sementara, ubah flag ke `FALSE`.
- Selalu lakukan toggle di staging dulu, verifikasi, baru di production.

---

## Debugging Dasar (Controller / Model)
1. Aktifkan logging di CodeIgniter: periksa `application/logs/`.
2. Gunakan `log_message('error', '...')` pada titik penting.
3. Jika query bermasalah, cetak SQL yang dieksekusi:

```php
$this->db->last_query();
```

4. Periksa `error_log` Apache / PHP (XAMPP: `xampp\apache\logs\error.log`).

---

## Common Bugfix Steps
1. Reproduksi bug di lokal/staging.
2. Tangkap input dan output (logs, var_dump di dev only).
3. Buat branch git terpisah (feature/bugfix-xxx).
4. Implement fix minimal & terukur.
5. Tambah unit test jika memungkinkan.
6. Push, code review, merge setelah lulus QA.

---

## Testing Strategy
- Unit tests: model dan helper functions — pastikan kalkulasi benar.
- Integration tests: alur end-to-end (teknisi → admin → kasir → order/part).
- Manual tests: UI flow, modal, form validation, error messages.

Peralatan yang direkomendasikan:
- Postman atau curl untuk menguji endpoints API.
- Browser devtools untuk AJAX/JS debugging.

---

## Contoh Test Manual — Refund Flow
1. Pastikan `refund_feature` = TRUE di `feature_flags.php`.
2. Buat transaksi contoh dengan DP.
3. Akses `Refund_management/create` (via UI atau curl) untuk membuat refund request.
4. Approve via endpoint `Refund_management/approve/{id}`.
5. Process via `Refund_management/process/{id}`.
6. Verifikasi record di tabel `refund_requests` dan log.

---

## QA Checklist (Singkat)
- [ ] Backup DB dibuat.
- [ ] Migration dijalankan di staging.
- [ ] Feature flag aktif untuk fitur yang diuji.
- [ ] Semua endpoint yang diubah punya session check.
- [ ] Validasi input server-side.
- [ ] Error handling user-friendly.
- [ ] Logging cukup untuk trace (ID transaksi, user id).
- [ ] Rollback steps terdokumentasi.

---

## Deployment & Rollback
1. Deploy ke staging, jalankan smoke tests.
2. Jika lulus, schedule maintenance window untuk production (jika perlu DB alter).
3. Run migration di production (pastikan backup sebelumnya).
4. Jika perlu rollback DB: restore dari dump yang diambil sebelum migration.

Restore contoh:

```powershell
mysql -u root --password= azzahra2_azza < C:\backup_azzahra_20260211.sql
```

---

## Praktik Pengembangan (Best Practices)
- Gunakan branch per fitur/bugfix.
- Commit kecil dan sering.
- Tulis unit tests untuk logika krusial (kalkulasi sisa pembayaran, refund calc).
- Gunakan feature flags untuk deploy bertahap.
- Dokumentasikan perubahan DB di folder `DB_MYSQL/`.

---

## Catatan Khusus untuk Project Ini
- File migration baru ada di: `DB_MYSQL/migration_2026_02_11_features.sql`.
- Feature flags ada di: `application/config/feature_flags.php`.
- Controller refund: `application/controllers/Refund_management.php`.
- Model refund: `application/models/M_refund.php`.

---

## Troubleshooting Cepat
- Error 500 → cek `application/logs/` dan `apache\logs\error.log`.
- SQL error saat migration → jalankan potongan SQL manual untuk menemukan baris bermasalah.
- Feature tidak muncul → cek `feature_flags.php` dan clear browser cache.

---

## Kontak & Eskalasi
- Developer lead: assign di project management.
- Jika masalah DB kritikal: hubungi DBA.
- Untuk isu security production: lakukan emergency rollback dan hubungi stakeholder.

---

Dokumen ini dapat diperbarui sesuai kebutuhan. Jika ingin, saya bisa menambahkan checklist terperinci per fitur atau template PR dan contoh perintah curl untuk tiap endpoint.
