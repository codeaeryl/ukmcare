# <h1 align="center">LAPORAN PROGRES TUGAS BESAR</h1>

<h2 align="center">Minggu Ke-2 — Target Implementasi 30%</h2>
<br>
### Anggota Kelompok

| NRP | Nama |
|-----|------|
| 2472012 | Jason |
| 2472023 | Gearald Christoffer Freederich |
| 2472048 | Jayden Marvel Ethanael |

### Repository GitHub
https://github.com/codeaeryl/ukmcare

---

## 1. Pendahuluan

Pada minggu kedua pengerjaan tugas besar, fokus utama pengembangan adalah implementasi fitur-fitur inti sistem informasi klinik, termasuk manajemen data pengguna (CRUD User), modul pendaftaran dan antrian online, rekam medis elektronik, manajemen jadwal dokter, sistem pembayaran dan billing, serta penyempurnaan tampilan antarmuka untuk seluruh role pengguna (Admin, Dokter, dan Pasien).

Target progres implementasi minggu ini adalah sebesar 30%, yang berfokus pada pengembangan fungsionalitas utama aplikasi, integrasi antar modul, perbaikan tampilan UI/UX, serta penyelesaian konflik merge antar branch pengembangan.

## 2. Teknologi yang Digunakan

- PHP 8.5.6
- PostgreSQL 18.3
- Laragon
- Laravel
- Tailwind CSS
- Blade Template

## 3. Implementasi Fitur

Pada minggu ini, beberapa fitur utama berhasil diimplementasikan:

### Manajemen Data User (Admin)
Sistem pengelolaan pengguna dibuat lebih terpusat. Admin dapat mengatur data Admin, Dokter, dan Pasien dalam satu menu yang sama. Fitur CRUD (tambah, lihat, edit, hapus) sudah berjalan dengan baik, dengan form input yang otomatis menyesuaikan berdasarkan role pengguna.

### Pendaftaran & Antrian Online (Pasien)
Pasien sudah dapat melakukan pendaftaran kunjungan secara online dan mendapatkan nomor antrian secara otomatis melalui sistem.

### Rekam Medis Elektronik (Dokter)
Dokter dapat mencatat diagnosa pasien dan meresepkan obat melalui modul rekam medis elektronik. Data tersimpan secara terstruktur dan terhubung dengan data registrasi pasien.

### Manajemen Jadwal Dokter (Admin)
Admin dapat mengatur jadwal praktik dokter, termasuk jam praktik dan kuota pasien per sesi.

### Sistem Pembayaran & Billing (Admin)
Admin dapat mengelola tagihan pasien dan melakukan konfirmasi pembayaran (acc pembayaran). Dashboard admin juga sudah menampilkan laporan pendapatan dan stok obat secara otomatis.

### Log Aktivitas (Admin)
Pencatatan aktivitas pengguna sudah diimplementasikan untuk menjaga keamanan dan auditability sistem.

### Rekam Medis & Tagihan Pasien (Pasien)
Pasien sudah dapat melihat riwayat rekam medis dan status tagihan melalui halaman khusus yang diakses dari sidebar. Controller dibuat terpisah mengikuti prinsip Single Responsibility Principle (SRP).

## 4. Implementasi Database

Progress yang dilakukan pada database minggu ini:

- Penambahan perubahan pada seeder untuk data registrasi, rekam medis, resep, dan tagihan agar data dummy lebih lengkap dan saling terhubung
- Setiap pasien memiliki 2 registrasi ke 2 dokter berbeda
- Setiap registrasi memiliki rekam medis, resep obat, dan tagihan yang saling terkait
- Dashboard admin secara otomatis menampilkan total pasien, dokter, appointment, dan pendapatan dari data yang ada

## 5. Implementasi Halaman

Beberapa halaman yang dibuat atau diperbarui pada minggu ini:

