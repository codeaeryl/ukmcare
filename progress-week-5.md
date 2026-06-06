<h1 align=center>LAPORAN PROGRES TUGAS BESAR</h1>

<h2 align="center">Minggu Ke-5 — Target Implementasi 75%</h2>
<br>
### Anggota Kelompok

| NRP     | Nama                           |
| ------- | ------------------------------ |
| 2472012 | Jason                          |
| 2472023 | Gearald Christoffer Freederich |
| 2472048 | Jayden Marvel Ethanael         |

### Repository GitHub

https://github.com/codeaeryl/ukmcare

---

## 1. Pendahuluan

Pada minggu kelima pengerjaan tugas besar, fokus utama pengembangan adalah penyempurnaan alur registrasi pasien dengan integrasi biodata lengkap, penguatan aspek keamanan (security audit), pencegahan anomali konkurensi (race condition), serta penataan ulang data integrity database. Selain itu, diimplementasikan fitur penonaktifan akun (deactivation) untuk dokter dan staf agar tidak merusak data historis transaksi saat akun tidak aktif, serta ditambahkan validasi waktu janji temu dan billing kasir.

Target progres implementasi minggu ini adalah sebesar 75%, berfokus pada ketahanan sistem (robustness), perlindungan data riwayat medis/transaksi, pencegahan kegagalan sistem (anti-crash), dan perluasan uji otomatis (testing).

## 2. Teknologi yang Digunakan

- PHP 8.2+
- PostgreSQL / SQLite (untuk unit testing)
- Laravel Framework
- Tailwind CSS
- Blade Template
- Alpine.js / SweetAlert2

## 3. Implementasi Fitur

Beberapa fitur utama yang berhasil diimplementasikan atau diperbarui pada minggu ini:

### Pendaftaran Pasien Mandiri (Register)

Pendaftaran pasien kini diwajibkan untuk mengisi data identitas lengkap meliputi NIK (16 digit), Tempat & Tanggal Lahir, Jenis Kelamin, Alamat, Nomor Telepon, dan Nomor BPJS (opsional). Sistem secara otomatis memproses data pasien tersebut dan langsung membuat profil rekam medisnya begitu proses registrasi selesai.

### Kelola Janji Temu (Appointment)

- **Tombol Booking:** Tombol booking appointment pasien kini ditampilkan setiap saat (tidak disembunyikan lagi) untuk mempermudah akses.
- **Multiple Appointment:** Pasien kini diperbolehkan memiliki banyak janji temu aktif, namun sistem memblokir pendaftaran ganda pada hari dan jam yang sama untuk mencegah antrian duplikat.
- **Validasi Waktu Lampau:** Pasien tidak dapat memilih slot waktu yang sudah terlewat untuk hari ini, dan tidak dapat membatalkan janji temu yang tanggal/waktunya sudah berlalu.

### Deaktivasi Akun Pengguna (User Deactivation)

Untuk menghindari penghapusan data secara permanen yang dapat merusak riwayat transaksi medis, diimplementasikan sistem status akun (`active` / `inactive`). Admin dapat menonaktifkan dokter atau pengguna lain. Pengguna dengan status `inactive` diblokir secara otomatis saat mencoba masuk (login). Dokter yang tidak aktif juga secara otomatis disembunyikan dari daftar dokter yang tersedia saat pasien melakukan booking janji temu.

### Proteksi Keamanan & Pencegahan Concurrency (Security Audit)

- **Mass Assignment:** Menutup celah kerentanan dengan membatasi parameter input menggunakan `$request->only()` di controller jadwal dan obat.
- **Pessimistic Locking:** Menerapkan `lockForUpdate` pada penentuan nomor rekam medis (MRN) dan ID dokter untuk menghindari *race condition*, serta pada transaksi billing kasir untuk mencegah pembayaran ganda (*double-payment*).
- **Atomic Stock Update:** Pengurangan stok obat dilakukan secara atomik di database untuk mencegah stok menjadi negatif jika diakses secara bersamaan oleh apoteker/dokter.
- **Anti-Crash:** Mengamankan sistem agar tidak terjadi Error 500 saat dokter yang belum memiliki profil lengkap membuka halaman jadwal praktik atau ketika dokter membuka riwayat rekam medis pasien.

### Validasi Pembayaran Kasir (Cashier Billing)

Ditambahkan validasi ketat pada nominal pembayaran yang diinput oleh kasir agar jumlah yang dibayarkan tidak boleh kurang dari total tagihan asli.

## 4. Implementasi Database

Perubahan dan perbaikan pada skema database minggu ini meliputi:

