# REQUIREMENT ANALYSIS - Dashboard Azzahra

**Analysis Date:** February 10, 2026

---

## **Workflow Transaksi Saat Ini:**

```
Baru → Diproses → Konfirmasi → Pelunasan → Lunas
```

---

## **PENJELASAN REQUIREMENT BOSS:**

### **1. Fitur Teknisi - Input Tindakan & Replacement Part**

**Status:** ✅ **Sudah Ada (Partial)**

- **Lokasi File:**
  - Controller: `application/controllers/Teknisi.php` (Line 34)
  - View: `application/views/Teknisi/input_tindakan.php`
  - Model: `application/models/M_service.php` (Line 142)

- **Fungsi Utama:**
  - Teknisi bisa input tindakan dengan qty dan subtotal
  - Data tersimpan di tabel `tindakan`

- **Yang Sudah Ada:**
  - Form input tindakan (text input)
  - Input keterangan kerja
  - Input quantity dan subtotal
  - Simpan ke database dengan timestamps

- **Yang Perlu Ditambah:**
  - [ ] Field untuk **pilih action/tindakan dari preset list** (bukan freetext)
  - [ ] Field untuk **replacement part** (spare part yang digunakan)
  - [ ] Kontrol harga otomatis berdasarkan action yang dipilih
  - [ ] Database table `action_preset` untuk master tindakan
  - [ ] Relasi antara `tindakan` dan `spare_part`

**Implementation Flow:**
```
Teknisi buka unit → Check kondisi → Pilih action dari dropdown → 
Harga otomatis populate → Add spare parts yang diganti → Save
```

---

### **2. Fitur Rangkuman Pembayaran (Summary per Hari)**

**Status:** ✅ **Sudah Ada (Partial)**

- **Lokasi File:**
  - Controller: `application/controllers/Kasir.php` (Line 21)
  - View: `application/views/Kasir/pembayaran.php`
  - Model: `application/models/M_kasir.php`

- **Yang Ada:**
  - Tab DP, Lunas, Return
  - Filter per customer
  - Laporan pembayaran per hari

- **Yang Perlu Ditambah:**
  - [ ] Dashboard **summary pembayaran hari ini** dengan breakdown:
    - Total DP (Tunai + Non-Tunai)
    - Total Lunas per bank (BCA, BRI, Mandiri)
    - Jumlah transaksi per kategori
    - Grafik/chart pembayaran harian
    - List pending transfer (status "Menunggu" di bank)

**SQL Query untuk Summary:**
```sql
-- DP Summary
SELECT 
  SUM(dtl_jml_bayar) as total_dp,
  dtl_jenis_bayar as tipe
FROM transaksi_detail
WHERE trans_status = 'Pelunasan' 
  AND dtl_status = 'DP'
  AND DATE(dtl_tanggal) = CURDATE()
GROUP BY dtl_jenis_bayar;

-- Lunas Summary
SELECT 
  SUM(dtl_jml_bayar) as total_lunas,
  dtl_bank as bank
FROM transaksi_detail
WHERE trans_status = 'Lunas'
  AND dtl_status = 'PELUNASAN'
  AND DATE(dtl_tanggal) = CURDATE()
GROUP BY dtl_bank;
```

---

### **3. Fitur Pendingan Order Part OOW (Out of Warranty) - oleh Management**

**Status:** ❌ **Belum Ada**

- **Tujuan:** Management bisa approve/reject order spare parts untuk service OOW

- **Requirement:**
  - [ ] View list pending orders dengan status OOW
  - [ ] Detail order (spare part, quantity, supplier, estimated cost)
  - [ ] Button Approve / Reject dengan catatan
  - [ ] Tracking status order → proses → selesai
  - [ ] Dashboard pending approval count

- **Workflow:**
  ```
  Teknisi input tindakan (OOW) + spare part
    ↓
  Admin confrim tindakan
    ↓
  Part list masuk ke order_list (status: pending)
    ↓
  Management review & approve
    ↓
  Order status: waiting → processing → received
  ```

