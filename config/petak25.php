<?php
// Data tujuan pembayaran yang tampil di halaman Payment pelanggan.
// Ganti dengan data asli Petak25 lewat file .env (atau ubah langsung di sini).
// Untuk QRIS asli, simpan gambar QRIS Petak25 sebagai public/img/qris.png.
return [
    'bank' => [
        'nama' => env('PETAK25_BANK_NAMA', 'BCA'),
        'nomor' => env('PETAK25_BANK_NOMOR', '1234567890'),
        'atas_nama' => env('PETAK25_BANK_ATAS_NAMA', 'Petak25'),
    ],
    'ewallet' => [
        'GoPay' => env('PETAK25_GOPAY', '081234567890'),
        'OVO' => env('PETAK25_OVO', '081234567890'),
    ],
];
