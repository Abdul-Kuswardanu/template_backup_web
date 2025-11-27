# Cara Download dan Install CodeIgniter 3 System Folder

## Error yang Terjadi
```
Your system folder path does not appear to be set correctly.
```

Ini terjadi karena folder `system/` dari CodeIgniter 3 belum ada di project.

## Solusi

### Opsi 1: Download Manual (Disarankan)

1. **Download CodeIgniter 3**
   - Kunjungi: https://codeigniter.com/en/download
   - Download versi terbaru CodeIgniter 3 (bukan CI4)
   - File yang didownload biasanya: `CodeIgniter-3.x.x.zip`

2. **Extract File**
   - Extract file ZIP yang didownload
   - Akan ada folder seperti `CodeIgniter-3.x.x/`

3. **Copy Folder System**
   - Buka folder hasil extract
   - Copy folder `system/` (bukan folder `application/`)
   - Paste ke root project ini: `template_backup-web/system/`

4. **Struktur Akhir**
   ```
   template_backup-web/
   ├── system/          ← Folder ini harus ada
   │   ├── core/
   │   ├── database/
   │   ├── fonts/
   │   ├── helpers/
   │   ├── language/
   │   ├── libraries/
   │   └── ...
   ├── application/
   ├── assets/
   └── index.php
   ```

### Opsi 2: Download via Composer (Alternatif)

Jika Anda menggunakan Composer:

```bash
composer create-project codeigniter/framework:^3.1 temp-ci3
```

Kemudian copy folder `system/` dari `temp-ci3/system/` ke project ini.

### Opsi 3: Download via Git (Alternatif)

```bash
git clone https://github.com/bcit-ci/CodeIgniter.git temp-ci3
cd temp-ci3
git checkout 3.1-stable
```

Kemudian copy folder `system/` dari `temp-ci3/system/` ke project ini.

## Verifikasi

Setelah folder `system/` ada, cek struktur:

```
template_backup-web/
├── system/
│   ├── core/
│   │   └── CodeIgniter.php  ← File ini harus ada
│   └── ...
```

Jika file `system/core/CodeIgniter.php` ada, berarti instalasi berhasil!

## Testing

Setelah folder `system/` ada:
1. Refresh browser
2. Error seharusnya sudah hilang
3. Halaman login akan muncul

## Catatan

- **JANGAN** copy folder `application/` dari CodeIgniter, karena kita sudah punya `application/` sendiri
- **HANYA** copy folder `system/` saja
- Folder `system/` berisi core framework CodeIgniter dan tidak boleh diubah

