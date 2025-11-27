# Quick Start Guide

## ⚠️ Error: "Your system folder path does not appear to be set correctly"

### Solusi Cepat:

1. **Download CodeIgniter 3**
   ```
   URL: https://codeigniter.com/en/download
   Pilih: CodeIgniter 3 (bukan CI4)
   ```

2. **Extract dan Copy**
   - Extract file ZIP
   - Buka folder hasil extract
   - **Copy folder `system/`** (bukan `application/`)
   - Paste ke: `C:\xampp\htdocs\template_backup-web\system\`

3. **Verifikasi**
   - Pastikan ada file: `system/core/CodeIgniter.php`
   - Jika ada, berarti sudah benar!

4. **Refresh Browser**
   - Buka: `http://localhost/template_backup-web/`
   - Error seharusnya sudah hilang

## Struktur Folder yang Benar

```
template_backup-web/
├── system/              ← HARUS ADA (dari CodeIgniter 3)
│   ├── core/
│   │   └── CodeIgniter.php
│   └── ...
├── application/          ← Sudah ada (dari project ini)
├── assets/              ← Sudah ada
├── index.php            ← Sudah ada
└── .htaccess            ← Sudah ada
```

## Setelah System Folder Ada

1. Edit `application/config/config.php`:
   ```php
   $config['base_url'] = 'http://localhost/template_backup-web/';
   ```

2. Buka browser: `http://localhost/template_backup-web/`

3. Halaman login akan muncul!

## Masih Error?

- Pastikan folder `system/` benar-benar ada di root project
- Pastikan ada file `system/core/CodeIgniter.php`
- Cek permission folder (harus readable)
- Restart Apache/XAMPP

