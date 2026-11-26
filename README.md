# Petak25 — Reservasi Meja & Pre-Order (Laravel)

Folder ini adalah *overlay*: salin ke dalam proyek Laravel baru (gabungkan/timpa file).

## Setup
1. `composer create-project laravel/laravel petak25`
2. Salin seluruh isi folder ini ke `petak25/` (timpa `app/Models/User.php` dan `routes/web.php`).
3. Di `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=petak25` (buat database kosong di phpMyAdmin).
4. Di `bootstrap/app.php`, dalam `->withMiddleware(function (Middleware $middleware) { ... })` tambahkan:
   `$middleware->alias(['role' => \App\Http\Middleware\RoleMiddleware::class]);`
5. `php artisan migrate --seed`
6. `php artisan serve` lalu buka http://127.0.0.1:8000

## Akun demo (password: `password`)
- Pemilik: pemilik@petak25.test (semua fitur: dashboard, menu, meja, pengguna, laporan)
- Staf: staf@petak25.test (reservasi/status pesanan + ubah stok menu saja)

## Alur pelanggan (tanpa login)
Reservasi -> pilih meja + pre-order menu -> bayar -> status (Received/Preparing/Ready) via kode RSV-XXXXX.

## Catatan UI pelanggan
Foto hero di halaman awal: simpan foto Petak25 sebagai `public/img/hero.jpg`.
Setelah menyalin ulang file, jalankan `php artisan migrate:fresh --seed` (ada kolom `catatan` baru).

## Denah meja
Meja ditampilkan di atas `public/img/denah.png` memakai kolom `pos_x` dan `pos_y` (persen). Ubah posisi lewat halaman Pemilik > Meja.
