# Metode Pembelian dan Alur Pembayaran

Dokumen ini adalah ringkasan teknis yang dibuat untuk di-feed ke sistem AI pelaporan di masa depan. Formatnya dibuat ringkas dan terstruktur agar model AI dapat membaca alur bisnis, struktur data, dan aturan pembayaran secara langsung.

## 1. Tujuan

- Menyediakan data ringkas dan terstruktur untuk feed AI pelaporan
- Menjelaskan alur end-to-end pembelian/penjualan di sistem
- Menunjukkan struktur data utama yang harus ditangkap AI
- Menyajikan detail form dan proses kasir untuk DP dan pelunasan
- Menjelaskan cara konfirmasi pelanggan menggunakan WhatsApp
- Membedakan antara `transaksi` dan `transaksi_detail`

## 2. Format Ringkas untuk Feed AI

- `order_event`: pembuatan order awal dari API
- `payment_event`: pembayaran DP atau pelunasan
- `status_flow`: urutan status transaksi
- `customer_data`: nama, HP, alamat, model
- `payment_data`: total, discount, DP, sisa pelunasan
- `whatsapp_confirmation`: isi pesan, nomor, link digital receipt
- `report_metrics`: DP tunai, pelunasan tunai, total pembayaran hari ini

## 3. Struktur Data Utama dan Tabel

## 2. Struktur Data Utama dan Tabel

### 2.1 Tabel `transaksi`

Tabel `transaksi` menyimpan header order utama dan status keseluruhan transaksi.

Field penting:
- `trans_kode` : kode unik transaksi
- `cos_kode` / `id_costomer` : relasi ke pelanggan
- `kry_kode` : relasi ke karyawan / kasir
- `trans_total` : total biaya sebelum diskon
- `trans_discount` : potongan harga atau voucher
- `trans_status` : status transaksi (`Pelunasan`, `Lunas`, `Return`, dll.)
- `trans_tanggal` : waktu pencatatan transaksi

### 2.2 Tabel `transaksi_detail`

Tabel ini menyimpan setiap pembayaran yang dilakukan, termasuk DP dan pelunasan.

Field penting:
- `dtl_kode` : kode unik detail pembayaran
- `trans_kode` : relasi ke `transaksi`
- `dtl_jml_bayar` : jumlah yang dibayar
- `dtl_jenis_bayar` : `TUNAI`, `DEBIT`, `TRANFER`, `RETURN`
- `dtl_status` : `DP`, `PELUNASAN`, `RETURN`
- `dtl_tanggal` : tanggal pembayaran
- `dtl_jam` : jam pembayaran

### 2.3 Tabel `costomer` dan relasi lain

- `costomer` menyimpan data customer seperti `cos_nama`, `cos_hp`, `cos_alamat`, `cos_model`, `cos_tanggal`, dan `cos_jam`.
- `karyawan` menyimpan data kasir / CS yang memproses transaksi.
- `tindakan` menyimpan item layanan yang diselesaikan dalam satu transaksi.

## 3. Endpoint dan Komponen Kunci

### 3.1 `application/controllers/Api_transaksi.php`

Fungsi utama: `tambah()`

- Membaca JSON dari `php://input`
- Memvalidasi format JSON
- Menyimpan order awal ke tabel `transaksi`
- Payload contoh:
  - `cos_kode` (id customer)
  - `kry_kode` (id kasir)
  - `trans_total`
  - `trans_discount`
  - `trans_status`

Jika sukses, API mengembalikan objek JSON dengan `status: true` dan data order.

### 3.2 `application/controllers/Kasir.php`

Fungsi utama di kasir:
- `index()` : daftar pelanggan dan transaksi yang belum lunas
- `ajax_search()` : pencarian transaksi untuk dibayar
- `pembayaran()` : tampilan daftar pembayaran berdasarkan filter
- `cari($trans_kode)` : membuka detail transaksi per nota
- `save_dp()` / `pelunasan()` : aksi penyimpanan pembayaran

### 3.3 `application/models/M_kasir.php`

Fungsi utama:
- `GetCustom()` / `GetCustomCount()` : daftar transaksi aktif yang belum `Lunas`
- `GetPembayaran()` / `GetPembayaranCount()` : daftar pembayaran berdasarkan filter `dp`, `lunas`, atau `return`
- `trans($kode)` : ambil detail transaksi per nota
- `tindakan($kode)` : ambil layanan / produk yang dipesan
- `Histori($kode)` : ambil histori pembayaran dari `transaksi_detail`
- `save_dp($detail)` : insert record DP ke `transaksi_detail`
- `pelunasan($detail)` : insert record pelunasan ke `transaksi_detail`
- `update_trans($trans,$kode)` : update status di tabel `transaksi`
- `lap_bayar()` : laporan pembayaran harian
- `DP_Tunai()`, `Sum_DP_Tunai()`, `Lunas_Tunai()`, `Sum_Lunas_Tunai()` : ringkasan kasir harian