- **Halaman Home (Landing Page):** Diperbarui menjadi landing page rumah sakit yang lebih modern dengan hero section, informasi layanan, serta tombol login dan dashboard yang lebih interaktif
- **Dashboard Khusus Pasien:** Menampilkan informasi kunjungan, jadwal antrian, status BPJS, dan riwayat rekam medis dengan tampilan yang rapi dan modern
- **Dashboard Admin:** Menampilkan statistik total pasien, dokter, appointment, pendapatan, stok obat, dan aktivitas terbaru secara otomatis
- **Halaman CRUD User:** Form input otomatis menyesuaikan berdasarkan role (Admin/Dokter/Pasien)
- **Halaman Jadwal Dokter:** Admin dapat menambah dan mengatur jadwal praktik dokter
- **Halaman Pendaftaran Pasien:** Pasien dapat mendaftar kunjungan dan mendapatkan nomor antrian
- **Halaman Rekam Medis (Dokter):** Dokter dapat mencatat diagnosa dan meresepkan obat
- **Halaman Rekam Medis (Pasien):** Pasien dapat melihat riwayat rekam medis (read-only)
- **Halaman Tagihan (Pasien):** Pasien dapat melihat status tagihan dan detail pembayaran
- **Halaman Billing (Admin):** Admin dapat mengelola dan mengkonfirmasi pembayaran
- **Halaman Log Aktivitas:** Admin dapat melihat pencatatan aktivitas pengguna
- **Profile & Settings:** Disesuaikan dengan desain dashboard utama, termasuk perbaikan bug update password dan delete account
- **Header:** Diperbarui untuk menampilkan nama dan inisial pengguna yang sedang login

## 6. Kendala yang Dihadapi

Beberapa kendala yang ditemui selama pengerjaan antara lain:

- **Merge conflict antar branch:** Terdapat konflik pada beberapa file utama (`routes/web.php`, `DashboardController.php`, `sidebar.blade.php`, `bootstrap/app.php`) yang harus diselesaikan secara manual saat menggabungkan branch `week2-2472012` dan `week2-2472023`
- **Kode tidak aman pada branch `week2-2472023`:** Ditemukan beberapa script PHP di root folder (`fix_all.php`, `fix_passwords.php`, dll) yang memiliki kerentanan keamanan, seperti mereset seluruh password user dan mengekspos data sensitif. Script tersebut dihapus dan diganti dengan pendekatan yang lebih aman
- **Duplikasi import dan inkonsistensi kode:** Beberapa controller menggunakan fully qualified class name (FQCN) secara inline alih-alih menggunakan `use` statement, sehingga perlu dilakukan pembersihan kode
- **Link sidebar pasien mengarah ke halaman yang belum ada:** Menu Medical Records dan My Bills pada sidebar pasien menggunakan `url()` ke halaman yang belum diimplementasikan

## 7. Progress Keseluruhan

Pada minggu kedua, target progres implementasi sebesar 30% telah dikerjakan dengan beberapa progress utama sebagai berikut:

- Implementasi CRUD User dengan form dinamis berdasarkan role
- Implementasi modul pendaftaran dan antrian online untuk pasien
- Implementasi rekam medis elektronik untuk dokter
- Implementasi manajemen jadwal dokter oleh admin
- Implementasi sistem pembayaran dan billing
- Implementasi log aktivitas pengguna
- Implementasi halaman rekam medis dan tagihan untuk pasien (read-only)
- Pembaruan tampilan dashboard untuk seluruh role (Admin, Dokter, Pasien)
- Pembaruan halaman home menjadi landing page modern
- Perbaikan tampilan profile & settings
- Perbaikan navigasi sidebar berdasarkan role
- Penyelesaian merge conflict antar branch
- Pembersihan kode dan penghapusan script yang rentan keamanan
- Perbaikan bug UI pada sidebar dan header

Tahap berikutnya akan berfokus pada implementasi fitur notifikasi otomatis, laporan penyakit terbanyak pada dashboard, manajemen data pasien oleh admin, serta pengujian dan optimasi performa aplikasi.

---

## Proses Pembagian Tugas

| NRP | Nama | Task |
|-----|------|------|
| 2472012 | Jason | Manajemen data user, CRUD user dengan form dinamis, tampilan dashboard khusus pasien, tampilan halaman home (landing page), perbaikan tampilan profile & settings, perbaikan navigasi sidebar berdasarkan role |
| 2472023 | Gearald Christoffer Freederich | Modul pendaftaran & antrian online (pasien), rekam medis elektronik (dokter), manajemen jadwal dokter (admin), sistem pembayaran & billing (admin), log aktivitas, dashboard admin (statistik & laporan pendapatan) |
| 2472048 | Jayden Marvel Ethanael | Penyelesaian merge conflict, pembersihan kode (penghapusan script rentan keamanan dan perapian kode import), mengupdate database seeders, pembuatan controller dan view rekam medis & tagihan pasien, perbaikan bug UI sidebar, pembaruan header (nama & inisial pengguna) |