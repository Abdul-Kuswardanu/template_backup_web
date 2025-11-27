# Data Backup Web Application

Aplikasi web untuk backup dan manajemen file menggunakan CodeIgniter 3 dengan tampilan interaktif menggunakan Bootstrap 5.

## 📋 Deskripsi

Aplikasi ini memungkinkan pengguna untuk:
- **Upload file** ke folder backup yang ditentukan
- **Download file** dalam format ZIP
- **Manajemen password** (ubah password, reset password)
- **Autentikasi pengguna** dengan session management

## ✨ Fitur

### 1. Upload File
- ✅ Drag & Drop file untuk upload
- ✅ Multiple file upload
- ✅ Progress bar saat upload
- ✅ Validasi ukuran file (max 50MB per file)
- ✅ Validasi ekstensi file
- ✅ Pilih folder tujuan (upload_1 atau upload_2)

### 2. Download File
- ✅ Download file dalam format ZIP
- ✅ Pilih multiple file untuk didownload
- ✅ Tampilkan ukuran dan tanggal modifikasi file
- ✅ Toggle folder untuk show/hide file

### 3. Authentication
- ✅ Login system
- ✅ Change Password dengan validasi real-time
- ✅ Reset Password
- ✅ Session management
- ✅ Logout

### 4. UI/UX
- ✅ Tampilan modern dengan Bootstrap 5
- ✅ Responsive design
- ✅ Interaktif dengan JavaScript
- ✅ Tab navigation untuk Upload dan Download
- ✅ Real-time validation
- ✅ Loading indicators

## 🛠️ Teknologi

- **Backend Framework**: CodeIgniter 3
- **Frontend Framework**: Bootstrap 5
- **Icons**: Bootstrap Icons
- **JavaScript**: Vanilla JS (ES6+)
- **PHP**: 5.6+ (disarankan 7.4+)
- **Server**: Apache dengan mod_rewrite

## 📁 Struktur Project

```
template_backup-web/
├── application/
│   ├── config/              # Konfigurasi CI3
│   │   ├── config.php       # Konfigurasi utama
│   │   ├── routes.php       # Routing
│   │   ├── database.php     # Konfigurasi database (kosong)
│   │   └── autoload.php     # Auto-load libraries
│   ├── controllers/
│   │   ├── Auth.php         # Controller untuk authentication
│   │   └── Home.php         # Controller untuk home, upload, download
│   ├── views/
│   │   ├── auth/            # View untuk login, change password, reset password
│   │   ├── home/            # View untuk halaman utama
│   │   └── inc/             # Include files (header, footer, sidebar)
│   │       ├── header.php   # Header dengan navigation
│   │       ├── footer.php   # Footer dengan scripts
│   │       └── sidebar.php  # Sidebar (disiapkan untuk nanti)
│   ├── logs/                # Log files
│   └── cache/               # Cache files
├── assets/
│   ├── css/
│   │   └── style.css        # Custom CSS
│   ├── js/
│   │   ├── home.js          # JavaScript untuk halaman home
│   │   ├── upload.js        # JavaScript untuk upload
│   │   └── password-validation.js  # Validasi password
│   └── images/
│       └── Logo_JICT.jpg    # Logo (tidak digunakan)
├── upload_1/                # Folder upload 1
├── upload_2/                # Folder upload 2
├── download_file/           # Folder temporary untuk ZIP
├── system/                  # CodeIgniter 3 System (harus didownload)
├── index.php                # Entry point
└── .htaccess                # URL Rewriting
```

## 📦 Instalasi

### 1. Persyaratan
- PHP 5.6 atau lebih tinggi (disarankan PHP 7.4+)
- Apache dengan mod_rewrite enabled
- CodeIgniter 3 System Files

### 2. Download CodeIgniter 3

⚠️ **PENTING**: Folder `system/` dari CodeIgniter 3 harus ada!

1. Download CodeIgniter 3 dari https://codeigniter.com/en/download
2. Extract file ZIP yang didownload
3. **Copy HANYA folder `system/`** (bukan `application/`) ke root project
4. Struktur akhir: `template_backup-web/system/`

Lihat file `DOWNLOAD_CI3.md` atau `QUICK_START.md` untuk instruksi lengkap.

### 3. Konfigurasi

#### Base URL
Edit file `application/config/config.php`:
```php
$config['base_url'] = 'http://localhost/template_backup-web/';
```
Sesuaikan dengan URL server Anda.

#### Session & Encryption
File `application/config/config.php` sudah dikonfigurasi dengan:
- Session save path: system temp directory
- Encryption key: (ubah di production!)

### 4. Set Permission (Linux/Mac)
```bash
chmod 755 application/logs
chmod 755 application/cache
chmod 755 download_file
chmod 755 upload_1
chmod 755 upload_2
```