### 3.4 `application/views/Kasir/cari.php`

Tampilan kasir untuk proses pembayaran dan konfirmasi.
- Terdapat tab: `Detail`, `Pelunasan`, `DP`
- Terdapat form untuk `Bayar DP` dan `Bayar Pelunasan`
- Terdapat tombol cetak nota dan WhatsApp
- Menggunakan JavaScript `sendToWA()` untuk membuat pesan otomatis

## 4. Alur Pembelian Lengkap

### 4.1 Order awal dibuat dan disimpan

1. Pelanggan order di front-end atau sistem lain.
2. Backend memanggil API `Api_transaksi/tambah`.
3. `Api_transaksi` membaca JSON dan menyimpan ke tabel `transaksi`.
4. Status awal dapat diberi nilai `Pelunasan` atau nilai lain sesuai kebutuhan.
5. Order tersimpan tanpa detail pembayaran di `transaksi_detail`.

### 4.2 Kasir membuka daftar transaksi

1. Kasir membuka `Kasir/index` atau `Kasir/pembayaran`.
2. Sistem memanggil `M_kasir->GetCustom()` untuk menampilkan transaksi dengan kondisi `trans_status != 'Lunas'`.
3. Hasil daftar bisa dipilih dari daftar transaksi untuk dibayar.
4. Transaksi `Return` akan muncul opsi khusus `Return`, sedangkan transaksi normal muncul tombol `Bayar`.

### 4.3 Kasir melihat detail transaksi

1. Kasir membuka halaman `Kasir/cari/{trans_kode}`.
2. Halaman menampilkan informasi:
   - Invoice / kode transaksi
   - Nama customer
   - Model unit
   - Alamat dan nomor HP
   - Tanggal dan jam order
   - Total, discount, DP, dan pelunasan
3. Detail layanan ditampilkan melalui query `tindakan($kode)`.
4. Histori pembayaran ditampilkan di tabel `transaksi_detail`.

### 4.4 Validasi harga dan diskon

1. Nilai `trans_total` adalah total biaya sebelum diskon.
2. `trans_discount` menunjukkan potongan harga / voucher.
3. Jika sudah ada DP, pelunasan dihitung sebagai:
   - `pelunasan = trans_total - trans_discount - dp`
4. Jika belum ada DP, pelunasan dihitung dari total dikurangi diskon.

### 4.5 Kasir memilih metode pembayaran

Form pembayaran mendukung:
- `TUNAI`
- `DEBIT`
- `TRANFER`

Untuk `DEBIT` atau `TRANFER`, field bank akan tampil dan pengguna dapat memilih:
- `BCA`
- `Mandiri`
- `BRI`
- `BNI`
- `Lainnya`

Jika metode non-tunai dipilih, sistem menampilkan data rekening: `FERRY JUANDA - 0470727705`.

## 5. Detail Proses DP

### 5.1 Input DP

1. Pada tab `DP`, kasir mengisi jumlah `dp`.
2. Kasir memilih `jenis_bayar`.
3. Data DP dikirim ke `Kasir/save_dp`.
4. `M_kasir->save_dp()` menyimpan record baru di `transaksi_detail`.

### 5.2 Data yang disimpan untuk DP

Record `transaksi_detail` untuk DP akan memuat:
- `trans_kode`
- `dtl_jml_bayar` = nilai DP
- `dtl_jenis_bayar` = metode pembayaran
- `dtl_status` = `DP`
- `dtl_tanggal`, `dtl_jam`

### 5.3 Status transaksi setelah DP

- Transaksi umumnya tetap dalam status `Pelunasan` sampai pelunasan selesai.
- Kasir dapat melihat DP pada halaman `Pelunasan` sebagai bagian dari perhitungan sisa.

## 6. Detail Proses Pelunasan

### 6.1 Input Pelunasan

1. Pada tab `Pelunasan`, kasir melihat jumlah sisa yang harus dibayar.
2. Sisa dihitung otomatis di sisi view berdasarkan total, diskon, dan DP yang sudah dibayar.
3. Kasir memilih `jenis_bayar` dan opsi bank jika perlu.
4. Data pelunasan dikirim ke `Kasir/pelunasan`.

### 6.2 Data yang disimpan untuk pelunasan

Record `transaksi_detail` untuk pelunasan akan memuat:
- `trans_kode`
- `dtl_jml_bayar` = nilai pelunasan
- `dtl_jenis_bayar` = metode pembayaran
- `dtl_status` = `PELUNASAN`
- `dtl_tanggal`, `dtl_jam`

### 6.3 Penyelesaian transaksi

Setelah pelunasan tercatat:
- Sistem dapat mengupdate `transaksi.trans_status` menjadi `Lunas`
- Transaksi dianggap selesai dan tidak lagi ditampilkan di daftar `Kasir` untuk pembayaran
- Kasir dapat mencetak nota dan mengirim pesan WhatsApp selesai service