- **Constraint Restrict pada Foreign Key:** Mengubah seluruh aturan `onDelete('cascade')` menjadi `onDelete('restrict')` pada tabel Registrasi, Rekam Medis, Resep, Tagihan, Pembayaran, serta item detail tagihan. Hal ini memastikan data transaksi lama tidak dapat terhapus secara tidak sengaja.
- **Kolom Status Akun:** Menambahkan kolom enum `status` pada tabel `users` untuk mendukung deaktivasi akun (Active/Inactive).
- **Non-negative & Unique Constraints:** Menambahkan check constraint non-negatif pada stok/harga obat dan constraint unik pada kolom `payments.bill_id` untuk mencegah pembayaran ganda.

## 5. Implementasi Halaman

Halaman yang diperbarui atau dibuat baru minggu ini:

- **Halaman Registrasi Pengguna:** Ditambahkan form biodata lengkap pasien (NIK, tempat/tanggal lahir, dll).
- **Halaman Jadwal Praktik Dokter:** Penyesuaian pengecekan status dokter untuk mencegah Error 500 saat profil belum lengkap.
- **Halaman Kelola User (Admin):**
  - **Index:** Menampilkan kolom 'Status' akun dengan lencana warna hijau/merah.
  - **Create/Edit:** Ditambahkan opsi dropdown untuk mengubah status user. Khusus untuk akun admin yang sedang login, pilihan role dan status dikunci (disabled) untuk menghindari self-demotion atau self-deactivation.
- **Halaman Riwayat Medis (Dokter):** Penanganan crash saat data penunjang profil kosong.

## 6. Kendala yang Dihadapi

- **Data Integrity & Relasi:** Mengubah constraint database dari cascade ke restrict membutuhkan penanganan perkecualian (`QueryException`) pada sisi controller agar ketika admin atau apoteker mencoba menghapus record yang berelasi, sistem tidak crash melainkan menampilkan pesan error yang ramah di UI.
- **Self-editing Admin:** Menangani penguncian role/status admin saat mengedit profil diri sendiri tanpa memicu kegagalan validasi form (diselesaikan dengan mengirimkan input tersembunyi).
- **Penyesuaian Test Suite:** Perubahan form registrasi pasien yang kini mewajibkan NIK dan biodata lengkap mengharuskan dilakukannya refaktor pada unit testing bawaan Laravel agar pengujian otomatis pendaftaran tetap berjalan sukses.

## 7. Progress Keseluruhan

Secara umum, progres tugas besar minggu ini telah mencapai target 75% dengan rincian berikut:

- [X] Input biodata lengkap (NIK, POB, DOB, Gender, Alamat) pada form Register pasien.
- [X] Otomatisasi pembuatan profil rekam medis saat pasien mendaftar.
- [X] Validasi anti-tabrakan waktu janji temu pasien (hari dan jam yang sama).
- [X] Proteksi crash sistem (Error 500) pada role Dokter dengan data profil tidak lengkap.
- [X] Validasi nominal input pembayaran kasir minimal sebesar total tagihan.
- [X] Implementasi status deaktivasi user (Active/Inactive) dan pemblokiran login.
- [X] Pengubahan onDelete cascade menjadi restrict pada seluruh relasi database utama.
- [X] Implementasi catch QueryException saat penghapusan data berelasi (User & Obat).
- [X] Proteksi concurrency menggunakan lockForUpdate dan atomic stock updates.
- [X] Pembaruan views Admin User Management untuk menampilkan dan mengubah status.
- [X] Penulisan automated tests (`BusinessLogicTest`) dengan 34 assertions sukses.

Tahap berikutnya adalah pengujian aplikasi akhir, penyesuaian estetika tampilan (UI Polish), dan penyusunan laporan final menuju rilis 100%.

---

## Progress Minggu Ini

| NRP     | Nama                           | Task                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ------- | ------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 2472023 | Gearald Christoffer Freederich | - Menambahkan input NIK, Tempat/Tanggal Lahir, Jenis Kelamin, Telepon, Alamat, dan BPJS pada registrasi pasien.<br>- Menampilkan tombol Book Appointment setiap saat.<br>- Memproses profil medis otomatis saat registrasi.<br>- Blokir janji temu pasien di hari/jam yang sama.<br>- Pencegahan crash (Error 500) jadwal dokter dan riwayat rekam medis pasien.<br>- Validasi pembayaran kasir tidak boleh kurang dari total tagihan.<br>- Menyesuaikan pengujian pendaftaran otomatis.                                                                                                                              |
| 2472048 | Jayden Marvel Ethanael         | - Melakukan audit dan implementasi perbaikan kerentanan keamanan (mass assignment, lockForUpdate pada billing/MRN/ID, atomic stock updates).<br>- Menambahkan status deaktivasi user (enum, blokir login, penyaringan list booking).<br>- Mengubah aturan database migrations dari `onDelete('cascade')` ke `onDelete('restrict')`.<br>- Menambahkan fallback catch `QueryException` pada penghapusan user/obat yang memiliki relasi aktif.<br>- Memperbarui views admin untuk kolom, edit, dan pembuatan status akun, serta memblokir self-modification.<br>- Membuat unit tests baru (`BusinessLogicTest.php`). |
