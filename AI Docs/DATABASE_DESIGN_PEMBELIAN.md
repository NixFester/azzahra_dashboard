# Database Design - Metode Pembelian dan Pembayaran

Dokumen ini mendesain struktur database untuk alur pembelian, konfirmasi harga, pembayaran DP, dan pelunasan di sistem Dashboard Azzahra.

## 1. Tujuan Desain

- Menjawab kebutuhan transaksi sales/service dengan DP dan pelunasan
- Menyediakan struktur yang jelas untuk pelaporan pembayaran
- Menunjukkan relasi antara transaksi, detail pembayaran, customer, karyawan, dan histori
- Menjadikan data mudah difeed ke sistem AI pelaporan di masa depan

## 2. Entitas Utama dan Relasi

- `transaksi` : header order / transaksi
- `transaksi_detail` : detail setiap pembayaran (DP, pelunasan, return)
- `transaksi_return` : record refund atau return terkait transaksi
- `costomer` : data pelanggan
- `karyawan` : data kasir / customer service
- `tindakan` : item layanan / service dalam satu transaksi

Relasi utama:
- `transaksi.cos_kode` -> `costomer.id_costomer`
- `transaksi.kry_kode` -> `karyawan.kry_kode`
- `transaksi_detail.trans_kode` -> `transaksi.trans_kode`
- `tindakan.trans_kode` -> `transaksi.trans_kode`
- `transaksi_return.trans_kode` -> `transaksi.trans_kode`

## 3. Tabel Utama

### 3.1 Tabel `transaksi`

Deskripsi: menyimpan informasi header transaksi service/pembelian.

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `trans_kode` | int(11) | NO | Primary key unik transaksi |
| `cos_kode` | varchar(13) | NO | Kode customer |
| `kry_kode` | varchar(8) | YES | Kode kasir / CS |
| `trans_total` | float | NO | Total biaya sebelum diskon |
| `trans_discount` | float | NO | Diskon / voucher |
| `trans_tanggal` | date | NO | Tanggal transaksi dicatat |
| `trans_status` | varchar(15) | NO | Status umum (`Pelunasan`,`Lunas`,`Return`, dll.) |
| `last_follow_up` | datetime | YES | Waktu follow-up terakhir |
| `follow_up_count` | int(11) | YES | Jumlah follow-up |

Catatan:
- `transaksi` menyimpan kondisi order pada level header.
- Status transaksi menentukan akses kasir, apakah tampil di daftar pembayaran atau sudah selesai.

### 3.2 Tabel `transaksi_detail`

Deskripsi: menyimpan setiap event pembayaran yang terjadi untuk sebuah `transaksi`.

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `dtl_kode` | int(11) | NO | Primary key detail pembayaran |
| `trans_kode` | varchar(255) | NO | Referensi ke `transaksi.trans_kode` |
| `kry_kode` | varchar(8) | NO | Kasir / CS yang mencatat pembayaran |
| `dtl_jml_bayar` | float | NO | Jumlah bayar |
| `dtl_jenis_bayar` | enum('TRANFER','TUNAI','RETURN') | NO | Metode pembayaran |
| `dtl_bank` | varchar(20) | NO | Nama bank untuk transfer |
| `dtl_status` | enum('DP','PELUNASAN','PEMBATALAN') | NO | Tipe pembayaran |
| `dtl_tanggal` | date | NO | Tanggal bayar |
| `dtl_jam` | time | NO | Jam bayar |
| `dtl_stt_stor` | varchar(20) | NO | Status tersimpan / catatan status |

Catatan:
- `transaksi_detail` berisi DP, pelunasan, dan return.
- Karena satu transaksi dapat memiliki beberapa pembayaran, tabel ini mendukung histori penuh.

### 3.3 Tabel `transaksi_return`

Deskripsi: menyimpan data pengembalian yang terkait dengan `transaksi_detail`.

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `ret_kode` | int(11) | NO | Primary key return |
| `trans_kode` | int(11) | NO | Referensi ke `transaksi.trans_kode` |
| `dtl_kode` | int(11) | NO | Referensi ke `transaksi_detail.dtl_kode` |
| `ret_jml` | float | NO | Jumlah return/refund |
| `ret_tanggal` | date | NO | Tanggal return |
| `ret_jam` | time | NO | Jam return |

## 4. Tabel Pendukung

### 4.1 Tabel `costomer`

Fungsi: menyimpan data pelanggan. Kode customer (`cos_kode`) digunakan di `transaksi`.

