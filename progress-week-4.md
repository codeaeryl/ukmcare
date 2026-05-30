<h1 align=center>LAPORAN PROGRES TUGAS BESAR</h1>

<h2 align="center">Minggu Ke-4 — Target Implementasi 60%</h2>
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

Pada minggu keempat pengerjaan tugas besar, fokus utama pengembangan adalah penyempurnaan sistem manajemen klinik melalui peningkatan kontrol akses, pembersihan fitur yang tidak diperlukan, penambahan role baru, serta perapian struktur database. Pekerjaan minggu ini meliputi pembuatan sistem verifikasi jadwal dokter, penghapusan fitur activity log, pembatasan hak akses admin pada user management, perapian file migration database, penambahan role Cashier dan Receptionist, serta pembatasan appointment aktif untuk pasien.

Target progres implementasi minggu ini adalah sebesar 60%, yang berfokus pada penyempurnaan alur kerja (workflow), penguatan hak akses (authorization), restrukturisasi role dan tanggung jawab fitur, serta optimasi struktur database.

## 2. Teknologi yang Digunakan

- PHP 8.5.6
- PostgreSQL 18.3
- Laragon
- Laravel
- Tailwind CSS
- Blade Template

## 3. Implementasi Fitur

Pada minggu ini, beberapa fitur utama berhasil diimplementasikan:

### Sistem Verifikasi Jadwal Dokter (Admin & Dokter)

Sistem penjadwalan dokter diperbarui secara signifikan. Akses dokter untuk membuat jadwal sendiri telah dihapus, sehingga seluruh jadwal kini di-assign oleh admin. Ditambahkan fitur verifikasi (terima/tolak) pada sisi dokter untuk jadwal yang telah di-assign oleh admin. Selain itu, admin juga mendapatkan fitur edit jadwal untuk mempermudah pengelolaan jadwal praktik.

### Penghapusan Fitur Activity Log

Fitur rekam aktivitas (activity logs) dihapus secara menyeluruh dari sistem. Penghapusan ini mencakup tabel database, model, controller, seeder, serta seluruh tampilan UI yang berkaitan dengan fitur tersebut. Keputusan ini diambil untuk menyederhanakan sistem dan menghilangkan fitur yang tidak lagi diperlukan.

### Pembatasan Hak Akses Admin pada User Management

Halaman edit user diperbarui agar admin tidak lagi dapat mengakses atau mengubah data pribadi user seperti NIK, nama, atau password. Admin kini hanya dapat mengubah peran (role) dari user yang bersangkutan, sehingga privasi data pengguna lebih terjaga.

### Perapian File Database Migration

Dilakukan penggabungan file migration yang sebelumnya terpisah (seperti penambahan kolom `status` pada jadwal dan `bpjs_status` pada pasien) ke dalam file migration utama masing-masing tabel. Setelah penggabungan, dilakukan migrasi ulang agar riwayat database lebih bersih dan terstruktur.

### Pembatasan Appointment Aktif Pasien

Sistem appointment diperbarui sehingga setiap akun pasien hanya dapat memiliki satu appointment aktif dalam satu waktu. Hal ini bertujuan untuk mencegah duplikasi pendaftaran dan menjaga efisiensi antrian.

### Penambahan Role Baru: Cashier & Receptionist

Ditambahkan dua role baru dalam sistem, yaitu Cashier dan Receptionist. Fitur billing yang sebelumnya dikelola oleh Admin dipindahkan ke role Cashier, sedangkan fitur inventory/manajemen obat yang sebelumnya dikelola oleh Admin dipindahkan ke role Pharmacist. Pemisahan ini dilakukan untuk menerapkan prinsip Separation of Concerns pada pembagian tugas antar role.

## 4. Implementasi Database

Progress yang dilakukan pada database minggu ini:

- Penggabungan file migration terpisah ke dalam file migration utama masing-masing tabel untuk menjaga kebersihan riwayat database
- Penghapusan tabel, model, dan seeder yang berkaitan dengan fitur activity log
- Penambahan kolom `status` pada tabel jadwal dokter untuk mendukung fitur verifikasi (pending/accepted/rejected)
- Migrasi ulang database setelah perapian file migration
- Penambahan role Cashier dan Receptionist pada tabel users/roles
- Penambahan constraint untuk membatasi appointment aktif pasien menjadi satu per akun