## 7. Konfirmasi WhatsApp

### 7.1 Cara kerja `sendToWA()`

- Fungsi JavaScript ini mengambil data `hp`, `nama`, `status`, `jumlah`, `tanggal`, dan link nota.
- Nomor telepon diubah menjadi format internasional Indonesia (`+62`).
- Pesan otomatis berisi salam, detail layanan, status transaksi, dan link tanda terima digital.
- URL akhir dibuka sebagai `https://wa.me/<nomor>?text=<pesan-terencode>`.

### 7.2 Isi pesan WhatsApp

Pesan standar berisi:
- salam pembuka `SALAM SATU HATI`
- nama customer
- informasi service dan status transaksi
- instruksi untuk menghubungi support jika ada keluhan
- link digital receipt (`CETAK/print_1/<dtl_kode>`)
- ajakan download aplikasi AzzaService

### 7.3 Tujuan WhatsApp

- Memberi notifikasi kepada customer tentang pembayaran dan service
- Mengonfirmasi bahwa DP/pelunasan telah diterima
- Mengirim tanda terima digital secara langsung

## 8. Status Transaksi dan Filter

### 8.1 Status utama di `transaksi`
- `Pelunasan` : transaksi sedang menunggu pelunasan atau sudah bayar DP
- `Lunas` : transaksi selesai
- `Return` : transaksi dengan pengembalian / retur

### 8.2 Filter pembayaran di `M_kasir`
- `filter == 'dp'` : menampilkan transaksi dengan `trans_status = 'Pelunasan'`
- `filter == 'lunas'` : menampilkan transaksi dengan `trans_status = 'Lunas'`
- `filter == 'return'` : menampilkan transaksi_detail dengan `dtl_jenis_bayar = 'RETURN'`

### 8.3 Tampilan daftar kasir
- Daftar default menampilkan transaksi yang belum `Lunas`
- Setiap baris menampilkan: invoice, nama customer, status, alamat, no HP, tanggal/jam
- Tombol `Bayar` membawa kasir ke detail transaksi

## 9. Histori Pembayaran dan Laporan

### 9.1 Histori pembayaran

- `M_kasir->Histori($kode)` membaca semua `transaksi_detail` untuk transaksi tertentu
- Tampilan histori menampilkan:
  - total bayar
  - jenis bayar
  - status (DP atau PELUNASAN)
  - tanggal dan jam

### 9.2 Laporan harian

- `M_kasir->lap_bayar()` menampilkan pembayaran hari ini
- `M_kasir->DP_Tunai()` dan `Sum_DP_Tunai()` untuk ringkasan DP tunai hari ini
- `M_kasir->Lunas_Tunai()` dan `Sum_Lunas_Tunai()` untuk ringkasan pelunasan tunai hari ini

### 9.3 Cetak nota

- Kasir dapat mencetak nota pembayaran dengan `Cetak/print_1/<dtl_kode>`
- Tersedia juga print thermal dan print surat pernyataan

## 10. Data Penting untuk Dokumentasi AI

Sistem menyimpan data penting berikut yang berguna untuk AI:
- `trans_kode`
- `cos_kode` / `id_costomer`
- `kry_kode`
- `trans_total`
- `trans_discount`
- `trans_status`
- `dtl_jml_bayar`
- `dtl_jenis_bayar`
- `dtl_status`
- `dtl_tanggal`
- `dtl_kode`
- `cos_nama`
- `cos_hp`
- `cos_alamat`
- `cos_model`
- `tindakan` (nama layanan, qty, subtotal)
- `bank`/`jenis_bayar`
- `created_at` / `trans_tanggal`

## 11. Catatan Implementasi

- Order awal dibuat oleh API dan dapat berisi status awal `Pelunasan`.
- Pembayaran DP dan pelunasan dicatat di `transaksi_detail`, bukan langsung di `transaksi`.
- `transaksi.trans_status` menentukan apakah transaksi masih aktif atau sudah selesai.
- WhatsApp menggunakan link `wa.me` tanpa backend WhatsApp resmi.
- Form pembayaran mendukung `TUNAI`, `DEBIT`, dan `TRANFER`.
- Jika metode non-tunai dipilih, customer diberi informasi rekening bank.

## 12. Rekomendasi Pengembangan Dokumentasi Selanjutnya

1. Tambahkan contoh payload API order di `Api_transaksi.php` dan contoh respons JSON.
2. Buat diagram alur `Order -> DP -> Pelunasan -> Lunas`.
3. Dokumentasikan secara terpisah status `trans_status` dan `dtl_status`.
4. Tambahkan contoh screenshot halaman `Kasir/cari.php` untuk tab `DP` dan `Pelunasan`.
5. Buat tabel field lengkap untuk `transaksi` dan `transaksi_detail`.