### 5. Testing
1. Buka browser: `http://localhost/template_backup-web/`
2. Halaman login akan muncul
3. Masukkan User ID dan Password apapun (untuk testing, belum ada validasi database)

## 🚀 Cara Penggunaan

### Login
1. Buka halaman login
2. Masukkan User ID dan Password
3. Klik "Login"

### Upload File
1. Setelah login, klik tab **"Upload File"**
2. Pilih folder tujuan: `upload_1` atau `upload_2`
3. Drag & drop file atau klik area upload untuk memilih file
4. File yang dipilih akan muncul di daftar
5. Klik **"Upload File"** untuk mulai upload
6. Tunggu hingga upload selesai

**Format file yang diizinkan:**
- Images: JPG, JPEG, PNG, GIF
- Documents: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT
- Archives: ZIP, RAR

**Batasan:**
- Maksimal 50MB per file
- Multiple file upload didukung

### Download File
1. Klik tab **"Download File"**
2. Klik header folder untuk expand/collapse
3. Centang file yang ingin didownload
4. Klik **"Download File Terpilih"**
5. File akan terdownload dalam format ZIP

### Change Password
1. Klik menu **"Ubah Password"** di header
2. Masukkan password saat ini
3. Masukkan password baru (8-16 karakter)
4. Konfirmasi password baru
5. Password harus mengandung:
   - Huruf besar
   - Huruf kecil
   - Angka
   - Karakter khusus
6. Klik **"Ubah Password"**

### Reset Password
1. Di halaman login, klik **"Lupa Password?"**
2. Masukkan User ID
3. Klik **"Kirim Password Baru"**
4. Password baru akan dikirim ke email (jika database sudah dihubungkan)

## 🔧 Konfigurasi Lanjutan

### Folder Upload
Folder upload dapat diubah di `application/controllers/Home.php`:
```php
$possible_folders = array(
    array('path' => 'upload_1', 'name' => 'upload_1', 'class' => 'folder1'),
    array('path' => 'upload_2', 'name' => 'upload_2', 'class' => 'folder2'),
);
```

### Validasi File Upload
Validasi file dapat diubah di `application/controllers/Home.php` method `upload()`:
- Ukuran maksimal: `50 * 1024 * 1024` (50MB)
- Ekstensi yang diizinkan: array `$allowed_extensions`

### Session Timeout
Session timeout dapat diubah di `application/config/config.php`:
```php
$config['sess_expiration'] = 7200; // 2 jam (dalam detik)
```

## ⚠️ Catatan Penting

### Database
⚠️ **Saat ini aplikasi TIDAK menggunakan database**. Semua fungsi authentication menggunakan session dummy untuk testing.

**Next Steps:**
1. Setup database connection di `application/config/database.php`
2. Install ion_auth library
3. Integrasikan ion_auth dengan controller yang sudah ada

### Security
- Ubah `encryption_key` di `application/config/config.php` untuk production
- Pastikan folder `application/logs/` dan `application/cache/` tidak accessible dari web
- Validasi file upload sudah dilakukan (ukuran, ekstensi)
- Path traversal protection sudah diimplementasi

### File Upload
- File disimpan di folder `upload_1/` atau `upload_2/`
- Jika file dengan nama sama sudah ada, akan ditambahkan timestamp
- File temporary ZIP dihapus otomatis setelah download

## 🐛 Troubleshooting

### Error: "Your system folder path does not appear to be set correctly"
**Solusi**: Download CodeIgniter 3 dan copy folder `system/` ke root project. Lihat `QUICK_START.md`.

### Error: "404 Page Not Found"
**Solusi**: 
- Pastikan mod_rewrite enabled di Apache
- Pastikan `.htaccess` ada di root
- Cek `base_url` di `application/config/config.php`

### Logo tidak muncul
**Solusi**: Logo JICT sudah dihapus dari aplikasi. Jika masih muncul, clear browser cache.

### Upload gagal
**Solusi**:
- Cek permission folder `upload_1/` dan `upload_2/` (harus writable)
- Cek ukuran file (max 50MB)
- Cek ekstensi file (harus diizinkan)

### Session expired
**Solusi**: Login kembali. Session timeout default 2 jam.

## 📝 Changelog

### Version 1.0.0
- ✅ Setup CodeIgniter 3
- ✅ Login system (tanpa database)
- ✅ Upload file dengan drag & drop
- ✅ Download file dalam format ZIP
- ✅ Change Password dengan validasi real-time
- ✅ Reset Password
- ✅ UI dengan Bootstrap 5
- ✅ Responsive design
- ✅ Layout dengan inc/header dan inc/footer

## 👤 Author

Dibuat untuk kebutuhan backup data internal.

## 📄 License

Internal use only.

## 🔗 Link Terkait

- [CodeIgniter 3 Documentation](https://codeigniter.com/userguide3/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)

---

**Note**: Aplikasi ini siap untuk dihubungkan dengan database dan ion_auth library nanti.