- **Database Needed:**
  - Extend `order_list` dengan field: `garansi_type` (OOW/IW), `approved_by`, `approved_at`
  - Add `order_detail` untuk track setiap spare part dalam order

---

### **4. Fitur Pendingan Order Part IW (In Warranty) - oleh Admin**

**Status:** ❌ **Belum Ada**

- **Tujuan:** Admin bisa approve order spare parts untuk service IW (dalam garansi)

- **Requirement:**
  - [ ] View list pending orders IW
  - [ ] Approval flow berbeda dari OOW (lebih sederhana)
  - [ ] Admin bs menentukan supplier & lead time
  - [ ] Notification ke teknisi ketika approved
  - [ ] Status tracking

- **Workflow:**
  ```
  Teknisi input tindakan (IW) + spare part
    ↓
  Admin confirm tindakan
    ↓
  Part list masuk ke order_list (status: pending, garansi_type: IW)
    ↓
  Admin approve → update supplier & lead time
    ↓
  Notification ke teknisi
  ```

---

### **5. Fitur Pendingan Unit Dikirim ke Pusat - oleh Admin**

**Status:** ❌ **Belum Ada**

- **Tujuan:** Track unit yang dikirim ke service center/pusat untuk repair komprehensif

- **Requirement:**
  - [ ] Admin create shipment record
  - [ ] Input data unit yang dikirim (invoice, customer, kondisi)
  - [ ] Status tracking: 
    - Disiapkan → Dikirim → Terima Pusat → Proses Pusat → Selesai → Kembali
  - [ ] Form untuk input bukti pengiriman (foto, asuransi, invoice)
  - [ ] Alert ketika unit belum kembali > X hari

- **Database Needed:**
  - Table `unit_shipment` dengan status history
  - Track waktu pengiriman, diterima, selesai dikerjakan

---

### **6. Fitur Refund - Gagal Service & DP Sudah Diproses**

**Status:** ❌ **Belum Ada**

- **Kondisi Refund:**
  - Service GAGAL (misal tidak bisa diperbaiki, hardware rusak, tidak dapat diterima)
  - DP sudah dibayarkan customer
  - Kasir/Admin process refund ke customer

- **Requirement:**
  - [ ] Flag transaction dengan status "REFUND_PENDING"
  - [ ] Kasir view list refund yang pending
  - [ ] Form refund dengan:
    - DP amount
    - Biaya diagnosa (50rb jika cancel)
    - Metode refund (ke rekening bank / transfer manual)
    - Alasan refund
  - [ ] Update payment history
  - [ ] Audit trail refund (siapa, kapan, berapa)

- **Refund Calculation:**
  ```
  Jika Service Gagal:
  Refund = DP yang dibayarkan - Biaya Cek (50,000)
  
  Contoh:
  DP dibayar: 200,000
  Biaya cek: 50,000
  Refund = 200,000 - 50,000 = 150,000
  ```

- **Workflow:**
  ```
  Teknisi report service gagal
    ↓
  Admin update status → "Gagal"
    ↓
  Kasir create refund record
    ↓
  Input metode refund
    ↓
  Generate bukti refund/slip
    ↓
  Update transaksi_detail dengan status "REFUND"
    ↓
  Payment masuk ke history dengan note negative
  ```

---

### **7. Menu Pilihan Jenis Pembayaran di Pelunasan (Lunas)**

**Status:** ⚠️ **Incomplete**

- **Current State:**
  - Ada pilihan Tunai/Transfer di menu DP
  - Tidak ada pilihan jenis bayar di menu Lunas

- **Problem:**
  - Kasir tidak bisa memilih metode pembayaran saat final payment (Lunas)
  - Data jenis bayar tidak tercatat dengan jelas