## 5. Implementasi Halaman

Beberapa halaman yang dibuat atau diperbarui pada minggu ini:

- **Halaman Jadwal Dokter (Dokter):** Diperbarui dengan menghapus fitur pembuatan jadwal mandiri dan menambahkan tombol verifikasi (terima/tolak) untuk jadwal yang di-assign oleh admin
- **Halaman Jadwal Dokter (Admin):** Ditambahkan fitur edit jadwal untuk mempermudah pengelolaan jadwal praktik dokter
- **Halaman Edit User (Admin):** Diperbarui agar admin hanya dapat mengubah role pengguna, tanpa akses untuk mengubah data pribadi (NIK, nama, password)
- **Dashboard & Sidebar:** Dihapusnya menu dan widget yang berkaitan dengan activity log pada seluruh halaman yang sebelumnya menampilkan fitur tersebut
- **Halaman Billing (Cashier):** Fitur billing dipindahkan dari admin ke halaman khusus Cashier
- **Halaman Inventory (Pharmacist):** Fitur manajemen inventory/obat dipindahkan dari admin ke halaman khusus Pharmacist
- **Halaman Pendaftaran (Pasien):** Diperbarui dengan validasi untuk mencegah pasien mendaftar lebih dari satu appointment aktif

## 6. Kendala yang Dihadapi

Beberapa kendala yang ditemui selama pengerjaan antara lain:

- **Perapian migration memerlukan migrasi ulang:** Proses penggabungan file migration mengharuskan dilakukannya `migrate:fresh`, yang berarti seluruh data harus di-seed ulang. Hal ini memerlukan koordinasi agar seeder tetap konsisten dengan struktur tabel terbaru
- **Pemindahan fitur antar role:** Memindahkan fitur billing ke Cashier dan inventory ke Pharmacist memerlukan penyesuaian pada routing, middleware, controller, dan tampilan sidebar secara menyeluruh
- **Penghapusan fitur activity log:** Penghapusan fitur yang sudah terintegrasi ke banyak bagian sistem memerlukan penelusuran menyeluruh untuk memastikan tidak ada referensi yang tersisa (orphan references)
- **Validasi appointment aktif:** Implementasi pembatasan satu appointment aktif per pasien memerlukan penanganan edge case seperti appointment yang masih berstatus pending atau sedang dalam proses

## 7. Progress Keseluruhan

Pada minggu keempat, target progres implementasi sebesar 70% telah dikerjakan dengan beberapa progress utama sebagai berikut:

- Implementasi sistem verifikasi jadwal dokter (terima/tolak) oleh dokter
- Penghapusan akses dokter untuk membuat jadwal sendiri
- Penambahan fitur edit jadwal untuk admin
- Penghapusan seluruh komponen fitur activity log (tabel, model, controller, seeder, UI)
- Pembatasan hak akses admin pada halaman edit user (hanya dapat mengubah role)
- Perapian dan penggabungan file database migration
- Migrasi ulang database untuk riwayat yang lebih bersih
- Pembatasan appointment aktif pasien menjadi satu per akun
- Penambahan role Cashier dan Receptionist
- Pemindahan fitur billing dari Admin ke Cashier
- Pemindahan fitur inventory dari Admin ke Pharmacist
- Penyesuaian routing, middleware, dan sidebar untuk mendukung role baru

Tahap berikutnya akan berfokus pada pengujian menyeluruh (testing), optimasi performa, penyempurnaan UI/UX, serta penambahan fitur pelengkap lainnya menuju target implementasi 100%.

---

## Progress Minggu Ini

| NRP     | Nama                   | Task                                                                                                                                                                                                                                                                                                                               |
| ------- | ---------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 2472012 | Jason                  | Pembuatan sistem verifikasi jadwal dokter (hapus akses dokter buat jadwal, tambah fitur terima/tolak, tambah fitur edit jadwal admin), penghapusan fitur activity log (tabel, model, controller, seeder, UI), pembatasan hak akses admin pada user management (hanya ubah role), perapian dan penggabungan file database migration |
| 2472048 | Jayden Marvel Ethanael | Pembatasan appointment aktif pasien menjadi satu per akun, penambahan role Cashier dan Receptionist, pemindahan fitur billing dari Admin ke Cashier, pemindahan fitur inventory dari Admin ke Pharmacist                                                                                                                           |