Field utama yang relevan:
- `id_costomer` / `cos_kode`
- `cos_nama`
- `cos_hp`
- `cos_alamat`
- `cos_model`
- `cos_tanggal`
- `cos_jam`

### 4.2 Tabel `karyawan`

Fungsi: menyimpan data kasir / customer service.

Field utama yang relevan:
- `kry_kode`
- `kry_nama`
- `kry_level` atau role

### 4.3 Tabel `tindakan`

Fungsi: menyimpan item layanan per transaksi.

Field utama yang relevan:
- `trans_kode`
- `tdkn_nama`
- `tdkn_qty`
- `tdkn_subtot`

## 5. Desain Relasi dan Flow Data

1. Pelanggan ada di `costomer`.
2. Order baru dibuat di `transaksi` dengan `cos_kode`, `kry_kode`, `trans_total`, `trans_discount`, dan `trans_status`.
3. Item service tercatat di `tindakan` menggunakan `trans_kode`.
4. Setiap pembayaran ditulis ke `transaksi_detail`.
   - DP: `dtl_status = 'DP'`
   - Pelunasan: `dtl_status = 'PELUNASAN'`
   - Return: `dtl_jenis_bayar = 'RETURN'`
5. Jika ada refund/return spesifik, catat di `transaksi_return`.
6. Setelah pelunasan penuh, `transaksi.trans_status` dapat diubah ke `Lunas`.

## 6. Skema SQL Referensi

### `transaksi`
```sql
CREATE TABLE `transaksi` (
  `trans_kode` int(11) NOT NULL,
  `cos_kode` varchar(13) NOT NULL,
  `kry_kode` varchar(8) DEFAULT NULL,
  `trans_total` float NOT NULL,
  `trans_discount` float NOT NULL,
  `trans_tanggal` date NOT NULL,
  `trans_status` varchar(15) NOT NULL,
  `last_follow_up` datetime DEFAULT NULL,
  `follow_up_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
```

### `transaksi_detail`
```sql
CREATE TABLE `transaksi_detail` (
  `dtl_kode` int(11) NOT NULL,
  `trans_kode` varchar(255) NOT NULL,
  `kry_kode` varchar(8) NOT NULL,
  `dtl_jml_bayar` float NOT NULL,
  `dtl_jenis_bayar` enum('TRANFER','TUNAI','RETURN') NOT NULL,
  `dtl_bank` varchar(20) NOT NULL,
  `dtl_status` enum('DP','PELUNASAN','PEMBATALAN') NOT NULL,
  `dtl_tanggal` date NOT NULL,
  `dtl_jam` time NOT NULL,
  `dtl_stt_stor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
```

### `transaksi_return`
```sql
CREATE TABLE `transaksi_return` (
  `ret_kode` int(11) NOT NULL,
  `trans_kode` int(11) NOT NULL,
  `dtl_kode` int(11) NOT NULL,
  `ret_jml` float NOT NULL,
  `ret_tanggal` date NOT NULL,
  `ret_jam` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
```

## 7. Kebutuhan Indeks dan Integritas

Rekomendasi indeks:
- `transaksi.trans_kode`
- `transaksi.cos_kode`
- `transaksi_detail.trans_kode`
- `transaksi_detail.dtl_status`
- `transaksi_detail.dtl_jenis_bayar`
- `transaksi_detail.dtl_tanggal`

Integritas data:
- `transaksi_detail.trans_kode` sebaiknya direlasikan ke `transaksi.trans_kode` meskipun di skema lama bertipe berbeda.
- `transaksi.cos_kode` agar konsisten dengan `costomer.id_costomer`.
- `transaksi.kry_kode` cocok dengan `karyawan.kry_kode`.

## 8. Catatan Desain untuk AI Pelaporan

- `transaksi` adalah unit laporan utama per order.
- `transaksi_detail` adalah unit laporan pembayaran.
- `dtl_status` membedakan DP vs pelunasan.
- `dtl_jenis_bayar` membedakan metode tunai/transfer.
- `trans_status` mengindikasikan apakah transaksi selesai atau belum.
- `transaksi_return` mengakomodasi refund/return.

## 9. Kesimpulan

Desain ini mendukung:
- proses DP + pelunasan
- histori pembayaran lengkap
- status laporan harian
- validasi detail pembayaran terpisah dari header transaksi
- pelaporan finansial berdasarkan jenis pembayaran dan status transaksi