- **Requirement:**
  - [ ] Add form modal untuk pilih metode pembayaran di Lunas:
    - TUNAI
    - DEBIT (kartu debit)
    - TRANSFER (BCA, BRI, Mandiri)
  - [ ] Jika TRANSFER: pilih bank
  - [ ] Input nomor referensi (untuk pendaftaran)
  - [ ] Update field `dtl_jenis_bayar` dengan nilai pilihan

- **Lokasi Update:**
  - View: `application/views/Kasir/cari.php` (tab "Pelunasan")
  - Controller: `application/controllers/Kasir.php` (function pembayaran)

---

### **8. Pilihan TUNAI di Pembayaran DP**

**Status:** ✅ **Sudah Ada**

- **Tujuan:** Customer Service bisa proses DP tunai (tidak harus Kasir)

- **Benefit:**
  - Ketika kasir tidak ada di tempat, CS bisa handle pembayaran
  - Fleksibilitas operasional

- **Code Reference:**
  - Field `dtl_jenis_bayar = 'TUNAI'` di database
  - Function `ds_dp_Tunai()` tracking DP tunai per hari
  - Status `dtl_stt_stor = 'Disetorkan'` (langsung masuk kas)

---

### **9. Konfirmasi/Approval Order oleh Admin**

**Status:** ✅ **Sudah Ada**

- **Lokasi File:**
  - Controller: `application/controllers/Admin.php` (Line 203)
  - View: `application/views/Admin/konfirmasi.php`
  - Model: `application/models/M_admin.php`

- **Current Workflow:**
  ```
  1. CS input tindakan (status: diproses)
  2. Admin review tindakan & harga
  3. Admin bisa edit/tambah/hapus tindakan
  4. Admin click "Konfirmasi" → update total harga
  5. Status transaction: Diproses → Konfirmasi
  6. Data masuk ke order_list (spare parts yang need order)
  ```

- **Fungsi yang Ada:**
  - View list tindakan yang diinput CS
  - Edit inline untuk harga tindakan
  - Validasi total cost
  - Auto-generate order code di order_list

---

### **10. Status Spare Part "Pending" (Belum Order)**

**Status:** ⚠️ **Partial - Needs Clarification**

- **Current Status di order_list:**
  ```
  pending, confirm, waitingApproval, itemSubmitted,
  tolak, waitingOrder, jobDone, completed
  ```

- **Requirement:**
  - [ ] Status `pending` untuk spare part yang:
    - Sudah di-approve oleh admin
    - Belum dipesan ke supplier
  - [ ] Tracking part yang masih pending order
  - [ ] Notification ketika stock spare part habis
  - [ ] Admin view "Pending Order Parts" dashboard

- **Logic:**
  ```
  part_status = "pending" artinya:
  - Sudah confirmed oleh admin ✓
  - Sudah ada di order_list ✓
  - Belum dipesan ke supplier ✗
  - Bisa di-assign ke supplier kemudian
  ```

---

### **11. Kalkulasi Sisa Pembayaran Per Kategori (Remaining Balance)**

**Status:** 🔴 **Logic Needed**

#### **a) FINISH (TANPA BIAYA) - UNIT IW IN WARRANTY**

```
Garansi: Device dalam periode garansi
Status: Selesai diperbaiki
Biaya Perbaikan: GRATIS
Biaya Part: GRATIS (ganti dari garansi)
Biaya Software: GRATIS
```

**Kalkulasi:**
```
Sisa Pembayaran = 0 (Zero)
Status: PAID / SELESAI
Customer tidak perlu bayar tambahan
```

---

#### **b) FINISH (DENGAN BIAYA)**

**b1. FINISH OOW (Out of Warranty - Diluar Garansi)**

```
Garansi: Device SUDAH expired warranty
Status: Selesai diperbaiki dengan biaya
Biaya Perbaikan: BERDASARKAN ACTION LIST
Biaya Part: HARGA SPARE PART (dibeli)
Biaya Software: -
```

