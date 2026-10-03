# KIT Konsultan IT

Website company profile dan sistem manajemen konten untuk **KIT Konsultan IT**, dibangun menggunakan Laravel.

Project ini menyediakan website publik sekaligus panel administrator untuk mengelola berbagai konten seperti layanan, solusi, portfolio, artikel, resource, karier, client, project, testimonial, pengumuman, SEO, dan data lainnya.

---

## Teknologi

Project ini menggunakan:

- PHP 8.3+
- Laravel 13
- SQLite
- Blade Template
- Tailwind CSS 4
- Vite 8
- Node.js & NPM

---

# Cara Menjalankan Project

Ada dua cara mendapatkan source code project ini:

1. Download ZIP dari GitHub
2. Clone menggunakan Git

---

## 1. Download ZIP

Klik tombol:

**Code → Download ZIP**

Kemudian extract file ZIP tersebut.

Masuk ke folder project melalui Terminal / CMD:

```bash
cd KonsultanIT
```

---

## 2. Clone Repository

Jika Git sudah terinstall:

```bash
git clone https://github.com/RasyaNurF/KonsultanIT.git
```

Kemudian masuk ke folder project:

```bash
cd KonsultanIT
```

---

# Persyaratan

Pastikan komputer sudah memiliki:

- PHP >= 8.3
- Composer
- Node.js
- NPM
- PHP SQLite Extension
- Git (opsional jika menggunakan ZIP)

Cek versi dengan:

```bash
php -v
composer --version
node -v
npm -v
```

---

# Instalasi

## 1. Install PHP Dependencies

Jalankan:

```bash
composer install
```

---

## 2. Buat File Environment

### Windows

```bash
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

---

## 3. Generate Application Key

```bash
php artisan key:generate
```

---

## 4. Siapkan Database

Project secara default menggunakan **SQLite**.

Pastikan file berikut tersedia:

```text
database/database.sqlite
```

Jika belum ada, buat file kosong dengan nama:

```text
database.sqlite
```

di dalam folder:

```text
database/
```

Pada Linux / macOS juga bisa menggunakan:

```bash
touch database/database.sqlite
```

---

## 5. Jalankan Migration

```bash
php artisan migrate
```

Jika ingin sekaligus memasukkan data awal / dummy:

```bash
php artisan migrate --seed
```

Atau jika ingin menghapus database lama dan membuat ulang seluruh data:

```bash
php artisan migrate:fresh --seed
```

> Perhatian: `migrate:fresh --seed` akan menghapus seluruh data yang sudah ada.

---

# Akun Administrator Default

Jika database dijalankan menggunakan seeder, akun administrator default adalah:

```text
Email    : admin@konsultanit.id
Password : password
```

Login melalui:

```text
http://localhost:8000/login
```

Setelah berhasil masuk, disarankan untuk segera mengganti password administrator.

---

# Install Frontend Dependencies

Jalankan:

```bash
npm install
```

---

# Menjalankan Project

Gunakan dua terminal.

### Terminal 1 — Laravel

```bash
php artisan serve
```

### Terminal 2 — Vite

```bash
npm run dev
```

Website dapat dibuka melalui:

```text
http://localhost:8000
```

---

# Cara Instalasi Cepat

Project juga memiliki Composer setup script.

Setelah source code selesai didownload / clone, jalankan:

```bash
composer run setup
```

Script tersebut akan menjalankan proses instalasi dependency, pembuatan `.env`, generate application key, migration database, install NPM dependencies, dan build frontend.

Setelah selesai:

```bash
composer run dev
```

---

# Build Frontend

Untuk development:

```bash
npm run dev
```

Untuk production:

```bash
npm run build
```

---

# Struktur Project

```text
KonsultanIT/
│
├── app/
│   ├── Http/
│   ├── Models/
│   └── ...
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
│   └── database.sqlite
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   └── web.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── vite.config.js
```

---

# Fitur Utama

Project memiliki beberapa fitur utama, antara lain:

- Company Profile
- Halaman Tentang Perusahaan
- Layanan IT
- Kategori Solusi
- Detail Solusi
- Portfolio
- Blog / Artikel
- Resource
- Event
- Whitepaper
- E-Book
- News
- Karier
- Lamaran Kerja
- Contact / Project Inquiry
- Sistem Pesan
- Login & Register
- Reset Password
- User Dashboard
- Admin Dashboard
- Management Client
- Management Project
- Management Portfolio
- Management Service
- Management Solution
- Management Resource
- Management Industry
- Management Artikel
- Management Testimonial
- Management Media
- Management SEO
- Management User
- Management Pengumuman
- Management Company Profile
- Management Hero Section
- Management Team
- Management Blog Category
- Management Career
- Maintenance Mode
- Sitemap

---

# Konfigurasi Environment

Konfigurasi aplikasi berada pada file:

```text
.env
```

Contoh konfigurasi default:

```env
APP_NAME="KIT Konsultan IT"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
```

Jangan upload file `.env` ke repository publik karena dapat berisi credential atau konfigurasi sensitif.

Gunakan `.env.example` sebagai template konfigurasi.

---

# Menggunakan MySQL

Secara default project menggunakan SQLite.

Jika ingin menggunakan MySQL, ubah `.env` menjadi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=konsultanit
DB_USERNAME=root
DB_PASSWORD=
```

