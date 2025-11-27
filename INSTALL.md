# Panduan Instalasi - Data Backup Web Application

## Persyaratan

- PHP 5.6 atau lebih tinggi (disarankan PHP 7.4+)
- Apache dengan mod_rewrite enabled
- MySQL/MariaDB (untuk nanti saat database dihubungkan)
- CodeIgniter 3 System Files

## Langkah Instalasi

### 1. Download CodeIgniter 3

1. Kunjungi https://codeigniter.com/en/download
2. Download CodeIgniter 3 (versi terbaru)
3. Extract file ZIP yang didownload
4. Copy folder `system` dari CodeIgniter ke root project ini
   - Struktur akhir: `template_backup-web/system/`

### 2. Konfigurasi Base URL

Edit file `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/template_backup-web/';
```

**Catatan**: Sesuaikan dengan URL server Anda:
- Local: `http://localhost/template_backup-web/`
- Server: `http://your-domain.com/` atau `http://your-ip/template_backup-web/`

### 3. Set Permission Folder

Pastikan folder berikut writable (chmod 755 atau 777):

```bash
# Linux/Mac
chmod 755 application/logs
chmod 755 application/cache
chmod 755 download_file

# Windows (biasanya sudah writable secara default)
```

### 4. Verifikasi Struktur Folder

Pastikan struktur folder seperti ini:

```
template_backup-web/
├── application/
│   ├── cache/          (writable)
│   ├── config/
│   ├── controllers/
│   ├── helpers/
│   ├── logs/           (writable)
│   └── views/
├── assets/
│   ├── css/
│   ├── images/         (Logo_JICT.jpg harus ada)
│   └── js/
├── download_file/      (writable)
├── system/             (dari CodeIgniter 3)
├── index.php
└── .htaccess
```

### 5. Testing

1. Buka browser dan akses: `http://localhost/template_backup-web/`
2. Halaman login akan muncul
3. Masukkan User ID dan Password apapun (untuk testing, belum ada validasi database)
4. Setelah login, akan redirect ke halaman Home

## Troubleshooting

### Error: "Your system folder path does not appear to be set correctly"

**Solusi**: Pastikan folder `system/` ada di root project dan berasal dari CodeIgniter 3.

### Error: "404 Page Not Found"

**Solusi**: 
1. Pastikan mod_rewrite enabled di Apache
2. Pastikan `.htaccess` ada di root
3. Cek `base_url` di `application/config/config.php`

### Logo tidak muncul

**Solusi**: 
1. Pastikan file `Logo_JICT.jpg` ada di `assets/images/`
2. Cek permission file
3. Cek path di view `application/views/auth/login.php`

### Folder tidak bisa diakses

**Solusi**: Pastikan folder `aku_coba2_folder` dan `aku_coba2_folder2` ada di parent directory (satu level di atas root project).

## Next Steps (Setelah Database Siap)

1. Edit `application/config/database.php` untuk koneksi database
2. Install ion_auth library
3. Update controller `Auth.php` untuk menggunakan ion_auth
4. Update validasi di semua controller

## Catatan Penting

⚠️ **Saat ini aplikasi TIDAK menggunakan database**. Semua authentication menggunakan session dummy. 
Setelah database siap dan ion_auth terinstall, semua fungsi akan bekerja dengan database yang sebenarnya.