**Kalkulasi:**
```
Sisa Pembayaran = Total Tindakan + Total Spare Parts
Status: OPEN INVOICE

Contoh:
- Penggantian HDD: 150,000
- Spare Part (SSD 256GB): 500,000
- Service Labor: 100,000
---
Total Sisa = 750,000
Customer harus bayar semua
```

---

**b2. FINISH IW (In Warranty + Biaya Software)**

```
Garansi: Device dalam periode garansi
Status: Selesai diperbaiki + tambahan software
Biaya Perbaikan: GRATIS (dalam garansi)
Biaya Part: GRATIS (ganti dari garansi)
Biaya Software: CHARGE (biaya upgrade/lisensi)
```

**Kalkulasi:**
```
Sisa Pembayaran = Biaya Software Only
Status: OPEN INVOICE

Contoh:
- Service: 0 (gratis garansi)
- Spare Part: 0 (gratis garansi)
- Software License Upgrade: 200,000
---
Total Sisa = 200,000
Customer bayar hanya license software
```

---

#### **c) CANCEL (BIAYA CEK / DIAGNOSA: 50,000)**

```
Status: Service dibatalkan / Tidak dapat diperbaiki
Status Garansi: Tidak relevan
Alasan: Hardware rusak parah, tidak ada solusi,
        customer batalkan, atau masalah external
```

**Kalkulasi:**
```
Biaya Diagnosa/Cek: 50,000 (CHARGE)
Biaya Perbaikan: 0
Biaya Part: 0
---
Total Sisa = 50,000

Refund yang diberikan:
Refund = (DP yang sudah dibayar) - 50,000

Contoh:
DP dibayar: 200,000
Biaya Cek: 50,000
Refund = 200,000 - 50,000 = 150,000
```

---

#### **Tabel Ringkasan:**

| Kategori | Garansi | Perbaikan | Part | Software | Total Sisa |
|----------|---------|-----------|------|----------|-----------|
| **Finish (No Cost)** | IW ✓ | FREE | FREE | FREE | **0** |
| **Finish OOW** | OOW ✗ | CHARGE | CHARGE | - | **Full Cost** |
| **Finish IW + Software** | IW ✓ | FREE | FREE | CHARGE | **SW Cost Only** |
| **Cancel** | - | - | - | - | **50,000** |

---

#### **Database Fields Needed:**

```sql
-- Di table transaksi
ALTER TABLE transaksi ADD COLUMN (
  trans_status_detail VARCHAR(50),  -- 'finish_no_cost', 'finish_oow', 'finish_iw_sw', 'cancel'
  garansi_type VARCHAR(20),          -- 'IW', 'OOW'
  is_warranty_period BOOLEAN,        -- true/false
  warranty_expiry_date DATE,
  software_charge DECIMAL(12,2),     -- untuk IW dengan SW charge
  diagnosis_fee DECIMAL(12,2),       -- untuk cancel = 50000
  cancel_reason VARCHAR(255),
  updated_at TIMESTAMP
);

-- Di table transaksi_detail
ALTER TABLE transaksi_detail ADD COLUMN (
  remaining_balance DECIMAL(12,2),   -- sisa pembayaran
  payment_category VARCHAR(50),      -- 'no_charge', 'oow', 'iw_software', 'cancel'
  calculated_at TIMESTAMP
);
```

---

#### **Calculation Logic (Pseudo Code):**

```php
function calculateRemainingBalance($trans_kode) {
  $trans = DB::table('transaksi')->find($trans_kode);
  
  if ($trans->trans_status != 'Konfirmasi') {
    return 0; // belum complete diagnosis
  }
  
  // Get diagnosis result
  $diagnosis = $trans->trans_status_detail;
  
  switch($diagnosis) {
    case 'finish_no_cost':
      return 0;
      
    case 'finish_oow':
      $total_tindakan = DB::table('tindakan')
        ->where('trans_kode', $trans_kode)
        ->sum('tdkn_subtot');
      return $total_tindakan;
      
    case 'finish_iw_sw':
      $software_charge = $trans->software_charge ?? 0;
      return $software_charge;
      
    case 'cancel':
      return 50000;
  }
}
```

