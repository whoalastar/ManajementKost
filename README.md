# Sistem Manajemen Kost

Sistem Manajemen Kost adalah aplikasi berbasis web yang digunakan untuk mengelola operasional kost secara terpusat, meliputi pengelolaan kamar, penghuni, invoice, serta penyediaan informasi kamar bagi publik.
Aplikasi ini dibangun menggunakan Laravel 12 dan disiapkan untuk integrasi Blade (server-side rendering).

## Fitur Utama

Sistem ini memiliki 3 jenis peran pengguna:
1. Admin (Pemilik / Pengelola Kost)
2. User Login (Penghuni Kost)
3. User Publik (Belum Login)

### 1. Fitur Admin

Admin memiliki akses penuh untuk mengelola seluruh data dan operasional kost.

**Autentikasi & Akses**
* Login & logout admin
* Reset password via email
* Proteksi route admin
* Role admin: Owner, Staff (opsional)
* Hak akses berbasis role

**Dashboard Admin**
* Ringkasan: Total kamar, Kamar kosong / terisi / maintenance, Jumlah penghuni aktif
* Ringkasan invoice: Invoice bulan berjalan, Invoice belum lunas
* Grafik: Pendapatan bulanan, Tingkat hunian kamar

**Manajemen Kamar**
* Tambah, edit, hapus kamar (soft delete)
* Data kamar: Nomor / kode kamar, Tipe kamar, Lantai, Harga sewa, Status kamar, Deskripsi
* Upload & kelola foto kamar
* Riwayat perubahan kamar

**Manajemen Fasilitas**
* Master fasilitas: Fasilitas kamar, Fasilitas umum
* Assign fasilitas ke kamar
* Update fasilitas per kamar

**Manajemen Penghuni**
* Tambah & edit data penghuni
* Assign kamar ke penghuni
* Pindah kamar
* Checkout penghuni
* Status penghuni: Aktif, Keluar
* Arsip data penghuni lama

**Manajemen Booking / Inquiry**
* Lihat booking dari user publik
* Detail calon penyewa
* Status booking: Baru, Dihubungi, Survey, Deal, Batal
* Konversi booking menjadi penghuni

**Manajemen Invoice & Tagihan**
* Generate invoice otomatis bulanan
* Generate invoice manual
* Komponen tagihan: Sewa kamar, Listrik, Air, Internet, Denda, Biaya tambahan
* Status invoice: Draft, Terkirim, Jatuh tempo, Lunas, Terlambat
* Download / cetak invoice (PDF)

**Manajemen Pembayaran (Manual)**
* Input pembayaran manual
* Metode pembayaran (transfer / cash)
* Upload bukti pembayaran
* Update status invoice
* Catatan pembayaran

**Email Management (Invoice)**
* Template email invoice
* Kirim & kirim ulang invoice via email
* Log pengiriman email

**Pengaduan & Maintenance**
* Lihat laporan kerusakan dari penghuni
* Update status pengaduan: Baru, Diproses, Selesai
* Catatan tindak lanjut
* Riwayat maintenance kamar

**Laporan & Export**
* Laporan pendapatan
* Laporan tunggakan
* Laporan hunian kamar
* Laporan pengaduan
* Export PDF / Excel

**Pengaturan Sistem**
* Profil kost
* Rekening pembayaran
* Branding invoice
* Konfigurasi SMTP
* Aturan kost
* Backup data (manual)

**Audit & Log Aktivitas**
* Log login admin
* Log perubahan data penting
* Timestamp & user admin

### 2. Fitur User Login (Penghuni Kost)

Fitur ini hanya dapat diakses oleh penghuni yang statusnya aktif.

**Autentikasi**
* Login & logout penghuni
* Reset password via email
* Session-based authentication

**Dashboard Penghuni**
* Informasi kamar yang ditempati
* Status sewa
* Tanggal jatuh tempo
* Ringkasan tagihan aktif
* Notifikasi dalam sistem

**Invoice & Tagihan**
* Daftar invoice
* Detail invoice
* Download invoice (PDF)
* Status pembayaran
* Riwayat tagihan
* Catatan: Invoice utama tetap dikirim melalui email.

**Konfirmasi Pembayaran (Manual)**
* Upload bukti pembayaran
* Input tanggal pembayaran
* Status verifikasi admin
* Riwayat konfirmasi pembayaran

**Pengaduan & Maintenance**
* Kirim laporan kerusakan
* Upload foto pendukung
* Lihat status pengaduan
* Riwayat pengaduan

**Profil Penghuni**
* Lihat data pribadi
* Update email & nomor HP
* Ganti password

**Informasi Kost**
* Aturan kost
* Fasilitas umum
* Kontak admin
* Jam operasional

### 3. Fitur User Publik (Belum Login)

Fitur ini bersifat informatif dan digunakan untuk promosi kost.

**Landing Page**
* Informasi umum kost
* Deskripsi & alamat
* Kontak admin
* Foto lingkungan kost
* Daftar fasilitas umum

**Daftar Kamar Kosong**
* List kamar tersedia
* Harga sewa
* Tipe kamar
* Status ketersediaan

**Detail Kamar**
* Foto kamar
* Harga sewa
* Fasilitas kamar
* Fasilitas umum
* Deskripsi kamar

**Filter & Sorting**
* Filter harga
* Filter tipe kamar
* Filter lantai
* Sorting harga

**Booking / Inquiry**
* Form booking: Nama, Nomor HP, Email, Kamar diminati
* Notifikasi booking ke admin via email

**Hubungi Admin**
* WhatsApp
* Telepon
* Email

## Batasan Scope

* Tidak ada payment gateway
* Tidak ada WhatsApp automation
* Tidak ada mobile app
* Tidak ada chat real-time

## Teknologi

* Laravel 12
* MySQL
* Blade Template
* Session-based Authentication
* SMTP Email (Invoice)