Buat terlebih dahulu database:

```text
konsultanit
```

Kemudian jalankan:

```bash
php artisan migrate --seed
```

---

# Jika Terjadi Error

## APP_KEY belum tersedia

Jalankan:

```bash
php artisan key:generate
```

---

## Database belum ada

Pastikan file berikut tersedia:

```text
database/database.sqlite
```

Kemudian jalankan:

```bash
php artisan migrate --seed
```

---

## Class / package tidak ditemukan

Jalankan:

```bash
composer install
```

atau:

```bash
composer dump-autoload
```

---

## CSS / JavaScript tidak tampil

Jalankan:

```bash
npm install
npm run dev
```

---

## Perubahan `.env` tidak terbaca

Jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## Storage / Permission Error

Pada Linux:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

# Development Commands

Menjalankan Laravel:

```bash
php artisan serve
```

Menjalankan Vite:

```bash
npm run dev
```

Build frontend:

```bash
npm run build
```

Migration:

```bash
php artisan migrate
```

Seed database:

```bash
php artisan db:seed
```

Reset database:

```bash
php artisan migrate:fresh --seed
```

Clear cache:

```bash
php artisan optimize:clear
```

Menjalankan test:

```bash
php artisan test
```

---

# Catatan Keamanan

Sebelum digunakan di server production:

- Ubah akun dan password administrator default.
- Set `APP_DEBUG=false`.
- Gunakan password database yang kuat.
- Jangan upload file `.env`.
- Pastikan HTTPS aktif.
- Atur permission file dan folder dengan benar.
- Jalankan frontend production build menggunakan `npm run build`.

Contoh konfigurasi:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com
```

---

# Kontribusi

Jika ingin mengembangkan project:

1. Fork repository.
2. Buat branch baru.
3. Lakukan perubahan.
4. Commit perubahan.
5. Push branch.
6. Buat Pull Request.

Contoh:

```bash
git checkout -b feature/nama-fitur
```

Kemudian:

```bash
git add .
git commit -m "Menambahkan fitur baru"
git push origin feature/nama-fitur
```

---

# Repository

Source code:

https://github.com/RasyaNurF/KonsultanIT

---

# Author

**RasyaNurF**

GitHub:

https://github.com/RasyaNurF

---

## Disclaimer

Project ini dikembangkan untuk kebutuhan website **KIT Konsultan IT**.

Silakan sesuaikan konfigurasi, database, konten, akun administrator, serta environment sebelum digunakan untuk development maupun production.