---

### **12. Teknisi Dashboard - Route `/Service/proses/TTS260206007`**

**Status:** ✅ **Sudah Ada (Partial)**

- **Lokasi File:**
  - Controller: `application/controllers/Service.php` (Line 417)
  - View: `application/views/Service/proses.php`

- **Current URL Format:**
  ```
  Service/proses/{trans_kode}
  
  Contoh: Service/proses/TTS260206007
  ```

- **Transaction Code Prefix:**
  - `TR` = Transaksi reguler
  - `TTS` = Teknisi Service (baru generated)
  - Format: `TTS{ddmmyy}{increment}`

- **Current Functionality:**
  ```
  1. Teknisi lihat detail transaksi masuk
  2. View customer data, keluhan unit
  3. Form input tindakan yang dilakukan
  4. Input spare part yang diganti
  5. Hitung total biaya
  6. Simpan → update status Diproses
  7. Auto-generate order_list untuk spare parts
  ```

- **What's There:**
  - Profile customer (nama, HP, model unit, keluhan)
  - Tab untuk input tindakan
  - Edit capability
  - Print/Download TTS

- **Enhancements Needed:**
  - [ ] Dropdown list untuk tindakan (bukan freetext)
  - [ ] Spare parts finder/selector
  - [ ] Auto calculation harga berdasarkan action
  - [ ] Visual feedback tindakan yang sudah di-add
  - [ ] Real-time total cost update

---

## **DATABASE SCHEMA OVERVIEW**

### **Tabel Utama:**

| Tabel | Purpose | Key Fields |
|-------|---------|-----------|
| `transaksi` | Transaksi service utama | trans_kode, cos_kode, trans_status, trans_total, garansi_type |
| `transaksi_detail` | Detail pembayaran | dtl_kode, trans_kode, dtl_jml_bayar, dtl_jenis_bayar, dtl_status |
| `tindakan` | Service actions by teknisi | tdkn_kode, trans_kode, tdkn_nama, tdkn_qty, tdkn_subtot |
| `order_list` | Spare parts order tracking | trans_kode, cos_kode, trans_status, device, merek |
| `costomer` | Customer data | id_costomer, cos_nama, cos_hp, cos_model |
| `vocer` | Voucher/discount | voc_kode, trans_kode, voc_jumlah, voc_status |
| `karyawan` | Employee/staff | kry_kode, kry_nama, kry_level |

### **Tabel yang Perlu Dibuat:**

```sql
-- Master action list untuk teknisi
CREATE TABLE action_preset (
  action_id INT PRIMARY KEY AUTO_INCREMENT,
  action_name VARCHAR(100),
  action_category VARCHAR(50),  -- 'repair', 'replacement', 'service'
  base_price DECIMAL(12,2),
  warranty_applicable BOOLEAN,
  active BOOLEAN,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Spare parts master
CREATE TABLE spare_parts (
  part_id INT PRIMARY KEY AUTO_INCREMENT,
  part_name VARCHAR(100),
  part_code VARCHAR(50),
  supplier_id INT,
  current_stock INT,
  reorder_level INT,
  purchase_price DECIMAL(12,2),
  selling_price DECIMAL(12,2),
  active BOOLEAN,
  created_at TIMESTAMP
);

-- Unit shipment tracking
CREATE TABLE unit_shipment (
  shipment_id INT PRIMARY KEY AUTO_INCREMENT,
  trans_kode VARCHAR(50),
  cos_kode VARCHAR(50),
  shipment_status VARCHAR(50),
  shipment_date TIMESTAMP,
  received_date TIMESTAMP,
  completed_date TIMESTAMP,
  notes TEXT,
  admin_id INT,
  created_at TIMESTAMP
);

-- Refund tracking
CREATE TABLE refund_requests (
  refund_id INT PRIMARY KEY AUTO_INCREMENT,
  trans_kode VARCHAR(50),
  cos_kode VARCHAR(50),
  refund_amount DECIMAL(12,2),
  diagnosis_fee DECIMAL(12,2),
  refund_reason VARCHAR(255),
  refund_method VARCHAR(50),  -- 'bank_transfer', 'cash'
  refund_status VARCHAR(50),  -- 'pending', 'approved', 'processed'
  approved_by INT,
  processed_by INT,
  approved_at TIMESTAMP,
  processed_at TIMESTAMP,
  created_at TIMESTAMP
);
```

---

## **RINGKASAN ACTION ITEMS UNTUK BOSS**

### **Priority List:**

| Priority | # | Feature | Status | Effort | Notes |
|----------|---|---------|--------|--------|-------|
| **🔴 HIGH** | 1 | Refund Feature (Gagal Service) | ❌ | 2-3 hari | Critical untuk customer satisfaction |
| **🔴 HIGH** | 7 | Jenis Bayar di Pelunasan | ⚠️ | 1 hari | Quick fix, improve payment tracking |
| **🔴 HIGH** | 1 | Action Preset + Harga Otomatis | ⚠️ | 2 hari | Improve teknisi UX |
| **🟠 MEDIUM** | 11 | Balance Calculation Logic | 🔴 | 3 hari | Core logic, affects reporting |
| **🟠 MEDIUM** | 2 | Payment Summary Dashboard | ⚠️ | 2 hari | Help kasir management |
| **🟠 MEDIUM** | 3,4 | Part Approval (OOW/IW) | 🔴 | 4 hari | Workflow improvement |
| **🟠 MEDIUM** | 5 | Unit Shipment Tracking | 🔴 | 3 hari | New feature |
| **🟡 LOW** | 10 | Spare Part Pending Status | ⚠️ | 1 hari | Clarify logic |
| **✅ DONE** | 8 | Tunai DP Payment | ✅ | - | Already implemented |
| **✅ DONE** | 9 | Order Confirmation | ✅ | - | Already implemented |
| **✅ DONE** | 12 | Teknisi Dashboard | ✅ | - | Already implemented |

---

## **RECOMMENDED IMPLEMENTATION ORDER:**

### **Phase 1 (Week 1)** - Critical Fixes
1. ✅ Add payment method selection to "Pelunasan" menu
2. ✅ Create refund feature
3. ✅ Add action preset dropdown for technician

### **Phase 2 (Week 2)** - Core Logic
1. ⚠️ Implement balance calculation logic (IW/OOW/Cancel)
2. ⚠️ Create payment summary dashboard
3. ⚠️ Add remaining balance to transaksi table

### **Phase 3 (Week 3)** - Workflows
1. 🔴 Part approval process (OOW/IW)
2. 🔴 Unit shipment tracking
3. 🔴 Notification system

---

## **TECHNICAL NOTES:**

### **Session & Access Control:**
- Kasir: `application/controllers/Kasir.php` → level check "Kasir" atau "Customer Service"
- Teknisi: `application/controllers/Teknisi.php` → level check "Teknisi"
- Admin: `application/controllers/Admin.php` → level check "Admin"

### **Key Models to Extend:**
- `M_kasir.php` → add payment summary functions
- `M_service.php` → add balance calculation
- `M_order.php` → add approval workflow
- Create new `M_refund.php` → refund management

### **Key Views to Create/Update:**
- `Kasir/pembayaran_summary.php` → daily payment dashboard
- `Kasir/cari.php` → add payment method modal
- `Admin/refund_pending.php` → refund list
- `Admin/part_approval.php` → part approval dashboard

---

**Status Updated: February 10, 2026**
